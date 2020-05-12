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
						<div class="col-md-4 error-validate-1">
							<i class="fa fa-sort-numeric-desc"></i>
							<label class="margin-top">
								Nit
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-1" name="nit" placeholder="Ejemplo: 102030-52" autocomplete="off" id="nit">
						</div>
					</div>
					<br>
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-1">
							<i class="fa fa-address-book"></i>
							<label class="margin-top">
								Nombre
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-1" name="nombre" placeholder="Ejemplo: Conjunto Cerrado Ejemplo" autocomplete="off" id="nombre">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-1">
							<i class="fa fa-envelope-open-o"></i>
							<label class="margin-top">
								Correo
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-1" name="correo" placeholder="Correo del conjunto" autocomplete="off" id="correo">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-4">
							<i class="fa fa-map-signs"></i>
							<label class="margin-top">
								Ciudad
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-4" name="ciudad" placeholder="Ejemplo: Cali, Medellín" autocomplete="off" id="ciudad">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-2">
							<i class="fa fa-map-marker"></i>
							<label class="margin-top">
								Dirección
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-2" name="direccion" placeholder="Ejemplo: Calle 5ta Esquina." autocomplete="off" id="direccion">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-3">
							<i class="fa fa-map-o"></i>
							<label class="margin-top">
								Barrio
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-3" name="barrio" placeholder="Ejemplo: Barrio del sol" autocomplete="off" id="barrio">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-4">
							<i class="fa fa-phone"></i>
							<label class="margin-top">
								Teléfono/Celular
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-4" name="tel_cel" placeholder="Ejemplo: 3215487" autocomplete="off" id="tel_cel">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-5">
							<i class="fa fa-university"></i>
							<label class="margin-top">
								Tipo De Propiedad
							</label>
						</div>
						<div class="col-md-8">
							<select name="id_tipo_propiedad" class="form-control field-5" id="id_tipo_propiedad">
								<option value="default">Seleccione...</option>
								@foreach($tipo_conjunto as $tipo)
									<option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
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