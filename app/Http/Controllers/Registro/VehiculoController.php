<?php

namespace App\Http\Controllers\Registro;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Vehiculo;
use App\Models\MarcaVehiculo;
use Illuminate\Support\Facades\Auth;

class VehiculoController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vehiculos = Vehiculo::with(['propietarioObj', 'marcaObj'])->paginate(10);
        $marcas = MarcaVehiculo::all();
        $personas = \App\Models\Persona::all();
        return view('registro.vehiculos.index', compact('vehiculos', 'marcas', 'personas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nun_placa' => 'required|string|max:7|unique:vehiculo,nun_placa',
            'tipo_vehiculo' => 'required|string',
            'propietario' => 'required|exists:persona,nun_documento',
            'marca' => 'required|exists:marcas_vehiculos,marca_id',
            'modelo' => 'required|string',
            'color' => 'required|string',
            'año' => 'required|integer',
            'serial_carroceria' => 'required|string|unique:vehiculo,serial_carroceria',
        ]);

        Vehiculo::create($request->all());

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo registrado exitosamente.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'tipo_vehiculo' => 'required|string',
            'propietario' => 'required|exists:persona,nun_documento',
            'marca' => 'required|exists:marcas_vehiculos,marca_id',
            'modelo' => 'required|string',
            'color' => 'required|string',
            'año' => 'required|integer',
        ]);

        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->update($request->all());

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $vehiculo = Vehiculo::findOrFail($id);
        $vehiculo->delete();

        return redirect()->route('vehiculos.index')->with('success', 'Vehículo eliminado correctamente.');
    }
}
