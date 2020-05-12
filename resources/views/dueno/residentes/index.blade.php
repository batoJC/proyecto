@extends('../layouts.app_dashboard_dueno')

@section('content')
	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('dueno') }}">Inicio</a>
		</li>
	  	<li>Residentes</li>
	</ul>
	<a class="btn btn-success" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Agregar Residente
	</a>
	@include('dueno.residentes.form')
	<br><br>
	<table class="table table-stripped datatable">
		<thead>
			<th>Tipo Residente</th>
			<th>Nombre</th>
			<th>Apellido</th>
			<th>Genero</th>
			<th>Tipo de Documento</th>
			<th>Documento</th>
			<th>Fecha de Ingreso</th>
			<th>Fecha de Salida</th>
			<th>Estado</th>
			<th>Tipo de Unidad</th>
			<th>Acciones</th>
		</thead>
		<tbody>
			@foreach($residentes as $resid)
				<tr>
					<td>{{ $resid->tipo_residente }}</td>
					<td>{{ $resid->nombre }}</td>
					<td>{{ $resid->apellido }}</td>
					<td>{{ $resid->genero }}</td>
					<td>{{ $resid->tipo_documento }}</td>
					<td>{{ $resid->documento }}</td>
					<td>{{ $resid->fecha_ingreso }}</td>
					<td>
						@if($resid->fecha_salida != null)
							{{ $resid->fecha_salida }}
						@else 
							No Aplica
						@endif
					</td>
					<td>{{ $resid->estado }}</td>
					<td>{{ $resid->tipo_unidad->tipo_unidad.' - '.$resid->tipo_unidad->numero_letra }}</td>
					<td>
						<a onclick="editForm('{{ $resid->id }}')" class="btn btn-default">
							<i class="fa fa-pencil"></i>
						</a>
						<a onclick="deleteData('{{ $resid->id }}')" class="btn btn-default">
							<i class="fa fa-trash"></i>
						</a>
					</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection
@section('ajax_crud')
	<script>
		// Agregar registro 
        // ---------------
        function addForm(){
            save_method = "add";
            $('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Agregar Residente');
            $('#send_form').attr('type', 'button');
        }

        // Editar registro
		// ****************

		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Editar Residente');

			$.ajax({
				url: "{{ url('residentes') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Data
					$('#id').val(data.id);
					$('#id_tipo_unidad').val(data.id_tipo_unidad);
					$('#tipo_residente').val(data.tipo_residente);
					$('#nombre').val(data.nombre);
					$('#apellido').val(data.apellido);
					$('#genero').val(data.genero);
					$('#tipo_documento').val(data.tipo_documento);
					$('#documento').val(data.documento);
					$('#fecha_ingreso').val(data.fecha_ingreso);
					$('#fecha_salida').val(data.fecha_salida);
					$('#estado').val(data.estado);
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
					url : "{{ url('residentes') }}" + "/" + id,
					type: "POST",
					data: {
						'_method': 'DELETE',
						'_token' : csrf_token,
					},
					success: function(){
						swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
							.then((value) => {
							location.reload();
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

        // Abrir la modal cuando pase algún evento
        // ---------------------------------------
        $('#modal-form form').on('submit', function(e){
        	e.preventDefault();
        	id = $('#id').val();
        	if(save_method == "add") url = "{{ url('residentes') }}";
        	else url = "{{ url('residentes') }}" + "/" + id;

        	$.ajax({
        		url: url,
        		type: 'POST',
        		data: $('#modal-form form').serialize(),
        		success: function(data){
        			$('#modal-form').modal('hide');
        			swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
                        .then((value) => {
                        	location.reload();
                    });
        		},
        		error: function(){
	        		swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
        		}
        	});
        });
    </script>
@endsection