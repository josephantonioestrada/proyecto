<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Municipalidad;
use Yajra\DataTables\DataTables;

class MunicipalidadesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    //funcion para el listado de municipalidades
    public function inicio()
    {
        return view('Municipalidades.inicio');
    }

    public function datos_inicio()
    {
        $munis = Municipalidad::get();
        return DataTables($munis)->make(true);
    }

    public function formulario_municipalidad()
    {
        return view('Municipalidades.formularioMuni');
    }

    //Funcion para guardar una nueva municipalidad
    public function guardar_municipalidad(Request $request)
    {
        $muni = new Municipalidad();
        $muni->nombre = $request->nombre;
        $muni->direccion = $request->direccion;
        $muni->fechaCreacion = Carbon::now();
        $muni->fechaModificacion = Carbon::now();
        $muni->save();
        return redirect()->route('inicioMunicipalidades')->with('success','Municipalidad agregada correctamente');
    }
}
