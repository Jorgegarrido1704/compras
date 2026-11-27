@extends('layouts.app')

@section('titulo', 'Por autorizar')

@section('contenido')
<h1 class="text-xl font-semibold mb-1">Por autorizar</h1>
<p class="text-sm text-stamp-neutral mb-6">Revisa por separado las compras de consumibles y los servicios.</p>

<a href="{{ route('gerencia.exportar-excel') }}" class="inline-block mb-4 bg-white border border-line text-ink font-medium px-4 py-2 rounded-sm hover:bg-paper transition-colors text-sm">
    Exportar a Excel
</a>

<div class="flex gap-1 mb-6 border-b border-line">
    <a href="{{ route('gerencia.index', ['tab' => 'consumibles']) }}"
       class="px-4 py-2 text-sm font-medium border-b-2 -mb-px {{ $tab === 'consumibles' ? 'border-ledger text-ledger' : 'border-transparent text-stamp-neutral hover:text-ink' }}">
        Consumibles
    </a>
    <a href="{{ route('gerencia.index', ['tab' => 'servicios']) }}"
       class="px-4 py-2 text-sm font-medium border-b-2 -mb-px {{ $tab === 'servicios' ? 'border-ledger text-ledger' : 'border-transparent text-stamp-neutral hover:text-ink' }}">
        Servicios
    </a>
</div>

