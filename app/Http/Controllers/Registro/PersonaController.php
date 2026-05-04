<?php

namespace App\Http\Controllers\Registro;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Persona;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PersonaController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personas = Persona::with(['direccion.parroquia.municipioObj'])->paginate(10);
        $estados = \App\Models\Estado::all();
        return view('registro.personas.index', compact('personas', 'estados'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Unir documento
        $documentoCompleto = $request->doc_type . '-' . $request->doc_number;
        $request->merge(['nun_documento' => $documentoCompleto]);

        $request->validate([
            'nun_documento' => 'required|unique:persona,nun_documento|max:12',
            'Nombre' => 'required|string|max:100',
            'Paterno' => 'required|string|max:100',
            'Materno' => 'nullable|string|max:100',
            'Genero' => 'required|in:M,F',
            'EstadoCivil' => 'required|string|max:50',
            'Telefono' => 'nullable|string|max:20',
            'FechaNacimiento' => 'required|date|before_or_equal:today|after_or_equal:-121 years',
            'parroquia_id' => 'required|integer',
            'localidad' => 'required|string|max:200',
            'tipo_vivienda' => 'required|string|max:50',
            'ruta' => 'nullable|string|max:100',
            'nun_vivienda' => 'nullable|integer',
            'foto_cara' => 'nullable|image',
            'foto_huella' => 'nullable|image',
        ]);

        try {
            DB::beginTransaction();

            // 1. Guardar o crear la Dirección
            $direccion = \App\Models\Direccion::create([
                'parroquia_id' => $request->parroquia_id,
                'localidad' => $request->localidad,
                'tipo_vivienda' => $request->tipo_vivienda,
                'ruta' => $request->ruta,
                'nun_vivienda' => $request->nun_vivienda,
            ]);

            // Manejo de Imágenes
            $fotoCaraPath = null;
            $fotoHuellaPath = null;

            if ($request->hasFile('foto_cara')) {
                $file = $request->file('foto_cara');
                $filename = 'cara_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('imagenes_db/caras'), $filename);
                $fotoCaraPath = 'imagenes_db/caras/' . $filename;
            }

            if ($request->hasFile('foto_huella')) {
                $file = $request->file('foto_huella');
                $filename = 'huella_' . time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('imagenes_db/huellas'), $filename);
                $fotoHuellaPath = 'imagenes_db/huellas/' . $filename;
            }

            // 2. Guardar la Persona
            Persona::create([
                'nun_documento' => $request->nun_documento,
                'Nombre' => $request->Nombre,
                'Paterno' => $request->Paterno,
                'Materno' => $request->Materno,
                'Genero' => $request->Genero,
                'EstadoCivil' => $request->EstadoCivil,
                'Telefono' => $request->Telefono,
                'FechaNacimiento' => $request->FechaNacimiento,
                'vivienda_id' => $direccion->vivienda_id,
                'foto_cara' => $fotoCaraPath,
                'foto_huella' => $fotoHuellaPath,
            ]);

            DB::commit();

            return redirect()->route('personas.index')->with('success', 'Persona registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al registrar: ' . $e->getMessage())->withInput();
        }
    }

    public function update(Request $request, string $id)
    {
        $persona = Persona::findOrFail($id);

        $request->validate([
            'Nombre' => 'required|string|max:100',
            'Paterno' => 'required|string|max:100',
            'Materno' => 'nullable|string|max:100',
            'Genero' => 'required|in:M,F',
            'EstadoCivil' => 'required|string|max:50',
            'Telefono' => 'nullable|string|max:20',
            'FechaNacimiento' => 'required|date|before_or_equal:today|after_or_equal:-121 years',
            'parroquia_id' => 'required|integer',
            'localidad' => 'required|string|max:200',
            'tipo_vivienda' => 'required|string|max:50',
            'ruta' => 'nullable|string|max:100',
            'nun_vivienda' => 'nullable|integer',
        ]);

        try {
            DB::beginTransaction();

            $direccion = \App\Models\Direccion::findOrFail($persona->vivienda_id);
            $direccion->update([
                'parroquia_id' => $request->parroquia_id,
                'localidad' => $request->localidad,
                'tipo_vivienda' => $request->tipo_vivienda,
                'ruta' => $request->ruta,
                'nun_vivienda' => $request->nun_vivienda,
            ]);

            $persona->update([
                'Nombre' => $request->Nombre,
                'Paterno' => $request->Paterno,
                'Materno' => $request->Materno,
                'Genero' => $request->Genero,
                'EstadoCivil' => $request->EstadoCivil,
                'Telefono' => $request->Telefono,
                'FechaNacimiento' => $request->FechaNacimiento,
            ]);

            DB::commit();
            return redirect()->route('personas.index')->with('success', 'Persona actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $persona = Persona::findOrFail($id);
        $persona->delete(); // Soft delete

        return redirect()->route('personas.index')->with('success', 'Persona eliminada correctamente.');
    }
}
