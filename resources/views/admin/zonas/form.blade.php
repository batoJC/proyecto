{{-- ***************************************** --}}
<!-- Modal -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" data-backdrop="static">
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
        		<form method="post" id="dataZona">
        			@csrf {{ method_field('POST') }}
        			<input type="hidden" name="id" id="id">
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 validate-label-1">
							<i class="fa fa-bank"></i>
							<label class="margin-top">
								Nombre
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control validate-input-1" name="nombre" placeholder="Ejemplo: Piscina, Parque" autocomplete="off" id="nombre">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-usd"></i>
							<label class="margin-top">
								Costo
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control" name="valor_uso_aux" onchange="changeValor(this,'valor_uso');" placeholder="Ejemplo: 200000" autocomplete="off" id="valor_uso_aux">
							<input type="hidden" class="form-control" name="valor_uso" placeholder="Ejemplo: 200000" autocomplete="off" id="valor_uso">
						</div>
					</div>
					<br>
					{{-- Se puede cancelar--}}
					<div class="row">
						<div class="col-md-3">
							<i class="fa fa-ban"></i>
							<label class="margin-top validate-label-2">
								Para cancelar
							</label>
						</div>
						<div class="col-md-8">
							<div class="col-6 col-md-6">
								<input class="form-control validate-input-2" type="number" name="numero" id="numero">
							</div>
							<div class="col-6 col-md-6">
								<select class="form-control" name="tipo" id="tipo">
									<option value="hora">Hora(s)</option>
									<option value="dia">Día(s)</option>
									<option value="mes">Mes(es)</option>
								</select>
							</div>
						</div>
						<div class="col-1 col-md-1">
							<label class="margin-top">antes</label>
						</div>
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