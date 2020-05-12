<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Cuenta de Cobro</title>
	<style>
		html,body{
			height: 100%;
			width: 100%;
			background: #EDEDED;

		}

		@page {
            margin: 0em 0em 0em 7em;
        }

        .header{
        	height: 200px;
        	width: 100%;
        	background: #bbb7b7;
        }

        h2{
			font-family: sans-serif;
			text-align: center;
        	background: #bbb7b7;
			text-transform: uppercase;
			padding-top: 30px;
			margin: 0px;
        }

		.header  .uno {
			float: left;
			width: 30%;
			text-align: center;
		}

		.header .uno img {
			width: 80%;
			height: auto;
			margin: 0px 10% 0px 10%;
		}

		.header .dos{
			padding-top: 50px;
			float: right;
			width: 70%;
		}

		.header .dos p{
			font-family: sans-serif;
			margin: 2px 0px 2px 30px;
		}

        .cuentas {
        	padding: 10px 30px 10px 30px;
        }

        table{
        	border-collapse: collapse;
        	width: 100%;
        	border-top: 2px solid black;
        	border-bottom: 2px solid black;
        }

        th{
			padding: 8px 13 8px 13px;
        	border-bottom: 1px solid black;
			font-family: sans-serif;
			font-weight: 100;
			font-size: 12px;
			text-align: center;
			text-transform: uppercase;
        }

        td{
			padding: 8px 13 8px 13px;
        	border-bottom: 1px solid black;
			font-family: sans-serif;
			font-weight: 100;
			font-size: 14px;
			text-align: center;
        }

		.sello{
			z-index: 10000;
			position: fixed;
			height: 300;
			top: 300px;
			left: 150px;
			opacity: 0.5;
		}

	</style>
</head>
<body>
	{{-- <img class="sello" src="./imgs/sello.png" alt="sello"> --}}
	<h2 class="titulo">Comprobante cuenta de cobro</h2>
	<div class="header">
		<div class="uno">
			<img src="./imgs/logos_conjuntos/{{ $cuenta->unidad->conjunto->logo }}" alt="">
		</div>
		<div class="dos">
			<p>Cuenta: {{ $cuenta->prefijo.$cuenta->numero }}</p>
			<p>Fecha: {{ date('d M Y', strtotime($cuenta->fecha)) }}</p>
			<p>Nombre: {{ $cuenta->unidad->dueno->nombre_completo }}</p>
			<p>Apartamento: {{ $cuenta->unidad->tipo_unidad." ".$cuenta->unidad->numero_letra }}</p>
			<br>
		</div>		
	</div>
	<div class="cuentas">
		<table>
			<thead>
				<tr>
					<th>Fecha Cobro</th>
					<th>Fecha Vencimiento</th>
					<th>Descripción</th>
					<th>Valor</th>
					<th>Interes</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($cuotas as $cuota)
					<tr>
						<td>{{ $cuota['fecha1'] }}</td>
						<td>{{ $cuota['fecha2'] }}</td>
						<td>{{ $cuota['descripcion'] }}</td>
						<td>{{ "$".number_format($cuota['costo']) }}</td>
						<td>{{ "$".number_format($cuota['interes']) }}</td>
						<td>{{ "$".number_format($cuota['costo'] + $cuota['interes']) }}</td>
					</tr>
				@endforeach
				<tr>
					<td colspan="5" style="text-align:right;">Total a Pagar:</td>
					<td style="text-align:right;">{{ "$".number_format($deuda) }}</td>
				</tr>
			</tbody>
		</table>
	</div>

	{{-- <h3>{{ $deuda }}</h3> --}}
</body>
</html>