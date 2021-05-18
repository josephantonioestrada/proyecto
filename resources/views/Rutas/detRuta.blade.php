@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link" href="{{route('home')}}">Atras</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active bg-warning" aria-current="page" href="#"><b>Detalles de ruta</b></a>
        </li>
    </ul>
    <table class="table">
        <thead class="table-primary">
            <tr>
                <th>#</th>
                <th colspan="2">Linea</th>
                <th>Bus</th>
                <th>Fecha inicio</th>
                <th>Fecha finalizacion</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$rutas->idRuta}}</td>
                <td colspan="2">{{$rutas->nombre}}</td>
                <td>{{$rutas->placa}}</td>
                <td>{{date('d/m/Y H:i:s','strtotime'($rutas->FechaInicio))}}</td>
                <td>{{date('d/m/Y H:i:s','strtotime'($rutas->FechaFinal))}}</td>
            </tr>
        </tbody>
        <thead>
            <tr>
                <th colspan="6" class="table-success">Parqueos dentro de la estacion</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Estacion</th>
                <th>Cantidad en estacion</th>
                <th>Pasajeros en bus</th>
                <th>Fecha ingreso</th>
                <th>Fecha salida</th>
            </tr>
        </thead>
        <tboby>
            @foreach($detRuta as $dr)
            <tr>
                <td>{{$dr->idDetRuta}}</td>
                <td>{{$dr->estacion}}</td>
                <td>{{$dr->CantidadPasajeros}}</td>
                <td>{{$dr->TotalPasajeros}}</td>
                <td>{{date('d/m/Y H:i:s','strtotime'($dr->FechaIngreso))}}</td>
                <td>{{date('d/m/Y H:i:s','strtotime'($dr->FechaSalida))}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection