<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NotasController;
use App\Http\Controllers\NotasSubtemaController;
use App\Http\Controllers\AreasController;
use App\Http\Controllers\MultimediaNotasController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('register', [AuthController::class, 'register']);
});

Route::group([
    'prefix' => 'notas'
], function ($router) {
    Route::post('crear', [NotasController::class, 'crear']); 
    Route::put('actualizar', [NotasController::class, 'actualizar']);
    Route::get('consultar/todo', [NotasController::class, 'consultarTodo']);
    Route::get('consultar/notas-usuario/{id_usuario}', [NotasController::class, 'consultarNotasUsuario']);
    Route::get('consultar/nota-usuario-tema/{tema}', [NotasController::class, 'consultarNotaUsuarioNombre']);
});

Route::group([
    'prefix' => 'subtemas'
], function ($router) {
    Route::post('crear', [NotasSubtemaController::class, 'crearSubtema']);
    Route::put('actualizar', [NotasSubtemaController::class, 'actualizar']);
    Route::get('consultar/todo', [NotasSubtemaController::class, 'consultarTodo']);
    Route::get('consultar/{id_nota}', [NotasSubtemaController::class, 'consultar']);
    Route::get('consultar/nota-subtemas/{idNota}', [NotasSubtemaController::class, 'consultarSubtemasNota']);
    Route::post('guardar-archivo', [NotasSubtemaController::class, 'guardarArchivo']);
});

Route::group([
    'prefix' => 'areas'
], function ($router) {
    Route::get('consultar/areas', [AreasController::class, 'consultarTodaAreas']);
    Route::get('consultar/subareas', [AreasController::class, 'consultarTodaSubareas']);
    Route::get('consultar/subareas/{idArea}', [AreasController::class, 'consultarSubareasEspecifica']);
});

Route::group([
    'prefix' => 'multimedia'
], function ($router) {
    Route::post('guardar-archivo', [MultimediaNotasController::class, 'guardarArchivo']);
    Route::post('traer-archivo', [MultimediaNotasController::class, 'traerArchivo']);
    Route::post('descargar-archivo', [MultimediaNotasController::class, 'descargarArchivo']);
});

