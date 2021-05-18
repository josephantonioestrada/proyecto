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
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Funciones para el controlador de rutas
Route::get('datRut',['as'=>'datosRutas','uses'=>'RutasController@datos_rutas']);
Route::get('formRut',['as'=>'formularioRuta','uses'=>'RutasController@formulario_ruta']);
Route::get('selBus/{id}',['as'=>'selBus','uses'=>'RutasController@seleccione_bus']);
Route::post('guaRu',['as'=>'guardarRuta','uses'=>'RutasController@guardar_ruta']);
Route::get('ediRu/{id}',['as'=>'editarRuta','uses'=>'RutasController@editar_ruta']);
Route::post('guaEdRuE/{id}',['as'=>'guarRutaEd','uses'=>'RutasController@guardar_ruta_editada']);
Route::get('finRut/{id}',['as'=>'finalizarRuta','uses'=>'RutasController@finalizar_ruta']);
Route::get('detRut/{id}',['as'=>'detalleRuta','uses'=>'RutasController@detalles_rutas']); 

//Rutas para controlador de municipalidad
Route::get('iniM',['as'=>'inicioMunicipalidades','uses'=>'MunicipalidadesController@inicio']);
Route::get('DatM',['as'=>'datosMunicipalidades','uses'=>'MunicipalidadesController@datos_inicio']);
Route::get('formMuni',['as'=>'formularioMunicipalidad','uses'=>'MunicipalidadesController@formulario_municipalidad']);
Route::post('guarMuni',['as'=>'guardarMunicipalidad','uses'=>'MunicipalidadesController@guardar_municipalidad']);

//Rutas para controlador de estaciones
Route::get('iniEst',['as'=>'inicioEstaciones','uses'=>'EstacionesController@inicio']);
Route::get('datEst',['as'=>'datosEstaciones','uses'=>'EstacionesController@datos_estaciones']);
Route::get('formEst',['as'=>'formularioEstacion','uses'=>'EstacionesController@formulario_estacion']);
Route::post('guarEst',['as'=>'guardarEstacion','uses'=>'EstacionesController@guardar_estacion']);
Route::get('detEst/{id}',['as'=>'detalleEstacion','uses'=>'EstacionesController@detalle_estacion']);
Route::get('detAcces/{id}',['as'=>'detallesAccesos','uses'=>'EstacionesController@detalles_accesos']);
Route::post('guaPar/{id}',['as'=>'guardarParqueo','uses'=>'EstacionesController@guardar_parqueo']);

//Rutas para controlador de Empleados
Route::get('iniE',['as'=>'inicioEmpleados','uses'=>'EmpleadoController@inicio']);
Route::get('datEmp',['as'=>'datosEmpleados','uses'=>'EmpleadoController@datos_empleados']);
Route::get('detEmp/{id}',['as'=>'detalleEmpleado','uses'=>'EmpleadoController@detalles_empleado']);
Route::get('formEmp',['as'=>'formularioEmpleado','uses'=>'EmpleadoController@formulario_empleado']);
Route::post('guaEmp',['as'=>'guardarEmpleado','uses'=>'EmpleadoController@guardar_empleado']);

//Rutas para controlador de buses
Route::get('iniBus',['as'=>'inicioBuses','uses'=>'BusesController@inicio']);
Route::get('datBus',['as'=>'datosBuses','uses'=>'BusesController@datos_buses']);
Route::get('formBus',['as'=>'formBus','uses'=>'BusesController@formulario_bus']);
Route::get('parq/{id}',['as'=>'parqueo','uses'=>'BusesController@seleccionar_parqueo']);
Route::post('guaBus',['as'=>'guardarBus','uses'=>'BusesController@guardar_bus']);
Route::get('ediBus/{id}',['as'=>'editarBus','uses'=>'BusesController@formulario_editar']);
Route::put('guaEdi/{id}',['as'=>'guardarEdicion','uses'=>'BusesController@guardar_edicion']);

//Rutas para controlador de Lineas
Route::get('iniLin',['as'=>'inicioLineas','uses'=>'LineasController@inicio']);
Route::get('datLin',['as'=>'datosLineas','uses'=>'LineasController@datos_lineas']);
Route::get('detLin/{id}',['as'=>'detalleLinea','uses'=>'LineasController@detalle_linea']);
Route::get('estLin/{id}',['as'=>'estacionesLinea','uses'=>'LineasController@estaciones_lineas']);
Route::get('busLin/{id}',['as'=>'busLinea','uses'=>'LineasController@buses_linea']);
Route::get('formLin',['as'=>'formularioLinea','uses'=>'LineasController@formulario_linea']);
Route::post('guaLin',['as'=>'guardarLinea','uses'=>'LineasController@guardar_linea']);