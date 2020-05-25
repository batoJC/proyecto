@extends('../layouts.app_dashboard_admin')

@section('title', 'Tipo de Ejecución Presupuestal')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Tipo de Ejecución Presupuestal</li>
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
						<a target="_blanck" href="https://youtu.be/aLpN1eEMVnM">¿Qué puedo hacer?s</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	{{-- Variables de estado --}}
	{{-- ******************* --}}
	@if(session('status'))
		<div class="alert alert-success-original alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">x</span>
			</button>
			{!! html_entity_decode(session('status')) !!}	
		</div>
	@endif
	{{-- ******************* --}}
	<a class="btn btn-success" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Agregar Tipo de Ejecución Presupuestal
	</a>
	<br><br>
	@include('admin.tipo_ejecucion.form')
	<table id="tipos-table" class="table">
		<thead>
			<th>Tipo</th>
			<th>Descripción</th>
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
                url: "{{ url('api.tipo_presupuesto.admin') }}",
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
		var table  = $('#tipos-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'tipo', name: 'tipo'},
          		{ data: 'descripcion', name: 'descripcion'},
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


		// Editar Registro
		// ***************
		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Editar Tipo de Ejecución Presupuestal');

			$.ajax({
				url: "{{ url('tipo_ejecucion_pre') }}" + "/" + id,
				type: 'GET',
				dataType: 'JSON',
				success: function(data){
					$('#modal-form').modal('show');
					// Datos
					$('#id').val(data.id);
					$('#tipo').val(data.tipo);
					$('#descripcion').val(data.descripcion);
				},
				error: function(){
					swal("¡Opps! Ocurrió un error", {
                          icon: "error",
                    });
				}
			});
		}

		// Eliminar registro
		// *****************

		function deleteData(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar este registro?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
          		$.ajax({
					url : "{{ url('tipo_ejecucion_pre') }}" + "/" + id,
					type: "POST",
					data: {
						'_method': 'DELETE',
						'_token' : csrf_token,
					},
					success: function(){
						swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
							.then((value) => {
								table.ajax.reload();
						});
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

		// Agregar Registro
		// ****************

		function addForm(){
			save_method = "add";
			$('input[name="_method"]').val('POST');
			$('#modal-form form')[0].reset();
			$('#modal-form').modal('show');
			$('.modal-title').text('Agregar Tipo de Ejecución Presupuestal');
		}

		// Evento submit
		// -------------

		$('#modal-form form').on('submit', function(e){
			e.preventDefault();
			id = $('#id').val();
			if(save_method == "add") url =  "{{ url('tipo_ejecucion_pre') }}";
			else url = "{{ url('tipo_ejecucion_pre') }}" + "/" + id;

			$.ajax({
				url: url,
				type: 'POST',
				data: $('#modal-form form').serialize(),
				success: function(data){
					$('#modal-form').modal('hide');
        			swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
						.then((value) => {
							table.ajax.reload();
							$('#modal-form').modal('hide');
					});
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
				}
			});
		});
	</script>
@endsection