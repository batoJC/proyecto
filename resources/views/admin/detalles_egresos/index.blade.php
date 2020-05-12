@extends('../layouts.app_dashboard_admin')

@section('title', 'Detalles de Egresos')

@section('content')
	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('admin') }}">Inicio</a>
		</li>
		<li>
			<a href="{{ asset('egresos') }}">Egresos</a>
		</li>
	  	<li>Detalles de Egresos</li>
	</ul>
	<a class="btn btn-default" href="{{ asset('egresos') }}">
		<i class="fa fa-arrow-left"></i>
	</a>
	<a class="btn btn-success" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Agregar Detalle de Egreso
	</a>
	<a target="_blank" href="{{ url('exportar_egresos/'.$egresos->id) }}" class="btn btn-default">
		<i class="fa fa-file-pdf-o"></i>
		&nbsp; Exportar Factura
	</a>
	{{-- <a class="btn btn-default">
		<i class="fa fa-file-pdf-o"></i>
		&nbsp; Exportar Factura
	</a> --}}
	<br><br>
	@include('admin.detalles_egresos.form')
	<table class="table datatable">
		<thead>
			<tr>
				<th>Sub Valor Antes del Iva</th>
				<th>Iva</th>
				<th>Descripcion</th>
				<th>Conceptos Retención</th>
				<th>Valor Retencion</th>
				<th>Egresos (Encabezado)</th>
				<th>Ejecución Presupuestal (Individual)</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			@foreach($egresos_detalles as $detalles)
				<tr>
					<td>{{ $detalles->sub_valor_antes_iva }}</td>
					<td>{{ $detalles->iva }}</td>
					<td>{{ $detalles->descripcion }}</td>
					<td>{{ $detalles->conceptos_retencion->descripcion.' - '.$detalles->conceptos_retencion->porcentaje.' %' }}</td>
					<td>{{ $detalles->valor_retencion }}</td>
					<td>{{ $detalles->egresos->concepto }}</td>
					<td>{{ $detalles->presup_individual->Tipo_ejecucion_pre->tipo }}</td>
					<td>
						<a onclick="editForm('{{ $detalles->id }}')" class="btn btn-default">
							<i class="fa fa-pencil"></i>
						</a>
						<a onclick="deleteData('{{ $detalles->id }}')" class="btn btn-default">
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
			$('.modal-title').text('Agregar Egreso');
		}

		// Editar registro
		// ****************

		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Agregar Cuota Administrativa ');

			$.ajax({
				url: "{{ url('detalles_egresos') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Data
					$('#id').val(data.id);
					$('#id_egresos').val(data.id_egresos);
					$('#id_presup_individual').val(data.id_presup_individual);
					$('#sub_valor_antes_iva').val(data.sub_valor_antes_iva);
					$('#iva').val(data.iva);
					$('#id_conceptos_retencion').val(data.id_conceptos_retencion);
					$('#descripcion').val(data.descripcion);
					$('.select-2').select2({
						// Este es el id de la ventana modal #modal-form
						dropdownParent: $('#modal-form')
					});
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
					url : "{{ url('detalles_egresos') }}" + "/" + id,
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

		// Abrir la modal cuando pase algún evento submit
        // ----------------------------------------------
        $('#modal-form').on('submit', function(e){
        	e.preventDefault();
        	id = $('#id').val();
        	if(save_method == "add") url = "{{ url('detalles_egresos') }}";
        	else url = "{{ url('detalles_egresos') }}" + "/" + id;

        	$.ajax({
        		url : url,
        		type: "POST",
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
