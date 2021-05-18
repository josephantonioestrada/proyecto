@extends('layouts.app2')

@section('content')
<div class="container-fluid">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active bg-warning" aria-current="page" href="#"><b>Rutas</b></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('formularioRuta')}}">Nueva ruta</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('inicioMunicipalidades')}}">Municipalidades</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('inicioEstaciones')}}">Estaciones</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('inicioEmpleados')}}" >Empleados</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('inicioLineas')}}">Lineas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('inicioBuses')}}">Buses</a>
        </li>
    </ul>
    <br>
    <table class="table" id="rutas">
        <thead class="table-primary">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Linea</th>
                <th scope="col">Bus</th>
                <th scope="col">Fecha de inicio</th>
                <th scope="col">Fecha finalizacion</th>
                <th scope="col">Editar</th>
                <th scope="col">Ver</th>
            </tr>
        </thead>
    </table>
</div>
<script>
    var table = $('#rutas').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 100,
        searching: true,
        responsive: false,
        ajax:{
            url: "{{route('datosRutas')}}",
            dataSrc: "data",
        },
        "order": [[ 0,"desc" ]],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "EmptyTable":     "Ningún dato disponible en esta tabla =(",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "InfoPostFix":    "",
            "search": "Buscar",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "paginate": {
                "First":    "Primero",
                "Last":     "Último",
                "next": "Siguiente",
                "previous": "Anterior",
            },
        },
        columns: [
                { data: 'idRuta', name: 'idRuta'},
                { data: 'nombre', name:'Lineas.Nombre'},
                { data: 'placa', name:'Buses.Placa'},
                { data: 'FechaInicio', name:'FechaFechaInicio'},
                { data: 'FechaFinal', name: 'FechaFinal'},
                { data: null, render: function(data,type,row){
                    return "<a href='{{url('ediRu')}}/"+data.idRuta+ "' class= 'btn btn-sm btn-dark' >Editar</button>"}
                },
                { data: null, render: function(data,type,row){
                    return "<a href='{{url('detRut')}}/"+data.idRuta+ "' class= 'btn btn-sm btn-danger' >Ver</button>"}
                }
                ],
        "columnDefs": [
        { targets: 0, searchable: true },
        { targets: [0,1,2], searchable: true },
        { targets: '_all', searchable: false },
        {targets: [3,4], render:function(data){
            moment.locale('es');
            return moment(data).format('LLLL');
        }}
    ]
    });
</script>
@endsection
