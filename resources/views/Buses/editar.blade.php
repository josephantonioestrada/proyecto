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
            <a class="nav-link" aria-current="page" href="{{route('inicioBuses')}}">Listado buses</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active bg-warning" href="#"><b>Editar bus</b></a>
        </li>
    </ul>
    <br>
    <form class="row g-3 needs-validation" novalidate method="post" action="{{url('guaEdi',$id)}}"> 
    {{csrf_field()}} 
    @method('PUT')
        <div class="col-md-6">
            <label for="validationCustom01" class="form-label">Placa</label>
            <input type="text" class="form-control" id="validationCustom01" name="placa" value="{{$bus->Placa}}" required>
            <div class="valid-feedback">
                Muy bien!
            </div>
            <div class="invalid-feedback">
            Favor complete este campo.
            </div>
        </div>
        <div class="col-md-6">
            <label for="validationCustom02" class="form-label">Capacidad de pasajeros</label>
            <input type="number" class="form-control" id="validationCustom02" name="capacidad" value="{{$bus->Capacidad}}" required>
            <div class="valid-feedback">
                Muy bien!
            </div>
            <div class="invalid-feedback">
                Favor complete este campo.
            </div>
        </div>
        <div class="col-md-6">
            <label for="Estacion" class="form-label">Seleccione la estacion</label>
            <select class="form-control" name="estacion" id="estacion" required>
                <option value="{{$bus->idEstacion}}">{{$bus->estacion}}</option>
                @foreach($estaciones as $est)
                @if($est->idEstacion != $bus->idEstacion)
                <option value="{{$est->idEstacion}}">{{$est->Nombre}}</option>
                @endif
                @endforeach
            </select>
            <div class="valid-feedback">
                Muy bien!
            </div>
            <div class="invalid-feedback">
                Seleccione una estacion.
            </div>
        </div>
        <div class="col-md-6">
            <label for="Parqueo" class="form-label">Seleccione el parqueo</label>
            <select class="form-control" name="parqueo" id="parqueo" required>
                <option value="{{$bus->idParqueo}}">{{$bus->parqueo}}</option>
            </select>
            <div class="valid-feedback">
                Muy bien!
            </div>
            <div class="invalid-feedback">
                No puede dejar este campo vacio.
            </div>
        </div>
        <div class="col-md-12">
            <label for="Linea" class="form-label">Asignar linea</label>
            <select class="form-control" name="linea">
                @if($bus->idLinea == '')
                <option value="">Seleccione una linea</option>
                @else
                <option value="{{$bus->idLinea}}">{{$bus->linea}}</option>
                @endif
                @foreach($lineas as $li)
                @if($bus->idLinea == $li->idLinea)
                @else
                <option value="{{$li->idLinea}}">{{$li->Nombre}}</option>
                @endif
                @endforeach
            </select>
            <div class="valid-feedback">
                Muy bien!
            </div>
        </div>
        
  <div class="col-12">
    <button class="btn btn-primary" type="submit">Clic para guardar</button>
  </div>
</form>
</div>
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
<script>
    $(function(){
        $('#estacion').on('click', onSelectEstacionChange);
    });
    function onSelectEstacionChange(){
        var idEstacion = $(this).val();
            if(! idEstacion){
                $('#parqueo').html('<option value ="">Seleccione un parqueo</option>');
                return;
            };
            
        $.get(url_global+'/parq/'+idEstacion,function(data){
            var html_select = '<option value ="">Seleccione un parqueo</option>';
            for (var i=0; i<data.length; ++i)
                html_select += '<option value="'+data[i].idParqueo+'">'+data[i].Nombre+'</option>';
                $('#parqueo').html(html_select);
        });
        }
</script>
@endsection