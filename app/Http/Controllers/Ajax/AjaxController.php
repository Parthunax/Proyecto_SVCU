<?php

namespace App\Http\Controllers\Ajax;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{
    public function getMunicipios($estado_id)
    {
        $municipios = \App\Models\Municipio::where('estado_id', $estado_id)->get();
        return response()->json($municipios);
    }

    public function getParroquias($municipio_id)
    {
        $parroquias = \App\Models\Parroquia::where('municipio_id', $municipio_id)->get();
        return response()->json($parroquias);
    }
}
