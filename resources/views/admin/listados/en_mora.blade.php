@extends('../layouts.app_dashboard_admin')

@section('title', 'Personas en mora')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Listado de personas en mora</li>
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
	<a target="_blanck" href="{{ url('en_moraDownload') }}" class="btn btn-primary"><i class="fa fa-download"></i> Descargar Listado</a>
	
	{{-- inicio modal para generar certificado --}}
	<div class="modal fade moda" id="modalCertificado" tabindex="-1" role="dialog" data-backdrop="static">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content modal-padding">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
					<h4 class="text-center modal-title">
						Generar certificado de mora
					</h4>
				  </div>
				  <div class="modal-body">
				  <form target="_blanck" method="post" action="{{ url('certificadoMora') }}" enctype="multipart/form-data">
						@csrf {{ method_field('POST') }}
						<input type="hidden" name="id" id="id">
						{{-- Encabezado --}}
						<div class="row">
							<div class="col-md-4">
								<i class="fa fa-font"></i>
								<label class="margin-top">
									Encabezado
								</label>
							</div>
							<div class="col-md-8">
								<textarea name="encabezado" id="encabezado" cols="30" rows="5" placeholder="Ejemplo: EL SUSCRITO ADMINISTRADOR y REPRESENTANTE LEGAL DE LA ..." class="form-control" autocomplete="off"></textarea>
							</div>
						</div>
						<br>
	
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
	
						{{-- Tabla de conceptos --}}
						<div id="tabla_conceptos" class="row"></div>
	
	
						{{-- Total --}}
						<div class="row">
							<div class="col-md-4">
								<i class="fa fa-font"></i>
								<label class="margin-top">
									Total en letras
								</label>
							</div>
							<div class="col-md-8">
								<input type="text" name="total" id="total" class="form-control" placeholder="Ejemplo: QUINIENTOS TREINTA Y SIETE MIL SEISCIENTOS PESOS MAS INTERESES DE MORA...." autocomplete="off">
							</div>
						</div>
						<br>
	
						{{-- Deudores --}}
						<div class="row">
							<div class="col-md-4">
								<i class="fa fa-users"></i>
								<label class="margin-top">
									Deudores directos
								</label>
							</div>
							<div class="col-md-8">
								<textarea name="deudores" id="deudores" cols="30" rows="5" placeholder="Ejemplo:Alberto jaramillo.." class="form-control" autocomplete="off"></textarea>
							</div>
						</div>
						<br>
	
						{{-- Solidarias --}}
						<div class="row">
							<div class="col-md-4">
								<i class="fa fa-users"></i>
								<label class="margin-top">
									Personas solidarias
								</label>
							</div>
							<div class="col-md-8">
								<textarea name="personas_solidarias" id="personas_solidarias" cols="30" rows="5" placeholder="Ejemplo:Albeiro Hurtado.." class="form-control" autocomplete="off"></textarea>
							</div>
						</div>
						<br>
	
						{{-- Solidarias --}}
						<div class="row">
							<div class="col-md-4">
								<i class="fa fa-font"></i>
								<label class="margin-top">
									Pie de página
								</label>
							</div>
							<div class="col-md-8">
								<textarea name="pie_pagina" id="pie_pagina" cols="30" rows="5" class="form-control" placeholder="fin del documento" autocomplete="off"></textarea>
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
	{{-- fin modal para generar certificado --}}
	
	
	
	<br><br>
	<table class="table">
		<thead>
			<tr>
				<th>Propietario</th>
				<th>Capital</th>
				<th>Interes</th>
				<th>Certificado</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($moras as $mora)
				<tr>
					<td>{{ $mora['propietario']->nombre_completo }}</td>
					<td>$ {{ number_format($mora['capital']) }}</td>
					<td>$ {{ number_format($mora['interes']) }}</td>
					<td>
						<a onclick="modalCrearCertificado({{$mora['propietario']->id}});" href="#" class="btn btn-default"><i class="fa fa-file-pdf-o"></i></a>
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

		function modalCrearCertificado(propietario){
			//cargar las cuentas en deuda
			$('#id').val(propietario);
			$.ajax({
				type: "POST",
				url: "{{ url('cuotasMora') }}/"+propietario,
				data: {
					_token
				},
				dataType: "json",
				success: function (response) {
					let table = `<table class="table">
									<thead>
										<th>Concepto</th>
										<th>Valor</th>
										<th>Fecha vencimiento</th>
									</thead>
									<tbody>`;
					response.cuentas.forEach(c => {
						table += `<tr>
									<td>${c.concepto}</td>
									<td>${c.valor}</td>
									<td>${c.fecha_vencimiento}</td>
								</tr>`;
					});
					table += `<tr>
									<td colspan="2">Total:</td>
									<td>${response.total}</td>
								</tr></tbody>
							</table>`;
					$('#tabla_conceptos').html(table);

					$('#deudores').val(response.propietario);

				}
			});
			$('#modalCertificado').modal('show');
		}


	</script>
@endsection