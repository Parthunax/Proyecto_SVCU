<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (\Illuminate\Support\Facades\Auth::check() && in_array(\Illuminate\Support\Facades\Auth::user()->rolObj->nombre_rol, ['oficial', 'comisario'])) {
            return redirect()->route('dashboard')->with('error', 'No tienes permisos para acceder a esta área.');
        }

        return $next($request);
    }
}
