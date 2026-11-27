@extends('layouts.app')

@section('titulo', 'Mis requisiciones')

@section('contenido')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-semibold">Mis requisiciones</h1>
    <a href="{{ route('solicitante.nueva') }}" class="bg-ledger text-white font-medium px-4 py-2 rounded-sm hover:bg-ledger-dark transition-colors text-sm">
        + Nueva
    </a>
</div>

@if ($requisiciones->isEmpty())
    <div class="bg-white border border-line rounded-sm p-8 text-center text-stamp-neutral text-sm">
        Aún no has enviado ninguna requisición.
    </div>
@else
    <div class="bg-white border border-line rounded-sm divide-y divide-line">
        @foreach ($requisiciones as $r)
            <div class="p-4 flex items-center justify-between gap-4">
                <div>
                    <div class="font-medium">{{ $r->titulo }}</div>
                    <div class="text-xs text-stamp-neutral font-mono mt-0.5">
                        {{ $r->created_at->format('d/m/Y') }}
                    </div>
                </div>
                <x-status-badge :estatus="$r->estatus" />
            </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $requisiciones->links() }}</div>
@endif
@endsection
