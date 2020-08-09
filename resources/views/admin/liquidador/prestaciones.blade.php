@extends('../layouts.app_dashboard_admin')

@section('title', 'Generar liquidación')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-11 col-md-11">
                <ul class="breadcrumb">
                    <li>
                        <a href="{{ asset('admin') }}">Inicio</a>
                    </li>
                    <li>
                        <a href="{{ url('empleados_conjunto') }}">Empleados conjunto</a>
                    </li>
                    <li>
                        <a href="{{ url('liquidador',['empleado' => $empleado->id]) }}">Liquidador de nómina</a>
                    </li>
                      <li>Generar prestaciones</li>
                </ul>
            </div>
            <div class="col-1 col md-1 text-right">
                <div class="btn-group">
                    <i  data-placement="left" 
                        title="Ayuda" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false"
                        class="fa blue fa-question-circle-o ayuda">
                    </i>
                    <ul role="menu" class="dropdown-menu pull-right">
                        <li>
                            <a target="_blanck" href="#">Video 1</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <h3 class="text-center">
                Selecciona la fecha de inicio y un consecutivo 
                para liquidar las prestaciones hasta la última fecha de liquidación
            </h3>
            <br>
            <div class="row">
                <div class="col-md-4">
                    <label for="consecutivo">Consecutivo</label>
                    <select class="form-control" name="consecutivo" id="consecutivo">
                        <option value="">Seleccione un consecutico</option>
                        @foreach ($consecutivos as $consecutivo)
                            <option value="{{ $consecutivo->id }}">{{$consecutivo->prefijo}}-{{$consecutivo->numero}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="fecha_inicio">Fecha de Inicio</label>
                    <input class="form-control" type="date" name="fecha_inicio" id="fecha_inicio">
                </div>
                <div class="col-md-4 text-center">
                    <label style="visibility:hidden" for="">none</label><br>
                    <button type="button" onclick="cargarPrestaciones()" class="btn btn-success"> Generar prestaciones <i class="fa fa-spinner"></i></button>
                </div>
            </div>
            <br>
            <div id="tabla_prestaciones">

            </div>


        </div>

    </div>

@endsection
@section('ajax_crud')
	<script>

        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        var empleado = {{ $empleado->id }};

        function cargarPrestaciones(){
            $.ajax({
                type: "POST",
                url: "{{ url('cargarPrestaciones') }}",
                data: {
                    _token : csrf_token,
                    consecutivo : consecutivo.value,
                    fecha_inicio : fecha_inicio.value,
                    empleado : empleado
                },
                dataType: 'html',
                success: function (response) {
                    $('#tabla_prestaciones').html(response);
                    $('[data-toggle="tooltip"]').tooltip({
                        container: 'body'
                    });
                }
            });
        }
        
        
        const formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2
        });

    </script>
@endsection
