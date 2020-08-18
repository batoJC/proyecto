@extends('../layouts.app_dashboard_admin')

@section('title', 'Liquidador de nómina')
<style>
    .tarjeta{
        transition: 0.3s all;
        border: 2px solid #1ABB9C;
        width: 100%;
        margin: 10px auto 10px auto;
        padding: 20px;
        border-radius: 5px;
        display: block;
    }

    .tarjeta:hover {
        background: #1ABB9C;
        cursor: pointer;
    }

    .selected {
        background: #1ABB9C;
        cursor: pointer;
        color: white;
    }
    .selected > h3{

        color: white !important;
    }

    .selected > .ayuda{
        color: white !important;
    }

    .selected > .icon{
        color: white !important;
    }

    .selected > .btn{
        background: white !important;
        color: #1ABB9C !important;
    }

    .tarjeta:hover > h3{
        color: white;
    }

    .tarjeta:hover > .ayuda{
        color: white;
    }

    .tarjeta:hover > .icon{
        color: white;
    }

    .tarjeta:hover > .btn{
        background: white;
        color: #1ABB9C;
    }

    .tarjeta > input {
        float: left;
        -ms-transform: scale(1.5); /* IE */
        -moz-transform: scale(1.5); /* FF */
        -webkit-transform: scale(1.5); /* Safari y Chrome */
        -o-transform: scale(1.5); /* Opera */
        padding: 10px;
    }

    .btn:hover {
        color: black !important;
    }

    .tarjeta > .ayuda{
        font-size: 20px !important;
        float: right;
        cursor: pointer;
    }

    .tarjeta > h3 {
        color: #1ABB9C;
        font-size: 22px;
    }
    
    .tarjeta > .btn{
        background: #1ABB9C;
        color: white;
    }

    .p-30{
        padding: 30px;
    }


    .icon{
        color: #1ABB9C;
        font-size: 64px !important;
        font-weight: 100 !important;
    }


</style>
@section('content')

@include('admin.liquidador.form-pdf')

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
				  <li>Liquidador de nómina</li>
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
						<a target="_blanck" href="https://youtu.be/Q3MDF4KDyF0">¿Cómo registrar jornadas?</a>
						<a target="_blanck" href="https://youtu.be/FOsuqtXVyoc">¿Como generar prestaciones?</a>
						<a target="_blanck" href="https://youtu.be/Ql4ndQJ5d0U">Otras funciones</a>
						<a target="_blanck" href="https://youtu.be/CkV-gX_sur4">Liquidador</a>
						<a target="_blanck" href="https://youtu.be/0iCC_P5gIoE">¿Cómo generar liquidación?</a>
						<a target="_blanck" href="https://youtu.be/PJ89tL-71cA">¿Cómo descargar jornadas?</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="container-fluid bg-white">
        <h3>{{ $empleado->nombre_completo }}</h3>
        <p>
            Identificación: {{ $empleado->cedula }} <br>
            Salario: ${{ number_format($empleado->salario) }}
        </p>
        <div class="row">
            <div class="col-12 col-md-4">
                <a href="{{ url('jornadas',['empleado'=>$empleado->id]) }}" class="tarjeta text-center">
                    <i
                        data-placement="left" 
                        title="Podrá registrar y consultar las jornadas de trabajo" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-pencil icon"></i>
                    <h3 class="text-center">Registrar jornada</h3>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="{{ url('generarLiquidacion',['empleado'=>$empleado->id]) }}" class="tarjeta text-center">
                    <i
                        data-placement="left" 
                        title="Genere una liquidación de acuerdo con las jornadas registradas" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-print icon"></i>
                    <h3 class="text-center">Generar liquidación</h3>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a  href="{{ url('generarLiquidacionPrestaciones',['empleado'=>$empleado->id]) }}"
                    class="tarjeta text-center">
                    <i
                        data-placement="left" 
                        title="Genere prima, cesantías y vacaciones" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-usd icon"></i>
                    <h3 class="text-center">Generar prestaciones</h3>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-4">
                <a href="{{ url('listaLiquidaciones',['empleado'=>$empleado->id]) }}" class="tarjeta text-center">
                    <i
                        data-placement="left" 
                        title="Consulte la lista de todas las liquidaciones generadas" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-list-ol icon"></i>
                    <h3 class="text-center">Listar liquidaciones</h3>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a target="_blanck" 
                    href="{{ url('informacionLiquidador',['empleado'=>$empleado->id]) }}" 
                    class="tarjeta text-center">
                    <i
                        data-placement="left" 
                        title="Aquí encontrara una descripción detallada de 
                        la manera en que se calcula las liquidaciones" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-file-text-o icon"></i>
                    <h3 class="text-center">Información de liquidador</h3>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a onclick="modalJornadas();" class="tarjeta text-center">
                    <i
                        data-placement="left" 
                        title="Descargue todas las jornadas en un rango de fecha" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-download icon"></i>
                    <h3 class="text-center">Descargar jornadas en pdf</h3>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-4">
                <a href="{{ url('liquidacionesDownload',['empleado'=>$empleado->id]) }}" class="tarjeta text-center">
                    <i
                        data-placement="left" 
                        title="Descargue todas las liquidaciones generadas para este empleado" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-download icon"></i>
                    <h3 class="text-center">Descargar liquidaciones en pdf</h3>
                </a>
            </div>
        </div>
    </div>
    <br>
    <br>
    <br>
</div>


	
@endsection
@section('ajax_crud')
	<script>

        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        function modalJornadas(){
            $('#modal-pdf').modal('show');
        }


    </script>
@endsection
