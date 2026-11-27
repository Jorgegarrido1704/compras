@extends('layouts.app')

@section('titulo', 'Nuevo proveedor')

@section('contenido')
<h1 class="text-xl font-semibold mb-6">Nuevo proveedor</h1>

<form method="POST" action="{{ route('proveedores.store') }}"
      class="bg-white border border-line rounded-sm shadow-sm p-6 space-y-5 max-w-md">
    @csrf

    <div>
        <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="nombre">Nombre / Razón social</label>
        <input id="nombre" name="nombre" required value="{{ old('nombre') }}"
               class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
    </div>

    <div>
        <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="rfc">RFC (opcional)</label>
        <input id="rfc" name="rfc" value="{{ old('rfc') }}"
               class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
    </div>

    <div>
        <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="contacto">Persona de contacto (opcional)</label>
        <input id="contacto" name="contacto" value="{{ old('contacto') }}"
               class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="telefono">Teléfono (opcional)</label>
            <input id="telefono" name="telefono" value="{{ old('telefono') }}"
                   class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
        </div>
        <div>
            <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="email">Correo (opcional)</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}"
                   class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
        </div>
    </div>

    <div>
        <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="direccion">Dirección (opcional)</label>
        <textarea id="direccion" name="direccion" rows="2"
                  class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">{{ old('direccion') }}</textarea>
    </div>

    <button type="submit" class="bg-ledger text-white font-medium px-4 py-2 rounded-sm hover:bg-ledger-dark transition-colors">
        Guardar proveedor
    </button>
</form>
@endsection