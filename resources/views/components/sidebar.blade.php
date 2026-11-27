@php
    $rol = auth()->user()?->getRoleNames()->first();

    $items = match ($rol) {
        'solicitante' => [
            ['url' => route('solicitante.nueva'), 'label' => 'Nueva requisición', 'active' => request()->routeIs('solicitante.nueva')],
            ['url' => route('solicitante.index'), 'label' => 'Mis requisiciones', 'active' => request()->routeIs('solicitante.index')],
        ],
       'compras' => [
            ['url' => route('compras.index'), 'label' => 'Requisiciones', 'active' => request()->routeIs('compras.*') && !request()->routeIs('proveedores.*')],
            ['url' => route('proveedores.index'), 'label' => 'Proveedores', 'active' => request()->routeIs('proveedores.*')],
           [
        'url' => route('compras.autorizadas'),
        'label' => 'Órdenes autorizadas',
        'active' => request()->routeIs('compras.autorizadas')
    ],
        ],
        'gerencia' => [
            ['url' => route('gerencia.index'), 'label' => 'Por autorizar', 'active' => request()->routeIs('gerencia.*')],
        ],
                'contabilidad' => [
            ['url' => route('contabilidad.index'), 'label' => 'Órdenes de pago', 'active' => request()->routeIs('contabilidad.*')],
        ],
        'admin' => [
            ['url' => route('admin.usuarios.index'), 'label' => 'Usuarios', 'active' => request()->routeIs('admin.usuarios.*')],
        ],
        default => [],

    };
@endphp

<aside class="w-60 shrink-0 border-r border-line bg-white flex flex-col min-h-screen">
    <div class="px-5 py-5 border-b border-line">
        <div class="font-mono text-[11px] tracking-widest text-stamp-neutral uppercase">Sistema de Compras</div>
        <div class="font-semibold text-ledger mt-0.5">Autorización</div>
    </div>

    @auth
        <nav class="flex-1 px-3 py-4 space-y-1">
            @foreach ($items as $item)
                <a href="{{ $item['url'] }}"
                   class="block px-3 py-2 rounded-sm text-sm font-medium transition-colors {{ $item['active'] ? 'bg-ledger text-white' : 'text-ink hover:bg-paper' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="px-5 py-4 border-t border-line">
            <div class="text-sm font-medium">{{ auth()->user()->name }}</div>
            <div class="text-xs text-stamp-neutral font-mono uppercase mb-3">{{ $rol }}</div>
            <a href="{{ route('perfil.password.edit') }}" class="text-xs text-ledger hover:underline block mb-2">
                Cambiar contraseña
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-xs text-stamp-rejected hover:underline">Cerrar sesión</button>
            </form>
        </div>
    @endauth
</aside>
