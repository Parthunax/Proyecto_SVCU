<?php

namespace App\Http\Controllers\Ubicaciones;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class MunicipioController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['municipio' => 'required|string|max:100', 'estado_id' => 'required|exists:estados,estado_id']);
        \App\Models\Municipio::create(['municipio' => $request->municipio, 'estado_id' => $request->estado_id]);
        return redirect()->route('ubicaciones.index')->with('success', 'Municipio registrado.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['municipio' => 'required|string|max:100', 'estado_id' => 'required|exists:estados,estado_id']);
        $municipio = \App\Models\Municipio::findOrFail($id);
        $municipio->update(['municipio' => $request->municipio, 'estado_id' => $request->estado_id]);
        return redirect()->route('ubicaciones.index')->with('success', 'Municipio actualizado.');
    }

    public function destroy(string $id)
    {
        $municipio = \App\Models\Municipio::findOrFail($id);
        $municipio->delete();
        return redirect()->route('ubicaciones.index')->with('success', 'Municipio eliminado.');
    }
}
