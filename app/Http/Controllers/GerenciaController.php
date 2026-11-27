<?php

namespace App\Http\Controllers;

use App\Exports\GerenciaExport;
use App\Models\OrdenCompra;
use App\Models\Requisicion;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class GerenciaController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'consumibles');

        $ordenes = OrdenCompra::with([
            'cotizacion.proveedor',
            'requisicion.solicitante',
            'requisicion.archivos',
            'historial.user',
            'requisicion.historial.user',
        ])
            ->where('estatus', 'pendiente_autorizacion')
            ->whereHas('requisicion', fn ($query) => $query->where('tipo', 'compra'))
            ->when($tab === 'servicios', fn ($query) => $query->whereRaw('1 = 0'))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $servicios = Requisicion::with([
            'solicitante',
            'archivos',
            'historial.user',
        ])
            ->where('tipo', 'servicio')
            ->where('estatus', 'pendiente_autorizacion')
            ->when($tab !== 'servicios', fn ($query) => $query->whereRaw('1 = 0'))
            ->latest()
            ->get();

        return view('gerencia.index', compact('ordenes', 'servicios', 'tab'));
    }

    public function exportarExcel()
    {
        return Excel::download(new GerenciaExport, 'autorizaciones_gerencia.xlsx');
    }

    public function show(OrdenCompra $ordenCompra)
    {
        $ordenCompra->load([
            'cotizacion.proveedor',
            'requisicion.solicitante',
            'requisicion.archivos',
        ]);

        return view('gerencia.show', compact('ordenCompra'));
    }

    public function autorizar(Request $request, OrdenCompra $ordenCompra)
    {
        abort_unless($ordenCompra->estatus === 'pendiente_autorizacion', 422);

        DB::transaction(function () use ($ordenCompra, $request) {
            $ordenCompra->update([
                'autorizado_por' => $request->user()->id,
                'fecha_autorizacion' => now(),
            ]);

            $ordenCompra->cambiarEstatus('autorizada', $request->user()->id, 'Autorizada por gerencia');

            $ordenCompra->requisicion->cambiarEstatus(
                'autorizada',
                $request->user()->id,
                'Autorizada, en espera de que compras suba la factura'
            );

            $pdfPath = 'ordenes/'.$ordenCompra->folio.'.pdf';
            $pdf = Pdf::loadView('pdf.orden-compra', [
                'orden' => $ordenCompra->load('cotizacion.proveedor', 'requisicion.solicitante'),
            ]);
            $pdf->save(storage_path('app/public/'.$pdfPath));

            $ordenCompra->update(['pdf_path' => $pdfPath]);
        });

        return redirect()->route('gerencia.index')->with('exito', "Orden {$ordenCompra->folio} autorizada.");
    }

    public function rechazar(Request $request, OrdenCompra $ordenCompra)
    {
        abort_unless($ordenCompra->estatus === 'pendiente_autorizacion', 422);

        $data = $request->validate([
            'motivo_rechazo' => ['required', 'string', 'min:5'],
        ]);

        DB::transaction(function () use ($ordenCompra, $data, $request) {
            $ordenCompra->update(['motivo_rechazo' => $data['motivo_rechazo']]);
            $ordenCompra->cambiarEstatus('rechazada', $request->user()->id, $data['motivo_rechazo']);

            $ordenCompra->requisicion->cambiarEstatus(
                'rechazada',
                $request->user()->id,
                $data['motivo_rechazo']
            );
        });

        return redirect()->route('gerencia.index')->with('exito', "Orden {$ordenCompra->folio} rechazada.");
    }

    public function autorizarServicio(Request $request, Requisicion $requisicion)
    {
        abort_unless($requisicion->tipo === 'servicio' && $requisicion->estatus === 'pendiente_autorizacion', 422);

        $data = $request->validate([
            'fecha_pago_programada' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $requisicion->update(['fecha_pago_programada' => $data['fecha_pago_programada']]);
        $requisicion->cambiarEstatus(
            'pendiente_pago',
            $request->user()->id,
            'Servicio autorizado y enviado a contabilidad'
        );

        return redirect()->route('gerencia.index')->with('exito', 'Servicio autorizado y enviado a contabilidad.');
    }

    public function rechazarServicio(Request $request, Requisicion $requisicion)
    {
        abort_unless($requisicion->tipo === 'servicio' && $requisicion->estatus === 'pendiente_autorizacion', 422);

        $data = $request->validate([
            'motivo_rechazo' => ['required', 'string', 'min:5'],
        ]);

        $requisicion->cambiarEstatus(
            'rechazada',
            $request->user()->id,
            $data['motivo_rechazo']
        );

        return redirect()->route('gerencia.index')->with('exito', 'Servicio rechazado.');
    }
}
