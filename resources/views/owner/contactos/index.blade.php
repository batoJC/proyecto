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
				  	<li>Contacto</li>
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
							<a target="_blanck" href="https://youtu.be/exTM2iBlDHQ">¿Qué puedo hacer?</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="col-md-12">

				<div class="alert alert-success-original alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
					<h1 class="text-center">Responder contactos</h1>
					<h4>
						-Para responder a algún contacto que llegue a este apartado se debe hacer desde el correo electrónico gestioncopropietario@gmail.com
					</h4>
				</div>


				@include('owner.contactos.form')
				<table id="contactos-table" class="table table-striped">
					<thead>
						<th>Nombre</th>
						<th>Correo</th>
						<th>Mensaje</th>
						<th>Fecha de envio</th>
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
				url: "{{ url('api.contactos') }}",
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
		var table  = $('#contactos-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'nombre', name: 'nombre'},
          		{ data: 'email', name: 'email'},
          		{ data: 'mensaje', name: 'mensaje'},
          		{ data: 'created_at', name: 'created_at'},
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
			$('.modal-title').text('Contacto');

			$.ajax({
				url: "{{ url('contactos') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Datos
					$('#id').val(data.id);
					$('#correo').val(data.correo);
					$('#mensaje').val(data.mensaje);
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Este contacto no existe", "error");
				}
			});
		}

		//eliminar un registro de contacto
		/*********************************/
		function functionDelete(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
              title: "¿Estás seguro?",
              text: "De querer eliminar este registro de contacto.",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                    url: "{{ url('contacto') }}" + "/" + id,
                    type: "POST",
                    data: {
                          '_token' : csrf_token,
                    },
                    success: function(data){
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


	</script>
@endsection