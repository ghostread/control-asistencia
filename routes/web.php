<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/coreui',function(){
    return view('coreui');
});

Route::resource('unidadacademica','UnidadAcademicaController');
Route::resource('user','UserController');
Route::resource('roles','RolController');

Route::resource('/herramientas','HerramientaController');

Route::resource('materias','MateriaController');
Route::resource('horarios','HorarioController');

Route::resource('clases','ClaseController');
Route::resource('/asistencias','AsistenciaController');
Route::resource('/personalacademico','PersonalAcademicoController');

Route::post('/pdfGenerate', 'PersonalAcademicoController@generarPdf')->name('pdf');
Route::post('/generarReporte', 'AsistenciaController@generarReportePdf');

Route::get('/auxiliar', 'AsistenciaController@auxiliar');