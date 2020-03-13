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

Route::get('/', function () {
    return view('welcome');
});

//Ruta de usuario
Route::get('/usuario/logeado', 'UsuarioController@logeado')->name('Usuario logeado');

//Rutas de clientes
Route::get('/clientes/usuario/{consulta}', 'ClienteController@index')->name('Listar clientes');
Route::post('/cliente/crear/actualizar', 'ClienteController@crear_actualizar')->name('Crear o actualizar cliente');
Route::post('/cliente/borrar', 'ClienteController@borrar')->name('Borrar cliente');
Route::get('/clientes/unico/{texto}', 'ClienteController@validar_unico')->name('Validar cliente existente');

//Rutas de especialidades
Route::get('/especialidades/usuario/{consulta}', 'EspecialidadController@index')->name('Listar especialidades');
Route::post('/especialidad/crear/actualizar', 'EspecialidadController@crear_actualizar')->name('Crear o actualizar especialidad');
Route::post('/especialidad/borrar', 'EspecialidadController@borrar')->name('Borrar cliente');
Route::get('/especialidades/unico/{texto}', 'EspecialidadController@validar_unico')->name('Validar cliente existente');

//Rutas de horas
Route::get('/horas/usuario/{consulta}', 'HoraClinicaController@index')->name('Listar horas');
Route::post('/hora/crear/actualizar', 'HoraClinicaController@crear_actualizar')->name('Crear o actualizar hora');
Route::post('/hora/borrar', 'HoraClinicaController@borrar')->name('Borrar cliente');
Route::get('/horas/unico/{texto}', 'HoraClinicaController@validar_unico')->name('Validar cliente existente');

//Rutas de profesionales
Route::get('/profesionales/usuario/{consulta}', 'ProfesionalController@index')->name('Listar profesionales');
Route::post('/profesional/crear/actualizar', 'ProfesionalController@crear_actualizar')->name('Crear o actualizar profesional');
Route::post('/profesional/borrar', 'ProfesionalController@borrar')->name('Borrar profesional');
Route::get('/profesionales/unico/{texto}', 'ProfesionalController@validar_unico')->name('Validar profesional existente');

//Rutas de servicios
Route::get('/servicios/usuario/{consulta}', 'ServicioController@index')->name('Listar servicios');
Route::post('/servicio/crear/actualizar', 'ServicioController@crear_actualizar')->name('Crear o actualizar servicio');
Route::post('/servicio/borrar', 'ServicioController@borrar')->name('Borrar profesional');

//Rutas de horarios
Route::get('/horarios/dias/{accion}/{semana}', 'HomeController@dias')->name('Listar dias');

//Rutas de reservas
Route::get('/reservas/{fecha_inicio}/{fecha_termino}', 'ReservaController@index')->name('Listar reservas');
Route::get('/horas/profesional/{id}/{fecha}', 'ReservaController@index_horas_profesional')->name('Listar horas por profesional');
Route::post('/reserva/agendar', 'ReservaController@agendar')->name('Agendar reserva');
Route::post('/reserva/actualizar/estado', 'ReservaController@actualizar_estado_reserva')->name('Agendar reserva');

Route::get('/{any}', 'HomeController@index')->where('any', '.*')->name('home');

Auth::routes();
