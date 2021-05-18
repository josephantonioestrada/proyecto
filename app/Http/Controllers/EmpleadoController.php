<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Empleado;
use Carbon\Carbon;
use App\DetalleEmpleado;
use Yajra\DataTables\DataTables;

class EmpleadoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Funcion para ver el listado de empleados ingresados
    public function inicio()
    {
        return view('Empleados.inicio');
    }

    //Funcion para cargar los datos principales de los empleados
    public function datos_empleados()
    {
        $empleados = DB::table('Empleados')
        ->join('Roles','Empleados.IdRol','Roles.idRol')
        ->select(['Empleados.idEmpleado','Empleados.Nombre','Empleados.Direccion','Empleados.Telefono',
        'Empleados.TelefonoOpcional','Empleados.CorreoElectronico','Empleados.FechaCreacion',
        DB::RAW('Roles.Nombre as plaza')]);
        return DataTables::of($empleados)->toJson();
    }

    //Funcion para visualizar el formulario para un nuevo empleado
    public function formulario_empleado()
    {
        $roles = DB::table('Roles')
        ->where('idRol','>',1)->get();
        return view('Empleados.formEmpleado',compact('roles'));
    }

    //Funcion para guardar los datos de un nuevo empleado
    public function guardar_empleado(Request $request)
    {
        $empleado = new Empleado();
        $empleado->Nombre = $request->nombre;
        $empleado->Direccion = $request->direccion;
        $empleado->Telefono = $request->telefono;
        $empleado->TelefonoOpcional = $request->telefonoop;
        $empleado->CorreoElectronico = $request->correo;
        $empleado->FechaCreacion = Carbon::now();
        $empleado->FechaModificacion = Carbon::now();
        $empleado->IdRol = $request->plaza;
        if($empleado->save())
        {
            $id = $empleado->id;
            foreach($request->nombreCentro as $key => $value)
            {
                $detempleado = new DetalleEmpleado();
                $data = array($detempleado->CentroEducativo = $request->nombreCentro[$key], 
                $detempleado->IdEmpleado = $id,$detempleado->FechaCreacion = Carbon::now(),
                $detempleado->FechaModificacion = Carbon::now(),$detempleado->FechaIngreso = $request->fechaIngreso[$key],
                $detempleado->FechaEgreso = $request->fechaEgreso[$key],$detempleado->Carrera = $request->carrera[$key],
                $detempleado->Telefono = $request->telefonoEst[$key]);
                $detempleado->save();
            }
        }
        return redirect()->route('inicioEmpleados')->with('success','Empleado agregado correctamente');
    }

    //Funcion para ver los detalles de un empleado
    public function detalles_empleado($id)
    {
        $empleado = DB::table('Empleados')
        ->join('Roles','Empleados.IdRol','Roles.idRol')
        ->select(['Empleados.idEmpleado','Empleados.Nombre','Empleados.Direccion','Empleados.Telefono',
        'Empleados.TelefonoOpcional','Empleados.CorreoElectronico','Empleados.FechaCreacion',
        'Empleados.FechaModificacion',DB::RAW('Roles.Nombre as plaza')])
        ->where('idEmpleado',$id)->first();

        $detEmpleado = DB::table('DetallesEmpleados')->where('IdEmpleado',$id)->get();
        return view('Empleados.detEmpleado',compact('empleado','detEmpleado'));
    }
}
