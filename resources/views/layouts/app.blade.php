<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('titulo', 'Autorización de Compras')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@400;500;600&family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        paper: '#F6F5F1',
                        ink: '#1B1F23',
                        ledger: { DEFAULT: '#1E4B4A', light: '#2E6D6B', dark: '#123332' },
                        stamp: { pending: '#B8860B', approved: '#1F7A4D', rejected: '#A8342A', neutral: '#6B6F76' },
                        line: '#DCD8CE',
                    },
                    fontFamily: {
                        sans: ['"IBM Plex Sans"', 'system-ui', 'sans-serif'],
                        mono: ['"IBM Plex Mono"', 'ui-monospace', 'monospace'],
                    },
                    borderRadius: { sm: '2px' },
                },
            },
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            background-image: linear-gradient(to bottom, transparent 0px, transparent 27px, #E9E6DB 28px);
            background-size: 100% 28px;
        }
        .folio-stamp { font-family: 'IBM Plex Mono', monospace; }
    </style>

    @stack('estilos')
</head>
<body class="bg-paper text-ink font-sans antialiased">
    <div class="flex">
        @include('components.sidebar')

        <main class="flex-1 px-8 py-8 max-w-5xl">
            @if (session('exito'))
                <div class="mb-4 border border-stamp-approved text-stamp-approved bg-white rounded-sm px-4 py-3 text-sm">
                    {{ session('exito') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-4 border border-stamp-rejected text-stamp-rejected bg-white rounded-sm px-4 py-3 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 border border-stamp-rejected text-stamp-rejected bg-white rounded-sm px-4 py-3 text-sm">
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('contenido')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
