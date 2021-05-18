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
            <a class="nav-link" href="{{route('inicioEmpleados')}}">Atras</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active bg-warning" aria-current="page" href="#"><b>Agregar nuevo empleado</b></a>
        </li>
    </ul>
    <br>
    <div class="card">
        <div class="card-body">
            <form class="row g-3 needs-validation" novalidate method="post" action="{{url('guaEmp')}}"> 
            {{csrf_field()}} 
                <div class="col-md-6">
                    <label for="validationCustom01" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="validationCustom01" name="nombre" required>
                    <div class="valid-feedback">
                        Muy bien!
                    </div>
                    <div class="invalid-feedback">
                    Favor complete este campo.
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom02" class="form-label">Direccion</label>
                    <input type="text" class="form-control" id="validationCustom02" name="direccion" required>
                    <div class="valid-feedback">
                        Muy bien!
                    </div>
                    <div class="invalid-feedback">
                        Favor complete este campo.
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom02" class="form-label">Telefono</label>
                    <input type="number" class="form-control" id="validationCustom02" name="telefono" required>
                    <div class="valid-feedback">
                        Muy bien!
                    </div>
                    <div class="invalid-feedback">
                        Favor complete este campo.
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom02" class="form-label">Telefono opcional</label>
                    <input type="number" class="form-control" id="validationCustom02" name="telefonoop">
                    <div class="valid-feedback">
                        Muy bien!
                    </div>
                    <div class="invalid-feedback">
                        Favor complete este campo.
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="validationCustom02" class="form-label">Correo</label>
                    <input type="email" class="form-control" id="validationCustom02" name="correo">
                    <div class="valid-feedback">
                        Muy bien!
                    </div>
                    <div class="invalid-feedback">
                        Formato no valido.
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="Municipalidad" class="form-label">Plaza</label>
                    <select class="form-control" name="plaza" required>
                        <option value="">Seleccione una plaza</option>
                        @foreach($roles as $r)
                        <option value="{{$r->idRol}}">{{$r->Nombre}}</option>
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
                <table class="table" >
                    <thead>
                        <tr>
                            <th>Centro educativo</th>
                            <th>Carrera cursada</th>
                            <th>Fecha de ingreso</th>
                        </tr>
                    </thead>
                    <tbody > 
                        <tr>
                            <td><input type="text" class="form-control" name="nombreCentro[]" id="centro" required></td>
                            <td><input type="text" class="form-control" name="carrera[]" id="carrera" required></td>
                            <td><input type="date" class="form-control" name="fechaIngreso[]" id="fechaIngreso" required></td>
                        </tr>
                    </tbody>
                    <thead>
                        <tr>
                            <th>Fecha de egreso</th>
                            <th>Telefono del establecimiento</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="date" class="form-control" name="fechaEgreso[]" id="fechaEgreso" required></td>
                            <td><input type="number" class="form-control" name="telefonoEst[]" id="telefonoEst"></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
                <div class="col-12">
                <td style="text-align: right;"><button type="button" name="add" id="add" class="btn btn-success">Más</button></td>
                </div>
            </div>
                <div class="col-12">
                    <button class="btn btn-primary" type="submit">Clic para guardar</button>
                </div>
            </form>
        </div>
    </div>
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
$(document).ready(function(){
    var i=1;

$('#add').click(function(){
    i++;
    $('#dynamic_field').append('<table id="row'+i+'" class="table"><thead><tr><th>Centro educativo</th><th>Carrera cursada</th><th>Fecha de ingreso</th></tr></thead><tbody><tr><td><input type="text" class="form-control" name="nombreCentro[]" id="centro" required></td><td><input type="text" class="form-control" name="carrera[]" id="carrera" required></td><td><input type="date" class="form-control" name="fechaIngreso[]" id="fechaIngreso" required></td></tr></tbody><thead><tr><th>Fecha de egreso</th><th>Telefono del establecimiento</th><th>Eliminar</th></tr></thead><tbody><tr><td><input type="date" class="form-control" name="fechaEgreso[]" id="fechaEgreso" required></td><td><input type="number" class="form-control" name="telefonoEst[]" id="telefonoEst"></td><td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr></tbody></table>');
});
$(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });
    });
</script>
@endsection