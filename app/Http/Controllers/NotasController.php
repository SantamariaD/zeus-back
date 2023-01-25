<?php

namespace App\Http\Controllers;

use App\Models\Nota;
use App\Respuestas\Respuestas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NotasController extends Controller
{
    public function crear(Request $request)
    {
        /**
         *  Método para crear una nota hecha por el usuario
         */

        $validator = Validator::make($request->all(), [
            'idUsuario' => 'int|required',
            'idAreaConocimiento' => 'int|required',
            'idSubarea' => 'int|required',
            'tema' => 'string|required',
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $uuid = Str::orderedUuid();
        $idUsuario = $request->input('idUsuario');
        $archivo = $request->file('file');
        $extension = $archivo->getClientOriginalExtension();

        $archivo->storeAs(
            "/" . $idUsuario,
            $uuid . '.' . $extension,
            'notas'
        );

        $nota = new Nota();
        $nota->user_id = $request->idUsuario;
        $nota->area_id = $request->idAreaConocimiento;
        $nota->uuid = $uuid;
        $nota->imagen = $uuid . '.' . $extension;
        $nota->tema = $request->tema;

        $nota->save();

        $nota = Nota::where('uuid', $uuid)->get();

        return response()->json(Respuestas::respuesta200('Se creo la nota.', $nota[0]));
    }

    public function actualizar(Request $request)
    {
        /**
         *  Método para actualizar una nota hecha por el usuario
         */

        $idUsuario = $request->idUsuario;
        $idNota = $request->idNota;

        $validator = Validator::make($request->all(), [
            'idUsuario' => 'int|required',
            'idNota' => 'int|required',
            'idAreaConocimiento' => 'int|required',
            'idSubareaConocimiento' => 'int|required',
            'tema' => 'string|required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        unset($request->idUsuario);

        $datosActualizado = [
            'id_area_conocimiento' => $request->idAreaConocimiento,
            'id_subarea' => $request->idSubareaConocimiento,
            'tema' => $request->tema,
        ];

        Nota::where('id_user', $idUsuario)
            ->where('id', $idNota)
            ->update($datosActualizado);

        return response()->json(Respuestas::respuesta200NoResultados('Se actualizó la nota.'));
    }

    public function consultarNotasUsuario($id_usuario)
    {
        /**
         *  Método para consultar una nota hecha por el usuario
         */

        if (!$id_usuario) {
            return response()->json(Respuestas::respuesta400('No se tiene el id del usuario.'));
        }

        $notas = Nota::join("areas_conocimiento", "notas.area_id", "=", "areas_conocimiento.id")
            ->select("notas.*", "areas_conocimiento.area")
            ->where('user_id', $id_usuario)
            ->get();

        return response()->json(Respuestas::respuesta200('Consulta exitosa.', $notas));
    }

    public function consultarNotaImagen($idUsuario, $imagen)
    {
        /**
         *  Método para consultar una nota hecha por el usuario
         */

        if (!$idUsuario and !$imagen) {
            return response()->json(Respuestas::respuesta400('No se tiene el id del usuario.'));
        }

        return Storage::disk('notas')->get("/" . $idUsuario . '/' . $imagen);

        return response()->json(Respuestas::respuesta200('Consulta exitosa.', $imagen));
    }

    public function consultarNotaUsuarioNombre($tema)
    {
        /**
         *  Método para consultar una nota hecha por el usuario con base en su nombre
         */

        if (!$tema) {
            return response()->json(Respuestas::respuesta400('No se tiene el tema a buscar.'));
        }

        $nota = Nota::where('tema', $tema)->get();

        return response()->json(Respuestas::respuesta200('Consulta exitosa.', $nota));
    }

    public function consultarTodo()
    {
        /**
         *  Método para consultaer todas las notas
         */

        return response()->json(
            Respuestas::respuesta200(
                'Consulta exitosa.',
                Nota::all()
            )
        );
    }
}
