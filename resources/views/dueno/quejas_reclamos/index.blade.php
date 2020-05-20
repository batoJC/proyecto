@extends('../layouts.app_dashboard_dueno')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('dueno') }}">Inicio</a>
				</li>
				  <li>Quejas y Reclamos</li>
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
						<a target="_blanck" href="https://youtu.be/2C2-BT_auyQ">¿Qué puedo hacer?</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<a class="btn btn-success" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Agregar PQR
	</a>
	<br><br>
	@include('dueno.quejas_reclamos.form')
	@include('dueno.quejas_reclamos.respuesta')
	<table id="quejas_reclamos-table" class="table table-striped">
		<thead>
			<th>Id</th>
			<th>Tipo</th>
			<th>Petición o pretensión</th>
			<th>Hechos</th>
			<th>Fecha de creación</th>
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
                url: "{{ url('api.quejas.dueno') }}",
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
		var table  = $('#quejas_reclamos-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'id', name: 'id'},
          		{ data: 'tipo', name: 'tipo'},
          		{ data: 'peticion', name: 'peticion'},
          		{ data: 'hechos', name: 'hechos'},
          		{ data: 'fecha_solicitud', name: 'fecha_solicitud'},
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


		//mostrar el nombre del archivo
		function fileNameChange(){
			document.querySelector('#fileName').innerText = 'Nombre del archivo: '+document.querySelector('#archivo').files[0].name;
		}
	
		// Mostrar respuesta
		// *****************
		function answerData(id){
			$('.modal-title').text('Respuesta');

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
		}

		// Mostrar registro
		// ***************

		function showForm(id){
			$('#modal-form form')[0].reset();
			$('.modal-title').text('PQR');
			$('#send_form').hide();
			$('#divArchivo').hide();

			$.ajax({
				url: "{{ url('quejas_reclamos') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Datos
					$('#tipo').val(data.tipo);
					$('#peticion').val(data.peticion);
					$('#hechos').val(data.hechos);
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Esta Queja/Reclamo no existe", "error");
				}
			});
		}


		// Agregar registro
		// ****************

		function addForm(){
			save_method = "add";
			$('input[name="_method"]').val('POST');
			$('#modal-form').modal('show');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Agregar PQR');
			$('#send_form').show();
			$('#send_form').attr('type', 'button');
			$('#divArchivo').show();
			$('#alerta').hide();
		}

		// Editar registro
		// ***************

		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Editar PQR');
			$('#modal-form').modal('show');
			$('#send_form').show();
			$('#send_form').attr('type', 'button');
			$('#divArchivo').show();
			$('#alerta').show();

			$.ajax({
				url: "{{ url('quejas_reclamos') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Datos
					$('#id').val(data.id);
					$('#tipo').val(data.tipo);
					$('#peticion').val(data.peticion);
					$('#hechos').val(data.hechos);
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Esta Esta Queja/Reclamo no existe", "error");
				}
			});
		}

		// Eliminar registro
		// *****************

		function deleteData(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar esta noticia?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
	          	$.ajax({
					url: "{{ url('quejas_reclamos') }}" + "/" + id,
					type: "POST",
					dataType: 'json',
					data:{
						'_method': 'DELETE',
						'_token' : csrf_token,
					},
					success: function(data){
						if(data.res == 1){
							swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
								.then((value) => {
								table.ajax.reload();
							});
						}else{
							swal("Error!", data.msg, "error");
						}
					},
					error: function(){
						swal("¡Opps! Ocurrió un error", {
		                      icon: "error",
		                });
					}
				});
              } else {
                swal("Operación cancelada", "Lo sentimos, vuelve a intentarlo", "error");
              }
            });
		}

		// Submit del formulario validación
		// ********************************

		$('#modal-form').on('submit', function(e){
			e.preventDefault();
			document.querySelector('#send_form').disabled = true;
			id = $('#id').val();
			if(save_method == "add") url = "{{ url('quejas_reclamos') }}";
			else url = "{{ url('quejas_reclamos') }}" + "/" + id;

			$.ajax({
				url: url,
				type: 'POST',
				data : new FormData($('#modal-form form')[0]),
				contentType: false,
				processData: false,
				success: function(data){
					$('#modal-form').modal('hide');
					swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
						.then((value) => {
							table.ajax.reload();
							$('#modal-form').modal('hide');
					});
					document.querySelector('#send_form').disabled = false;
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
					document.querySelector('#send_form').disabled = false;
				}
			});
		});
	</script>
@endsection