<?php

namespace App\Http\Controllers;

use App\Models\Requisicion;
use Illuminate\Http\Request;

class RequisicionController extends Controller
{
    public function index(Request $request)
    {
        $requisiciones = Requisicion::where('solicitante_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view('solicitante.index', compact('requisiciones'));
    }

    public function nueva()
    {
        return view('solicitante.nueva');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'prioridad' => ['required', 'in:baja,media,alta,urgente'],
            'archivos.*' => ['nullable', 'file', 'max:10240'],
        ]);

        $requisicion = Requisicion::create([
            'solicitante_id' => $request->user()->id,
            'titulo' => $data['titulo'],
            'descripcion' => $data['descripcion'],
            'tipo' => 'compra',
            'prioridad' => $data['prioridad'],
            'estatus' => 'solicitada',
        ]);

        foreach ($request->file('archivos', []) as $archivo) {
            $ruta = $archivo->store('requisiciones/'.$requisicion->id, 'public');

            $requisicion->archivos()->create([
                'nombre_original' => $archivo->getClientOriginalName(),
                'ruta' => $ruta,
                'tipo' => $archivo->getClientMimeType(),
            ]);
        }

        $requisicion->historial()->create([
            'estatus_anterior' => null,
            'estatus_nuevo' => 'solicitada',
            'user_id' => $request->user()->id,
            'comentario' => 'Requisición creada',
        ]);

        return redirect()->route('solicitante.index')->with('exito', 'Requisición enviada correctamente.');
    }

    public function store_servicio(Request $request)
    {
        $data = $request->validate([
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string'],
            'prioridad' => ['required', 'in:baja,media,alta,urgente'],
            'costo_servicio' => ['required', 'numeric', 'min:0'],
            'archivos' => ['required', 'array', 'min:1'],
            'archivos.*' => ['file', 'mimes:pdf', 'max:10240'],
        ]);

        $requisicion = Requisicion::create([
            'solicitante_id' => $request->user()->id,
            'titulo' => $data['titulo'],
            'descripcion' => $data['descripcion'],
            'tipo' => 'servicio',
            'costo_servicio' => $data['costo_servicio'],
            'prioridad' => $data['prioridad'],
            'estatus' => 'pendiente_autorizacion',
        ]);

        foreach ($request->file('archivos', []) as $archivo) {
            $ruta = $archivo->store('requisiciones/'.$requisicion->id, 'public');

            $requisicion->archivos()->create([
                'nombre_original' => $archivo->getClientOriginalName(),
                'ruta' => $ruta,
                'tipo' => $archivo->getClientMimeType(),
            ]);
        }

        $requisicion->historial()->create([
            'estatus_anterior' => null,
            'estatus_nuevo' => 'pendiente_autorizacion',
            'user_id' => $request->user()->id,
            'comentario' => 'Requisición de servicios creada',
        ]);

        return redirect()->route('solicitante.index')->with('exito', 'Requisición de servicios enviada correctamente.');
    }
}
