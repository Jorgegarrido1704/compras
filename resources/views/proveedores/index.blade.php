@extends('layouts.app')

@section('titulo', 'Proveedores')

@section('contenido')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-semibold">Proveedores</h1>
    <a href="{{ route('proveedores.create') }}" class="bg-ledger text-white font-medium px-4 py-2 rounded-sm hover:bg-ledger-dark transition-colors text-sm">
        + Nuevo proveedor
    </a>
</div>

@if ($proveedores->isEmpty())
    <div class="bg-white border border-line rounded-sm p-8 text-center text-stamp-neutral text-sm">
        No hay proveedores registrados todavía.
    </div>
@else
    <div class="bg-white border border-line rounded-sm divide-y divide-line">
        @foreach ($proveedores as $p)
            <div class="p-4">
                <div class="font-medium">{{ $p->nombre }}</div>
                <div class="text-xs text-stamp-neutral mt-0.5">
                    {{ $p->rfc ?? 'Sin RFC' }} · {{ $p->contacto ?? 'Sin contacto' }} · {{ $p->telefono ?? 'Sin teléfono' }}
                </div>
                @if ($p->email)
                    <div class="text-xs text-ledger mt-0.5">{{ $p->email }}</div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-4">{{ $proveedores->links() }}</div>
@endif
@endsection