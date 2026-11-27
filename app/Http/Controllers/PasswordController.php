<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('perfil.password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password_actual' => ['required', 'current_password'],
            'password_nueva' => [
                'required',
                'confirmed',
                Password::min(10)
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ], [
            'password_actual.current_password' => 'La contraseña actual no es correcta.',
            'password_nueva.confirmed' => 'La confirmación no coincide con la nueva contraseña.',
        ]);

        $request->user()->update([
            'password' => Hash::make($request->input('password_nueva')),
        ]);

        return back()->with('exito', 'Tu contraseña se actualizó correctamente.');
    }
}
