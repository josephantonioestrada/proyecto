@extends('layouts.app')

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
            <a class="nav-link" aria-current="page" href="{{route('inicioMunicipalidades')}}">Municipalidad</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active bg-warning" href="#"><b>Agregar municipalidad</b></a>
        </li>
    </ul>
    <br>
    <form class="row g-3 needs-validation" novalidate method="post" action="{{url('guarMuni')}}"> 
    {{csrf_field()}} 
        <div class="col-md-12">
            <label for="validationCustom01" class="form-label">Municipalidad</label>
            <input type="text" class="form-control" id="validationCustom01" name="nombre" required>
            <div class="valid-feedback">
                Muy bien!
            </div>
            <div class="invalid-feedback">
            Favor complete este campo.
            </div>
        </div>
        <div class="col-md-12">
            <label for="validationCustom02" class="form-label">Direccion</label>
            <input type="text" class="form-control" id="validationCustom02" name="direccion" required>
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