{{-- Modal para agregar empleados  --}}
<div id="empleados" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
	
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" >Agregar empleado para conjunto</h4>
		</div>
		<div class="modal-body">
			<form id="dataEmpleado">
				@csrf {{ method_field('POST') }}
				<input type="hidden" name="id" id="id">
				<label class="validate-label-1" for="fecha_ingreso">Fecha ingreso</label>
				<input class="form-control validate-input-1" type="date" id="fecha_ingreso" name="fecha_ingreso">
				<br>

				<label class="validate-label-2" for="nombre_completo">Nombre Completo</label>
				<input class="form-control validate-input-2" type="text" id="nombre_completo" name="nombre_completo">
				<br>
	
				<label class="validate-label-3" for="cedula">Cédula</label>
				<input class="form-control validate-input-3" type="text" id="cedula" name="cedula">
				<br>
	
				<label class="validate-label-4" for="direccion">Dirección</label>
				<input type="text" class="form-control validate-input-4" name="direccion" id="direccion">
				<br>

				<label class="validate-label-6" for="salario">Salario</label>
				<input class="form-control validate-input-6" onchange="changeValor(this,'salario');" type="text"  id="salario_aux" name="salario_aux">
				<input class="form-control" type="hidden"  id="salario" name="salario">
				<br>

				<label class="validate-label-5" for="cargo">Cargo</label>
				<input type="text" class="form-control validate-input-5" name="cargo" id="cargo">
				<br>

				<div class="row" id="divArchivo">
					<div id="alerta" class="alert alert-danger-original alert-dismissible" role="alert">
						<button type="button" class="close btn_close_alert" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">x</span>
						</button>
						<h5>
							Si selecciona un archivo, se reemplazara el enviado en el primer envio.
						</h5>
					</div>
					<label class="btn btn-default" for="foto">Foto<i class="fa fa-file-picture-o"></i></label>
					<input onchange="fileNameChange();" accept="image/jpeg" id="foto" type="file" style="display:none;" name="foto">
					<label id="fileName">Nombre del archivo de la foto: </label>
				</div>
	
			</form>
	
		</div>
		<div class="modal-footer">
			<button onclick="guardarEmpleado();" type="button" class="btn btn-primary">Guardar</button>
		</div>
	
		</div>
	</div>
</div>