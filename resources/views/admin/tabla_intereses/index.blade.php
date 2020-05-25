@extends('../layouts.app_dashboard_admin')

@section('title', 'Tabla de Intereses')
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			{{-- ************************************** --}}
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				<li>Tabla de intereses</li>
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
						<a target="_blanck" href="https://youtu.be/9x1ZilqPaNs">¿Qué son?</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<style>
		th,td{
			font-size: 13px !important;
		}
	</style>
	{{-- Variable de session para actualización --}}
	{{-- ************************************** --}}
	@if(session('status'))
		<div class="alert alert-error alert-dismissible" role="alert">
			<button type="button" class="close btn_close_alert" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">x</span>
			</button>
			{!! html_entity_decode(session('status')) !!}
		</div>
	@endif
	<br>
	
	<table id="intereses-table" class="table">
		<thead>
			<th>Periodo</th>
			<th>Numero de Resolución</th>
			<th>Fecha de inicio (Vigencia)</th>
			<th>Fecha de fin (Vigencia)</th>
			<th>Tasa Efectiva Anual</th>
			<th>Tasa Efectiva Anual Mora</th>
			<th>Tasa Mora Nominal Anual</th>
			<th>Tasa Mora Nominal Mensual</th>
			<th>Tasa Diaria</th>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

@endsection
@section('ajax_crud')
	<script>
		var actualizarTabla = (data,callback,settings) => {
			$.ajax({
				type: "GET",
				url: "{{ url('api.intereses.admin') }}",
				data: data,
				dataType: "json",
				success: function (response) {
				callback(
					response
				);
				$('[data-toggle="tooltip"]').tooltip({
					container: 'body'
				});
				}
			});
		}

		// Listar los registros
		// *************************************
		var table  = $('#intereses-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
				{ orderable: false,  data: 'periodo', name: 'periodo'},
          		{ orderable: false,  data: 'numero_resolucion', name: 'numero_resolucion'},
          		{ data: 'fecha_vigencia_inicio', name: 'fecha_vigencia_inicio'},
          		{ orderable: false,  data: 'fecha_vigencia_fin', name: 'fecha_vigencia_fin'},
          		{ orderable: false,  data: 'tasa_efectiva_anual', name: 'tasa_efectiva_anual'},
          		{ orderable: false,  data: 'tasa_efectiva_anual_mora', name: 'tasa_efectiva_anual_mora'},
          		{ orderable: false,  data: 'tasa_mora_nominal_anual', name: 'tasa_mora_nominal_anual'},
          		{ orderable: false,  data: 'tasa_mora_nominal_mensual', name: 'tasa_mora_nominal_mensual'},
          		{ orderable: false,  data: 'tasa_diaria', name: 'tasa_diaria'},
          	],
			language: {
                "processing": "Procesando...",
                "search": "Buscar:",
                "lengthMenu": "Mostrando _MENU_ por página",
                "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
                "infoEmpty": "Mostrando 0 de 0 registros",
                "infoFiltered": "(se han filtrado _MAX_ registros)",
                "infoPostFix": "",
                "loadingRecords": "Cargando...",
                "zeroRecords": "Ningún registro coincide con la búsqueda",
                "emptyTable": "Sin registros",
                "paginate": {
                    "first": "Primero",
                    "previous": "Anterior",
                    "next": "Siguiente",
                    "last": "Último"
                },
                "aria": {
                    "sortAscending": ": aktywuj, by posortować kolumnę rosnąco",
                    "sortDescending": ": aktywuj, by posortować kolumnę malejąco"
                }
			}
		});

	</script>
@endsection
