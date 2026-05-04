<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckJefe
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->rolObj->nombre_rol !== 'comisario') {
            return redirect()->route('dashboard')->with('error', 'Acceso denegado. Solo el Comisario puede acceder a esta área.');
        }

        return $next($request);
    }
}
