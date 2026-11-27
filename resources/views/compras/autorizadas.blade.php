@extends('layouts.app')

@section('titulo', 'Autorizadas por facturar')

@section('contenido')
<h1 class="text-xl font-semibold mb-1">Autorizadas por facturar</h1>
<p class="text-sm text-stamp-neutral mb-6">Carga la factura y la fecha de pago para enviar la orden a contabilidad.</p>

@if ($ordenes->isEmpty())
    <div class="bg-white border border-line rounded-sm p-8 text-center text-stamp-neutral text-sm">
        No hay solicitudes pendientes de autorización.
    </div>
@else
    <div class="space-y-3">
        @foreach ($ordenes as $o)
            <div x-data="{ abierto: false }" class="bg-white border border-line rounded-sm p-5">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <span class="folio-stamp text-xs font-semibold tracking-wider uppercase inline-flex items-center gap-1.5 border px-2 py-1 rounded-sm text-stamp-pending border-stamp-pending">
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
                            <a href="{{ asset('storage/' . $o->requisicion->archivos->first()->ruta) }}" target="_blank" class="text-xs text-ledger hover:underline">
                                Ver PDF de requisición
                            </a>
                        @endif
                        @if ($o->cotizacion->pdf_path)
                            <a href="{{ asset('storage/' . $o->cotizacion->pdf_path) }}" target="_blank" class="text-xs text-ledger hover:underline">
                                Ver PDF de cotización
                            </a>
                        @endif
                        @if ($o->pdf_path)
                            <a href="{{ asset('storage/' . $o->pdf_path) }}" target="_blank" class="text-xs text-ledger hover:underline">
                                Ver orden de compra
                            </a>
                        @endif
                    </div>
                    <button @click="abierto = !abierto"
                            class="bg-white border border-line text-ink font-medium px-4 py-2 rounded-sm hover:bg-paper transition-colors text-sm">
                        <span x-text="abierto ? 'Cerrar' : 'Revisar'"></span>
                    </button>
                </div>

                <div x-show="abierto" x-cloak class="mt-5 pt-5 border-t border-line grid md:grid-cols-2 gap-4">
                    <form method="POST" action="{{ route('compras.ordenes.factura', $o) }}" enctype="multipart/form-data" class="bg-paper border border-line rounded-sm p-4 space-y-3">
                        @csrf
                        <div class="text-xs uppercase tracking-wide text-stamp-neutral font-medium">Cargar factura</div>
                        <input type="file" name="factura" accept="application/pdf" required
                               class="w-full border border-line rounded-sm px-3 py-2 bg-white text-sm">
                        <input type="date" name="fecha_pago_programada" required min="{{ now()->toDateString() }}" value="{{ now()->toDateString() }}"
                               class="w-full border border-line rounded-sm px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
                        <button type="submit" class="w-full bg-ledger text-white font-medium px-4 py-2 rounded-sm hover:bg-ledger-dark transition-colors text-sm">
                            Guardar factura
                        </button>
                    </form>

                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $ordenes->links() }}</div>
@endif
@endsection
