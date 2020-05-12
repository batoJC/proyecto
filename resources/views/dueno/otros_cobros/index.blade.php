@extends('../layouts.app_dashboard_dueno')

@section('content')
	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('dueno') }}">Inicio</a>
		</li>
	  	<li>Mis Otros Cobros</li>
	</ul>
	<table class="table datatable">
		<thead>
			<th>Costo</th>
			<th>Descipción (Concepto)</th>
			<th>Estado</th>
		</thead>
		<tbody>
			@foreach($otros_cobros as $otros)
				<tr>
					<td>{{ $otros->costo }}</td>
					<td>{{ str_limit($otros->descripcion, 50) }}</td>
					<td>{{ $otros->estado }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection