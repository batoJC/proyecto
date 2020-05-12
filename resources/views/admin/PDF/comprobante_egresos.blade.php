<?php  

	// Dos arreglos para la sumatoria de los elementos
    $array_retencion_total = [];
    $array_sumatoria_total = [];

    // Ciclo que define los arreglos y los llena con el array push
    foreach ($detalles_egresos as $value) {
        array_push($array_retencion_total, $value->valor_retencion);
        array_push($array_sumatoria_total, $value->sub_valor_antes_iva);
    }

    // Sumar los elementos de la retencion y retorno a la vista
    $retencion_total = array_sum($array_retencion_total);
    $sumatoria_total = array_sum($array_sumatoria_total) - array_sum($array_retencion_total);	
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Factura</title>
	<style>
		table{
			width: 100%;
			border-spacing: 0px;
		}
		table th, td{
			padding:10px;
			text-align: center;
			font-family: sans-serif;
			font-weight: 100;
			font-size: 12px;
		}
		table th{
			font-size: 14px;
			background-color: #67ceb8;
		}
		table td{
			font-size: 13px;
		}
		table td.special_td_color_1{
			color:red;
		}
		table td.special_td_color_2{
			font-weight: bolder;
			font-size: 14px;
		}
		div.container-gestion{
			width: 100%;
			height: 300px;
		}
		div.information-gestion{
			width: 100%;
			height: 60px;
		}
		div.information-gestion > div.information-gestion-container{
			float: right;
			width: 340px;
			height: 100%;
		}
		div.information-gestion > div.information-gestion-container > p.p-comprobante{
			color:red;
			font-weight: bold;
		}
		div.information-gestion > div.information-gestion-container > span.division{
			display: inline-block;
			width: 100%;
			height: 3px;
			margin-top: 3px;
			background-color: #67ceb8;
		}
		p.p-fecha{
			font-weight: bold;
		}
		div.information-gestion-container p {
			font-size: 12px;
			margin:5px 0px 0px 0px;
		}
		div.logo-gestion{
			
			height: 240px;
			width: 20%;
		}
		div.logo-gestion img{
			margin-top: 20px;
			margin-left: 20px;
		}
		div.cliente-gestion{
			border-left:6px solid #67ceb8;
			border-right:6px solid #67ceb8;
			height: 190px;
			padding:0px 30px;
			top:70px;
			position:fixed;
			right:0px;
			width: 43%;
		}
		div.cliente-gestion h1{
			color:#2a3f54;
			font-size: 20px;
		}
		div.cliente-gestion p{
			font-size: 12px;
			margin:4px;
		}
		span.division-fin{
			display: inline-block;
			width: 100%;
			height: 3px;
			background-color: #67ceb8;
			margin-top: 150px;
		}
		.color-general{
			color:#2a3f54;
			font-family: sans-serif;
			font-weight: 100;
			font-size: 12px;
		}
		.border{
			border:1px solid black;
		}
	</style>
</head>
<body>
	<div class="container-gestion">
		<div class="information-gestion">
			<div class="information-gestion-container">
				<p class="p-comprobante color-general">Comprobante de ingreso: {{ $egresos->id }}</p>
				<span class="division"></span>
				<p class="p-fecha color-general">Fecha: {{ $egresos->created_at }}</p>
			</div>
		</div>
		<div class="logo-gestion">
			<img src="./imgs/gestioncopropietarios_color.jpg" alt="Gestion_logo" width="150%">
		</div>
		<div class="cliente-gestion">
			<h1 class="color-general">Cliente</h1>
			<p class="color-general">{{ $egresos->user->nombre_completo }}</p>
			<p class="color-general">CC o NIT: {{ $egresos->user->numero_cedula }}</p>
			<p class="color-general">Concepto: {{ $egresos->concepto }}</p>
			<p class="color-general">Total: <?php echo($sumatoria_total) ?></p>
		</div>
	</div>
	<table>
		<thead>
			<tr>
				<th>Conceptos retencion y su porcentaje</th>
				<th>Valor Retencion</th>
				<th>Sub Valor Con Iva</th>
				<th>Iva</th>
				<th>Descripcion</th>
				<th>Sub Valor Antes del Iva</th>
			</tr>
		</thead>
		<tbody>
			@foreach($detalles_egresos as $detalles)
				<tr>
					<td>{{ $detalles->conceptos_retencion->descripcion.' - '.$detalles->conceptos_retencion->porcentaje }}</td>
					<td>$ {{ number_format($detalles->valor_retencion) }}</td>
					<td>$ {{ number_format($detalles->sub_valor_con_iva) }}</td>
					<td>{{ $detalles->iva.' %' }}</td>
					<td>{{ $detalles->descripcion }}</td>
					<td>$ {{ number_format($detalles->sub_valor_antes_iva) }}</td>
				</tr>
			@endforeach
			{{-- Machetazo para ponerle margen a la tabla --}}
			<tr>
				<td></td>
			</tr>
			<tr>
				<td></td>
			</tr>
			<tr>
				<td></td>
			</tr>
			{{-- Columna de abajo para calculos de RTFE --}}
			{{-- ************************************** --}}
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="special_td_color_1">Menos RTFE</td>
				<td class="special_td_color_1"><?php echo('- '.$retencion_total) ?></td>
			</tr>
			{{-- Total --}}
			{{-- ***** --}}
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td class="special_td_color_2">TOTAL A PAGAR</td>
				<td class="special_td_color_2"><?php echo($sumatoria_total) ?></td>
			</tr>
		</tbody>
	</table>
	<br>
	<span class="division-fin"></span>
	<p class="p-fecha color-general">Fecha: {{ $egresos->created_at }}</p>
{{-- 	<br>
	<p>Concepto del Detalle</p>
	<p>{{ $detalles->egresos->concepto }}</p> --}}
</body>
</html>