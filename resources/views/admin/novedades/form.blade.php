{{-- Modal para agregar recordatorios de mantenimientos  --}}
<div id="novedades" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
	
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" >Novedad del conjunto</h4>
		</div>
		<div class="modal-body">
			<form id="dataNovedad">
				@csrf

				
	
				<label class="validate-label-1" for="fecha">Fecha de novedad</label>
				<input class="form-control validate-input-1" type="date" id="fecha" name="fecha">
                <br>
                
                <label class="validate-label-1" for="contenido">Contenido</label>
				<textarea class="form-control validate-input-2" type="text" id="contenido" name="contenido"></textarea>
				<br>
	
                <div class="col-12 text-center" id="btnGuardar">
                    <button onclick="guardar();" type="button" class="btn btn-primary">Guardar</button>
                </div>
	
			</form>
	
		</div>
	
		</div>
	</div>
</div>