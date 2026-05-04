<?php

namespace App\Http\Controllers\Gestion;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PoliciaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $policias = \App\Models\Policia::with('usuarioObj', 'usuarioObj.rolObj')->paginate(10);
        $roles = \App\Models\Rol::all();
        return view('gestion.policias.index', compact('policias', 'roles'));
    }

    public function store(Request $request)
    {
        // Compose document from type + number
        $documentoCompleto = $request->doc_type . '-' . $request->doc_number;
        $request->merge(['nun_documento' => $documentoCompleto]);

        $request->validate([
            'nun_documento' => [
                'required', 'string', 'max:12',
                Rule::unique('policia', 'nun_documento')->whereNull('deleted_at'),
            ],
            'nombre' => 'required|string|max:30',
            'apellido' => 'required|string|max:30',
            'sexo' => 'required|in:M,F',
            'fecha_nac' => 'required|date|before_or_equal:today',
            'telefono' => 'nullable|string|max:15',
            'especialidad' => 'required|string|max:50',
            'Grado' => 'required|string|max:30',
            'usuario' => [
                'required', 'string', 'max:50',
                Rule::unique('usuarios', 'usuario')->whereNull('deleted_at'),
            ],
            'Contrasena' => 'required|string|min:6',
            'rol' => 'required|exists:roles,rol_id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('imagenes_db/perfiles'), $filename);
                $fotoPath = 'imagenes_db/perfiles/' . $filename;
            }

            // Create Usuario
            $usuario = \App\Models\Usuario::create([
                'nun_documento' => $request->nun_documento,
                'usuario' => $request->usuario,
                'Contrasena' => \Illuminate\Support\Facades\Hash::make($request->Contrasena),
                'rol' => $request->rol,
                'Foto' => $fotoPath,
                'estadus' => 'activo',
                'ultimo_acceso' => now()
            ]);

            // Create Policia
            \App\Models\Policia::create([
                'usuario_id' => $usuario->usuario_id,
                'nun_documento' => $request->nun_documento,
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'sexo' => $request->sexo,
                'fecha_nac' => $request->fecha_nac,
                'telefono' => $request->telefono,
                'especialidad' => $request->especialidad,
                'Grado' => $request->Grado,
            ]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('policias.index')->with('success', 'Policía registrado correctamente.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Error al registrar el policía: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $policia = \App\Models\Policia::findOrFail($id);
        $usuario = $policia->usuarioObj;

        // En edición NO se modifica el documento
        $request->validate([
            'nombre' => 'required|string|max:30',
            'apellido' => 'required|string|max:30',
            'sexo' => 'required|in:M,F',
            'fecha_nac' => 'required|date|before_or_equal:today',
            'telefono' => 'nullable|string|max:15',
            'especialidad' => 'required|string|max:50',
            'Grado' => 'required|string|max:30',
            'usuario' => [
                'required', 'string', 'max:50',
                Rule::unique('usuarios', 'usuario')->whereNull('deleted_at')->ignore($usuario->usuario_id, 'usuario_id'),
            ],
            'Contrasena' => 'nullable|string|min:6',
            'rol' => 'required|exists:roles,rol_id',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('imagenes_db/perfiles'), $filename);
                
                if ($usuario->Foto && file_exists(public_path($usuario->Foto))) {
                    unlink(public_path($usuario->Foto));
                }
                $usuario->Foto = 'imagenes_db/perfiles/' . $filename;
            }

            if ($request->filled('Contrasena')) {
                $usuario->Contrasena = \Illuminate\Support\Facades\Hash::make($request->Contrasena);
            }

            $usuario->usuario = $request->usuario;
            $usuario->rol = $request->rol;
            $usuario->save();

            // NO se modifica nun_documento por seguridad
            $policia->update([
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'sexo' => $request->sexo,
                'fecha_nac' => $request->fecha_nac,
                'telefono' => $request->telefono,
                'especialidad' => $request->especialidad,
                'Grado' => $request->Grado,
            ]);

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('policias.index')->with('success', 'Datos del policía actualizados.');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Error al actualizar el policía: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            \Illuminate\Support\Facades\DB::beginTransaction();
            $policia = \App\Models\Policia::findOrFail($id);
            $usuario = $policia->usuarioObj;

            $policia->delete();
            if($usuario) {
                $usuario->delete();
            }

            \Illuminate\Support\Facades\DB::commit();
            return redirect()->route('policias.index')->with('success', 'Policía y usuario eliminados (Soft Delete).');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return back()->with('error', 'Error al eliminar el policía: ' . $e->getMessage());
        }
    }
}
