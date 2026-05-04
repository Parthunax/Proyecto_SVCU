<?php

namespace App\Http\Controllers\Registro;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ReporteVehiculoController extends Controller
{
    public function index()
    {
        $reportes = \App\Models\ReporteVehiculo::with('vehiculoObj')->paginate(10);
        $vehiculos = \App\Models\Vehiculo::all();
        return view('registro.reportes.index', compact('reportes', 'vehiculos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nun_placa' => 'required|exists:vehiculo,nun_placa',
            'tipo_reporte' => 'required|string',
            'fecha_reporte' => 'required|date',
        ]);

        \App\Models\ReporteVehiculo::create($request->all());
        return redirect()->route('reportes.index')->with('success', 'Reporte registrado.');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nun_placa' => 'required|exists:vehiculo,nun_placa',
            'tipo_reporte' => 'required|string|max:50',
            'fecha_reporte' => 'required|date|before_or_equal:today',
        ]);

        $reporte = \App\Models\ReporteVehiculo::findOrFail($id);
        $reporte->update($request->all());

        return redirect()->route('reportes.index')->with('success', 'Reporte actualizado.');
    }

    public function destroy(string $id)
    {
        $reporte = \App\Models\ReporteVehiculo::findOrFail($id);
        $reporte->delete();

        return redirect()->route('reportes.index')->with('success', 'Reporte eliminado.');
    }
}
