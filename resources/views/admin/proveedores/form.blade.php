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
						<div class="col-md-4">
							<i class="fa fa-address-book"></i>
							<label class="margin-top">
								Nombre Completo
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control" name="nombre_completo" placeholder="Ejemplo: Pinturas el cali, Juan carlos" autocomplete="off" id="nombre_completo">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-cog"></i>
							<label class="margin-top">
								Tipo de Documento
							</label>
						</div>
						<div class="col-md-8">
							<select name="tipo_documento" class="form-control" id="tipo_documento">
								@foreach ($tipo_documentos as $tipo)
									<option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-address-card-o"></i>
							<label class="margin-top">
								Número de Documento
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control" name="documento" placeholder="Ejemplo: 123456789-1" autocomplete="off" id="documento">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-envelope-o"></i>
							<label class="margin-top">
								Email
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control" name="email" placeholder="Ejemplo: pinturas@hotmail.com" autocomplete="off" id="email">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-map-marker"></i>
							<label class="margin-top">
								Dirección
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control" name="direccion" placeholder="Ejemplo: Cra 4 # 45-56" autocomplete="off" id="direccion">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-phone"></i>
							<label class="margin-top">
								Telefono
							</label>
						</div>
						<div class="col-md-8">
							<input type="number" class="form-control" name="telefono" placeholder="Ejemplo: 8812172" autocomplete="off" id="telefono">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-mobile"></i>
							<label class="margin-top">
								Celular
							</label>
						</div>
						<div class="col-md-8">
							<input type="number" class="form-control" name="celular"
							placeholder="Ejemplo: 3215487878" autocomplete="off" id="celular">
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