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
            <a class="nav-link" href="{{route('home')}}">Atras</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active bg-warning" aria-current="page" href="#"><b>Listado empleados</b></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{route('formularioEmpleado')}}">Agregar empleado</a>
        </li>
    </ul>
    <br>
    <table class="table" id="empleado">
        <thead class="table-primary">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Direccion</th>
                <th scope="col">Telefono</th>
                <th scope="col">Telefono opcional</th>
                <th scope="col">Correo</th>
                <th scope="col">Plaza</th>
                <th scope="col">Fecha de ingreso</th>
                <th scope="col">Ver mas</th>
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
            url: "{{route('datosEmpleados')}}",
            dataSrc: "data",
        },
        "order": [[ 0,"asc" ]],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "EmptyTable":     "Ning??n dato disponible en esta tabla =(",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "InfoPostFix":    "",
            "search": "Buscar",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "paginate": {
                "First":    "Primero",
                "Last":     "??ltimo",
                "next": "Siguiente",
                "previous": "Anterior",
            },
        },
        columns: [
                { data: 'idEmpleado', name: 'idEmpleado'},
                { data: 'Nombre', name:'Nombre'},
                { data: 'Direccion', name:'Direccion'},
                { data: 'Telefono', name: 'Telefono'},
                { data: 'TelefonoOpcional', name: 'TelefonoOpcional'},
                { data: 'CorreoElectronico', name: 'CorreoElectronico'},
                { data: 'plaza', name:'Roles.Nombre'},
                { data: 'FechaCreacion', name: 'FechaCreacion'},
                { data: null, render: function(data,type,row){
                return "<a href='{{url('detEmp')}}/"+data.idEmpleado+ "' class= 'btn btn-sm btn-danger' >Ver</button>"}
            }
                ],
        "columnDefs": [
        { targets: 0, searchable: true },
        { targets: [0,1,2,3,4,5,6,7], searchable: true },
        { targets: '_all', searchable: false },
        {targets: [8], render:function(data){
            moment.locale('es');
            return moment(data).format('LLLL');
        }}
    ]
    });
</script>
@endsection