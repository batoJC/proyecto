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
							<i class="fa fa-user"></i>
							<label class="margin-top">
								Nombre Completo
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-1" name="nombre_completo" placeholder="Ejemplo: Juan Carlos Anduquia" autocomplete="off" id="nombre_completo">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-1">
							<i class="fa fa-address-book"></i>
							<label class="margin-top">
								Ocupación
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-1" name="ocupacion" placeholder="Ejemplo: Ingeniero, Ama de casa" autocomplete="off" id="ocupacion">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
							<div class="col-md-4 error-validate-7">
								<i class="fa fa-map-marker"></i>
								<label class="margin-top">
									Dirección
								</label>
							</div>
							<div class="col-md-8">
								<input type="text" class="form-control field-7" name="direccion" placeholder="Ejemplo: Cra 5 #45-43" autocomplete="off" value="{{ $conjuntos->direccion }}"d="direccion">
							</div>
						</div>
						<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-bars"></i>
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
						<div class="col-md-4 error-validate-9">
							<i class="fa fa-address-card-o"></i>
							<label class="margin-top">
								Número de Documento
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-9" name="numero_cedula" placeholder="Ejemplo: 123456789" autocomplete="off" id="numero_cedula">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-2">
							<i class="fa fa-envelope-o"></i>
							<label class="margin-top">
								Email
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-2" name="email" placeholder="Ejemplo: carlos@hotmail.com" autocomplete="off" id="email">
						</div>
					</div>
					<br class="password-div">
					{{-- Cada campo --}}
					<div class="row password-div">
						<div class="col-md-4 error-validate-3">
							<i class="fa fa-lock"></i>
							<label class="margin-top">
								Contraseña
							</label>
						</div>
						<div class="col-md-8">
							<style>
								.show_pass{
									right: 19px;
									margin-top: -25px;
									position: absolute;
									cursor: pointer;
								}
							</style>
							<input type="password" class="form-control field-3" name="password" placeholder="Ejemplo: 123456789ASDF" autocomplete="off" id="password">
							<i title="Mostrar contraseña" onclick="showPass(this);" class="fa fa-eye show_pass"></i>
							<script>
								function showPass(e){
									if(password.type == 'password'){
										$(e).removeClass('fa-eye');
										$(e).addClass('fa-eye-slash');
										$(e).attr('title','Ocultar contraseña');
										password.type = 'text';
									}else{
										$(e).addClass('fa-eye');
										$(e).removeClass('fa-eye-slash');
										$(e).attr('title','Mostrar contraseña');
										password.type = 'password';
									}
								}
							</script>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-4">
							<i class="fa fa-calendar"></i>
							<label class="margin-top">
								Fecha de Nacimiento
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control field-4" name="fecha_nacimiento"  autocomplete="off" id="fecha_nacimiento">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-5">
							<i class="fa fa-mars-stroke"></i>
							<label class="margin-top">
								Genero
							</label>
						</div>
						<div class="col-md-8">
							<select name="genero" class="form-control field-5" id="genero">
								<option value="">Seleccione...</option>
								<option value="Masculino">Masculino</option>
								<option value="Femenino">Femenino</option>
								<option value="Me abstengo">Me abstengo</option>
							</select>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-phone"></i>
							<label class="margin-top">
								Teléfono
							</label>
						</div>
						<div class="col-md-8">
							<input type="number" class="form-control" name="telefono" placeholder="Ejemplo: 8812172" autocomplete="off" id="telefono">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-6">
							<i class="fa fa-mobile"></i>
							<label class="margin-top">
								Celular
							</label>
						</div>
						<div class="col-md-8">
							<input type="number" class="form-control field-6" name="celular"
							placeholder="Ejemplo: 3215487878" autocomplete="off" id="celular">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-8">
							<i class="fa fa-user"></i>
							<label class="margin-top">
								Rol en el conjunto
							</label>
						</div>
						<div class="col-md-8">
							<select name="id_rol" id="id_rol" class="form-control field-8">
								<option value="">Seleccione...</option>
								<option value="3">Propietario</option>
								<option value="4">Portero</option>
								{{-- <option value="5">Empleado</option>
								<option value="6">Proveedor</option> --}}
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