<?php

namespace App\Http\Controllers\Ubicaciones;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class EstadoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['estado' => 'required|string|max:250', 'iso_3166_2' => 'nullable|string|max:4']);
        \App\Models\Estado::create(['estado' => $request->estado, 'iso_3166-2' => $request->iso_3166_2]);
        return redirect()->route('ubicaciones.index')->with('success', 'Estado registrado.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate(['estado' => 'required|string|max:250', 'iso_3166_2' => 'nullable|string|max:4']);
        $estado = \App\Models\Estado::findOrFail($id);
        $estado->update(['estado' => $request->estado, 'iso_3166-2' => $request->iso_3166_2]);
        return redirect()->route('ubicaciones.index')->with('success', 'Estado actualizado.');
    }

    public function destroy(string $id)
    {
        $estado = \App\Models\Estado::findOrFail($id);
        $estado->delete();
        return redirect()->route('ubicaciones.index')->with('success', 'Estado eliminado.');
    }
}
