@extends('../layouts.app_dashboard')

@section('title', 'Tipos de Conjunto')

@section('content')
	<div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12">
				<div class="col-11 col-md-11">
					<ul class="breadcrumb">
						<li>
							<a href="{{ asset('owner') }}">Inicio</a>
						</li>
						  <li>Tipos de Conjuntos</li>
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
								<a target="_blanck" href="https://youtu.be/HpHk4JwBmXU">¿Qué puedo hacer?</a>
							</li>
						</ul>
					</div>
				</div>
            				
				<a class="btn btn-success" onclick="addForm()">
					<i class="fa fa-plus"></i>
					Agregar Tipo de Conjunto
				</a>
				@include('owner.tipos_conjunto.form')
				<br><br>
				<table id="tipo_conjunto-table" class="table table-striped">
					<thead>
						<th>Tipo</th>
						<th>Acciones</th>
					</thead>
					<tbody>
						{{-- @foreach ($tipos as $tipo)
							<tr>
								<td>{{ $tipo->tipo }}</td>
								<td>
										<a onclick="editForm('{{$tipo->id}}')" class="btn btn-default"><i class="fa fa-pencil"></i></a>
										<a onclick="deleteData('{{$tipo->id}}')" class="btn btn-default"><i class="fa fa-trash"></i></a>
								</td>
							</tr>
						@endforeach --}}
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
				url: "{{ url('api.tipo_conjunto') }}",
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

		var table  = $('#tipo_conjunto-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax :  actualizarTabla,
          	columns: [
          		{ data: 'tipo', name: 'tipo'},
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
                "emptyTable": "Brak danych",
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


		// Agregar Registro
		// ****************
		function addForm(){
			save_method = "add";
			$('input[name="_method"]').val('POST');
			$('#modal-form').modal('show');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Agregar Tipo de Conjunto');
			$('#send_form').attr('type', 'button');
		}

		// Editar Registro
		// ***************
		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Editar Tipo de Conjunto');
			$('#send_form').attr('type', 'button');

			$.ajax({
				url: "{{ url('tipo_conjunto') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Data
					$('#id').val(data.id);
					$('#tipo').val(data.tipo);
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
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar este Tipo de Conjunto?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
          		$.ajax({
          			url : "{{ url('tipo_conjunto') }}" + "/" + id,
          			type: "POST",
          			data: {
          				'_method': 'DELETE',
          				'_token' : csrf_token,
          			},
          			success: function(data){
          				swal("¡El Tipo de Conjunto ha sido eliminado!", {
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
        	if(save_method == "add") url = "{{ url('tipo_conjunto') }}";
        	else url = "{{ url('tipo_conjunto') }}" + "/" + id;

        	$.ajax({
        		url: url,
        		type: 'POST',
        		data: $('#modal-form form').serialize(),
        		success: function(data){
        			$('#modal-form').modal('hide');
					swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success").then(()=>{
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