<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Respuestas\Respuestas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotasController extends Controller
{
    public function crear(Request $request)
    {
        /**
         *  Método para crear una nota hecha por el usuario
         */

        $datos_request = array_map('trim', $request->all());

        $validator = Validator::make($request->all(), [
            'id_usuario' => 'int|required',
            'id_area_conocimiento' => 'int|required',
            'tema' => 'string|required|max:120',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $nota = new Nota();
        $nota->id_user = $datos_request['id_usuario'];
        $nota->id_area_conocimiento = $datos_request['id_area_conocimiento'];
        $nota->tema = $datos_request['tema'];

        $nota->save();

        return response()->json(Respuestas::respuesta200NoResultados('Se creo la nota.'));
    }

    public function actualizar(Request $request)
    {
        /**
         *  Método para actualizar una nota hecha por el usuario
         */

        $datos_request = array_map('trim', $request->all());
        $id_usuario = $datos_request['id_usuario'];
        $id_nota = $datos_request['id'];

        $validator = Validator::make($request->all(), [
            'id' => 'int|required',
            'id_usuario' => 'int|required',
            'id_area_conocimiento' => 'int|required',
            'tema' => 'string|required|max:120',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        unset($datos_request['id_usuario']);
        unset($datos_request['id']);

        Nota::where('id_user', $id_usuario)
            ->where('id', $id_nota)
            ->update($datos_request);

        return response()->json(Respuestas::respuesta200NoResultados('Se actualizó la nota.'));
    }

    public function consultar($id_nota)
    {
        /**
         *  Método para consultar una nota hecha por el usuario
         */

        if (!$id_nota) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $notas = Nota::where('id', $id_nota)->get();

        return response()->json(Respuestas::respuesta200('Se actualizó la nota.', $notas));
    }

    public function consultarTodo()
    {
        /**
         *  Método para consultaer todos los subtemas de la nota hecha por el usuario
         */

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                Nota::all())
        );
    }
}
