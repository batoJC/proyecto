{{-- ***************************************** --}}
<!-- Modal -->
<div class="modal fade" id="modal-errores" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">x</span>
		        </button>
		        <h4 class="text-center modal-title">
		        	<i class="fa fa-user"></i>
		        	&nbsp;
		        </h4>
      		</div>
      		<div class="modal-body">
        		<form method="post">
        			@csrf {{ method_field('GET') }}
        			<input type="hidden" name="id" id="id">
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-5">
							<i class="fa fa-building-o"></i>
							<label class="margin-top">
								Nombre
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-5" name="nombre" id="nombre">
						</div>
					</div>
					<br>
					<div class="">
						<label class="check">
							Coeficiente
							<input name="coeficiente" value="1" type="checkbox" class="js-switch" data-switchery="true">
						</label>
					</div>
					<br>
					<div class="">
						<label class="check">
							Propietario con usuario
							<input name="propietario" value="1" type="checkbox" class="js-switch" data-switchery="true">
						</label>
					</div>
					<br>
					<div class="">
						<label class="check">
							Lista residentes
							<input name="lista_residentes" value="1" type="checkbox" class="js-switch" data-switchery="true">
						</label>
					</div>
					<br>
					<div class="">
						<label class="check">
							Lista mascotas
							<input name="lista_mascotas" value="1" type="checkbox" class="js-switch" data-switchery="true">
						</label>
					</div>
					<br>
					<div class="">
						<label class="check">
							Lista vehículos
							<input name="lista_vehiculos" value="1" type="checkbox" class="js-switch" data-switchery="true">
						</label>
					</div>
					<br>
					<div class="">
						<label class="check">
							Lista empleados
							<input name="lista_empleados" value="1" type="checkbox" class="js-switch" data-switchery="true">
						</label>
					</div>
					<br>
					<div class="">
						<label class="check">
							Lista visitantes frecuentes
							<input name="lista_visitantes" value="1" type="checkbox" class="js-switch" data-switchery="true">
						</label>
					</div>
					<br>
        			<div class="">
						<label class="check">
							Observaciones
							<input name="observaciones" value="1" type="checkbox" class="js-switch" data-switchery="true">
						</label>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12 text-center">
							<button type="button" class="btn btn-success" id="send_form">
		        				<i class="fa fa-send"></i>
		        				&nbsp; Enviar
		        			</button>
						</div>
					</div>
        		</form>
      		</div>
    	</div>
  	</div>
</div>