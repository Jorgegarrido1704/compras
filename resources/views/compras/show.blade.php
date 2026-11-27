@extends('layouts.app')

@section('titulo', $requisicion->titulo)

@section('contenido')
<div class="max-w-2xl">
    <div class="flex items-center justify-between mb-1">
        <h1 class="text-xl font-semibold">{{ $requisicion->titulo }}</h1>
        <x-status-badge :estatus="$requisicion->estatus" />
    </div>
    <p class="text-sm text-stamp-neutral mb-6">Solicitó {{ $requisicion->solicitante->name }}</p>

    <div class="bg-white border border-line rounded-sm p-5 mb-6">
        <div class="text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1">Descripción</div>
        <p class="text-sm">{{ $requisicion->descripcion }}</p>

        @if ($requisicion->archivos->isNotEmpty())
            <div class="mt-4">
                <div class="text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1">Archivos adjuntos</div>
                <ul class="text-sm space-y-1">
                    @foreach ($requisicion->archivos as $archivo)
                        <li>
                            <a href="{{ asset('storage/' . $archivo->ruta) }}" target="_blank" class="text-ledger hover:underline">
                                {{ $archivo->nombre_original }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <h2 class="text-sm font-semibold uppercase tracking-wide text-stamp-neutral mb-3">Cotizaciones</h2>

    <div class="space-y-3 mb-6">
        @forelse ($requisicion->cotizaciones as $c)
            <div class="bg-white border border-line rounded-sm p-4 flex items-center justify-between gap-4">
                <div>
                    <div class="font-medium">{{ $c->proveedor->nombre }}</div>
                    <div class="text-sm font-mono text-stamp-neutral">
                        {{ $c->moneda }} ${{ number_format($c->monto, 2) }}
                    </div>
                    @if ($c->pdf_path)
                        <a href="{{ asset('storage/' . $c->pdf_path) }}" target="_blank" class="text-xs text-ledger hover:underline">
                            Ver PDF de cotización
                        </a>
                    @endif
                </div>

                @if ($c->ordenCompra)
                    <span class="folio-stamp text-xs font-semibold tracking-wider uppercase inline-flex items-center gap-1.5 border px-2 py-1 rounded-sm text-stamp-approved border-stamp-approved">
                        {{ $c->ordenCompra->folio }}
                    </span>
                @else
                    <form method="POST" action="{{ route('cotizaciones.enviar-autorizacion', $c) }}">
                        @csrf
                        <button type="submit" class="bg-white border border-line text-ink font-medium px-4 py-2 rounded-sm hover:bg-paper transition-colors text-sm">
                            Enviar a autorización
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <p class="text-sm text-stamp-neutral">Aún no hay cotizaciones capturadas.</p>
        @endforelse
    </div>

    @unless (in_array($requisicion->estatus, ['pendiente_autorizacion', 'pendiente_pago', 'autorizada', 'pagada']))
        <h2 class="text-sm font-semibold uppercase tracking-wide text-stamp-neutral mb-3">Capturar cotización</h2>

        <form method="POST" action="{{ route('compras.cotizaciones.store', $requisicion) }}" enctype="multipart/form-data"
              class="bg-white border border-line rounded-sm p-5 space-y-4">
            @csrf

            <div>
                <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="proveedor_id">Proveedor</label>
                <select id="proveedor_id" name="proveedor_id" required
                        class="w-full border border-line rounded-sm px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
                    <option value="">Selecciona un proveedor</option>
                    @foreach ($proveedores as $p)
                        <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="monto">Monto</label>
                    <input id="monto" name="monto" type="number" step="0.01" required
                           class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
                </div>
                <div>
                    <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="moneda">Moneda</label>
                    <select id="moneda" name="moneda"
                            class="w-full border border-line rounded-sm px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
                        <option value="MXN">MXN</option>
                        <option value="USD">USD</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="notas">Notas (opcional)</label>
                <textarea id="notas" name="notas" rows="2"
                          class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger"></textarea>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="pdf">PDF de la cotización</label>
                <input id="pdf" name="pdf" type="file" accept="application/pdf" required
                       class="w-full border border-line rounded-sm px-3 py-2 bg-white">
            </div>

            <button type="submit" class="bg-ledger text-white font-medium px-4 py-2 rounded-sm hover:bg-ledger-dark transition-colors">
                Guardar cotización
            </button>
        </form>
    @endunless
</div>
@endsection
