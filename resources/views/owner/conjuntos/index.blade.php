@extends('../layouts.app_dashboard')

@section('title', 'Usuarios')

@section('content')
	<div class="right_col" role="main">
        <div class="row">
			<div class="col-11 col-md-11">
				<ul class="breadcrumb">
					<li>
						<a href="{{ asset('owner') }}">Inicio</a>
					</li>
				  	<li>Conjuntos</li>
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
            <div class="col-md-12">
				<a class="btn btn-success" onclick="addForm()">
					<i class="fa fa-plus"></i>
					Agregar Conjunto
				</a>
				@include('owner.conjuntos.form')
				<br><br>
				<table id="conjunto-table" class="table table-striped">
					<thead>
						<th>Nit</th>
						<th>Nombre</th>
						<th>Correo</th>
						<th>Ciudad</th>
						<th>Dirección</th>
						<th>Barrio</th>
						<th>Tel o Cel</th>
						<th>Tipo de Propiedad</th>
						<th>Acciones</th>
					</thead>
					<tbody>
					</tbody>
				</table>
            </div>
        </div>
    </div>
@endsection
@section('ajax_crud')
	<script>
		var actualizarTabla = (data,callback,settings) => {
			$.ajax({
				type: "GET",
				url: "{{ url('api.conjuntos') }}",
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

		//listar todos los registros
		var table  = $('#conjunto-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'nit', name: 'nit'},
          		{ data: 'nombre', name: 'nombre'},
          		{ data: 'correo', name: 'correo'},
          		{ data: 'ciudad', name: 'ciudad'},
          		{ data: 'direccion', name: 'direccion'},
          		{ data: 'barrio', name: 'barrio'},
          		{ data: 'tel_cel', name: 'tel_cel'},
          		{ data: 'tipo_conjunto', name: 'tipo_conjunto'},
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

		// Mostrar Registro
		// ----------------
		function showForm(id){
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Conjunto');
			$('#send_form').hide();

			$.ajax({
				url : "{{ url('conjuntos') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Datos...
					$('#nit').val(data.nit);
					$('#nombre').val(data.nombre);
					$('#correo').val(data.correo);
					$('#direccion').val(data.direccion);
					$('#barrio').val(data.barrio);
					$('#tel_cel').val(data.tel_cel);
					$('#ciudad').val(data.ciudad);
					$('#fecha_inicio_interes').val(data.fecha_inicio_interes);
					$('#tipo_propiedad').val(data.tipo_propiedad);
				}, 
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Este usuario no existe", "error");
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
			$('.modal-title').text('Agregar Conjunto');
			$('#send_form').attr('type', 'button');
		}

		// Editar Registro
		// ***************
		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Editar Conjunto');
			$('#send_form').show();
			$('#send_form').attr('type', 'button');

			$.ajax({
				url : "{{ url('conjuntos') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Data...
					$('#id').val(data.id);
					$('#nit').val(data.nit);
					$('#nombre').val(data.nombre);
					$('#correo').val(data.correo);
					$('#direccion').val(data.direccion);
					$('#barrio').val(data.barrio);
					$('#tel_cel').val(data.tel_cel);
					$('#ciudad').val(data.ciudad);
					$('#id_tipo_propiedad').val(data.id_tipo_propiedad).focus();
					$('#id').focus();
				}, 
				error: function(){
					swal("¡Opps! Ocurrió un error", {
                          icon: "error",
                    });
				}
			});
		}

		// Eliminar Registro
		// ***************

		function deleteData(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar este conjunto?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
          		$.ajax({
          			url : "{{ url('conjuntos') }}" + "/" + id,
          			type: "POST",
          			data: {
          				'_method': 'DELETE',
          				'_token' : csrf_token,
          			},
          			success: function(data){
          				swal("¡El conjunto ha sido eliminado!", {
                          icon: "success",
                        }).then(()=>{
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
		$('#modal-form form').on('submit', function(e){
			e.preventDefault();
			id = $('#id').val();
			if(save_method == "add") url = "{{ url('conjuntos') }}";
			else url = "{{ url('conjuntos') }}" + "/" + id;

			$.ajax({
				url: url,
				type: 'POST',
				data: $('#modal-form form').serialize(),
				success: function(data){
					$('#modal-form').modal('hide');
					swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
						.then((value) => {
						table.ajax.reload();
					});
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
				}
			})
		});
	</script>
@endsection
