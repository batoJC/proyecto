@extends('../layouts.app_dashboard_admin')

@section('title', 'Personas a paz y salvo')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Listado de personas a paz y salvo</li>
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
	
	<a target="_blanck" href="{{ url('paz_salvoDownload') }}" class="btn btn-primary">
	<i class="fa fa-download"></i> Descargar Listado</a>
	<br><br>
	
	{{-- inicio modal para el cuerpo de la carta de paz y salvo --}}
	<div class="modal fade moda" id="modalCertificado" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
				<h4 class="text-center modal-title">
					Generar certificado de paz y salvo
				</h4>
			  </div>
			  <div class="modal-body">
			  <form target="_blanck" method="post" action="{{ url('pdfPazSalvo') }}" enctype="multipart/form-data">
					@csrf {{ method_field('POST') }}
					{{-- Cuerpo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-font"></i>
							<label class="margin-top">
								Cuerpo
							</label>
						</div>
						<div class="col-md-8">
							<textarea name="cuerpo" id="cuerpo" cols="30" rows="5" placeholder="Ejemplo: QUE DE ACUERDO A INFORMACION QUE RESPOSA EN CONTABILIDAD..." class="form-control" autocomplete="off"></textarea>
						</div>
					</div>
					<br>
	
					<div class="row">
						<div class="col-md-12 text-center">
							<button type="button" class="btn btn-success" id="send_form">
								<i class="fa fa-send"></i>
								&nbsp; Enviar
							</button>
						</div>
					</div>
				</form>
			  </div>
		</div>
	  </div>
	</div>
	{{-- fin modal para el cuerpo de la carta de paz y salvo --}}
	
	
	
	<table class="table">
	<thead>
		<tr>
			<th>Cédula</th>
			<th>Nombre</th>
			<th>Carta</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($propietarios as $propietario)
			<tr>
				<td>{{ $propietario->numero_cedula }}</td>
				<td>{{ $propietario->nombre_completo }}</td>
				<td>
					<a data-toggle="tooltip" data-placement="top" title="Generar certificado" onclick="modalCrearPazSalvo({{$propietario->id}});" href="#" class="btn btn-default"><i class="fa fa-file-pdf-o"></i></a>
				</td>
			</tr>
		@endforeach
	</tbody>
	</table>
</div>


@endsection
@section('ajax_crud')
	<script>
		var _token = $('meta[name="csrf-token"]').attr('content');

		$('.table').DataTable({
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

		function modalCrearPazSalvo(propietario){
			//cargar las cuentas en deuda
			$('#id').val(propietario);
			$.ajax({
				type: "POST",
				url: "{{ url('cuerpoPazSalvo') }}/"+propietario,
				data: {
					_token
				},
				dataType: "json",
				success: function (response) {
					$('#cuerpo').val(response.cuerpo);

				}
			});
			$('#modalCertificado').modal('show');
		}


	</script>
@endsection