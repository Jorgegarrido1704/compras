<?php

namespace App\Http\Controllers;

use App\Exports\OrdenesCompraExport;
use App\Models\OrdenCompra;
use App\Models\Requisicion;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ContabilidadController extends Controller
{
    public function index(Request $request)
    {
        $query = OrdenCompra::with([
            'cotizacion.proveedor',
            'requisicion.solicitante',
            'requisicion.archivos',
        ]);

        $estatus = $request->query('estatus', 'pendiente_pago');
        if ($estatus !== '') {
            $query->where('estatus', $estatus);
        }

        $ordenes = $query
            ->orderByRaw('fecha_pago_programada IS NULL, fecha_pago_programada ASC')
            ->paginate(20)
            ->withQueryString();

        $servicios = Requisicion::with(['solicitante', 'archivos'])
            ->where('tipo', 'servicio')
            ->when($estatus !== '', fn ($q) => $q->where('estatus', $estatus))
            ->orderByRaw('fecha_pago_programada IS NULL, fecha_pago_programada ASC')
            ->paginate(20, ['*'], 'servicios_page')
            ->withQueryString();

        return view('contabilidad.index', compact('ordenes', 'servicios'));
    }

    public function marcarPagada(Request $request, OrdenCompra $ordenCompra)
    {
        abort_unless($ordenCompra->estatus === 'pendiente_pago', 422);

        $ordenCompra->update(['fecha_pago_real' => now()]);
        $ordenCompra->cambiarEstatus('pagada', $request->user()->id, 'Pago realizado');
        $ordenCompra->requisicion->cambiarEstatus('pagada', $request->user()->id, 'Pago realizado');

        return back()->with('exito', "Orden {$ordenCompra->folio} marcada como pagada.");
    }

    public function marcarServicioPagado(Request $request, Requisicion $requisicion)
    {
        abort_unless($requisicion->tipo === 'servicio' && $requisicion->estatus === 'pendiente_pago', 422);

        $requisicion->update(['fecha_pago_real' => now()]);
        $requisicion->cambiarEstatus('pagada', $request->user()->id, 'Pago de servicio realizado');

        return back()->with('exito', "Servicio {$requisicion->titulo} marcado como pagado.");
    }

    public function exportarExcel()
    {
        return Excel::download(new OrdenesCompraExport, 'ordenes_de_compra.xlsx');
    }
}
