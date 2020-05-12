{{-- Modal para agregar mascotas  --}}
<div id="visitantes" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
	
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" >Agregar visitantes</h4>
		</div>
		<div class="modal-body">
			<form id="dataVisitante">
				@csrf

				<label class="validate-label-1" for="unidad_id">Unidad</label>
				<select class="form-control validate-input-1" name="unidad_id" id="unidad_id">
					<option value="">Seleccione la unidad</option>
					@foreach ($unidades as $unidad)
						<option value="{{ $unidad->id }}">{{ $unidad->tipo->nombre }} {{ $unidad->numero_letra }}</option>				
					@endforeach
				</select>
				<br>
	
				<label class="validate-label-2" for="identificacion">identificación</label>
				<input class="form-control validate-input-2" type="text" id="identificacion" name="identificacion">
				<br>
	
				<label class="validate-label-3" for="nombre">Nombre Completo</label>
				<input class="form-control validate-input-3" type="text" id="nombre" name="nombre">
				<br>
	
				<label class="validate-label-4" for="parentesco">Parentesco / Otro</label>
				<input type="text" class="form-control validate-input-4" name="parentesco" id="parentesco">
				<br>
	
			</form>
	
		</div>
		<div class="modal-footer">
			<button onclick="guardarVisitante();" type="button" class="btn btn-primary">Guardar</button>
		</div>
	
		</div>
	</div>
</div>