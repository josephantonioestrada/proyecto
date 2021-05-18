@extends('layouts.app2')

@section('content')
<div class="container">
    @if($message= Session::get('success'))
 	<div class="alert alert-success alert-dismissible fade show" role="alert">
 		<strong>{{ $message}}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
 	</div>
    @endif
    @if($message= Session::get('error'))
 	<div class="alert alert-danger alert-dismissible fade show" role="alert">
 		<strong>{{ $message}}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
 	</div>
    @endif
</div>
<div class="container-fluid">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="{{route('inicioEmpleados')}}">Atras</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active bg-warning" aria-current="page" href="#"><b>Detalles de empleado</b></a>
        </li>
    </ul>
    <table class="table">
        <thead class="table-primary">
            <tr>
                <th>Nombre</th>
                <th>Direccion</th>
                <th>Telefono</th>
                <th>Telefono Opcional</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$empleado->Nombre}}</td>
                <td>{{$empleado->Direccion}}</td>
                <td>{{$empleado->Telefono}}</td>
                <td>{{$empleado->TelefonoOpcional}}</td>
            </tr>
        </tbody>
        <thead class="table-primary">
            <tr>
                <th>Correo electronico</th>
                <th>Plaza</th>
                <th>Fecha de ingreso</th>
                <th>Fecha de modificacion</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$empleado->CorreoElectronico}}</td>
                <td>{{$empleado->plaza}}</td>
                <td>{{date('d/m/Y H:i:s','strtotime'($empleado->FechaCreacion))}}</td>
                <td>{{date('d/m/Y H:i:s','strtotime'($empleado->FechaModificacion))}}</td>
            </tr>
        </tbody>
        <thead>
            <tr>
                <th colspan="5" class="table-success" style="text-align:center;">Historial educativo</th>
            </tr>
            <tr>
                <th>Centro educativo</th>
                <th>Carrera cursada</th>
                <th>Fecha de ingreso</th>
                <th>Fecha de egreso</th>
            </tr>
        </thead>
        <tbody>
            @foreach($detEmpleado as $det)
            <tr>
                <td>{{$det->CentroEducativo}}</td>
                <td>{{$det->Carrera}}</td>
                <td>{{date('d/m/Y','strtotime'($det->FechaIngreso))}}</td>
                <td>{{date('d/m/Y','strtotime'($det->FechaEgreso))}}</td>
            </th>
            @endforeach
        </tbody>
    </table>
    <br>
</div>

@endsection