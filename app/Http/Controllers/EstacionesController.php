<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Acceso;
use App\Estacion;
use App\Parqueo;
use Carbon\Carbon;
use App\Municipalidad;
use Yajra\DataTables\DataTables;

class EstacionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Funciones para el listado de estaciones
    public function inicio()
    {
        return view('Estaciones.inicio');
    } 

    public function datos_estaciones()
    {
        $estaciones = DB::table('Estaciones')
        ->join('Municipalidad','Municipalidad.idMunicipalidad','=','Estaciones.IdMunicipalidad')
        ->select([DB::raw('Municipalidad.Nombre as Muni'),'Estaciones.idEstacion','Estaciones.Nombre','Estaciones.Ubicacion',
        'Estaciones.FechaCreacion','Estaciones.FechaModificacion']);
        return DataTables::of($estaciones)->toJson();
    }

    //Funcion para ver el formulario de inster de una estacion
    public function formulario_estacion()
    {
        $muni = Municipalidad::get();
        return view('Estaciones.formEstacion',compact('muni'));
    }

    //Funcion que permite guardar los datos de una estacion en la base de datos
    public function guardar_estacion(Request $request)
    {
        $estacion = new Estacion();
        $estacion->Nombre = $request->nombre;
        $estacion->Ubicacion = $request->direccion;
        $estacion->IdMunicipalidad = $request->municipalidad;
        $estacion->FechaCreacion = Carbon::now();
        $estacion->FechaModificacion = Carbon::now();
        if($estacion->save())
        {
            $id = $estacion->id;
            foreach($request->nombreAcceso as $key => $value)
            {
                $acceso = new Acceso();
                $data = array($acceso->Nombre = $request->nombreAcceso[$key], 
                $acceso->IdEstacion = $id,$acceso->FechaCreacion = Carbon::now(),
                $acceso->FechaModificacion = Carbon::now());
                $acceso->save();
            }
        }
        return redirect()->route('inicioEstaciones')->with('success','Estacion agregada correctamente');
    }

    //Funcion para ver los detalles de una estacion
    public function detalle_estacion($id)
    {
        $estacion = DB::table('Estaciones')
        ->join('Municipalidad','Municipalidad.idMunicipalidad','=','Estaciones.IdMunicipalidad')
        ->select([DB::raw('Municipalidad.Nombre as Muni'),'Estaciones.idEstacion','Estaciones.Nombre','Estaciones.Ubicacion',
        'Estaciones.FechaCreacion','Estaciones.FechaModificacion'])
        ->where('idEstacion',$id)->first();
        $parqueos = Parqueo::where('IdEstacion',$id)->get();
        return view('Estaciones.detEstacion',compact('estacion','parqueos'));
    }

    //Funcion para ver los detalles de un acceso por estacion
    public function detalles_accesos($id)
    {
        $accesos = Acceso::where('idEstacion',$id)->get();
        return DataTables::of($accesos)->toJson();
    }

    //Funcion para guardar un parqueo dentro de una estacion
    public function guardar_parqueo(Request $request,$id)
    {
        $parqueo = new Parqueo();
        $parqueo->Nombre = $request->nombre;
        $parqueo->IdEstacion = $id;
        $parqueo->FechaCreacion = Carbon::now();
        $parqueo->FechaModificacion = Carbon::now();
        $parqueo->save();
        return back()->with('success','Parqueo agregado correctamente');
    }
}
