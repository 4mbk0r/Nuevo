<?php

use App\Http\Controllers\CitaController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PdfViewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonaAntiguoController;
use App\Models\personas_antiguo;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


 
Route::get('articles', function($id) {
    return PersonaAntiguoController::mostrar($id);
});    

Route::get('/citas_fecha/{id}', function ($id) {
    return CitaController::mostrar($id);
});

Route::post('/datos_usuario/{id}', function ($id) {
    return CitaController::usuario($id);
});

Route::post('/datos_citas/{id}', function ($id) {
    return CitaController::cita_usuario($id);
});

Route::post('/citas_actuales/{fecha}', function ($fecha) {
    return CitaController::citas_disponibles($fecha);
});

Route::post('/guardar_citas', function (Request $request) {
    return CitaController::guardar($request);
});


Route::post('/update_usuario', function (Request $request) {
    return CitaController::update_persona($request);
});



Route::post('/dar_cita', function (Request $request) {
    return CitaController::dar_cita($request);
});


Route::post('/imprimir', function (Request $request) {
    return PdfViewController::store($request);
});


Route::middleware('auth:sanctum')->Route::post('/doctor', function (Request $request) {
    return DoctorController::listadoctor($request);
});