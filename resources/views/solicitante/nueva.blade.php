@extends('layouts.app')

@section('titulo', 'Nueva requisición')

@section('contenido')
<div class="flex flex-wrap -mx-2">
    <!-- Primera columna (50% de ancho) -->
    <div class="w-1/2 px-2">
        <h1 class="text-xl font-semibold mb-1">Nueva requisición</h1>
        <p class="text-sm text-stamp-neutral mb-6">
            Describe qué necesitas. El equipo de compras la cotizará antes de enviarla a autorización.
        </p>

        <form method="POST" action="{{ route('solicitante.store') }}" enctype="multipart/form-data"
            class="bg-white border border-line rounded-sm shadow-sm p-6 space-y-5 max-w-xl">
            @csrf

            <div>
                <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="titulo">Título</label>
                <input id="titulo" name="titulo" required value="{{ old('titulo') }}"
                    class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger"
                    placeholder="Ej. Laptop para nuevo desarrollador">
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="descripcion">Descripción / especificaciones</label>
                <textarea id="descripcion" name="descripcion" required rows="5"
                        class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger"
                        placeholder="Modelo, cantidad, características, links de referencia…">{{ old('descripcion') }}</textarea>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="prioridad">Prioridad</label>
                <select id="prioridad" name="prioridad"
                        class="w-full border border-line rounded-sm px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
                    <option value="baja">Baja</option>
                    <option value="media" selected>Media</option>
                    <option value="alta">Alta</option>
                    <option value="urgente">Urgente</option>
                </select>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="archivos">Archivos de referencia (opcional)</label>
                <input id="archivos" name="archivos[]" type="file" multiple
                    class="w-full border border-line rounded-sm px-3 py-2 bg-white">
            </div>

            <button type="submit" class="bg-ledger text-white font-medium px-4 py-2 rounded-sm hover:bg-ledger-dark transition-colors">
                Enviar requisición
            </button>
        </form>
    </div>

    <!-- Segunda columna (50% de ancho) -->
    <div class="w-1/2 px-2">
        <h1 class="text-xl font-semibold mb-1">Nueva requisición de servicios</h1>
        <p class="text-sm text-stamp-neutral mb-6">
           Adjunta la factura del servicio. Gerencia revisara tu solicitud antes de enviarla a contabilidad.
        </p>

        <form method="POST" action="{{ route('solicitante.store_servicio') }}" enctype="multipart/form-data"
            class="bg-white border border-line rounded-sm shadow-sm p-6 space-y-5 max-w-xl">
            @csrf

            <div>
                <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="titulo_2">Título</label>
                <input id="titulo_2" name="titulo" required value="{{ old('titulo') }}"
                    class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger"
                    placeholder="Ej. Laptop para nuevo desarrollador">
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="descripcion_2">Descripción / especificaciones</label>
                <textarea id="descripcion_2" name="descripcion" required rows="5"
                        class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger"
                        placeholder="Modelo, cantidad, características, links de referencia…">{{ old('descripcion') }}</textarea>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="prioridad_2">Prioridad</label>
                <select id="prioridad_2" name="prioridad"
                        class="w-full border border-line rounded-sm px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger">
                    <option value="baja">Baja</option>
                    <option value="media" selected>Media</option>
                    <option value="alta">Alta</option>
                    <option value="urgente">Urgente</option>
                </select>
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="costo_servicio">Costo del servicio (MXN)</label>
                <input id="costo_servicio" name="costo_servicio" type="number" min="0" step="0.01" required
                    value="{{ old('costo_servicio') }}"
                    class="w-full border border-line rounded-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-ledger/40 focus:border-ledger"
                    placeholder="0.00">
            </div>

            <div>
                <label class="block text-xs uppercase tracking-wide text-stamp-neutral font-medium mb-1" for="archivos_2">Factura del servicio (PDF)</label>
                <input id="archivos_2" name="archivos[]" type="file" accept="application/pdf" required
                    class="w-full border border-line rounded-sm px-3 py-2 bg-white">
            </div>

            <button type="submit" class="bg-ledger text-white font-medium px-4 py-2 rounded-sm hover:bg-ledger-dark transition-colors">
                Enviar requisición
            </button>
        </form>
    </div>
</div>>
</div>
@endsection
