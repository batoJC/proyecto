<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style>
		table{
			width: 100%;
			border-spacing: 0px;
		}
		table th, td{
			padding:10px;
			text-align: center;
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
				<p class="p-comprobante color-general">Comprobante de ingreso: {{ $ingresos->id }}</p>
				<span class="division"></span>
				<p class="p-fecha color-general">Fecha: {{ $ingresos->created_at }}</p>
			</div>
		</div>
		<div class="logo-gestion">
			<img src="http://asweb.com.co/gestioncopropietarios/imgs/gestioncopropietarios_color.jpg" alt="Gestion_logo" width="150%">
		</div>
		<div class="cliente-gestion">
			<h1 class="color-general">Cliente</h1>
			<p class="color-general">{{ $ingresos->persona_pago }}</p>
			@if($ingresos->descripcion != null)
				<p class="color-general">{{ str_limit($ingresos->descripcion, 50) }}</p>
			@else
				<p class="color-general">No aplica</p>	
			@endif
			<p class="color-general">{{ $ingresos->valor }}</p>
		</div>
	</div>
	<table>
		<thead>
			<tr>
				<th>Valor</th>
				<th>Persona (Que Pago)</th>
				<th>Persona (Que Recibió el dinero)</th>
				<th>Tipo de Unidad</th>
				<th>Conjunto</th>
			</tr>
			<tr>
				<td>{{ $ingresos->valor }}</td>
				<td>{{ $ingresos->persona_pago }}</td>
				<td>{{ $ingresos->persona_recibe }}</td>
				<td>{{ $ingresos->tipo_unidad->tipo_unidad.' - '.$ingresos->tipo_unidad->numero_letra }}</td>
				<td>{{ $ingresos->conjunto->nombre }}</td>
			</tr>
			{{-- Machetazo para ponerle margen a la tabla --}}
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
				<td class="special_td_color_2">TOTAL A PAGAR</td>
				<td class="special_td_color_2">{{ $ingresos->valor }}</td>
			</tr>
		</tbody>
	</table>
	<br>
	<span class="division-fin"></span>
	<p class="p-fecha color-general">Fecha: {{ $ingresos->created_at }}</p>
</body>
</html>