@if ($tab === 'consumibles')
    @if ($ordenes->isEmpty())
        <div class="bg-white border border-line rounded-sm p-8 text-center text-stamp-neutral text-sm">
            No hay consumibles pendientes de autorización.
        </div>
    @else
        <div class="space-y-3">
            @foreach ($ordenes as $o)
                <div x-data="{ abierto: false }" class="bg-white border border-line rounded-sm p-5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <span class="folio-stamp text-xs font-semibold tracking-wider uppercase inline-flex items-center gap-1.5 border px-2 py-1 text-stamp-pending border-stamp-pending">
                                {{ $o->folio }}
                            </span>
                            <div class="font-medium mt-1">{{ $o->requisicion->titulo }}</div>
                            <div class="text-xs text-stamp-neutral mt-0.5">
                                Solicitó {{ $o->requisicion->solicitante->name }} · Proveedor {{ $o->cotizacion->proveedor->nombre }}
                            </div>
                            <div class="text-sm font-mono mt-1">
                                {{ $o->cotizacion->moneda }} ${{ number_format($o->cotizacion->monto, 2) }}
                            </div>
                            @if ($o->requisicion->archivos->isNotEmpty())
                                <a href="{{ asset('storage/' . $o->requisicion->archivos->first()->ruta) }}" target="_blank" class="text-xs text-ledger hover:underline">Ver requisición</a>
                            @endif
                            @if ($o->cotizacion->pdf_path)
                                <a href="{{ asset('storage/' . $o->cotizacion->pdf_path) }}" target="_blank" class="text-xs text-ledger hover:underline">Ver cotización</a>
                            @endif
                        </div>
                        <button @click="abierto = !abierto" class="bg-white border border-line text-ink font-medium px-4 py-2 rounded-sm hover:bg-paper transition-colors text-sm">
                            <span x-text="abierto ? 'Cerrar' : 'Revisar'"></span>
                        </button>
                    </div>
                    <div x-show="abierto" x-cloak class="mt-5 pt-5 border-t border-line space-y-4">
                        <div class="bg-paper border border-line rounded-sm p-4">
                            <div class="text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-3">Historial</div>
                            <div class="space-y-2 text-sm">
                                @forelse ($o->requisicion->historial->concat($o->historial)->sortByDesc('created_at') as $evento)
                                    <div class="flex items-start justify-between gap-4">
                                        <div><span class="font-medium">{{ str_replace('_', ' ', ucfirst($evento->estatus_nuevo)) }}</span>@if ($evento->comentario) <span class="text-stamp-neutral">— {{ $evento->comentario }}</span>@endif</div>
                                        <div class="text-xs text-stamp-neutral text-right whitespace-nowrap">{{ $evento->user->name }}<br>{{ $evento->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                @empty
                                    <p class="text-stamp-neutral">No hay movimientos registrados.</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <form method="POST" action="{{ route('gerencia.autorizar', $o) }}" class="bg-paper border border-line rounded-sm p-4 space-y-3">
                                @csrf
                                <div class="text-xs uppercase tracking-wide text-stamp-neutral font-medium">Autorizar</div>
                                <input type="date" name="fecha_pago_programada" required min="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}" class="w-full border border-line rounded-sm px-3 py-2 bg-white">
                                <button type="submit" class="w-full bg-ledger text-white font-medium px-4 py-2 rounded-sm hover:bg-ledger-dark transition-colors text-sm">Autorizar compra</button>
                            </form>
                            <form method="POST" action="{{ route('gerencia.rechazar', $o) }}" class="bg-paper border border-line rounded-sm p-4 space-y-3">
                                @csrf
                                <div class="text-xs uppercase tracking-wide text-stamp-neutral font-medium">Rechazar</div>
                                <textarea name="motivo_rechazo" rows="2" required placeholder="Motivo del rechazo" class="w-full border border-line rounded-sm px-3 py-2 bg-white"></textarea>
                                <button type="submit" class="w-full bg-white border border-stamp-rejected text-stamp-rejected font-medium px-4 py-2 rounded-sm hover:bg-stamp-rejected hover:text-white transition-colors text-sm">Rechazar compra</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-4">{{ $ordenes->links() }}</div>
    @endif
@else
    @if ($servicios->isEmpty())
        <div class="bg-white border border-line rounded-sm p-8 text-center text-stamp-neutral text-sm">
            No hay servicios pendientes de autorización.
        </div>
    @else
        <div class="space-y-3">
            @foreach ($servicios as $servicio)
                <div x-data="{ abierto: false }" class="bg-white border border-line rounded-sm p-5">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <span class="folio-stamp text-xs font-semibold tracking-wider uppercase inline-flex items-center gap-1.5 border px-2 py-1 text-stamp-pending border-stamp-pending">Servicio</span>
                            <div class="font-medium mt-1">{{ $servicio->titulo }}</div>
                            <div class="text-xs text-stamp-neutral mt-0.5">Solicitó {{ $servicio->solicitante->name }}</div>
                            <div class="text-sm font-mono mt-1">${{ number_format((float) $servicio->costo_servicio, 2) }} MXN</div>
                            @foreach ($servicio->archivos as $archivo)
                                <a href="{{ asset('storage/' . $archivo->ruta) }}" target="_blank" class="text-xs text-ledger hover:underline">Ver factura del servicio</a>
                            @endforeach
                        </div>
                        <button @click="abierto = !abierto" class="bg-white border border-line text-ink font-medium px-4 py-2 rounded-sm hover:bg-paper transition-colors text-sm">
                            <span x-text="abierto ? 'Cerrar' : 'Revisar'"></span>
                        </button>
                    </div>
                    <div x-show="abierto" x-cloak class="mt-5 pt-5 border-t border-line space-y-4">
                        <p class="text-sm text-ink">{{ $servicio->descripcion }}</p>
                        <div class="bg-paper border border-line rounded-sm p-4">
                            <div class="text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-3">Historial</div>
                            @forelse ($servicio->historial->sortByDesc('created_at') as $evento)
                                <div class="flex items-start justify-between gap-4 text-sm">
                                    <div><span class="font-medium">{{ str_replace('_', ' ', ucfirst($evento->estatus_nuevo)) }}</span> <span class="text-stamp-neutral">— {{ $evento->comentario }}</span></div>
                                    <div class="text-xs text-stamp-neutral text-right whitespace-nowrap">{{ $evento->user->name }}<br>{{ $evento->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                            @empty
                                <p class="text-stamp-neutral">No hay movimientos registrados.</p>
                            @endforelse
                        </div>
                        <div class="grid md:grid-cols-2 gap-4">
                            <form method="POST" action="{{ route('gerencia.servicios.autorizar', $servicio) }}" class="bg-paper border border-line rounded-sm p-4 space-y-3">
                                @csrf
                                <div class="text-xs uppercase tracking-wide text-stamp-neutral font-medium">Autorizar servicio</div>
                                <input type="date" name="fecha_pago_programada" required min="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}" class="w-full border border-line rounded-sm px-3 py-2 bg-white">
                                <button type="submit" class="w-full bg-ledger text-white font-medium px-4 py-2 rounded-sm hover:bg-ledger-dark transition-colors text-sm">Enviar a contabilidad</button>
                            </form>
                            <form method="POST" action="{{ route('gerencia.servicios.rechazar', $servicio) }}" class="bg-paper border border-line rounded-sm p-4 space-y-3">
                                @csrf
                                <div class="text-xs uppercase tracking-wide text-stamp-neutral font-medium">Rechazar servicio</div>
                                <textarea name="motivo_rechazo" rows="2" required placeholder="Motivo del rechazo" class="w-full border border-line rounded-sm px-3 py-2 bg-white"></textarea>
                                <button type="submit" class="w-full bg-white border border-stamp-rejected text-stamp-rejected font-medium px-4 py-2 rounded-sm hover:bg-stamp-rejected hover:text-white transition-colors text-sm">Rechazar servicio</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endif
@endsection
