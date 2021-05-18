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
            <a class="nav-link" href="{{route('inicioEstaciones')}}">Atras</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active bg-warning" aria-current="page" href="#"><b>Estacion {{$estacion->Nombre}}</b></a>
        </li>
        <li class="nav-item">
            <button type="button" class="nav-link" data-bs-toggle="modal" data-bs-target="#formParqueo">
                Agregar parqueo
            </button>
        </li>
    </ul>
    <table class="table">
        <thead class="table-primary">
            <tr>
                <th>Nombre estacion</th>
                <th>Direccion</th>
                <th>Municipalidad</th>
                <th>Fecha creacion</th>
                <th>Fecha modificacion</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$estacion->Nombre}}</td>
                <td>{{$estacion->Ubicacion}}</td>
                <td>{{$estacion->Muni}}</td>
                <td>{{date('d/m/Y H:i:s','strtotime'($estacion->FechaCreacion))}}</td>
                <td>{{date('d/m/Y H:i:s','strtotime'($estacion->FechaModificacion))}}</td>
            </tr>
            <tr>
                <th colspan="5" class="table-success">Parqueos dentro de la estacion</th>
            </tr>
            @foreach($parqueos as $pq)
            <tr>
                <td colspan="3">{{$pq->Nombre}}</td>
                <td>{{date('d/m/Y H:i:s','strtotime'($pq->FechaCreacion))}}</td>
                <td>{{date('d/m/Y H:i:s','strtotime'($pq->FechaModificacion))}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table class="table" id="muni">
        <thead class="table-warning">
            <tr>
                <th scope="col" colspan="4" style="text-align:center;">Accesos dentro de la estacion</th>
            </tr>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Fecha de creacion</th>
                <th scope="col">Fecha de modificacion</th>
            </tr>
        </thead>
    </table>
</div>
<!-- Modal -->
<div class="modal fade" id="formParqueo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar parqueo a la estacion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form class="row g-3 needs-validation" novalidate method="post" action="{{url('guaPar',$estacion->idEstacion)}}"> 
            {{csrf_field()}} 
                <div class="col-md-12">
                    <label for="validationCustom01" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="validationCustom01" name="nombre" required>
                    <div class="valid-feedback">
                        Muy bien!
                    </div>
                    <div class="invalid-feedback">
                    Favor complete este campo.
                    </div>
                </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Clic para guardar</button>
                </div>
            </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    var table = $('#muni').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 100,
        searching: true,
        responsive: false,
        ajax:{
            url: "{{route('detallesAccesos',$estacion->idEstacion)}}",
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
                { data: 'idAcceso', name: 'idAcceso'},
                { data: 'Nombre', name:'Nombre'},
                { data: 'FechaCreacion', name: 'FechaCreacion'},
                { data: 'FechaModificacion', name:'FechaModificacion'}
                ],
        "columnDefs": [
        { targets: 0, searchable: true },
        { targets: [0,1], searchable: true },
        { targets: '_all', searchable: false },
        {targets: [2,3], render:function(data){
            moment.locale('es');
            return moment(data).format('LLLL');
        }}
    ]
    });
</script>
<script type="application/javascript">
// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
  'use strict'

  // Fetch all the forms we want to apply custom Bootstrap validation styles to
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()
</script>
@endsection