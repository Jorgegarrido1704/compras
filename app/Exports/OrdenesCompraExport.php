<?php

namespace App\Exports;

use App\Models\OrdenCompra;
use App\Models\Requisicion;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdenesCompraExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private string $fechaInicio,
        private string $fechaFin,
    ) {}

    public function collection(): Collection
    {
        $finExclusivo = CarbonImmutable::parse($this->fechaFin)
            ->addDay()
            ->toDateString();

        $ordenes = OrdenCompra::with(['cotizacion.proveedor', 'requisicion.solicitante'])
            ->where('fecha_pago_real', '>=', $this->fechaInicio)
            ->where('fecha_pago_real', '<', $finExclusivo)
            ->orderByRaw('fecha_pago_programada IS NULL, fecha_pago_programada ASC')
            ->get();

        $servicios = Requisicion::with(['solicitante', 'archivos'])
            ->where('tipo', 'servicio')
            ->where('fecha_pago_real', '>=', $this->fechaInicio)
            ->where('fecha_pago_real', '<', $finExclusivo)
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
            'Fecha pago real',
            'Factura',
        ];
    }

    public function map($orden): array
    {
        if ($orden instanceof Requisicion) {
            return [
                null,
                'Servicio',
                $orden->titulo,
                $orden->solicitante?->name,
                null,
                null,
                null,
                $orden->costo_servicio,
                $orden->estatus,
                null,
                optional($orden->fecha_pago_programada)->format('Y-m-d'),
                optional($orden->fecha_pago_real)->format('Y-m-d'),
                $orden->archivos->first()?->ruta,
            ];
        }

        return [
            $orden->folio,
            'Consumible',
            $orden->requisicion?->titulo,
            $orden->requisicion?->solicitante?->name,
            $orden->cotizacion->proveedor->nombre,
            $orden->cotizacion->monto,
            $orden->cotizacion->moneda,
            null,
            $orden->estatus,
            optional($orden->fecha_autorizacion)->format('Y-m-d'),
            optional($orden->fecha_pago_programada)->format('Y-m-d'),
            optional($orden->fecha_pago_real)->format('Y-m-d'),
            $orden->factura_path,
        ];
    }
}
