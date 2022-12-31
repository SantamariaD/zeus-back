<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Respuestas\Respuestas;
use App\Models\Area;
use App\Models\Subarea;

class AreasController extends Controller
{

    public function consultarTodaAreas()
    {
        /**
         *  Método para consultaer todas las áreas
         */

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                Area::all())
        );
    }

    public function consultarSubareasEspecifica($id_nota)
    {
        /**
         *  Método para consultar las subáreas de un área
         */

        if (!$id_nota) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $subareas = Subarea::where('id_area_conocimiento', $id_nota)->get();

        return response()->json(Respuestas::respuesta200('Consulta exitosa.', $subareas));
    }
    
}
