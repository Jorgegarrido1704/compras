@extends('layouts.app')

@section('titulo', 'Cambiar contraseña')

@section('contenido')
<h1 class="text-xl font-semibold mb-1">Cambiar contraseña</h1>
<p class="text-sm text-stamp-neutral mb-6">
    Usa una contraseña única que no utilices en otros sitios.
</p>

<form method="POST" action="{{ route('perfil.password.update') }}"
      class="bg-white border border-line rounded-sm shadow-sm p-6 space-y-5 max-w-md">
    @csrf
    @method('PUT')

    <div>
        <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="password_actual">
            Contraseña actual
        </label>
        <input id="password_actual" name="password_actual" type="password" required
               class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
    </div>

    <div>
        <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="password_nueva">
            Contraseña nueva
        </label>
        <input id="password_nueva" name="password_nueva" type="password" required
               class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
        <ul class="text-xs text-stamp-neutral mt-2 space-y-0.5 list-disc pl-4">
            <li>Al menos 10 caracteres</li>
            <li>Mayúsculas y minúsculas</li>
            <li>Al menos un número y un símbolo</li>
        </ul>
    </div>

    <div>
        <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="password_nueva_confirmation">
            Confirmar contraseña nueva
        </label>
        <input id="password_nueva_confirmation" name="password_nueva_confirmation" type="password" required
               class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
    </div>

    <button type="submit" class="bg-ledger text-white font-medium px-4 py-2 rounded-sm hover:bg-ledger-dark transition-colors">
        Actualizar contraseña
    </button>
</form>
@endsection
