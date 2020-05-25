@extends('../layouts.app_dashboard_admin')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Zonas Sociales</li>
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
						<a target="_blanck" href="https://youtu.be/Z3drhJqc63A">¿Qué puedo hacer?</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<a class="btn btn-success" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Agregar Zona social
	</a>
	@include('admin.zonas.form')
	<br><br>
	<table id="zonas-table" class="table table-striped">
		<thead>
			<th>Nombre</th>
			<th>Valor (De su uso)</th>
			<th>Cancelar reserva antes</th>
			<th>Acciones</th>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>


	
@endsection
@section('ajax_crud')
	<script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
	<script>
		$(document).ready(function () {
			$('#valor_uso_aux').maskMoney({precision:0});
		});

		 var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.zonas_comunes.admin') }}",
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
		var table  = $('#zonas-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'nombre', name: 'nombre'},
          		{ data: 'valor_uso', name: 'valor_uso'},
          		{ data: 'antes', name: 'antes'},
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

		
		// Mostrar registro
		// ****************

		function showForm(id){
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Zona social');
			$('#send_form').hide();

			$.ajax({
				url: "{{ url('zonas_comunes') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Datos
					$('#nombre').val(data.nombre);
					$('#valor_uso_aux').val(data.valor_uso);
					$('#numero').val(data.numero);
					$('#tipo').val(data.tipo);
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Esta zona social no existe", "error");
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
			$('.modal-title').text('Agregar Zona social');
			$('#send_form').attr('type', 'button');
		}

		// Editar registro
		// ***************

		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Editar Zona social');
			$('#send_form').show();
			$('#send_form').attr('type', 'button');
			
			$.ajax({
				url: "{{ url('zonas_comunes') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Datos
					$('#id').val(data.id);
					$('#nombre').val(data.nombre);
					$('#valor_uso_aux').val(data.valor_uso);
					$('#numero').val(data.numero);
					$('#tipo').val(data.tipo);
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
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar esta Zona social?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
          		$.ajax({
					url: "{{ url('zonas_comunes') }}" + "/" + id,
					type: "POST",
					data: {
						'_method': 'DELETE',
						'_token': csrf_token,
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

		// Abrir la modal cuando pase algún evento submit
        // ----------------------------------------------

        $('#modal-form').on('submit', function(e){
			e.preventDefault();
			if(verificarFormulario('dataZona',2)){
				id = $('#id').val();
				if(save_method == "add") url = "{{ url('zonas_comunes') }}";
				else url = "{{ url('zonas_comunes') }}" + "/" + id;
	
				$.ajax({
					url: url,
					type: "POST",
					data: $('#modal-form form').serialize(),
					success: function(data){
						if(data.res){
							$('#modal-form').modal('hide');
							swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
								.then((value) => {
									$('#modal-form').modal('hide');
									table.ajax.reload();
							});
						}else{
							swal('Error!',data.msg,'error');
						}
					},
					error: function(){
						swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
					}
				});	
			}
        });
	</script>
@endsection