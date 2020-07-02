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
                Selecciona los rangos de fechas y genera una liquidación
            </h3>
        </div>

    </div>

@endsection
@section('ajax_crud')
	<script>

        var csrf_token = $('meta[name="csrf-token"]').attr('content');

     

    </script>
@endsection
