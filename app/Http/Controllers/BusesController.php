<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bus;
use DB;
use App\Linea;
use Carbon\Carbon;
use App\Estacion;
use Yajra\DataTables\DataTables;

class BusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //Funcion para ver el listado de buses
    public function inicio()
    {
        return view('Buses.inicio');
    }

    //Funcion para cargar los datos de los buses
    public function datos_buses()
    {
        $buses = DB::table('Buses')
        ->join('Parqueos','Buses.IdParqueo','Parqueos.idParqueo')
        ->join('Estaciones','Parqueos.IdEstacion','Estaciones.idEstacion')
        ->leftjoin('Lineas','Buses.IdLinea','Lineas.idLinea')
        ->select(['Buses.idBus','Buses.Placa','Buses.Capacidad','Parqueos.Nombre',
        'Buses.FechaModificacion','Buses.FechaCreacion',DB::RAW('Lineas.Nombre as linea'),
        DB::RAW('Estaciones.Nombre as estacion')]);
        return DataTables::of($buses)->toJson();
    }

    //Funcion para visualizar el formulario para agregar un bus
    public function formulario_bus()
    {
        $estaciones = DB::table('Estaciones')
        ->select(['Estaciones.idEstacion','Estaciones.Nombre'])->get();
        $lineas = Linea::get();
        return view('Buses.formBus',compact('estaciones','lineas'));
    }

    //Funcion para llenar el select de parqueo en el formulario de un nuevo bus
    public function seleccionar_parqueo($id)
    {
        return DB::table('Parqueos')->where('IdEstacion',$id)->get();
    }

    //Funcion para guardar un nuevo bus
    public function guardar_bus(Request $request)
    {
        $linea = DB::table('Lineas')
        ->join('DetallesLineas','Lineas.idLinea','DetallesLineas.IdLinea')
        ->leftjoin('Buses','Lineas.idLinea','Buses.IdLinea')
        ->select([DB::RAW('count(distinct DetallesLineas.idDetLinea)*2 as estaciones'),
        DB::RAW('count(distinct Buses.idBus) as buses')])
        ->where('Lineas.idLinea',$request->linea)->get();
        $estaciones = '';
        $buses = '';
        foreach($linea as $li)
        {
            $estaciones = $li->estaciones;
            $buses = $li->buses;
        }
        if($buses < $estaciones)
        {
            $bus = new Bus();
            $bus->Placa = $request->placa;
            $bus->IdLinea = $request->linea;
            $bus->IdParqueo = $request->parqueo;
            $bus->Capacidad = $request->capacidad;
            $bus->FechaCreacion = Carbon::now();
            $bus->FechaModificacion = Carbon::now();
            $bus->save();
            return redirect()->route('inicioBuses')->with('success','Bus agregado correctamente');
        }
        else 
        {
            $bus = new Bus();
            $bus->Placa = $request->placa;
            $bus->IdLinea = NULL;
            $bus->IdParqueo = $request->parqueo;
            $bus->Capacidad = $request->capacidad;
            $bus->FechaCreacion = Carbon::now();
            $bus->FechaModificacion = Carbon::now();
            $bus->save();
            return redirect()->route('inicioBuses')->with('error','No se permite agregar mas buses a la linea');
        }
    }

    //Funcion para ver el formulario de edicion de un bus
    public function formulario_editar($id)
    {
        $bus = DB::table('Buses')
        ->join('Parqueos','Buses.IdParqueo','Parqueos.idParqueo')
        ->join('Estaciones','Parqueos.IdEstacion','Estaciones.idEstacion')
        ->leftjoin('Lineas','Buses.IdLinea','Lineas.idLinea')
        ->select(['Buses.idBus','Buses.Placa','Buses.Capacidad','Parqueos.Nombre',
        'Buses.FechaModificacion','Buses.FechaCreacion','Estaciones.idEstacion',
        'Parqueos.idParqueo',DB::RAW('Buses.IdLinea as idLinea'),DB::RAW('Lineas.Nombre as linea'),
        DB::RAW('Estaciones.Nombre as estacion'),DB::RAW('Parqueos.Nombre as parqueo')])
        ->where('idBus',$id)->first();
        $estaciones = DB::table('Estaciones')
        ->select(['Estaciones.idEstacion','Estaciones.Nombre'])->get();
        $lineas = Linea::get();
        return view('Buses.editar',compact('bus','estaciones','id','lineas'));
    }

    //Funcion para guardar los datos editados de un bus
    public function guardar_edicion(Request $request,$id)
    {
        $linea = DB::table('Lineas')
        ->join('DetallesLineas','Lineas.idLinea','DetallesLineas.IdLinea')
        ->leftjoin('Buses','Lineas.idLinea','Buses.IdLinea')
        ->select([DB::RAW('count(distinct DetallesLineas.idDetLinea)*2 as estaciones'),
        DB::RAW('count(distinct Buses.idBus) as buses')])
        ->where('Lineas.idLinea',$request->linea)->get();
        $estaciones = '';
        $buses = '';
        foreach($linea as $li)
        {
            $estaciones = $li->estaciones;
            $buses = $li->buses;
        }
        if($buses < $estaciones)
        {
            $bus = Bus::where('idBus',$id)
            ->update(['Placa'=>$request->placa,'IdLinea'=>$request->linea,
            'IdParqueo'=>$request->parqueo,'Capacidad'=>$request->capacidad,
            'FechaCreacion'=>Carbon::now(),'FechaModificacion'=>Carbon::now()]);
            return redirect()->route('inicioBuses')->with('success','Datos actualizados correctamente');
        }
        else
        {
            return redirect()->route('inicioBuses')->with('error','No se permite agregar mas buses a la linea');
        }
    }
}
