<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function mostrarLogin()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->route('home');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    /**
     * Redirige al panel correspondiente según el rol del usuario autenticado.
     */
    public function home(Request $request)
    {
        $rol = $request->user()->getRoleNames()->first();

        return match ($rol) {
            'solicitante' => redirect()->route('solicitante.index'),
            'compras' => redirect()->route('compras.index'),
            'gerencia' => redirect()->route('gerencia.index'),
            'contabilidad' => redirect()->route('contabilidad.index'),
            default => redirect()->route('solicitante.index'),
        };
    }
}
