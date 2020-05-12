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
						<div class="col-12 col-md-4 error-validate-3">
							<i class="fa fa-sort-alpha-asc"></i>
							<label class="margin-top">
								Prefijo
							</label>
						</div>
						<div class="col-12 col-md-8">
							<input class="form-control field-5" type="input" id="prefijo" name="prefijo"  value="">
						</div>
					</div>
					<br>
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-12 col-md-4 error-validate-3">
							<i class="fa fa-sort-numeric-asc"></i>
							<label class="margin-top">
								Inicio
							</label>
						</div>
						<div class="col-12 col-md-8">
							<input type="number" class="form-control field-5" name="numero" placeholder="Ejemplo: 12345" autocomplete="off" id="id_consecutivo">
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