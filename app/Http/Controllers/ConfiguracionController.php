<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    public function index()
    {
        return view('configuracion.index');
    }

    public function update(Request $request)
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        $request->validate([
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        // Cambiar Contraseña
        if ($request->filled('current_password') || $request->filled('new_password')) {
            if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->Contrasena)) {
                return back()->with('error', 'La contraseña actual es incorrecta.');
            }
            if (!$request->filled('new_password')) {
                return back()->with('error', 'Debe ingresar la nueva contraseña.');
            }
            $user->Contrasena = \Illuminate\Support\Facades\Hash::make($request->new_password);
        }

        // Subir Foto
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            // Move to public/imagenes_db
            $file->move(public_path('imagenes_db/perfiles'), $filename);
            
            // Delete old photo if it exists and is not a default
            if ($user->Foto && file_exists(public_path($user->Foto))) {
                unlink(public_path($user->Foto));
            }
            
            $user->Foto = 'imagenes_db/perfiles/' . $filename;
        }

        $user->save();

        return redirect()->route('configuracion.index')->with('success', 'Configuración actualizada correctamente.');
    }
}
