<?php

namespace App\Http\Controllers\Consultas;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ConsultaController extends Controller
{
    public function personas(Request $request)
    {
        $query = \App\Models\Persona::with(['historialDelictivo.delito', 'direccion.parroquia.municipioObj.estadoObj']);
        if ($request->filled('q')) {
            $query->where('nun_documento', 'like', '%' . $request->q . '%')
                  ->orWhere('Nombre', 'like', '%' . $request->q . '%')
                  ->orWhere('Paterno', 'like', '%' . $request->q . '%');
        }
        $personas = $query->paginate(10);
        return view('consultas.personas', compact('personas'));
    }

    public function vehiculos(Request $request)
    {
        $query = \App\Models\Vehiculo::with(['marcaObj', 'propietarioObj']);
        if ($request->filled('q')) {
            $query->where('nun_placa', 'like', '%' . $request->q . '%')
                  ->orWhere('modelo', 'like', '%' . $request->q . '%')
                  ->orWhere('color', 'like', '%' . $request->q . '%');
        }
        // Load reportes separately since FK name might differ
        $vehiculos = $query->paginate(10);
        $vehiculos->load(['reportes' => function($q) {
            $q->orderBy('fecha_reporte', 'desc');
        }]);
        return view('consultas.vehiculos', compact('vehiculos'));
    }
}
