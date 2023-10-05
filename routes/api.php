<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\UniversidadController;
use App\Http\Controllers\UniversidadesCarreraController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'universidades'], function () {
    // Ruta para listar todas las universidades
    Route::get('/', [UniversidadController::class, 'index']);
    // Ruta para mostrar los detalles de una universidad específica por su ID
    Route::get('/{id}', [UniversidadController::class, 'show']);
    // Ruta para crear una nueva universidad
    Route::post('/', [UniversidadController::class, 'store']);
    // Ruta para actualizar los detalles de una universidad por su ID
    Route::put('/{id}', [UniversidadController::class, 'update']);
    // Ruta para eliminar una universidad por su ID
    Route::delete('/{id}', [UniversidadController::class, 'destroy']);
    
    Route::post('/', [UniversidadController::class, 'crearUniversidadYAsociarCarreras']);
   
});

Route::group(['prefix' => 'carreras'], function () {
    Route::get('/', [CarreraController::class, 'index']);
    Route::get('/{id}', [CarreraController::class, 'show']);
    Route::post('/', [CarreraController::class, 'store']);
    Route::put('/{id}', [CarreraController::class, 'update']);
    Route::delete('/{id}', [CarreraController::class, 'destroy']);
});

Route::post('/universidades/{universidadId}/carreras/registrar', [UniversidadesCarreraController::class, 'registrarRelacionUniversidadCarreras']);
Route::post('/universidades/{universidadId}/carreras/desasociar', [UniversidadesCarreraController::class, 'desasociarRelacionUniversidadCarreras']);

Route::get('/universidades-con-carreras', [UniversidadController::class, 'listarUniversidadesConCarreras']);
Route::post('/universidades-con-carreras', [UniversidadController::class, 'crearUniversidadYAsociarCarreras']);
Route::put('/universidades-con-carreras/{id}', [UniversidadController::class, 'update']);
Route::delete('/universidades-con-carreras/{id}', [UniversidadController::class, 'destroy']);
