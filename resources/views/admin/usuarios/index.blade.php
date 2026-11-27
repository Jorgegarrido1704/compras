@extends('layouts.app')

@section('titulo', 'Usuarios')

@section('contenido')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-semibold">Usuarios</h1>
    <a href="{{ route('admin.usuarios.create') }}" class="bg-ledger text-white font-medium px-4 py-2 rounded-sm hover:bg-ledger-dark transition-colors text-sm">
        + Nuevo usuario
    </a>
</div>

<div class="bg-white border border-line rounded-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-left border-b border-line text-xs uppercase tracking-wide text-stamp-neutral">
                <th class="px-4 py-3">Nombre</th>
                <th class="px-4 py-3">Correo</th>
                <th class="px-4 py-3">Rol</th>
                <th class="px-4 py-3">Estatus</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-line">
            @foreach ($usuarios as $u)
                <tr>
                    <td class="px-4 py-3 font-medium">{{ $u->name }}</td>
                    <td class="px-4 py-3 text-stamp-neutral">{{ $u->email }}</td>
                    <td class="px-4 py-3 font-mono text-xs uppercase">{{ $u->roles->pluck('name')->join(', ') ?: '—' }}</td>
                    <td class="px-4 py-3">
                        @if ($u->activo)
                            <span class="folio-stamp text-xs font-semibold uppercase inline-flex items-center gap-1.5 border px-2 py-1 rounded-sm text-stamp-approved border-stamp-approved">Activo</span>
                        @else
                            <span class="folio-stamp text-xs font-semibold uppercase inline-flex items-center gap-1.5 border px-2 py-1 rounded-sm text-stamp-neutral border-stamp-neutral">Inactivo</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <form method="POST" action="{{ route('admin.usuarios.toggle-activo', $u) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs {{ $u->activo ? 'text-stamp-rejected' : 'text-stamp-approved' }} hover:underline">
                                {{ $u->activo ? 'Desactivar' : 'Reactivar' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $usuarios->links() }}</div>
@endsection