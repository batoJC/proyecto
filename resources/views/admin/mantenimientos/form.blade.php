{{-- Modal para agregar recordatorios de mantenimientos  --}}
<div id="mantenimientos" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
	
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" >Agregar mantenimiento para conjunto</h4>
		</div>
		<div class="modal-body">
			<form id="dataMantenimiento">
				@csrf

				
	
				<label class="validate-label-1" for="fecha">Fecha de mantenimiento</label>
				<input class="form-control validate-input-1" type="date" id="fecha" name="fecha">
                <br>
                
                <label class="validate-label-1" for="descripcion">Descripción</label>
				<textarea class="form-control validate-input-2" type="text" id="descripcion" name="descripcion"></textarea>
				<br>
	
                <div class="modal-footer">
                    <button onclick="guardar();" type="button" class="btn btn-primary">Guardar</button>
                </div>
	
			</form>
	
		</div>
	
		</div>
	</div>
</div>