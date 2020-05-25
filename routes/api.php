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

Route::post('/auth/login', 'Auth\LoginController@login')->name('login');

Route::middleware(['auth:api'])->group(function () {
    Route::get('/user', function (Request $request) {return $request->user();});

    //Rutas de usuarios
    Route::apiResource('especialidades', 'EspecialidadController');

    //Rutas de horarios
    Route::apiResource('horarios/clinica', 'HoraClinicaController');
    Route::get('/horarios/clinica/unico/{texto}', 'HoraClinicaController@unico')->name('horario_clinica.unico');

    //Rutas de horarios de profesional
    Route::apiResource('horarios/profesional', 'HorarioProfesionalController');
    Route::post('/disponibilidad/profesional/', 'HorarioProfesionalController@index_disponibilidad')->name('horarios_profesional.index.disponibildiad');
    //Rutas de servicios
    Route::apiResource('servicios', 'ServicioController')->except(['index']);
    Route::get('/servicio/{id}', 'ServicioController@index')->name('servicios.index');
    Route::get('/servicios', 'ServicioController@index_servicios')->name('servicios.index.servicios');

    //Rutas de profesionales
    Route::apiResource('profesionales', 'ProfesionalController');
    Route::get('/profesionales/unico/{texto}', 'ProfesionalController@unico')->name('profesional.unico');
    

    //Rutas de clientes
    Route::apiResource('clientes', 'ClienteController');
    Route::get('/clientes/reservas/{id}', 'ClienteController@reservas')->name('reserva.cliente');
    Route::get('/clientes/unico/{texto}', 'ClienteController@unico')->name('cliente.unico');

    //Rutas de horarios
    Route::get('/horario/dias/{accion}/{semana}', 'HoraClinicaController@dias')->name('Listar dias');

    //Rutas de reservas
    Route::apiResource('reservas', 'ReservaController')->except(['index']);
    Route::get('/reservas/{fecha_inicio}/{fecha_termino}', 'ReservaController@index')->name('reservas.index');
    Route::put('/pago/reserva/{id}', 'ReservaController@pago')->name('reservas.pago');

    //Rutas de reservas
    Route::apiResource('ventas', 'VentaController');

    //Rutas de usuarios
    Route::apiResource('horarios', 'HoraClinicaController');

    //Rutas de usuarios
    Route::apiResource('usuarios', 'UsuarioController');
    Route::get('/usuarios/unico/{texto}', 'UsuarioController@unico')->name('usuario.unico');  

    Route::apiResource('/detalle/reserva', 'DetalleReservaController');
});

