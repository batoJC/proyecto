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
						<div class="col-md-4 error-validate-5">
							<i class="fa fa-address-book"></i>
							<label class="margin-top">
								Tipo de solicitud
							</label>
						</div>
						<div class="col-md-8">
							<select name="tipo" id="tipo" class="form-control field-5">
								<option value="default">Seleccione...</option>
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
						<div class="col-md-4">
							<i class="fa fa-university"></i>
							<label class="margin-top">
								Petición o pretensión
							</label>
						</div>
						<div class="col-md-8">
							<textarea name="peticion" id="peticion" cols="30" rows="5" placeholder="" class="form-control field-2" autocomplete="off"></textarea>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-2">
							<i class="fa fa-lock"></i>
							<label class="margin-top">
								Hechos
							</label>
						</div>
						<div class="col-md-8">
							<textarea name="hechos" id="hechos" cols="30" rows="5" placeholder="" class="form-control field-2" autocomplete="off"></textarea>
						</div>
					</div>
					<br>
					<div class="respuesta" style="display:none;" >
						<h3 class="text-center">Respuesta</h3>
						<br>
						{{-- Cada campo --}}
						<div class="row">
							<div class="col-md-4" id="div-col-respuesta">
								<i class="fa fa-calendar"></i>
								<label class="margin-top">
									Fecha
								</label>
							</div>
							<div class="col-md-8">
								<input class="form-control" type="date" name="fecha_respuesta" id="fecha_respuesta">
							</div>
						</div>
						<br>
						{{-- Cada campo --}}
						<div class="row">
							<div class="col-md-4">
								<i class="fa fa-user"></i>
								<label class="margin-top">
									Proveedor
								</label>
							</div>
							<div class="col-md-8">
								<input class="form-control" type="text" name="proveedor" id="proveedor">
							</div>
						</div>
						<br>
						{{-- Cada campo --}}
						<div class="row">
							<div class="col-md-4">
								<i class="fa fa-font"></i>
								<label class="margin-top">
									Respuesta
								</label>
							</div>
							<div class="col-md-8">
								<textarea class="form-control" name="respuesta" id="respuesta" rows="5" placeholder=""></textarea>
							</div>
						</div>
					</div>

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