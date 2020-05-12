{{-- Modal para agregar empleados  --}}
<div id="empleados" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
	
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" >Agregar empleado</h4>
		</div>
		<div class="modal-body">
			<form id="dataEmpleado">
				@csrf

				<label class="validate-label-1" for="unidad_id">Unidad</label>
				<select class="form-control validate-input-1" name="unidad_id" id="unidad_id">
					<option value="">Seleccione la unidad</option>
					@foreach ($unidades as $unidad)
						<option value="{{ $unidad->id }}">{{ $unidad->tipo->nombre }} {{ $unidad->numero_letra }}</option>				
					@endforeach
				</select>
				<br>
	
				<label class="validate-label-2" for="nombre">Nombre</label>
				<input class="form-control validate-input-2" type="text" id="nombre" name="nombre">
				<br>
	
				<label class="validate-label-3" for="apellido">Apellido</label>
				<input class="form-control validate-input-3" type="text" id="apellido" name="apellido">
				<br>
	
				<label for="genero">Género</label>
				<select class="form-control" name="genero" id="genero">
					<option value="masculino">Masculino</option>
					<option value="femenino">Femenino</option>
					<option value="me abstengo">Me abstengo</option>
				</select>
				<br>
	
				<label for="tipo_documento_id">Tipo de documento</label>
				<select class="form-control" name="tipo_documento_id" id="tipo_documento_id">
					@foreach ($tipos_documentos as $tipo)
						<option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
					@endforeach
				</select>
				<br>
	
				<label class="validate-label-4" for="documento">Documento</label>
				<input type="text" class="form-control validate-input-4" name="documento" id="documento">
				<br>
	
			</form>
	
		</div>
		<div class="modal-footer">
			<button onclick="guardarEmpleado();" type="button" class="btn btn-primary">Guardar</button>
		</div>
	
		</div>
	</div>
</div>