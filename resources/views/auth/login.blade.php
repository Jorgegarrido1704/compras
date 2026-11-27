@extends('layouts.app')

@section('titulo', 'Iniciar sesión')

@section('contenido')
<div class="min-h-[80vh] flex items-center justify-center">
    <div class="w-full max-w-sm">
        <div class="text-center mb-8">
            <div class="font-mono text-[11px] tracking-widest text-stamp-neutral uppercase">Sistema de Compras</div>
            <h1 class="text-2xl font-semibold text-ledger mt-1">Autorización</h1>
        </div>

        <form method="POST" action="{{ route('login.attempt') }}" class="bg-white border border-line rounded-sm shadow-sm p-6 space-y-4">
            @csrf

            <div>
                <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="email">Correo</label>
                <input id="email" name="email" type="email" required autofocus value="{{ old('email') }}"
                       class="w-full border border-line rounded-sm px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger"
                       placeholder="tu@empresa.com">
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="password">Contraseña</label>
                <input id="password" name="password" type="password" required
                       class="w-full border border-line rounded-sm px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger"
                       placeholder="••••••••">
            </div>

            <div class="flex items-center gap-2">
                <input id="remember" name="remember" type="checkbox" class="rounded-sm border-line text-ledger focus:ring-ledger/40">
                <label for="remember" class="text-sm text-stamp-neutral">Recordarme</label>
            </div>

            <button type="submit" class="w-full bg-ledger text-white font-medium px-4 py-2 rounded-sm hover:bg-ledger-dark transition-colors">
                Entrar
            </button>
        </form>
    </div>
</div>
@endsection
