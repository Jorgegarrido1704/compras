<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('roles')->orderBy('name')->paginate(20);

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->pluck('name');

        return view('admin.usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(10)->mixedCase()->numbers()->symbols()],
            'rol' => ['required', 'exists:roles,name'],
        ]);

        $usuario = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $usuario->assignRole($data['rol']);

        return redirect()->route('admin.usuarios.index')->with('exito', "Usuario {$usuario->name} creado correctamente.");
    }

    public function toggleActivo(User $usuario)
    {
        $usuario->update(['activo' => !$usuario->activo]);

        $mensaje = $usuario->activo ? 'reactivado' : 'desactivado';

        return back()->with('exito', "Usuario {$usuario->name} {$mensaje}.");
    }
}