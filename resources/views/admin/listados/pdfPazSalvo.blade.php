
@extends('admin.PDF.plantilla')

@section('style')

    <style>
        main{
            margin-left: 0px !important;
            margin-right: 0px !important;
        }

        .borde{
            border-radius: 10px;
            border: 1px solid #333;
            padding: 10px;
            width: 100%;
        }

        .red{
            color: red;
        }

        .text-center{
            text-align: center;
        }

        .text-left{
            text-align: left;
        }

        h1,h4{
            margin: 2px 0px 2px 0px;
            font-weight: 200;
        }

        table{
            border-collapse: collapse;
        }

        th,td{
            margin: 0px !important;
            border: 1px solid black;
            font-size: 13px;
            text-align: center;
        }

        p{
            margin-bottom: 2px;
            margin-top: 2px;
        }

        h6{
            margin:0px;
        }

    </style>
    
@endsection

@section('contenido')
<h6>Fecha: {{ date('d-m-Y') }}</h6>

<h2 class="text-center">Listado de personas a paz y salvo</h2>

<br><br>
	<table class="table">
		<thead>
			<tr>
				<th>Cédula</th>
				<th>Nombre</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($propietarios as $propietario)
				<tr>
					<td>{{ $propietario->numero_cedula }}</td>
					<td>{{ $propietario->nombre_completo }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection
