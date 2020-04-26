<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/{vue_capture?}', function () {
    return view('inicio');
})->where('vue_capture', '[\/\w\.-]*');

//Ruta de usuario
//Route::get('/usuario/logeado', 'UsuarioController@logeado')->name('Usuario logeado');

//Rutas de reservas
//Route::get('/horas/profesional/{id}/{fecha}', 'ReservaController@index_horas_profesional')->name('Listar horas por profesional');
//Route::post('/reserva/agendar', 'ReservaController@agendar')->name('Agendar reserva');
//Route::post('/reserva/actualizar/estado', 'ReservaController@actualizar_estado_reserva')->name('Agendar reserva');

//Auth::routes();
 