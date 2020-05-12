<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Comprobante de Pago</title>
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
			opacity: 0.05;
		}

	</style>
</head>
<body>
	<img class="sello" src="./imgs/sello.png" alt="sello">
	<h2>Comprobante de Pago</h2>
	<div class="header">
		<div class="uno">
			<img src="./imgs/logos_conjuntos/{{ $unidad->conjunto->logo }}" alt="">
		</div>
		<div class="dos">
			<p>Cuenta: {{ $cuenta }}</p>
			<p>Fecha: {{ date('d M Y', strtotime($fecha)) }}</p>
			<p>Nombre: {{ $unidad->dueno->nombre_completo }}</p>
			<p>Apartamento: {{ $unidad->tipo_unidad." ".$unidad->numero_letra }}</p>
		</div>		
	</div>
	<div class="cuentas">
		<table>
			<thead>
				<tr>
					<th>Fecha</th>
					<th>Movimiento</th>
					<th>Capital</th>
					<th>Interes</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody>
            @php
                $total = 0;
            @endphp
				@foreach ($cuentas as $cuenta)
                    @php
                        $cuenta['capital'] = filter_var($cuenta['capital'], FILTER_SANITIZE_NUMBER_INT);
                        $cuenta['interes'] = filter_var($cuenta['interes'], FILTER_SANITIZE_NUMBER_INT);                    
                    @endphp
					<tr>
						<td>{{ $cuenta['fecha'] }}</td>
						<td>{{ $cuenta['movimiento'] }}</td>
						<td>{{ number_format($cuenta['capital']) }}</td>
						<td>{{ number_format($cuenta['interes']) }}</td>
                        <td>${{ number_format($cuenta['capital'] + $cuenta['interes']) }}</td>
					</tr>
                    @php
                        $total += $cuenta['capital'] + $cuenta['interes'];
                    @endphp
				@endforeach
				<tr>
					<td colspan="4" style="text-align:right">Total Pagado:</td>
					<td style="text-align:right">${{ number_format($total) }}</td>
				</tr>
			</tbody>
		</table>
	</div>

</body>
</html>