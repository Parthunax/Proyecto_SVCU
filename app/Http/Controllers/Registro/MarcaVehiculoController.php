<?php

namespace App\Http\Controllers\Registro;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class MarcaVehiculoController extends Controller
{
    public function index()
    {
        $marcas = \App\Models\MarcaVehiculo::paginate(10);
        return view('registro.marcas.index', compact('marcas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_marca' => 'required|string',
            'descripcion' => 'nullable|string',
        ]);

        \App\Models\MarcaVehiculo::create($request->all());
        return redirect()->route('marcas.index')->with('success', 'Marca registrada exitosamente.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre_marca' => 'required|string|max:100',
            'descripcion' => 'nullable|string',
        ]);

        $marca = \App\Models\MarcaVehiculo::findOrFail($id);
        $marca->update($request->all());

        return redirect()->route('marcas.index')->with('success', 'Marca actualizada.');
    }

    public function destroy(string $id)
    {
        $marca = \App\Models\MarcaVehiculo::findOrFail($id);
        $marca->delete();

        return redirect()->route('marcas.index')->with('success', 'Marca eliminada.');
    }
}
