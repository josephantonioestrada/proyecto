@extends('layouts.app')

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
            <a class="nav-link active bg-warning" aria-current="page" href="#"><b>Detalles de la ruta</b></a>
        </li>
        <li class="nav-item">
            <A class="nav-link bg-danger" href="{{route('finalizarRuta',$id)}}">Finalizar ruta</a>
        </li> 
    </ul>
    <table class="table">
        <thead class="table-primary">
            <tr>
                <th scope="col">Estacion actual</th>
                <th scope="col">Cantidad de pasajeros</th>
                <th scope="col">Distancia siguiente estacion</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{$est->Nombre}}</td>
                @if($detRut == '')
                <td></td>
                @else
                <td>{{$detRut->TotalPasajeros}}</td>
                @endif
                <td>{{$detLin->DistanciaSigEstacion}}</td> 
            </tr>
        </tbody>
    </table>
    <form class="row g-3 needs-validation" novalidate method="post" action="{{url('guaEdRuE',$id)}}"> 
    {{csrf_field()}} 
        <div class="col-md-6">
            <label for="Bus" class="form-label">Pasajeros que subieron</label>
            <input class="form-control" type="number" step="1" min="0" name="spasajeros" required>
            <input class="form-control" type="number" required name="estacion" value="{{$est->idEstacion}}" style="visibility: hidden;">
            <div class="valid-feedback">
                Muy bien!
            </div>
            <div class="invalid-feedback">
                No puede dejar este campo vacio.
            </div>
        </div>
        <div class="col-md-6">
            <label for="Bus" class="form-label">Pasajeros que salieron</label>
            <input class="form-control" type="number" step="1" min="0" name="bpasajeros" required>
            <div class="valid-feedback">
                Muy bien!
            </div>
            <div class="invalid-feedback">
                No puede dejar este campo vacio.
            </div>
        </div>
        <div class="col-md-12">
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
        $('#linea').on('change', onSelectEstacionChange);
    });
    function onSelectEstacionChange(){
        var idLinea = $(this).val();
            if(! idLinea){
                $('#bus').html('<option value ="">Seleccione un bus</option>');
                return;
            };
            
        $.get('selBus/'+idLinea,function(data){
            var html_select = '<option value ="">Seleccione un bus</option>';
            for (var i=0; i<data.length; ++i)
                html_select += '<option value="'+data[i].idBus+'">'+data[i].Placa+'</option>';
                $('#bus').html(html_select);
        });
        }
</script>
@endsection