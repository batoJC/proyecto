@extends('../layouts.app_dashboard_admin')

@section('title', 'Gestion de Recaudo')

@section('content')
	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('admin') }}">Inicio</a>
		</li>
	  	<li>
	  		<a href="{{ asset('admin') }}">Gestión de Recaudo</a>
	  	</li>
	  	<li>Inserción Masiva</li>
	</ul>
	<form method="post" id="special_form_masiva">
		@csrf {{ method_field('POST') }}
		<table class="table datatableasd">
			<thead>
				<th>Valor</th>
				<th>Fecha de Ingreso</th>
				<th>Referencia</th>
				<th>Identificado</th>
				<th>Primero</th>
				<th>Segundo</th>
				<th>Tercero</th>
				<th>Cuarto</th>
				<th>Quinto</th>
			</thead>
			<tbody>
				@foreach($registros as $key => $reg)
					{{-- <tr>
						<td>
							<input type="hidden" value="{{ $reg->id }}" name="id[]">
							<input type="text" value="{{ $reg->valor }}" name="valor[]">
						</td>
						<td>
							<input type="text" value="{{ $reg->fecha_ingreso }}" name="fecha_ingreso[]">
						</td>
						<td>
							<input type="text" value="{{ $reg->referencia }}" name="referencia[]">
						</td>
						@if($reg->id_tipo_unidad != null)
							<td class="td-identificado">
								<input type="hidden" value="{{ $reg->id_tipo_unidad }}" name="id_tipo_unidad[]">
								Identificado
							</td>
						@else
							<td class="td-no-identificado">
								No identificado
							</td>
						@endif
						<td>
							<select class="form-control field-gestion-primero" name="primero[]">
								<option value="Multas">Multas</option>
								<option value="Alquileres">Alquileres</option>
								<option value="Cuotas Extraordinarias">Cuotas Extraordinarias</option>
								<option value="Cuotas Ordinarias">Cuotas Ordinarias</option>
								<option value="Otros Cobros">Otros Cobros</option>
							</select>
						</td>
						<td>
							<select class="form-control field-gestion-segundo" name="segundo[]">
								<option value="Intereses">Intereses</option>
								<option value="Multas">Multas</option>
								<option value="Alquileres">Alquileres</option>
								<option value="Cuotas Extraordinarias">Cuotas Extraordinarias</option>
								<option value="Cuotas Ordinarias">Cuotas Ordinarias</option>
								<option value="Otros Cobros">Otros Cobros</option>
								<option value="No aplica">No aplica</option>
							</select>
						</td>
						<td>
							<select class="form-control field-gestion-tercero" name="tercero[]">
								<option value="Cuotas Extraordinarias">Cuotas Extraordinarias</option>
								<option value="Multas">Multas</option>
								<option value="Alquileres">Alquileres</option>
								<option value="Cuotas Ordinarias">Cuotas Ordinarias</option>
								<option value="Otros Cobros">Otros Cobros</option>
								<option value="No aplica">No aplica</option>
							</select>
						</td>
						<td>
							<select class="form-control field-gestion-cuarto" name="cuarto[]">
								<option value="Alquileres">Alquileres</option>
								<option value="Multas">Multas</option>
								<option value="Cuotas Extraordinarias">Cuotas Extraordinarias</option>
								<option value="Cuotas Ordinarias">Cuotas Ordinarias</option>
								<option value="Otros Cobros">Otros Cobros</option>
								<option value="No aplica">No aplica</option>
							</select>
						</td>
						<td>
							<select class="form-control field-gestion-quinto" name="quinto[]">
								<option value="Cuotas Ordinarias">Cuotas Ordinarias</option>
								<option value="Multas">Multas</option>
								<option value="Alquileres">Alquileres</option>
								<option value="Cuotas Extraordinarias">Cuotas Extraordinarias</option>
								<option value="Otros Cobros">Otros Cobros</option>
								<option value="No aplica">No aplica</option>
							</select>
						</td>
					</tr> --}}
					{{-- Ensayo --}}
					<tr>
						<td>
							<input type="hidden" value="{{ $reg->id }}" name="id[]">
							<input type="text" value="{{ $reg->valor }}" name="valor[]">
						</td>
						<td>
							<input type="text" value="{{ $reg->fecha_ingreso }}" name="fecha_ingreso[]">
						</td>
						<td>
							<input type="text" value="{{ $reg->referencia }}" name="referencia[]">
						</td>
						@if($reg->id_tipo_unidad != null)
							<td class="td-identificado">
								<input type="hidden" value="{{ $reg->id_tipo_unidad }}" name="id_tipo_unidad[]">
								Identificado
							</td>
						@else
							<td class="td-no-identificado">
								No identificado
							</td>
						@endif
						<td>
							<select class="form-control field-gestion-primero" name="primero[]">
								<option value="Multas">Multas</option>
								<option value="Alquileres">Alquileres</option>
								<option value="Cuotas Extraordinarias">Cuotas Extraordinarias</option>
								<option value="Cuotas Ordinarias">Cuotas Ordinarias</option>
								<option value="Otros Cobros">Otros Cobros</option>
							</select>
						</td>
						<td>
							<select class="form-control field-gestion-segundo" name="segundo[]">
								<option value="Intereses">Intereses</option>
								<option value="Multas">Multas</option>
								<option value="Alquileres">Alquileres</option>
								<option value="Cuotas Extraordinarias">Cuotas Extraordinarias</option>
								<option value="Cuotas Ordinarias">Cuotas Ordinarias</option>
								<option value="Otros Cobros">Otros Cobros</option>
								<option value="No aplica">No aplica</option>
							</select>
						</td>
						<td>
							<select class="form-control field-gestion-tercero" name="tercero[]">
								<option value="Cuotas Extraordinarias">Cuotas Extraordinarias</option>
								<option value="Multas">Multas</option>
								<option value="Alquileres">Alquileres</option>
								<option value="Cuotas Ordinarias">Cuotas Ordinarias</option>
								<option value="Otros Cobros">Otros Cobros</option>
								<option value="No aplica">No aplica</option>
							</select>
						</td>
						<td>
							<select class="form-control field-gestion-cuarto" name="cuarto[]">
								<option value="Alquileres">Alquileres</option>
								<option value="Multas">Multas</option>
								<option value="Cuotas Extraordinarias">Cuotas Extraordinarias</option>
								<option value="Cuotas Ordinarias">Cuotas Ordinarias</option>
								<option value="Otros Cobros">Otros Cobros</option>
								<option value="No aplica">No aplica</option>
							</select>
						</td>
						<td>
							<select class="form-control field-gestion-quinto" name="quinto[]">
								<option value="Cuotas Ordinarias">Cuotas Ordinarias</option>
								<option value="Multas">Multas</option>
								<option value="Alquileres">Alquileres</option>
								<option value="Cuotas Extraordinarias">Cuotas Extraordinarias</option>
								<option value="Otros Cobros">Otros Cobros</option>
								<option value="No aplica">No aplica</option>
							</select>
						</td>
					</tr>
				@endforeach
			</tbody>
		</table>
		<div class="row">
			<div class="col-md-12 text-center">
				<button type="submit" class="btn btn-success">
    				<i class="fa fa-send"></i>
    				&nbsp; Ejecutar Carga Masiva
    			</button>
			</div>
		</div>
	</form>
@endsection
@section('ajax_crud')
	<script>
		// Evento Submit del formulario
		// ****************************

		$('#special_form_masiva').on('submit', function(e){
			e.preventDefault();

			url = "{{ url('gestion_masivos_insert') }}";

			$.ajax({
				url: url,
				type: 'POST',
				data: $('#special_form_masiva').serialize(),
				success: function(data){
					console.log(data);
					// $('#modal-form').modal('hide');
					// swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
					// 	.then((value) => {
					// 	location.reload();
					// });
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
				}
			});
		});
	</script>
@endsection