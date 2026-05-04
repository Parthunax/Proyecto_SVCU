<?php

namespace App\Http\Controllers\Ubicaciones;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ParroquiaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['parroquia' => 'required|string|max:250', 'municipio_id' => 'required|exists:municipios,municipio_id']);
        \App\Models\Parroquia::create(['parroquia' => $request->parroquia, 'municipio_id' => $request->municipio_id]);
        return redirect()->route('ubicaciones.index')->with('success', 'Parroquia registrada.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['parroquia' => 'required|string|max:250', 'municipio_id' => 'required|exists:municipios,municipio_id']);
        $parroquia = \App\Models\Parroquia::findOrFail($id);
        $parroquia->update(['parroquia' => $request->parroquia, 'municipio_id' => $request->municipio_id]);
        return redirect()->route('ubicaciones.index')->with('success', 'Parroquia actualizada.');
    }

    public function destroy(string $id)
    {
        $parroquia = \App\Models\Parroquia::findOrFail($id);
        $parroquia->delete();
        return redirect()->route('ubicaciones.index')->with('success', 'Parroquia eliminada.');
    }
}
