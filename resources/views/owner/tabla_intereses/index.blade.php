@extends('../layouts.app_dashboard')

@section('title', 'Tabla de Intereses')

@section('content')
	<div class="right_col" role="main">
        <div class="row">
			<div class="col-11 col-md-11">
				<ul class="breadcrumb">
					<li>
						<a href="{{ asset('owner') }}">Inicio</a>
					</li>
				  	<li>Tabla de intereses</li>
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
							<a target="_blanck" href="https://youtu.be/vexC7exekQI">¿Qué puedo hacer?</a>
						</li>
					</ul>
				</div>
			</div>
            <div class="col-md-12">
            	{{-- Variable de session para actualización --}}
				{{-- ************************************** --}}
				@if(session('status'))
					<div class="alert alert-error alert-dismissible" role="alert">
						<button type="button" class="close btn_close_alert" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">x</span>
						</button>
						{!! html_entity_decode(session('status')) !!}
					</div>
				@endif
				<br>
				{{-- ************************************** --}}
            	
				<a class="btn btn-success" onclick="addForm()">
					<i class="fa fa-plus"></i>
					Agregar Nuevo Registro
				</a>
				@include('owner.tabla_intereses.form')
				<br><br>
				<table id="intereses-table" class="table">
					<thead>
						<th>Periodo</th>
						<th>Numero de Resolución</th>
						<th>Fecha de inicio (Vigencia)</th>
						<th>Fecha de fin (Vigencia)</th>
						<th>Tasa Efectiva Anual</th>
						<th>Tasa Efectiva Anual Mora</th>
						<th>Tasa Mora Nominal Anual</th>
						<th>Tasa Mora Nominal Mensual</th>
						<th>Tasa Diaria</th>
						<th>Acciones</th>
					</thead>
					<tbody>
						{{-- @foreach($tabla_intereses as $interes)
							<tr>
								<td>{{ $interes->periodo }}</td>
								<td>{{ $interes->numero_resolucion }}</td>
								<td>{{ $interes->fecha_vigencia_inicio }}</td>
								<td>{{ $interes->fecha_vigencia_fin }}</td>
								<td>{{ $interes->tasa_efectiva_anual }}</td>
								<td>{{ $interes->tasa_efectiva_anual_mora }}</td>
								<td>{{ $interes->tasa_mora_nominal_anual }}</td>
								<td>{{ $interes->tasa_mora_nominal_mensual }}</td>
								<td>{{ number_format($interes->tasa_diaria, 9) }}</td>
								<td>
									
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
				url: "{{ url('api.tabla_interes_owner') }}",
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
		var table  = $('#intereses-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ orderable: false,  data: 'periodo', name: 'periodo'},
          		{ orderable: false,  data: 'numero_resolucion', name: 'numero_resolucion'},
          		{ data: 'fecha_vigencia_inicio', name: 'fecha_vigencia_inicio'},
          		{ orderable: false,  data: 'fecha_vigencia_fin', name: 'fecha_vigencia_fin'},
          		{ orderable: false,  data: 'tasa_efectiva_anual', name: 'tasa_efectiva_anual'},
          		{ orderable: false,  data: 'tasa_efectiva_anual_mora', name: 'tasa_efectiva_anual_mora'},
          		{ orderable: false,  data: 'tasa_mora_nominal_anual', name: 'tasa_mora_nominal_anual'},
          		{ orderable: false,  data: 'tasa_mora_nominal_mensual', name: 'tasa_mora_nominal_mensual'},
          		{ orderable: false,  data: 'tasa_diaria', name: 'tasa_diaria'},
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
		}).order([ 2, "asc" ]);


		// Agregar Registro
		// ****************
		function addForm(){
			save_method = "add";
			$('input[name="_method"]').val('POST');
			$('#modal-form').modal('show');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Agregar Registro');
		}

		// Editar Registro
		// ***************
		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Editar Registro');

			$.ajax({
				url: "{{ url('tabla_intereses') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Data
					$('#id').val(data.id);
					$('#periodo').val(data.periodo);
					$('#numero_resolucion').val(data.numero_resolucion);
					$('#tasa_efectiva_anual').val(data.tasa_efectiva_anual);
					$('#fecha_vigencia_inicio').val(data.fecha_vigencia_inicio);
					$('#fecha_vigencia_fin').val(data.fecha_vigencia_fin);
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
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar este registro?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
          		$.ajax({
          			url : "{{ url('tabla_intereses') }}" + "/" + id,
          			type: "POST",
          			data: {
          				'_method': 'DELETE',
          				'_token' : csrf_token,
          			},
          			success: function(data){
          				table.ajax.reload();
          				swal("¡El registro ha sido eliminado!", {
                          icon: "success",
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
        	if(save_method == "add") url = "{{ url('tabla_intereses') }}";
        	else url = "{{ url('tabla_intereses') }}" + "/" + id;

        	$.ajax({
        		url: url,
        		type: 'POST',
        		data: $('#modal-form form').serialize(),
        		success: function(data){
        			// Respuesta del controlador si hay una coma
        			// *****************************************
        			if(data == 'Error'){
	        			swal("Ocurrió un error", "Por favor evita usar ' , ' te invitamos a usar ' . ' para los decimales", "error");
        			} else if(data == 'Error_porcentaje'){
        				swal("Ocurrió un error", "Por favor evita usar ' % '", "error");
        			} else {
	        			$('#modal-form').modal('hide');
	        			swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
							.then((value) => {
							table.ajax.reload();
						});
        			}
        			// *****************************************
        		},
        		error: function(){
        			swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
        		}
        	});
        });
	</script>
@endsection
