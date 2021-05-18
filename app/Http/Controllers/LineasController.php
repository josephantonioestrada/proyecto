<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Bus;
use App\Linea;
use App\Estacion;
use Carbon\Carbon;
use App\Municipalidad;
use App\DetalleLinea;
use Yajra\DataTables\DataTables;

class LineasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //Funcion para ver el listado de lienas
    public function inicio()
    {
        return view('Lineas.inicio');
    }

    //Funcion para visualizar el formulario para agregar una nueva liena
    public function formulario_linea()
    {
        $estaciones = Estacion::get();
        $municipalidades = Municipalidad::get();
        return view('Lineas.formLinea',compact('estaciones','municipalidades'));
    }

    //Funcion para cargar los datos de las lineas existentes
    public function datos_lineas()
    {
        $lineas = DB::table('Lineas')
        ->join('Municipalidad','Lineas.IdMunicipalidad','Municipalidad.idMunicipalidad')
        ->select(['Lineas.idLinea','Lineas.Nombre','Lineas.FechaCreacion','Lineas.FechaEdicion',
        DB::RAW('Municipalidad.Nombre as municipalidad')]);
        return DataTables::of($lineas)->toJson();
    }

    //Funcion para ver los detalles de una linea
    public function detalle_linea($id)
    {
        $linea = DB::table('Lineas')
        ->join('Municipalidad','Lineas.IdMunicipalidad','Lineas.IdMunicipalidad')
        ->select(['Lineas.Nombre','Lineas.idLinea','Lineas.FechaCreacion','Lineas.FechaEdicion',
        'Municipalidad.Nombre as muni'])
        ->where('idLinea',$id)->first();
        return view('Lineas.detalleLinea',compact('linea','id'));
    }

    //Funcion para ver las estaciones que pertenecen a una linea
    public function estaciones_lineas($id)
    {
        $estaciones = DB::table('DetallesLineas')
        ->join('Estaciones','DetallesLineas.IdEstacion','Estaciones.idEstacion')
        ->select(['DetallesLineas.idDetLinea','DetallesLineas.NoOrden',
        'DetallesLineas.DistanciaSigEstacion','DetallesLineas.FechaCreacion',
        DB::RAW('Estaciones.Nombre')])
        ->where('DetallesLineas.IdLinea',$id)->get();
        return DataTables::of($estaciones)->toJson();
    }

    //Funcion para ver los buses que pertenecen a una linea
    public function buses_linea($id)
    {
        $buses = Bus::where('IdLinea',$id);
        return DataTables::of($buses)->toJson();
    }

    //Funcion para guardar los datos de una nueva linea
    public function guardar_linea(Request $request)
    {
        $linea = new Linea();
        $linea->Nombre = $request->nombre;
        $linea->IdMunicipalidad = $request->municipalidad;
        $linea->FechaCreacion = Carbon::now();
        $linea->FechaEdicion = Carbon::now();
        if($linea->save())
        {
            $id = $linea->id;
            foreach($request->estacion as $key => $value)
            {
                $detlinea = new DetalleLinea();
                $data = array($detlinea->IdEstacion = $request->estacion[$key], 
                $detlinea->IdLinea = $id,$detlinea->FechaCreacion = Carbon::now(),
                $detlinea->FechaModificacion = Carbon::now(),$detlinea->NoOrden = $request->orden[$key],
                $detlinea->DistanciaSigEstacion = $request->distancia[$key],
                $detlinea->DistanciaAntEstacion = $request->anterior[$key]);
                $detlinea->save();
            }
        }
        return redirect()->route('inicioLineas')->with('success','Linea agregada correctamente');
    }
}
