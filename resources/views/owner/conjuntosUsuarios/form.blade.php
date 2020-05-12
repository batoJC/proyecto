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
						<div class="col-md-4 error-validate-2">
							<i class="fa fa-user"></i>
							<label class="margin-top">
								Administrador
							</label>
						</div>
						<div class="col-md-8">
							<select name="id_user" id="id_user" class="form-control field-2 select-2">
								<option value="">Seleccione...</option>
								@foreach($users as $user)
									<option value="{{ $user->id }}">{{ $user->nombre_completo}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-3">
							<i class="fa fa-university"></i>
							<label class="margin-top">
								Conjunto
							</label>
						</div>
						<div class="col-md-8">
							<select name="id_conjunto" class="form-control field-3 select-2" id="id_conjunto">
								<option value="default">Seleccione...</option>
								@foreach($conjuntos as $conjutn)
									<option value="{{ $conjutn->id }}">{{ $conjutn->nombre }}</option>
								@endforeach
							</select>
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