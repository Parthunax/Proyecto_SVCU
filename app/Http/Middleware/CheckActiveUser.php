<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Verifica en CADA petición que el usuario logueado siga activo.
 * Si un administrador inactiva a un usuario mientras está logueado,
 * este middleware lo desconectará en su próxima acción.
 */
class CheckActiveUser
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            // Re-leer el usuario desde la BD para obtener su estado actual
            $user = \App\Models\Usuario::find(Auth::id());

            // Si el usuario no existe o está inactivo, cerrar sesión
            if (!$user || $user->estadus !== 'activo') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')->withErrors([
                    'usuario' => 'Usted ha sido bloqueado.',
                ]);
            }
        }

        return $next($request);
    }
}
