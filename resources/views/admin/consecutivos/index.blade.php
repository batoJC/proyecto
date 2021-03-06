@extends('../layouts.app_dashboard_admin')

@section('title', 'Consecutivos')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Consecutivos</li>
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
						<a target="_blanck" href="https://youtu.be/MkNq31M0yRQ">¿Qué son?</a>
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
		Agregar Consecutivo
	</a>
	<br><br>
	@include('admin.consecutivos.form')
	<table id="consecutivos-table" class="table">
		<thead>
			<tr>
				<th>Prefijo</th>
				<th>Valor Actual</th>
				<th>Acciones</th>
			</tr>
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
                url: "{{ url('api.consecutivos.admin') }}",
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
		var table  = $('#consecutivos-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'prefijo', name: 'prefijo'},
          		{ data: 'numero', name: 'numero'},
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


		// Select 2 
		// ********
		$('.select-2').select2({
			// Este es el id de la ventana modal #modal-form
			dropdownParent: $('#modal-form')
		});

		// Agregar registro
		// ****************
		
		function addForm(){
			save_method = "add";
			$('input[name="_method"]').val('POST');
			$('#modal-form').modal('show');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Agregar Consecutivo');
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
					url : "{{ url('consecutivos') }}" + "/" + id,
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

		// Abrir la modal cuando pase algún evento submit
        // ----------------------------------------------
        $('#modal-form').on('submit', function(e){
        	e.preventDefault();
        	id = $('#id').val();
        	if(save_method == "add") url = "{{ url('consecutivos') }}";
        	else url = "{{ url('consecutivos') }}" + "/" + id;

        	$.ajax({
        		url : url,
        		type: "POST",
        		data: $('#modal-form form').serialize(),
        		success: function(data){
        			$('#modal-form').modal('hide');
        			swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
						.then((value) => {
							$('#modal-form').modal('hide');
							table.ajax.reload();
					});
        		},
        		error: function(){
        			swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
        		}
        	});
        });
	</script>
@endsection
