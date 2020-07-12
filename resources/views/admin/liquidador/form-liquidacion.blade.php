{{-- Modal para agregar recordatorios de mantenimientos  --}}
<div id="modal-devengo" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
	
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" >Agregar nuevo devengo</h4>
		</div>
		<div class="modal-body">
            <form id="data_devengo">
				<label class="validate-label-1" for="descripcion_devengo">Descripción</label>
				<input class="form-control validate-input-1" id="descripcion_devengo" type="text">
				<br>
				<label class="validate-label-2" for="valor_devengo_aux">Valor</label>
				<input type="text" class="form-control validate-input-2" onchange="changeValor(this,'valor_devengo');" name="valor_devengo_aux" placeholder="Ejemplo: 200,000" autocomplete="off" id="valor_devengo_aux">
				<input type="hidden" class="form-control" name="valor_devengo" id="valor_devengo">
				<br>
				<input type="checkbox" id="retencion">
				<label for="retencion">Genera retención</label>
				<div class="col-md-12 text-center">
					<button type="button" onclick="devengos.agregar();" class="btn btn-success">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
		</div>
	</div>
</div>
{{-- Modal para agregar recordatorios de mantenimientos  --}}
<div id="modal-deduccion" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" >Agregar nueva deducción</h4>
		</div>
		<div class="modal-body">
            <form id="data_deduccion">
				<label class="validate-label-1" for="descripcion_deduccion">Descripción</label>
				<input class="form-control validate-input-1" id="descripcion_deduccion" type="text">
				<br>
				<label class="validate-label-2" for="valor_deduccion">Valor</label>
				<input placeholder="puede colocar un valor fijo o un porcentaje agregando % al final" class="form-control validate-input-2" id="valor_deduccion" type="text">
				<br>
				<div class="col-md-12 text-center">
					<button type="button" onclick="deducciones.agregar();" class="btn btn-success">Guardar <i class="fa fa-save"></i></button>
				</div>
			</form>
		</div>
		</div>
	</div>
</div>