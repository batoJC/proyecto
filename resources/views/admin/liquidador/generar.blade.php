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
                      <li>Generar liquidación</li>
                </ul>
            </div>
        </div>
        <div class="container-fluid">
            <h3 class="text-center">
                Selecciona los rangos de fechas y genera una liquidación
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
                <div class="col-md-4">
                    <label for="fecha_fin">Fecha final</label>
                    <input class="form-control" type="date" name="fecha_fin" id="fecha_fin">
                </div>
            </div>
            <br>
            <div class="col text-center">
                <button type="button" onclick="cargarLiquidacion()" class="btn btn-success">Cargar liquidación <i class="fa fa-spinner"></i></button>
            </div>
            <br>
            <div id="tabla_liquidacion">

            </div>


        </div>

    </div>

@endsection
@section('ajax_crud')
	<script>

        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        var empleado = {{ $empleado->id }};

        function cargarLiquidacion(){
            $.ajax({
                type: "POST",
                url: "{{ url('cargarLiquidacion') }}",
                data: {
                    _token : csrf_token,
                    consecutivo : consecutivo.value,
                    fecha_inicio : fecha_inicio.value,
                    fecha_fin :fecha_fin.value,
                    empleado : empleado
                },
                dataType: 'html',
                success: function (response) {
                    $('#tabla_liquidacion').html(response);
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
