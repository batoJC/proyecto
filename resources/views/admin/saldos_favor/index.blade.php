@extends('../layouts.app_dashboard_admin')

@section('title', 'Saldos a Favor')

@section('content')
	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('admin') }}">Inicio</a>
		</li>
	  	<li>Saldos a Favor</li>
	</ul>
	<br>
	@include('admin.saldos_favor.form')
	<table class="table datatable">
		<thead>
			<th>Saldo</th>
			<th>Estado</th>
			<th>Tipo de unidad</th>
			<th>Fecha</th>
		</thead>
		<tbody>
			@foreach($saldos_favor as $saldo)
				<tr>
					<td>{{ $saldo->saldo }}</td>
					<td>{{ $saldo->estado }}</td>
					<td>{{ $saldo->tipo_unidad->tipo_unidad.' - '.$saldo->tipo_unidad->numero_letra }}</td>
					<td>{{ $saldo->created_at }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection
