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
            <a class="nav-link" href="{{route('inicioLineas')}}">Atras</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active bg-warning" aria-current="page" href="#"><b>{{$linea->Nombre}}</b></a>
        </li>
    </ul>
    <table class="table">
        <thead class="table-primary">
            <tr>
                <th>Nombre de la linea</th>
                <th>Municipalidad</th>
                <th>Fecha creacion</th>
                <th>Fecha modificacion</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$linea->Nombre}}</td>
                <td>{{$linea->muni}}</td>
                <td>{{date('d/m/Y H:i:s','strtotime'($linea->FechaCreacion))}}</td>
                <td>{{date('d/m/Y H:i:s','strtotime'($linea->FechaEdicion))}}</td>
            </tr>
        </tbody>
    </table>
    <br>
    <table class="table" id="buses">
        <thead class="table-success">
            <tr>
                <th scope="col" colspan="3" style="text-align:center;">Listado de buses</th>
            </tr>
            <tr>
                <th>#</th>
                <th>Placa</th>
                <th>Capacidad</th>
            </tr>
        </thead>
    </table>
    <br>
    <table class="table" id="estaciones">
        <thead class="table-warning">
            <tr>
                <th scope="col" colspan="5" style="text-align:center;">Estaciones dentro de la linea</th>
            </tr>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Estacion</th>
                <th scope="col">Orden de recorrido</th>
                <th scope="col">Distacia siguiente estacion</th>
                <th scope="col">Fecha de creacion</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
<script>
    var table = $('#estaciones').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 100,
        searching: true,
        responsive: false,
        ajax:{
            url: "{{route('estacionesLinea',$id)}}",
            dataSrc: "data",
        },
        "order": [[ 0,"asc" ]],
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
                { data: 'idDetLinea', name: 'idDetLinea'},
                { data: 'Nombre', name:'Nombre'},
                { data: 'NoOrden', name: 'NoOrden'},
                { data: 'DistanciaSigEstacion', name: 'DistanciaSigEstacion'},
                { data: 'FechaCreacion', name: 'FechaCreacion'}
                ],
        "columnDefs": [
            { targets: 0, searchable: true },
            { targets: [0,1,2,3], searchable: true },
            { targets: '_all', searchable: false },
            {targets: [4], render:function(data){
                moment.locale('es');
                return moment(data).format('LLLL');
            }}
        ],
        "footerCallback": function(row,data,start,end,display){
            var api = this.api(), data;
            var intVal = function(i){
                return typeof i == 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                typeof i == 'number' ?
                    i:0;
            };
               
            var Distancia = api
            .column(3)
            .data()
            .reduce(function (a,b){
                return intVal(a) + intVal(b);
            }, 0);

            var numFormat = $.fn.dataTable.render.number( '\,', '.', 2 ).display;
                numFormat(Distancia);
            pageTotal = api.column(3,{page: 'current'})
            .data()
            .reduce( function (a,b){
                return intVal(a) + intVal(b);
            }, 0);
            $(api.column(2).footer() ).html('Distancia total');
               $(api.column(3).footer()).html(numFormat(Distancia)+' Km');
        }
    });
</script>
<script>
    var table = $('#buses').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 100,
        searching: true,
        responsive: false,
        ajax:{
            url: "{{route('busLinea',$id)}}",
            dataSrc: "data",
        },
        "order": [[ 0,"asc" ]],
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
                { data: 'idBus', name: 'idBus'},
                { data: 'Placa', name:'Placa'},
                { data: 'Capacidad', name: 'Capacidad'}
                ],
        "columnDefs": [
            { targets: 0, searchable: true },
            { targets: [0,1,2], searchable: true },
            { targets: '_all', searchable: false },
        ]
    });
</script>
@endsection