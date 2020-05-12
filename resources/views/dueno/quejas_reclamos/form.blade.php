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
        		<form method="post">
        			@csrf {{ method_field('POST') }}
        			<input type="hidden" name="id" id="id">
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-3">
							<i class="fa fa-address-book"></i>
							<label class="margin-top">
								Tipo de solicitud
							</label>
						</div>
						<div class="col-md-8">
							<select name="tipo" id="tipo" class="form-control field-3">
								<option value="">Seleccione...</option>
								<option value="Petición">Petición</option>
								<option value="Sugerencia">Sugerencia</option>
								<option value="Reclamo">Reclamo</option>
								<option value="Queja">Queja</option>
							</select>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-1">
							<i class="fa fa-font"></i>
							<label class="margin-top">
								Petición o pretensión
							</label>
						</div>
						<div class="col-md-8">
							<textarea name="peticion" id="peticion" cols="30" rows="6" placeholder="Redacte brevemente lo que pide o pretende que se solucione" class="form-control field-1" autocomplete="off"></textarea>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-2">
							<i class="fa fa-text-width"></i>
							<label class="margin-top">
								Hechos
							</label>
						</div>
						<div class="col-md-8">
							<textarea name="hechos" id="hechos" cols="30" rows="6" placeholder="Redacte brevemente los hechos sucedidos y que tienen relación con su petición o pretensión señalando las circunstancias de tiempo (fecha), modo (qué ocurrió) y lugar (donde)" class="form-control field-2" autocomplete="off"></textarea>
						</div>
					</div>
					<br>

					<div class="row" id="divArchivo">
						<div id="alerta" class="alert alert-danger-original alert-dismissible" role="alert">
							<button type="button" class="close btn_close_alert" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">x</span>
							</button>
							<h5>
								Si selecciona un archivo, se reemplazara el enviado en el primer envio.
							</h5>
						</div>
						<label class="btn btn-default" for="archivo"><i class="fa fa-file"></i> Archivo</label>
						<input onchange="fileNameChange();" id="archivo" type="file" style="display:none;" name="archivo">
						<label id="fileName">Nombre del archivo: </label>
					</div>

					<div class="row">
						<div class="col-md-12 text-center">
							<button type="button" class="btn btn-success"  id="send_form">
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