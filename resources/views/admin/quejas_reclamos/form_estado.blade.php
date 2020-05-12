{{-- ***************************************** --}}
<!-- Modal -->
<div class="modal fade" id="modal-form_estado" tabindex="-1" role="dialog" data-backdrop="static">
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
        		<form method="post" id="form_respuesta">
        			@csrf {{ method_field('POST') }}
        			<input type="hidden" name="id" id="id">
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-address-book"></i>
							<label class="margin-top">
								Tipo de solicitud
							</label>
						</div>
						<div class="col-md-8">
							<select name="tipo" id="tipo" class="form-control">
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
							<i class="fa fa-cog"></i>
							<label class="margin-top">
								Estado
							</label>
						</div>
						<div class="col-md-8">
							<select name="estado" id="estado" class="form-control">
								<option value="Pendiente">Pendiente</option>
								<option value="Resuelto">Resuelto</option>
								<option value="En proceso">En proceso</option>
								<option value="Cerrado">Cerrado</option>
							</select>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-font"></i>
							<label class="margin-top">
								Días de respuesta
							</label>
						</div>
						<div class="col-md-8">
							<input class="form-control" type="number" name="dias_restantes" id="dias_restantes">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row" id="div-row-respuesta">
						<div class="col-md-4" id="div-col-respuesta">
							<i class="fa fa-font"></i>
							<label class="margin-top">
								Respuesta
							</label>
						</div>
						<div class="col-md-8">
							<textarea class="form-control" name="respuesta" id="respuesta" rows="5" placeholder="Por favor digite la respuesta..."></textarea>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row" id="div-row-provee">
						<div class="col-md-4">
							<i class="fa fa-handshake-o"></i>
							<label class="margin-top">
								Proveedor
							</label>
						</div>
						<div class="col-md-8">
							<select name="id_proveedor" id="id_proveedor" class="select-2 form-control">
								<option value="">Seleccione...</option>
								@foreach($proveedores as $provee)
									<option value="{{ $provee->id }}">{{ $provee->nombre_completo }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12 text-center">
							<button type="button" onclick="return guardar();" class="btn btn-success" id="send_form_estado">
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