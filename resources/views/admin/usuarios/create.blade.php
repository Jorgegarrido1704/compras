@extends('layouts.app')

@section('titulo', 'Nuevo usuario')

@section('contenido')
<h1 class="text-xl font-semibold mb-6">Nuevo usuario</h1>

<form method="POST" action="{{ route('admin.usuarios.store') }}"
      class="bg-white border border-line rounded-sm shadow-sm p-6 space-y-5 max-w-md">
    @csrf

    <div>
        <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="name">Nombre completo</label>
        <input id="name" name="name" required value="{{ old('name') }}"
               class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
    </div>

    <div>
        <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="email">Correo</label>
        <input id="email" name="email" type="email" required value="{{ old('email') }}"
               class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
    </div>

    <div>
        <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="rol">Rol</label>
        <select id="rol" name="rol" required
                class="w-full border border-line rounded-sm px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
            <option value="">Selecciona un rol</option>
            @foreach ($roles as $rol)
                <option value="{{ $rol }}">{{ ucfirst($rol) }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="password">Contraseña</label>
        <input id="password" name="password" type="password" required
               class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
        <ul class="text-xs text-stamp-neutral mt-2 space-y-0.5 list-disc pl-4">
            <li>Al menos 10 caracteres, mayúsculas, minúsculas, número y símbolo</li>
        </ul>
    </div>

    <div>
        <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="password_confirmation">Confirmar contraseña</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required
               class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
    </div>

    <button type="submit" class="bg-ledger text-white font-medium px-4 py-2 rounded-sm hover:bg-ledger-dark transition-colors">
        Crear usuario
    </button>
</form>
@endsection