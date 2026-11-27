<?php

namespace App\Exports;

use App\Models\OrdenCompra;
use App\Models\Requisicion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class GerenciaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection(): Collection
    {
        $ordenes = OrdenCompra::with(['cotizacion.proveedor', 'requisicion.solicitante'])
            ->orderByRaw('fecha_pago_programada IS NULL, fecha_pago_programada ASC')
            ->get();

        $servicios = Requisicion::with('solicitante')
            ->where('tipo', 'servicio')
            ->orderByRaw('fecha_pago_programada IS NULL, fecha_pago_programada ASC')
            ->get();

        return $ordenes->concat($servicios);
    }

    public function headings(): array
    {
        return [
            'Folio',
            'Tipo',
            'Requisición',
            'Solicitante',
            'Proveedor',
            'Monto',
            'Moneda',
            'Costo servicio',
            'Estatus',
            'Fecha autorización',
            'Fecha pago programada',
            'Factura',
        ];
    }

    public function map($registro): array
    {
        if ($registro instanceof Requisicion) {
            return [
                null,
                'Servicio',
                $registro->titulo,
                $registro->solicitante?->name,
                null,
                null,
                null,
                $registro->costo_servicio,
                $registro->estatus,
                null,
                optional($registro->fecha_pago_programada)->format('Y-m-d'),
                $registro->archivos->first()?->ruta,
            ];
        }

        return [
            $registro->folio,
            'Consumible',
            $registro->requisicion?->titulo,
            $registro->requisicion?->solicitante?->name,
            $registro->cotizacion->proveedor->nombre,
            $registro->cotizacion->monto,
            $registro->cotizacion->moneda,
            null,
            $registro->estatus,
            optional($registro->fecha_autorizacion)->format('Y-m-d'),
            optional($registro->fecha_pago_programada)->format('Y-m-d'),
            $registro->cotizacion->requisicion->archivos->first()?->ruta,
        ];
    }
}
