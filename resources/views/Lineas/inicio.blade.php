@extends('layouts.app2')

@section('content')
<script>
    var url_global='{{url("/")}}';
</script>
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
            <a class="nav-link" href="{{route('home')}}">Atras</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active bg-warning" aria-current="page" href="#"><b>Listado de lineas</b></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('formularioLinea')}}">Agregar linea</a>
        </li>
    </ul>
    <br>
    <table class="table" id="empleado">
        <thead class="table-primary">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Municipalidad</th>
                <th scope="col">Fecha de ingreso</th>
                <th scope="col">Fecha de modificacion</th>
                <th scope="col">Ver</th>
            </tr>
        </thead>
    </table>
</div>
<script>
    var table = $('#empleado').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 100,
        searching: true,
        responsive: false,
        ajax:{
            url: "{{route('datosLineas')}}",
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
                { data: 'idLinea', name: 'idLinea'},
                { data: 'Nombre', name:'Nombre'},
                { data: 'municipalidad', name:'Municipalidad.Nombre'},
                { data: 'FechaCreacion', name:'FechaCreacion'},
                { data: 'FechaEdicion', name: 'FechaEdicion'},
                { data: null, render: function(data,type,row){
                    return "<a href='{{url('detLin')}}/"+data.idLinea+ "' class= 'btn btn-sm btn-danger' >Ver</button>"}
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