<?php

namespace App\Http\Controllers;

use App\Models\NotaSubtema;
use App\Respuestas\Respuestas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotasSubtemaController extends Controller
{
    public function crear(Request $request)
    {
        /**
         *  Método para crear un subtema de la nota hecha por el usuario
         */

        $datos_request = array_map('trim', $request->all());

        $validator = Validator::make($request->all(), [
            'id_nota' => 'int|required',
            'subtema' => 'string|required|max:120',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $notasSubtema = new NotaSubtema();
        $notasSubtema->id_nota = $datos_request['id_nota'];
        $notasSubtema->subtema = $datos_request['subtema'];

        $notasSubtema->save();

        return response()->json(Respuestas::respuesta200NoResultados('Se creo la nota.'));
    }

    public function actualizar(Request $request)
    {
        /**
         *  Método para actualizar un subtema de la nota hecha por el usuario
         */

        $datos_request = array_map('trim', $request->all());
        $id_subtema = $datos_request['id'];

        $validator = Validator::make($request->all(), [
            'id' => 'int|required',
            'subtema' => 'string|required|max:120',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        unset($datos_request['id']);

        NotaSubtema::where('id', $id_subtema)
            ->update($datos_request);

        return response()->json(Respuestas::respuesta200NoResultados('Se actualizó el subtema de la nota.'));
    }

    public function consultar($id_nota)
    {
        /**
         *  Método para consultaer un subtema de la nota hecha por el usuario
         */

        if (!$id_nota) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $subtemas = NotaSubtema::where('id', $id_nota)->get();

        return response()->json(Respuestas::respuesta200('Consulta exitosa.', $subtemas));
    }

    public function consultarTodo()
    {
        /**
         *  Método para consultaer todos los subtemas de la nota hecha por el usuario
         */

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                NotaSubtema::all())
        );
    }
}
