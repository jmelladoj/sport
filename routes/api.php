<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Rutas de usuarios
Route::apiResource('especialidades', 'EspecialidadController');

//Rutas de servicios
Route::apiResource('servicios', 'ServicioController')->except(['index']);
Route::get('/servicio/{id}', 'ServicioController@index')->name('servicios.index');

//Rutas de profesionales
Route::apiResource('profesionales', 'ProfesionalController');
Route::get('/profesionales/unico/{texto}', 'ProfesionalController@unico')->name('profesional.unico');

//Rutas de clientes
Route::apiResource('clientes', 'ClienteController');
Route::get('/clientes/unico/{texto}', 'ClienteController@unico')->name('cliente.unico');

//Rutas de horarios
Route::get('/horarios/dias/{accion}/{semana}', 'HomeController@dias')->name('Listar dias');

//Rutas de reservas
Route::apiResource('reservas', 'ReservaController')->except(['index']);
Route::get('/reservas/{fecha_inicio}/{fecha_termino}', 'ReservaController@index')->name('reservas.index');

//Rutas de usuarios
Route::apiResource('horarios', 'HoraClinicaController');