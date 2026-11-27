<?php

namespace App\Http\Controllers;

use App\Models\Cotizacion;
use App\Models\OrdenCompra;
use App\Models\Proveedor;
use App\Models\Requisicion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComprasController extends Controller
{
    public function index(Request $request)
    {
        $query = Requisicion::with('solicitante')->latest();

        $estatus = $request->query('estatus', 'solicitada');
        if ($estatus !== '') {
            $query->where('estatus', $estatus);
        }

        $requisiciones = $query->paginate(15)->withQueryString();

        return view('compras.index', compact('requisiciones'));
    }

    public function autorizadas(Request $request)
    {
        $query = OrdenCompra::with(['cotizacion.proveedor', 'requisicion.solicitante'])
            ->where('estatus', 'autorizada')
            ->latest();

        $ordenes = $query->paginate(15)->withQueryString();

        return view('compras.autorizadas', compact('ordenes'));
    }

    public function show(Requisicion $requisicion)
    {
        $requisicion->load(['solicitante', 'archivos', 'cotizaciones.proveedor', 'cotizaciones.ordenCompra']);
        $proveedores = Proveedor::orderBy('nombre')->get();

        return view('compras.show', compact('requisicion', 'proveedores'));
    }

    public function storeCotizacion(Request $request, Requisicion $requisicion)
    {
        $data = $request->validate([
            'proveedor_id' => ['required', 'exists:proveedores,id'],
            'monto' => ['required', 'numeric', 'min:0'],
            'moneda' => ['required', 'in:MXN,USD'],
            'notas' => ['nullable', 'string'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $rutaPdf = $request->file('pdf')->store('cotizaciones/'.$requisicion->id, 'public');

        Cotizacion::create([
            'requisicion_id' => $requisicion->id,
            'proveedor_id' => $data['proveedor_id'],
            'creado_por' => $request->user()->id,
            'monto' => $data['monto'],
            'moneda' => $data['moneda'],
            'pdf_path' => $rutaPdf,
            'notas' => $data['notas'] ?? null,
        ]);

        if ($requisicion->estatus === 'solicitada') {
            $requisicion->cambiarEstatus('en_cotizacion', $request->user()->id, 'Primera cotización capturada');
        }

        return back()->with('exito', 'Cotización guardada.');
    }

    public function enviarAutorizacion(Request $request, Cotizacion $cotizacion)
    {
        if ($cotizacion->ordenCompra) {
            return back()->with('error', 'Esta cotización ya fue enviada a autorización.');
        }

        DB::transaction(function () use ($cotizacion, $request) {
            $cotizacion->update(['seleccionada' => true]);

            $orden = OrdenCompra::create([
                'cotizacion_id' => $cotizacion->id,
                'folio' => OrdenCompra::generarFolio(),
                'estatus' => 'pendiente_autorizacion',
            ]);

            $orden->historial()->create([
                'estatus_anterior' => null,
                'estatus_nuevo' => 'pendiente_autorizacion',
                'user_id' => $request->user()->id,
                'comentario' => 'Enviada a autorización de gerencia',
            ]);

            $cotizacion->requisicion->cambiarEstatus(
                'pendiente_autorizacion',
                $request->user()->id,
                'Cotización enviada a gerencia'
            );
        });

        return back()->with('exito', 'Cotización enviada a autorización de gerencia.');
    }

    public function subirFactura(Request $request, OrdenCompra $ordenCompra)
    {
        abort_unless($ordenCompra->estatus === 'autorizada', 422);

        $data = $request->validate([
            'fecha_pago_programada' => ['required', 'date', 'after_or_equal:today'],
            'factura' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $rutaFactura = $request->file('factura')->store(
            'facturas/'.$ordenCompra->folio,
            'public'
        );

        DB::transaction(function () use ($ordenCompra, $data, $rutaFactura, $request) {
            $ordenCompra->update([
                'factura_path' => $rutaFactura,
                'fecha_pago_programada' => $data['fecha_pago_programada'],
            ]);

            $ordenCompra->cambiarEstatus(
                'pendiente_pago',
                $request->user()->id,
                'Factura cargada por compras'
            );

            $ordenCompra->requisicion->cambiarEstatus(
                'pendiente_pago',
                $request->user()->id,
                'Factura cargada, en espera de pago'
            );
        });

        return back()->with('exito', "Factura de {$ordenCompra->folio} guardada.");
    }
}
