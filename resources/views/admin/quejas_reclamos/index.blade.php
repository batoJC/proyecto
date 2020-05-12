@extends('../layouts.app_dashboard_admin')

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-11 col-md-11">
				<ul class="breadcrumb">
					<li>
						<a href="{{ asset('admin') }}">Inicio</a>
					</li>
					  <li>Quejas / Reclamos</li>
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
		@include('admin.quejas_reclamos.form')
		@include('admin.quejas_reclamos.form_estado')
		<table id="quejas-table" class="table table-striped">
			<thead>
				<th>Id</th>
				<th>Tipo</th>
				<th>Petición</th>
				<th>Hechos</th>
				<th>Autor</th>
				<th>Fecha de creación</th>
				<th class="text-center">Días de respuesta</th>
				<th class="text-center">Fecha Respuesta</th>
				<th class="text-center">Estado</th>
				<th>Acciones</th>
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
				url: "{{ url('api.reclamos') }}",
				data: data,
				dataType: "json",
				success: function (response) {
					callback(
						response
					);
					$('[data-toggle="tooltip"]').tooltip({
						container: 'body'
					});
					let filas = $('tbody [role="row"]');
					for (let i = 0; i < filas.length; i++) {
						let data = JSON.parse(filas[i].cells[6].innerText);
						filas[i].cells[6].className =  (data.class);
						filas[i].cells[6].innerText = data.dias;
						data = JSON.parse(filas[i].cells[8].innerText);
						filas[i].cells[8].className = (data.class);
						filas[i].cells[8].innerText = data.estado;
					}
				}
			});
		}



		 // Listar los registros
		// *************************************
		var table  = $('#quejas-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'id', name: 'id'},
          		{ data: 'tipo', name: 'tipo'},
          		{ data: 'peticion', name: 'peticion'},
          		{ data: 'hechos', name: 'hechos'},
          		{ data: 'nombre_completo', name: 'nombre_completo'},
          		{ data: 'fecha_solicitud', name: 'fecha_solicitud'},
          		{ data: 'dias_restantes', name: 'dias_restantes'},
          		{ data: 'fecha_respuesta', name: 'fecha_respuesta'},
          		{ data: 'estado', name: 'estado'},
          		{ data: 'action', name: 'action', orderable: false, searchable: false},
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

		
		$('.select-2').select2({
			// Este es el id de la ventana modal #modal-form
			dropdownParent: $('#modal-form_estado')
		});

		// Visaje muy op para la respuesta
		// *******************************

		$('#div-row-respuesta').hide();
		$('#div-row-provee').hide();

		$('#estado').change(function(event){
			$('#div-row-respuesta').fadeIn('200');
			$('#div-row-provee').fadeIn('200');
			$('#respuesta').addClass('field-estado-2');
			$('#div-col-respuesta').addClass('error-validate-estado-2');
		});

		// Mostrar el registro
		// *******************

		function showForm(id){
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Queja / Reclamo');
			$('#send_form').hide();
			$('.respuesta').fadeOut(200);

			$.ajax({
				url: "{{ url('quejas_reclamos') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// datos
					console.table(data);
					$('#tipo').val(data.tipo);
					$('#peticion').val(data.peticion);
					$('#hechos').val(data.hechos);
					if(data.fecha_respuesta != null){
						$('.respuesta').fadeIn(200);
						if(data.idproveedor != null){
							$.ajax({
								url: "{{ url('respuesta') }}" + "/" + id,
								type: "GET",
								dataType: "JSON",
								success: function(data){
									$('#fecha_respuesta').val(data.fecha_respuesta);
									$('#proveedor').val(data.proveedor);
									$('#respuesta').val(data.respuesta);
									
									$('#modal-form-respuesta').modal('show');
									// Data
								},
								error: function(){
									swal("Ocurrió un error", "Lo sentimos, Esta Queja/Reclamo no existe", "error");
								}
							});
						}else{
							$('#fecha_respuesta').val(data.fecha_respuesta);
							$('#proveedor').val('No aplica');
							$('#respuesta').val(data.respuesta);
						}
					}
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Esta Queja/Reclamo no existe", "error");
				}
			});	
		}

		// Editar registro
		// ***************

		function editForm(id){
			save_method = "edit";
			$('.modal-title').text('Cambiar estado');
			$('#div-row-respuesta').hide();
			$('#div-row-provee').hide();

			$.ajax({
				url: "{{ url('quejas_reclamos') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form_estado').modal('show');
					// datos
					$('#id').val(data.id);
					$('#tipo').val(data.tipo);
					$('#estado').val(data.estado);
					$('#dias_restantes').val(data.dias_restantes);
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Esta Queja/Reclamo no existe", "error");
				}
			});
		}

		// Submit event del form
		// *********************
		function guardar(){
			// e.preventDefault();
			id = $('#id').val();
			if(save_method == "edit") url = "{{ url('quejas_estado') }}" + "/" + id;

			$.ajax({
				url: url,
				type: 'POST',
				data: $('#form_respuesta').serialize(),
				success: function(data){
					$('#modal-form_estado').modal('hide');
					swal("Operación Exitosa", "El estado ha sido cambiado", "success")
						.then((value) => {
							table.ajax.reload();
					});
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
				}
			});
			return false;
		}

		// $('#form_respuesta').on('submit', function(e){
		// 	console.log('oe llave');
		// });
	</script>
@endsection