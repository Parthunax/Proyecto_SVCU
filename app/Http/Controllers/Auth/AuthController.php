<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'usuario' => ['required'],
            'password' => ['required'],
        ]);

        // Primero: verificar si el usuario existe pero está bloqueado/inactivo
        $usuario = \App\Models\Usuario::where('usuario', $credentials['usuario'])->first();
        if ($usuario && $usuario->estadus !== 'activo') {
            return back()->withErrors([
                'usuario' => 'Usted ha sido bloqueado.',
            ])->onlyInput('usuario');
        }

        // Segundo: intentar login normal (solo usuarios activos)
        if (Auth::attempt(['usuario' => $credentials['usuario'], 'password' => $credentials['password'], 'estadus' => 'activo'])) {
            $request->session()->regenerate();

            // Actualizar ultimo_acceso
            $user = Auth::user();
            $user->ultimo_acceso = now();
            $user->save();

            return redirect()->route('dashboard');
        }

        // Si llega aquí: contraseña incorrecta o usuario no existe
        return back()->withErrors([
            'usuario' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('usuario');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
