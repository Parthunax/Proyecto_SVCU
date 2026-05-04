<?php

namespace App\Http\Controllers\Ubicaciones;
use App\Http\Controllers\Controller;

class UbicacionController extends Controller
{
    public function index()
    {
        $estados = \App\Models\Estado::paginate(10, ['*'], 'estados_page');
        $municipios = \App\Models\Municipio::with('estadoObj')->paginate(10, ['*'], 'municipios_page');
        $parroquias = \App\Models\Parroquia::with('municipioObj')->paginate(10, ['*'], 'parroquias_page');

        $todosEstados = \App\Models\Estado::all();
        $todosMunicipios = \App\Models\Municipio::all();

        return view('ubicaciones.index', compact('estados', 'municipios', 'parroquias', 'todosEstados', 'todosMunicipios'));
    }
}
