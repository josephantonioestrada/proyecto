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
            <a class="nav-link active bg-warning" aria-current="page" href="#"><b>Listado buses</b></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('formBus')}}">Agregar bus</a>
        </li>
    </ul>
    <br>
    <table class="table" id="empleado">
        <thead class="table-primary">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Placa</th>
                <th scope="col">Capacidad</th>
                <th scope="col">Estacion</th>
                <th scope="col">Parqueo</th>
                <th scope="col">Linea</th>
                <th scope="col">Fecha de ingreso</th>
                <th scope="col">Fecha de modificacion</th>
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
            url: "{{route('datosBuses')}}",
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
                { data: 'Capacidad', name:'Capacidad'},
                { data: 'estacion', name: 'Estaciones.Nombre'},
                { data: 'Nombre', name: 'Parqueos.Nombre'},
                { data: 'linea', name: 'Lineas.Nombre'},
                { data: 'FechaCreacion', name:'FechaCreacion'},
                { data: 'FechaModificacion', name: 'FechaModificacion'}
                ],
        "columnDefs": [
        { targets: 0, searchable: true },
        { targets: [0,1,2,3,4,5], searchable: true },
        { targets: '_all', searchable: false },
        {targets: [6,7], render:function(data){
            moment.locale('es');
            return moment(data).format('LLLL');
        }}
    ]
    });
    $('#empleado').on('click','tbody tr', function(){
      var tr = $(this).closest('tr');
      var row = table.row(tr);
      window.location.href =(url_global+'/ediBus/'+row.data().idBus); 
      redirectWindow.location; 
    });
</script>
@endsection