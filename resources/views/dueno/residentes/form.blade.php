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
        			<input type="hidden" value="{{ $tipo_unidad->id }}" name="id_tipo_unidad" id="id_tipo_unidad">
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-address-card"></i>
							<label class="margin-top">
								Tipo de Residente
							</label>
						</div>
						<div class="col-md-8">
							<select name="tipo_residente" class="form-control" id="tipo_residente">
								<option value="inquilino">Inquilino</option>
								<option value="familiar">Familiar</option>
							</select>
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
							<input type="text" class="form-control field-1" name="nombre" placeholder="Ejemplo: Juan Carlos" autocomplete="off" id="nombre">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-2">
							<i class="fa fa-address-book"></i>
							<label class="margin-top">
								Apellido
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-2" name="apellido" placeholder="Ejemplo: Anduquia Restrepo" autocomplete="off" id="apellido">
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
								<option value="default">Seleccione...</option>
								<option value="Masculino">Masculino</option>
								<option value="Femenino">Femenino</option>
								<option value="Indefinido">Indefinido</option>
							</select>
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
								<option value="Cedula de Ciudadanía">Cédula de Ciudadanía</option>
								<option value="Cedula de Extranjería">Cédula de Extranjería</option>
								<option value="Tarjeta de Identidad">Tarjeta de Identidad</option>
							</select>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-6">
							<i class="fa fa-address-card-o"></i>
							<label class="margin-top">
								Número de Documento
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-6" name="documento" placeholder="Ejemplo: 123456789" autocomplete="off" id="documento">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-9">
							<i class="fa fa-calendar-check-o"></i>
							<label class="margin-top">
								Fecha Ingreso
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control field-9" name="fecha_ingreso" id="fecha_ingreso">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-calendar-check-o"></i>
							<label class="margin-top">
								Fecha Salida
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control" name="fecha_salida" id="fecha_salida">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-8">
							<i class="fa fa-mars-stroke"></i>
							<label class="margin-top">
								Estado
							</label>
						</div>
						<div class="col-md-8">
							<select name="estado" class="form-control field-8" id="estado">
								<option value="Activo">Activo</option>
								<option value="Inactivo">Inactivo</option>
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