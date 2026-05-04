<?php

namespace App\Http\Controllers\Gestion;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $usuarios = \App\Models\Usuario::with('rolObj')->paginate(10);
        $roles = \App\Models\Rol::all();
        return view('gestion.usuarios.index', compact('usuarios', 'roles'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'rol' => 'required|exists:roles,rol_id',
            'estadus' => 'required|in:activo,inactivo'
        ]);

        $usuario = \App\Models\Usuario::findOrFail($id);
        
        // Prevent Jefe from deactivating themselves
        if ($usuario->usuario_id === \Illuminate\Support\Facades\Auth::id() && $request->estadus === 'inactivo') {
            return back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }

        $usuario->update([
            'rol' => $request->rol,
            'estadus' => $request->estadus
        ]);

        return redirect()->route('usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(string $id)
    {
        return back()->with('error', 'Para eliminar un usuario, debe eliminar el registro del Policía asociado en el módulo Policías.');
    }
}
