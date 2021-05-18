<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Bus;
use App\Ruta;
use App\Linea;
use App\Estacion;
use Carbon\Carbon;
use App\DetalleRuta;
use App\DetalleLinea;
use Yajra\DataTables\DataTables;

class RutasController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Funcion para cargar los datos de las rutas
    public function datos_rutas()
    {
        $rutas = DB::table('Rutas')
        ->join('Buses','Rutas.IdBus','Buses.idBus')
        ->join('Lineas','Rutas.IdLinea','Lineas.idLinea')
        ->select(['Rutas.idRuta','Rutas.FechaInicio','Rutas.FechaFinal',
        DB::RAW('Buses.Placa as placa'),DB::RAW('Lineas.Nombre as nombre')])
        ->orderBy('idRuta','desc');
        return DataTables::of($rutas)->toJson();
    }
    //funcion para generar una nueva ruta
    public function formulario_ruta()
    {
        $lineas = Linea::get();
        return view('Rutas.formRuta',compact('lineas'));
    }

    //Funcion para seleccionar un bus para la nueva ruta
    public function seleccione_bus($id)
    {
        //$rutas = Ruta::where('FechaFinal','')->where('IdLinea',$id)->get();
        return DB::table('Buses')->where('IdLinea',$id)->get();
    }

    //Funcion para guardar una nueva ruta
    public function guardar_ruta(Request $request)
    {
        $ruta = new Ruta();
        $ruta->IdLinea = $request->linea;
        $ruta->IdBus = $request->bus;
        $ruta->sentidoRuta = $request->sentido;
        $ruta->FechaInicio = Carbon::now();
        $ruta->save();
        $id = $ruta->id;
        return redirect()->route('editarRuta',$id)->with('success','Ruta creada correctamente');
    }

    //Funcion para editar una ruta
    public function editar_ruta($id)
    {
        $edRuta = Ruta::where('idRuta',$id)->first();
        if($edRuta->sentidoRuta == 'A')
        {
            $detRut = DetalleRuta::where('IdRuta',$id)
            ->orderBy('IdEstacion','desc')->first(); 
            if($detRut == '')
            {
                $detLin = DetalleLinea::where('IdLinea',$edRuta->IdLinea)
                ->orderBy('NoOrden','asc')->first();
                $est = Estacion::where('idEstacion',$detLin->IdEstacion)->first();
                return view('Rutas.editRuta',compact('detLin','est','detRut','id'));
            }
            else if($detRut->FechaSalida == '')
            {
                $detLin = DetalleLinea::where('IdLinea',$edRuta->IdLinea)
                ->orderBy('NoOrden','asc')->first();
                $est = Estacion::where('idEstacion',$detLin->IdEstacion)->first();
                return view('Rutas.editRuta',compact('detLin','est','detRut','id'));
            }
            else
            {
                $detLin = DetalleLinea::where('IdLinea',$edRuta->IdLinea)
                ->where('IdEstacion','>',$detRut->IdEstacion)
                ->orderBy('NoOrden','asc')->first();
                if($detLin == '')
                {
                    $ruta = Ruta::where('idRuta',$id)
                    ->update(['FechaFinal'=>Carbon::now()]);
                    return redirect()->route('home')->with('success','Ruta finalizada correctamente');
                }
                else
                {
                    $detLin = DetalleLinea::where('IdLinea',$edRuta->IdLinea)
                    ->where('IdEstacion','>',$detRut->IdEstacion)
                    ->orderBy('NoOrden','asc')->first();
                    $est = Estacion::where('idEstacion',$detLin->IdEstacion)->first();
                    return view('Rutas.editRuta',compact('detLin','est','detRut','id'));
                }
            }
        }
        else 
        {
            $detRut = DetalleRuta::where('IdRuta',$id)
            ->orderBy('idDetRuta','desc')->first(); 
            if($detRut == '')
            {
                $detLin = DB::table('DetallesLineas')
                ->select(['DistanciaAntEstacion as DistanciaSigEstacion','IdEstacion'])
                ->where('IdLinea',$edRuta->IdLinea)
                ->orderBy('NoOrden','desc')->first();
                $est = Estacion::where('idEstacion',$detLin->IdEstacion)->first();
                return view('Rutas.editRuta',compact('detLin','est','detRut','id'));
            }
            else if($detRut->FechaSalida == '')
            {
                $detLin = DetalleLinea::where('IdLinea',$edRuta->IdLinea)
                ->orderBy('NoOrden','asc')->first();
                $est = Estacion::where('idEstacion',$detLin->IdEstacion)->first();
                return view('Rutas.editRuta',compact('detLin','est','detRut','id'));
            }
            else
            {
                $detLin = DetalleLinea::where('IdLinea',$edRuta->IdLinea)
                ->where('IdEstacion','<',$detRut->IdEstacion)
                ->orderBy('NoOrden','asc')->first();
                if($detLin == '')
                {
                    $ruta = Ruta::where('idRuta',$id)
                    ->update(['FechaFinal'=>Carbon::now()]);
                    return redirect()->route('home')->with('success','Ruta finalizada correctamente');
                }
                else
                {
                    $detLin = DetalleLinea::where('IdLinea',$edRuta->IdLinea)
                    ->select(['DistanciaAntEstacion as DistanciaSigEstacion','IdEstacion'])
                    ->where('IdEstacion','<',$detRut->IdEstacion)
                    ->orderBy('NoOrden','desc')->first();
                    $est = Estacion::where('idEstacion',$detLin->IdEstacion)->first();
                    return view('Rutas.editRuta',compact('detLin','est','detRut','id'));
                }
            }
        }
    }

    //Funcion para guardar una ruta editada
    public function guardar_ruta_editada(Request $request,$id)
    {
        $edRuta = Ruta::where('idRuta',$id)->first();
        $detRut = DetalleRuta::where('IdRuta',$id)
        ->orderBy('idDetRuta','desc')->first(); 
        if($detRut == '')
        {
            $bus = Bus::where('idBus',$edRuta->IdBus)->first();
            if(($bus->Capacidad - $request->spasajeros + $request->bpasajeros)/$bus->Capacidad <= 0.75 )
            {
                $detRuta = new DetalleRuta();
                $detRuta->IdRuta = $id;
                $detRuta->IdEstacion = $request->estacion;
                $detRuta->CantidadPasajeros = $request->spasajeros;
                $detRuta->TotalPasajeros = $request->spasajeros - $request->bpasajeros;
                $detRuta->FechaIngreso = Carbon::now();
                $detRuta->FechaSalida = Carbon::now();
                $detRuta->save();
                return back()->with('success','El bus puede continuar a la siguiente estacion');
            }
            else if(($bus->Capacidad - $request->spasajeros + $request->bpasajeros)/$bus->Capacidad <= 0.50)
            {
                $detRuta = new DetalleRuta();
                $detRuta->IdRuta = $id;
                $detRuta->IdEstacion = $request->estacion;
                $detRuta->CantidadPasajeros = $request->spasajeros;
                $detRuta->TotalPasajeros = $request->spasajeros - $request->bpasajeros;
                $detRuta->FechaIngreso = Carbon::now();
                $detRuta->FechaSalida = Carbon::now();
                $detRuta->save();
                return back()->with('success','Solicite un bus de esta linea a la estacion');
            }
            else 
            {
                $detRuta = new DetalleRuta();
                $detRuta->IdRuta = $id;
                $detRuta->IdEstacion = $request->estacion;
                $detRuta->CantidadPasajeros = $request->spasajeros;
                $detRuta->TotalPasajeros = $request->spasajeros - $request->bpasajeros;
                $detRuta->FechaIngreso = Carbon::now();
                $detRuta->save();
                return back()->with('error','El bus debe esperar 5 minuntos mas en la estacion');
            }
        }
        else
        {
            if($detRut->FechaSalida == '')
            {
                if(Carbon::now() >= Carbon::parse($detRut->FechaIngreso)->addMinutes(5))
                {
                    $detRuta = DetalleRuta::where('idDetRuta',$detRut->idDetRuta)
                    ->update(['CantidadPasajeros'=>$detRut->CantidadPasajeros+$request->spasajeros - $request->bpasajeros,
                    'TotalPasajeros'=>$detRut->TotalPasajeros+$request->spasajeros-$request->bpasajeros,'FechaSalida'=>Carbon::now()]);
                    return back()->with('success','El bus puede continuar a la siguiente estacion');
                }
                else 
                {
                    $detRuta = DetalleRuta::where('idDetRuta',$detRut->idDetRuta)
                    ->update(['CantidadPasajeros'=>$detRut->CantidadPasajeros+$request->spasajeros - $request->bpasajeros,
                    'TotalPasajeros'=>$detRut->TotalPasajeros+$request->spasajeros-$request->bpasajeros]);
                    return back()->with('error','El bus debe esperar mas tiempo en la estacion');
                }
            }
            else 
            {
                $bus = Bus::where('idBus',$edRuta->IdBus)->first();
                $pasaje = DetalleRuta::where('IdRuta',$id)->orderBy('idDetRuta','desc')->first();
                if(($bus->Capacidad - $request->spasajeros -$pasaje->TotalPasajeros + $request->bpasajeros)/$bus->Capacidad <= 0.75 )
            {
                    $detRuta = new DetalleRuta();
                    $detRuta->IdRuta = $id;
                    $detRuta->IdEstacion = $request->estacion;
                    $detRuta->CantidadPasajeros = $request->spasajeros ;
                    $detRuta->TotalPasajeros = $request->spasajeros + $pasaje->TotalPasajeros -$request->bpasajeros;
                    $detRuta->FechaIngreso = Carbon::now();
                    $detRuta->FechaSalida = Carbon::now();
                    $detRuta->save();
                    return back()->with('success','El bus puede continuar a la siguiente estacion');
                }
                else if(($bus->Capacidad - $request->spasajeros -$pasaje->TotalPasajeros + $request->bpasajeros)/$bus->Capacidad <= 0.50)
                {
                    $detRuta = new DetalleRuta();
                    $detRuta->IdRuta = $id;
                    $detRuta->IdEstacion = $request->estacion;
                    $detRuta->CantidadPasajeros = $request->spasajeros;
                    $detRuta->TotalPasajeros = $request->spasajeros + $pasaje->TotalPasajeros -$request->bpasajeros;
                    $detRuta->FechaIngreso = Carbon::now();
                    $detRuta->FechaSalida = Carbon::now();
                    $detRuta->save();
                    return back()->with('success','Solicite un bus de esta linea a la estacion');
                }
                else 
                {
                    $detLin = DetalleLinea::where('IdLinea',$edRuta->IdLinea)
                    ->where('IdEstacion','>',$detRut->IdEstacion)
                    ->orderBy('NoOrden','asc')->first();
                    $detRuta = new DetalleRuta();
                    $detRuta->IdRuta = $id;
                    $detRuta->IdEstacion = $request->estacion;
                    $detRuta->CantidadPasajeros = $request->spasajeros;
                    $detRuta->TotalPasajeros = $request->spasajeros + $pasaje->TotalPasajeros -$request->bpasajeros;
                    $detRuta->FechaIngreso = Carbon::now();
                    $detRuta->save();
                    return back()->with('error','El bus debe esperar 5 minuntos mas en la estacion');
                }
            }
        }
    }

    //Funcion para finalizar una ruta 
    public function finalizar_ruta($id)
    {
        $ruta = Ruta::where('idRuta',$id)->update(['FechaFinal'=>Carbon::now()]);
        $detruta = DetalleRuta::where('IdRuta',$id)->update(['FechaSalida'=>Carbon::now()]);
        return redirect()->route('home')->with('success','Ruta finalizada correctamente');
    }

    //Funcion para ver los detalles de una ruta
    public function detalles_rutas($id)
    {
        $rutas = DB::table('Rutas')
        ->join('Buses','Rutas.IdBus','Buses.idBus')
        ->join('Lineas','Rutas.IdLinea','Lineas.idLinea')
        ->select(['Rutas.idRuta','Rutas.FechaInicio','Rutas.FechaFinal',
        DB::RAW('Buses.Placa as placa'),DB::RAW('Lineas.Nombre as nombre')])
        ->where('Rutas.idRuta',$id)
        ->orderBy('idRuta','desc')->first();
        $detRuta = DB::table('DetalleRutas')
        ->join('Estaciones','DetalleRutas.IdEstacion','Estaciones.idEstacion')
        ->select(['DetalleRutas.idDetRuta','DetalleRutas.CantidadPasajeros',
        'DetalleRutas.TotalPasajeros','DetalleRutas.FechaIngreso','DetalleRutas.FechaSalida',
        DB::RAW('Estaciones.Nombre as estacion')])
        ->where('DetalleRutas.IdRuta',$id)->get();
        return view('Rutas.detRuta',compact('rutas','detRuta'));
    }
}
