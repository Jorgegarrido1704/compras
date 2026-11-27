@extends('layouts.app')

@section('titulo', 'Órdenes de pago')

@section('contenido')
<div class="flex items-center justify-between mb-1">
    <h1 class="text-xl font-semibold">Órdenes de pago</h1>
    <a href="{{ route('contabilidad.exportar-excel') }}" class="bg-white border border-line text-ink font-medium px-4 py-2 rounded-sm hover:bg-paper transition-colors text-sm">
        Exportar a Excel
    </a>
</div>
<p class="text-sm text-stamp-neutral mb-6">Ordenadas por fecha de pago programada.</p>

<div class="flex gap-1 mb-4 border-b border-line">
    @foreach ([
        'pendiente_pago' => 'Por pagar',
        'pagada' => 'Pagadas',
        '' => 'Todas',
    ] as $valor => $etiqueta)
        <a href="{{ route('contabilidad.index', $valor ? ['estatus' => $valor] : []) }}"
           class="px-4 py-2 text-sm font-medium border-b-2 -mb-px transition-colors {{ request('estatus', 'pendiente_pago') === $valor ? 'border-ledger text-ledger' : 'border-transparent text-stamp-neutral hover:text-ink' }}">
            {{ $etiqueta }}
        </a>
    @endforeach
</div>

@if ($ordenes->isEmpty())
    <div class="bg-white border border-line rounded-sm p-8 text-center text-stamp-neutral text-sm">
        No hay órdenes en esta vista.
    </div>
@else
    <div class="bg-white border border-line rounded-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-line text-xs uppercase tracking-wide text-stamp-neutral">
                    <th class="px-4 py-3">Folio</th>
                    <th class="px-4 py-3">Proveedor</th>
                    <th class="px-4 py-3">Monto</th>
                    <th class="px-4 py-3">Fecha de pago</th>
                    <th class="px-4 py-3">Estatus</th>
                    <th class="px-4 py-3">Documentos</th>
                    
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @foreach ($ordenes as $o)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs">{{ $o->folio }}</td>
                        <td class="px-4 py-3">{{ $o->cotizacion->proveedor->nombre }}</td>
                        <td class="px-4 py-3 font-mono">{{ $o->cotizacion->moneda }} ${{ number_format($o->cotizacion->monto, 2) }}</td>
                        <td class="px-4 py-3 font-mono">
                            {{ $o->fecha_pago_programada ? $o->fecha_pago_programada->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-4 py-3"><x-status-badge :estatus="$o->estatus" /></td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            @if ($o->requisicion->archivos->isNotEmpty())
                                @foreach ($o->requisicion->archivos as $archivo)
                                    <a href="{{ asset('storage/' . $archivo->ruta) }}" target="_blank" class="text-ledger hover:underline text-xs">
                                        Requerimiento
                                    </a>
                                @endforeach
                            @endif
                            @if ($o->cotizacion->pdf_path)
                                <a href="{{ asset('storage/' . $o->cotizacion->pdf_path) }}" target="_blank" class="text-ledger hover:underline text-xs">Cotización</a>
                            @endif
                            @if ($o->pdf_path)
                                <a href="{{ asset('storage/' . $o->pdf_path) }}" target="_blank" class="text-ledger hover:underline text-xs">Orden de compra</a>
                            @endif
                            @if ($o->factura_path)
                                <a href="{{ asset('storage/' . $o->factura_path) }}" target="_blank" class="text-ledger hover:underline text-xs">Factura</a>
                            @endif
                            @if ($o->estatus === 'pendiente_pago')
                                <form method="POST" action="{{ route('contabilidad.marcar-pagada', $o) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-stamp-approved hover:underline">Marcar pagada</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $ordenes->links() }}</div>
@endif

<h2 class="text-lg font-semibold mt-8 mb-3">Pagos de servicios</h2>
@if ($servicios->isEmpty())
    <div class="bg-white border border-line rounded-sm p-8 text-center text-stamp-neutral text-sm">
        No hay servicios en esta vista.
    </div>
@else
    <div class="bg-white border border-line rounded-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left border-b border-line text-xs uppercase tracking-wide text-stamp-neutral">
                    <th class="px-4 py-3">Servicio</th>
                    <th class="px-4 py-3">Solicitante</th>
                    <th class="px-4 py-3">Costo</th>
                    <th class="px-4 py-3">Fecha de pago</th>
                    <th class="px-4 py-3">Estatus</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-line">
                @foreach ($servicios as $servicio)
                    <tr>
                        <td class="px-4 py-3">{{ $servicio->titulo }}</td>
                        <td class="px-4 py-3">{{ $servicio->solicitante->name }}</td>
                        <td class="px-4 py-3 font-mono">
                            ${{ number_format((float) $servicio->costo_servicio, 2) }} MXN
                        </td>
                        <td class="px-4 py-3 font-mono">
                            {{ $servicio->fecha_pago_programada ? $servicio->fecha_pago_programada->format('d/m/Y') : '—' }}
                        </td>
                        <td class="px-4 py-3"><x-status-badge :estatus="$servicio->estatus" /></td>
                        <td class="px-4 py-3 text-right space-x-3 whitespace-nowrap">
                            @foreach ($servicio->archivos as $archivo)
                                <a href="{{ asset('storage/' . $archivo->ruta) }}" target="_blank" class="text-ledger hover:underline text-xs">Factura</a>
                            @endforeach
                            @if ($servicio->estatus === 'pendiente_pago')
                                <form method="POST" action="{{ route('contabilidad.servicios.marcar-pagada', $servicio) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-stamp-approved hover:underline">Marcar pagado</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $servicios->links() }}</div>
@endif
@endsection
