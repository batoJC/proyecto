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
						<a target="_blanck" href="#">Video 1</a>
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
                        title="Descripción" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-pencil icon"></i>
                    <h3 class="text-center">Registrar jornada.</h3>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a href="{{ url('generarLiquidacion',['empleado'=>$empleado->id]) }}" class="tarjeta text-center">
                    <i
                        data-placement="left" 
                        title="Descripción" 
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
                <a class="tarjeta text-center">
                    <i
                        data-placement="left" 
                        title="Descripción" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-gears icon"></i>
                    <h3 class="text-center">Generar prima</h3>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-4">
                <a class="tarjeta text-center">
                    <i
                        data-placement="left" 
                        title="Descripción" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-usd icon"></i>
                    <h3 class="text-center">Generar cesantía</h3>
                </a>
            </div>
            <div class="col-12 col-md-4">
                <a class="tarjeta text-center">
                    <i
                        data-placement="left" 
                        title="Descripción" 
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
                <a target="_blanck" href="{{ url('informacionLiquidador') }}" class="tarjeta text-center">
                    <i
                        data-placement="left" 
                        title="Descripción" 
                        data-toggle="dropdown" 
                        type="button" 
                        aria-expanded="false" 
                        class="fa fa-info ayuda"></i>
                    <br>
                    <i class="fa fa-file-text-o icon"></i>
                    <h3 class="text-center">Información de liquidador</h3>
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

        


    </script>
@endsection
