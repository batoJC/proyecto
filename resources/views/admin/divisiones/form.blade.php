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
							<i class="fa fa-object-ungroup"></i>
							<label class="margin-top">
								Tipo de Division
							</label>
						</div>
						<div class="col-md-8">
							<select onchange="checkTipo(this);" name="id_tipo_division" id="id_tipo_division" class="form-control field-5">
								<option value="">Seleccione un tipo</option>
								@foreach ($tipos_divisiones as $tipo)
									<option value="{{ $tipo->id }}">{{ $tipo->division }}</option>
								@endforeach
								<option value="otro">Otro</option>
							</select>
						</div>
					</div>
					<br>
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-1">
							<i class="fa fa-font"></i>
							<label class="margin-top">
								Numero / Letra
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-1" name="numero_letra" placeholder="Ejemplo: A, B, C. 100, 200" autocomplete="off" id="numero_letra">
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