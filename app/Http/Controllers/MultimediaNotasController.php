<?php

namespace App\Http\Controllers;

use App\Models\NotaSubtema;
use App\Respuestas\Respuestas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class MultimediaNotasController extends Controller
{

    public function guardarArchivo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'idUsuario' => 'int|required',
            'idNota' => 'int|required',
            'uuid' => 'string|required',
            'file0' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $archivo = $request->file('file0');
        $idUsuario = $request->input('idUsuario');
        $UUID = Str::orderedUuid();
        $extension = $archivo->getClientOriginalExtension();

        $archivo->storeAs(
            "/" . $idUsuario . "/" . $UUID . '.' . $extension,
            $UUID,
            'notas'
        );

        $datos_request = array_map('trim', $request->all());

        $notaSuntema = new NotaSubtema();
        $notaSuntema->subtema = $datos_request['subtema'];
        $notaSuntema->id_nota = $datos_request['idNota'];
        $notaSuntema->uuid = $UUID;
        $notaSuntema->extension = $extension;

        $notasSubtema->save();

        return response()->json(Respuestas::respuesta200NoResultados('Archivo guardado.'));
    }

    public function traerArchivo(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'uuid' => 'string|required',
            'extension' => 'string|required',
            'idUsuario' => 'int|required',
        ]);

        if ($validator->fails()) {
            return response()->json(Respuestas::respuesta400($validator->errors()));
        }

        $UUID = $request->input('uuid');
        $extension = $request->input('extension');
        $idUsuario = $request->input('idUsuario');

        return Storage::disk('notas')->get($idUsuario . "/" . $UUID . "." . $extension);
    }

    public function descargarArchivo(Request $request)
    {
        $UUID = $request->input('uuid');
        $extension = $request->input('extension');
        $idUsuario = $request->input('idUsuario');

        return Storage::disk('notas')->download($idUsuario . "/" . $UUID . "." . $extension);
    }
}
