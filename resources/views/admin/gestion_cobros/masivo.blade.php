{{-- ***************************************** --}}
<!-- Modal -->
<div class="modal fade" id="modal-form-masivo" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">x</span>
		        </button>
		        <h4 class="text-center modal-title">
		        	<i class="fa fa-shopping-bag"></i>
		        	&nbsp;
		        </h4>
      		</div>
      		<div class="modal-body">
        		<form method="post" enctype="multipart/form-data">
        			@csrf {{ method_field('POST') }}
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-file-pdf-o"></i>
							<label class="margin-top">
								Archivo
							</label>
						</div>
						<div class="col-md-8">
							<button type="button" class="btn btn-logotype-brand">
								<i class="fa fa-plus icon-plus-brand-logo"></i>
							</button>
							<input type="file" name="archivo" id="file-input" class="upload hidden">
							<p id="name-file"></p>
							<button type="button" class="btn btn-default btn-speacial">
								Quitar el Archivo&nbsp;
								<i class="fa fa-trash"></i>
							</button>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-university"></i>
							<label class="margin-top">
								Banco 
							</label>
						</div>
						<div class="col-md-8">
							<select class="form-control" name="banco" id="banco">
								<option value="">Seleccione</option>
								<option value="Colpatria">Colpatria</option>
								<option value="Bancolombia">Bancolombia</option>
								<option value="Davivienda">Davivienda</option>
								<option value="Sudameris">Sudameris</option>
								<option value="Otro">Otro</option>
							</select>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12 text-center">
							<button type="submit" class="btn btn-success" id="send_form_masivo">
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