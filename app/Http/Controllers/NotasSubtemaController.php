<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Models\NotaSubtema;
use App\Models\Subtitulo;
use App\Respuestas\Respuestas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class NotasSubtemaController extends Controller
{
    public function crearSubtema(Request $request)
    {
        /**
         *  Método para crear un subtema de la nota hecha por el usuario
         */

        $datos_request = array_map('trim', $request->all());

        $validator = Validator::make($request->all(), [
            'idNota' => 'int|required',
            'subtema' => 'string|required|max:200',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $uuid = Str::orderedUuid();

        $notasSubtema = new NotaSubtema();
        $notasSubtema->nota_id = $datos_request['idNota'];
        $notasSubtema->subtema = $datos_request['subtema'];
        $notasSubtema->numeroSubtema = $datos_request['numeroSubtema'];
        $notasSubtema->uuid = $uuid;

        $notasSubtema->save();

        $notaSubtemaResponse = NotaSubtema::where('uuid', $uuid)->get();
        $respuesta = [
            "created_at" => $notaSubtemaResponse[0]->created_at,
            "id" => $notaSubtemaResponse[0]->id,
            "nota_id" => $notaSubtemaResponse[0]->nota_id,
            "numeroSubtema" => $notaSubtemaResponse[0]->numeroSubtema,
            "subtema" => $notaSubtemaResponse[0]->subtema,
            "updated_at" => $notaSubtemaResponse[0]->updated_at,
            "uuid" => $notaSubtemaResponse[0]->uuid,
            "subtitulos" => []
        ];

        return response()->json(Respuestas::respuesta200('Se creo el subtema.', $respuesta));
    }

    public function crearSubtitulo(Request $request)
    {
        /**
         *  Método para crear un subtitulo del subtema hecho por el usuario
         */

        $datos_request = array_map('trim', $request->all());

        $validator = Validator::make($request->all(), [
            'nota_id' => 'int|required',
            'id_subtema' => 'string|required|max:200',
            'subtitulo' => 'string|required',
            'html' => 'string|required',
            'numeroSubtitulo' => 'int|required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $subtitulo = new NotaSubtema();
        $subtitulo->nota_id = $datos_request['nota_id'];
        $subtitulo->id_subtema = $datos_request['id_subtema'];
        $subtitulo->subtitulo = $datos_request['subtitulo'];
        $subtitulo->html = $datos_request['html'];
        $subtitulo->numeroSubtitulo = $datos_request['numeroSubtitulo'];
        $subtitulo->uuid = $uuid;

        $subtitulo->save();

        return response()->json(Respuestas::respuesta200('Se creo el subtitulo.'));
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

    public function consultarSubtemasNota($idNota)
    {
        /**
         *  Método para consultar todos los subtemas de una nota
         */

        if (!$idNota) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $respuestaBD = Nota::join("areas_conocimiento", "notas.area_id", "=", "areas_conocimiento.id")
            ->join("subareas", "notas.subarea_id", "=", "subareas.id")
            ->select("notas.*", "areas_conocimiento.area", "subareas.subarea")
            ->with(['notasubtemas', 'subtitulos'])
            ->find($idNota);

        $subtitulos = $respuestaBD['subtitulos'];
        $notasSubtemas = $respuestaBD['notasubtemas'];
        unset($respuestaBD['notasubtemas']);
        unset($respuestaBD['subtitulos']);
        $subtemas = [];
        $subtitulosFiltrados = [];

        foreach ($notasSubtemas as $subtema) {
            foreach ($subtitulos as $subtitulo) {
                if ($subtitulo['subtema_id'] == $subtema['id']) {
                    array_push($subtitulosFiltrados, $subtitulo);
                }
            }
            $subtema['subtitulo'] = $subtitulosFiltrados;
            $subtitulosFiltrados = [];
        }

        $respuestaBD['subtemas'] = $notasSubtemas;

        return response()->json(Respuestas::respuesta200('Consulta exitosa.', $respuestaBD));

    }

}
