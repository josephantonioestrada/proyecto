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
            <a class="nav-link" aria-current="page" href="{{route('inicioBuses')}}">Listado de lineas</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active bg-warning" href="#"><b>Agregar linea</b></a>
        </li>
    </ul>
    <form class="row g-3 needs-validation" novalidate method="post" action="{{url('guaLin')}}"> 
    {{csrf_field()}} 
        <div class="col-md-6">
            <label for="validationCustom01" class="form-label">Nombrea</label>
            <input type="text" class="form-control" id="validationCustom01" name="nombre" required>
            <div class="valid-feedback">
                Muy bien!
            </div>
            <div class="invalid-feedback">
            Favor complete este campo.
            </div>
        </div>
        <div class="col-md-6">
            <label for="validationCustom02" class="form-label">Municipalidad</label>
            <select class="form-control" name="municipalidad" required>
                <option value="">Seleccione una municipalidad</option>
                @foreach($municipalidades as $muni)
                <option value="{{$muni->idMunicipalidad}}">{{$muni->Nombre}}</option>
                @endforeach
            </select>
            <div class="valid-feedback">
                Muy bien!
            </div>
            <div class="invalid-feedback">
                Favor complete este campo.
            </div>
        </div>
        <div class="table-responsive" id="dynamic_field">
            <table class="table">
                <thead>
                    <tr>
                        <th>Estacion</th>
                        <th>Orden de estacion</th>
                        <th>Distancia a la proxima estacion</th>
                        <th>Distancia estacion anterior</th>
                    </tr>
                </thead>
                <tbody> 
                    <tr>
                        <td><select class="form-control" name="estacion[]" id="estacion" required>
                                <option value="">Seleccione una estacion</option>
                                @foreach($estaciones as $est)
                                <option value="{{$est->idEstacion}}">{{$est->Nombre}}</option>
                                @endforeach
                            </select>
                            <div class="valid-feedback">
                                Muy bien!
                            </div>
                            <div class="invalid-feedback">
                                Seleccione una estacion.
                            </div>
                        </td>
                        <td><input type="number" name="orden[]" class="form-control" id="orden" required>
                            <div class="valid-feedback">
                                Muy bien!
                            <div class="invalid-feedback">
                                Favor complete este campo.
                            </div>
                        </td>
                        <td><input type="number" step="0.1" name="distancia[]" class="form-control" id="distancia" required>
                            <div class="valid-feedback">
                                Muy bien!
                            </div>
                            <div class="invalid-feedback">
                                Favor complete este campo.
                            </div>
                        </td>
                        <td><input type="number" step="0.1" name="anterior[]" class="form-control" id="distanciaAnt" required>
                            <div class="valid-feedback">
                                Muy bien!
                            </div>
                            <div class="invalid-feedback">
                                Favor complete este campo.
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> 
        <div class="col-md-12">
            <td style="text-align: right;"><button type="button" name="add" id="add" class="btn btn-success">Más</button></td>
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
        $('#estacion').on('change', onSelectEstacionChange);
    });
    function onSelectEstacionChange(){
        var idEstacion = $(this).val();
            if(! idEstacion){
                $('#parqueo').html('<option value ="">Seleccione un parqueo</option>');
                return;
            };
            
        $.get('parq/'+idEstacion,function(data){
            var html_select = '<option value ="">Seleccione un parqueo</option>';
            for (var i=0; i<data.length; ++i)
                html_select += '<option value="'+data[i].idParqueo+'">'+data[i].Nombre+'</option>';
                $('#parqueo').html(html_select);
        });
        }
</script>
<script>
$(document).ready(function(){
    var i=1;

$('#add').click(function(){
    i++;
    $('#dynamic_field').append('<table id="row'+i+'" class="table"><thead><tr><th>Estacion</th><th>Orden de estacion</th><th>Distancia a la proxima estacion</th><th>Distancia estacion anterior</th></tr></thead><tbody><tr><td><select class="form-control" name="estacion[]" id="estacion" required><option value="">Seleccione una estacion</option>@foreach($estaciones as $est)<option value="{{$est->idEstacion}}">{{$est->Nombre}}</option>@endforeach</select><div class="valid-feedback">Muy bien!</div><div class="invalid-feedback">Seleccione una estacion.</div></td><td><input type="number" name="orden[]" class="form-control" id="orden" required><div class="valid-feedback">Muy bien!<div class="invalid-feedback">Favor complete este campo.</div></td><td><input type="number" name="distancia[]" step="0.1" class="form-control" id="distancia" required><div class="valid-feedback">Muy bien!</div><div class="invalid-feedback">Favor complete este campo.</div></td><td><input type="number" step="0.1" name="anterior[]" class="form-control" id="distanciaAnt" required><div class="valid-feedback">Muy bien!</div><div class="invalid-feedback">Favor complete este campo.</div></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr></tbody></table>');});
$(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });
    });
</script>
@endsection