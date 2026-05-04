<?php

namespace App\Http\Controllers\Registro;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class HistorialDelictivoController extends Controller
{
    public function index()
    {
        $historiales = \App\Models\HistorialDelictivo::with(['persona', 'delito'])->paginate(10);
        $personas = \App\Models\Persona::all();
        $delitos = \App\Models\Delito::all();
        return view('registro.historial.index', compact('historiales', 'personas', 'delitos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'persona_id' => 'required|exists:persona,nun_documento',
            'delito_id' => 'required|exists:delito,delito_id',
            'fecha_delito' => 'required|date',
            'descripcion' => 'required|string',
            'estatus' => 'required|string',
        ]);

        \App\Models\HistorialDelictivo::create($request->all());
        return redirect()->route('historial.index')->with('success', 'Historial registrado.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'persona_id' => 'required|exists:persona,nun_documento',
            'delito_id' => 'required|exists:delito,delito_id',
            'fecha_delito' => 'required|date|before_or_equal:today',
            'descripcion' => 'required|string',
            'estatus' => 'required|in:procesado,en_investigacion,cerrado',
        ]);

        $historial = \App\Models\HistorialDelictivo::findOrFail($id);
        $historial->update($request->all());

        return redirect()->route('historial.index')->with('success', 'Historial actualizado.');
    }

    public function destroy(string $id)
    {
        $historial = \App\Models\HistorialDelictivo::findOrFail($id);
        $historial->delete();

        return redirect()->route('historial.index')->with('success', 'Registro eliminado.');
    }
}
