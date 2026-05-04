<?php

namespace App\Http\Controllers\Registro;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Delito;

class DelitoController extends Controller
{
    public function index()
    {
        $delitos = Delito::paginate(10);
        return view('registro.delitos.index', compact('delitos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'Tipo' => 'required|in:penal,faltas,medida cautelar,infraccion',
            'cargo_penal' => 'required|string|max:100',
        ]);

        Delito::create($request->all());
        return redirect()->route('delitos.index')->with('success', 'Delito registrado exitosamente.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:100',
            'Tipo' => 'required|in:penal,faltas,medida cautelar,infraccion',
            'cargo_penal' => 'required|string|max:100',
        ]);

        $delito = Delito::findOrFail($id);
        $delito->update($request->all());

        return redirect()->route('delitos.index')->with('success', 'Delito actualizado.');
    }

    public function destroy(string $id)
    {
        $delito = Delito::findOrFail($id);
        $delito->delete();

        return redirect()->route('delitos.index')->with('success', 'Delito eliminado.');
    }
}
