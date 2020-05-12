@extends('../layouts.app_dashboard')

@section('title', 'Ingresos')

@section('content')
	<div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12">
            	<ul class="breadcrumb">
					<li>
						<a href="{{ asset('owner') }}">Inicio</a>
					</li>
				  	<li>Ingresos (En la oficina)</li>
				</ul>
				{{-- Variable de session para actualización --}}
			    {{-- ************************************** --}}
			    @if(session('status'))
			        <div class="alert alert-success-original alert-dismissible" role="alert">
			            <button type="button" class="close btn_close_alert" data-dismiss="alert" aria-label="Close">
			                <span aria-hidden="true">x</span>
			            </button>
			            {!! html_entity_decode(session('status')) !!}
			        </div>
			    @endif			
				<a class="btn btn-success" onclick="addForm()">
					<i class="fa fa-plus"></i>
					Agregar Ingresos
				</a>
				<a class="btn btn-warning" href="{{ url('download_ingresos') }}" target="_blank">
			        <i class="fa fa-file-archive-o"></i>
			        &nbsp; Archivo Base de Ingresos
			    </a>
			    <a class="btn btn-info" onclick="cargaForm()">
			        <i class="fa fa-money"></i>
			        &nbsp; Agregar Ingresos (Carga masiva)
			    </a>
				@include('owner.ingresos.form')
				@include('owner.ingresos.formConjunto')
				<br><br>
				<table class="table datatable">
					<thead>
						<th>Valor</th>
						<th>Descripción</th>
						<th>Persona (Que Pago)</th>
						<th>Persona (Que Recibió el dinero)</th>
						<th>Conjunto</th>
						<th>Tipo de Unidad</th>
						<th>Acciones</th>
						<th>Exportar a PDF</th>
					</thead>
					<tbody>
						@foreach($ingresos as $ingres)
							<tr>
								<td>{{ $ingres->valor }}</td>
								<td>
									@if($ingres->descripcion != null)
										{{ str_limit($ingres->descripcion, 20) }}
									@else
										No aplica
									@endif
								</td>
								<td>{{ $ingres->persona_pago }}</td>
								<td>{{ $ingres->persona_recibe }}</td>
								<td>{{ $ingres->conjunto->nombre }}</td>
								<td>{{ $ingres->tipo_unidad->tipo_unidad.' - '.$ingres->tipo_unidad->numero_letra }}</td>
								<td>
									<a onclick="editForm('{{ $ingres->id }}')" class="btn btn-default">
										<i class="fa fa-pencil"></i>
									</a>
									<a onclick="deleteData('{{ $ingres->id }}')" class="btn btn-default">
										<i class="fa fa-trash"></i>
									</a>
								</td>
								<td>
									<form action="{{ url('ingres_pdf/'.$ingres->id) }}" method="POST">
										@csrf
										<button type="submit" class="btn btn-default">
											Exportar Recibo &nbsp;
										<i class="fa fa-file-pdf-o"></i>
										</button>
									</form>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
            </div>
        </div>
    </div>
@endsection
@section('ajax_crud')
	<script>
		// Código hermoso, select dependiente precioso ♥
        // *********************************************
        $('#id_conjunto').on('change', function(e){
            var id_conjunto = e.target.value;
            console.log(id_conjunto);

            $.get('tipo_unidad_get?id_conjunto=' + id_conjunto,function(data) {

              $('#id_apto').empty();
              $('#id_apto').append('<option value="default">Seleccione...</option>');

              $.each(data, function(fetch, tipos_unidad){
                $('#id_apto').append('<option value="'+ tipos_unidad.id +'">'+ tipos_unidad.tipo_unidad + ' - ' +tipos_unidad.numero_letra + '</option>');
              })
            });
        });
    
        // Select 2 
        // ****************
        $('.select-22').select2({
            // Este es el id de la ventana modal #modal-form
            dropdownParent: $('#modal-form')
        });

        // Select 2 
        // ****************
        $('.select-2').select2({
            // Este es el id de la ventana modal #modal-form
            dropdownParent: $('#modal-form-conjunto')
        });

        // Form para seleccionar el conjunto
        // *********************************
        function cargaForm(){
        	$('input[name="_method"]').val('POST');
			$('#modal-form-conjunto').modal('show');
			$('#modal-form-conjunto form')[0].reset();
			$('.modal-title').text('Seleccione Conjunto...');
        }

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
				url: "{{ url('ingresos_oficina') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Data
					$('#id').val(data.id);
					$('#valor').val(data.valor);
					$('#descripcion').val(data.descripcion);
					$('#persona_pago').val(data.persona_pago);
					$('#persona_recibe').val(data.persona_recibe);
					$('#id_conjunto').val(data.id_conjunto);
					$('#id_apto').val(data.id_apto);
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
          			url : "{{ url('ingresos_oficina') }}" + "/" + id,
          			type: "POST",
          			data: {
          				'_method': 'DELETE',
          				'_token' : csrf_token,
          			},
          			success: function(data){
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
        $('#modal-form form').on('submit', function(e){
        	e.preventDefault();
        	id = $('#id').val();
        	if(save_method == "add") url = "{{ url('ingresos_oficina') }}";
        	else url = "{{ url('ingresos_oficina') }}" + "/" + id;

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
