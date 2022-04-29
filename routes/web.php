<?php

use App\Http\Controllers\CitaController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PersonaAntiguoController;
use App\Http\Controllers\PersonaCitaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return 'hola';
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return 'ddd';
})->name('Dashboard');

Route::middleware(['auth:sanctum', 'verified'])->get('/inicio', [CitaController::class, 'index'],)->name('inicio');

Route::resource('/agendar', CitaController::class)->middleware(['auth:sanctum', 'verified']);


Route::middleware(['auth:sanctum', 'verified'])->get('/registra', function () {
    return Inertia::render('Registro');;
})->name('registro');

Route::resource('/persona_antigua', PersonaAntiguoController::class)->middleware(['auth:sanctum', 'verified']);
/*
Route::middleware(['auth:sanctum', 'verified'])->get('/administrar', function () {
    return Inertia::render('Administrador');
})->name('administrar');
**/
Route::resource('/administrar', PersonaCitaController::class)->middleware(['auth:sanctum', 'verified']);
