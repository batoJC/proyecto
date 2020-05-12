{{-- ***************************************** --}}
<!-- Modal -->
<div class="modal fade" id="modal-form-respuesta" tabindex="-1" role="dialog" data-backdrop="static">
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
					<div class="row" id="div-row-respuesta">
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
					<div class="row" id="div-row-respuesta">
						<div class="col-md-4" id="div-col-respuesta">
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
					<div class="row" id="div-row-respuesta">
						<div class="col-md-4" id="div-col-respuesta">
							<i class="fa fa-font"></i>
							<label class="margin-top">
								Respuesta
							</label>
						</div>
						<div class="col-md-8">
							<textarea class="form-control" name="respuesta" id="respuesta" rows="5" placeholder=""></textarea>
						</div>
					</div>
        		</form>
      		</div>
    	</div>
  	</div>
</div>