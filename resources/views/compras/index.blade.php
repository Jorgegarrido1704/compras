@extends('layouts.app')

@section('titulo', 'Requisiciones')

@section('contenido')
<h1 class="text-xl font-semibold mb-1">Requisiciones</h1>
<p class="text-sm text-stamp-neutral mb-6">
    Captura una cotización por proveedor y envíala a autorización de gerencia.
</p>

<div class="flex gap-1 mb-4 border-b border-line">
    @foreach ([
        'solicitada' => 'Por cotizar',
        'en_cotizacion' => 'En cotización',
        '' => 'Todas',
    ] as $valor => $etiqueta)
        <a href="{{ route('compras.index', $valor ? ['estatus' => $valor] : []) }}"
           class="px-4 py-2 text-sm font-medium border-b-2 -mb-px transition-colors {{ request('estatus', 'solicitada') === $valor ? 'border-ledger text-ledger' : 'border-transparent text-stamp-neutral hover:text-ink' }}">
            {{ $etiqueta }}
        </a>
    @endforeach
</div>

@if ($requisiciones->isEmpty())
    <div class="bg-white border border-line rounded-sm p-8 text-center text-stamp-neutral text-sm">
        No hay requisiciones en esta vista.
    </div>
@else
    <div class="bg-white border border-line rounded-sm divide-y divide-line">
        @foreach ($requisiciones as $r)
            <a href="{{ route('compras.show', $r) }}" class="p-4 flex items-center justify-between gap-4 hover:bg-paper transition-colors block">
                <div>
                    <div class="font-medium">{{ $r->titulo }}</div>
                    <div class="text-xs text-stamp-neutral mt-0.5">
                        Solicitó {{ $r->solicitante->name }} · {{ $r->created_at->format('d/m/Y') }}
                    </div>
                </div>
                <x-status-badge :estatus="$r->estatus" />
            </a>
        @endforeach
    </div>

    <div class="mt-4">{{ $requisiciones->links() }}</div>
@endif
@endsection
