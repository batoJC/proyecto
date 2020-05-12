{{-- Modal para agregar vehículos  --}}
<div id="vehiculos" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-md">
		  <div class="modal-content">
	  
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			  </button>
			  <h4 class="modal-title" id="myModalLabel2">Agregar vehículo</h4>
			</div>
			<div class="modal-body">
			  <form id="dataVehiculo">
					<label class="validate-label-1" for="unidad_id">Unidad</label>
					<select class="form-control validate-input-1" name="unidad_id" id="unidad_id">
						<option value="">Seleccione la unidad</option>
						@foreach ($unidades as $unidad)
							<option value="{{ $unidad->id }}">{{ $unidad->tipo->nombre }} {{ $unidad->numero_letra }}</option>				
						@endforeach
					</select>
					<br>


					<label class="btn btn-warning" for="foto1">Foto Vehículo</label>
					<input onchange="changeFileVehiculo(1)" style="display:none"  accept="image/jpeg" type="file" name="foto1" id="foto1">
					<label id="filename1" for="">Nombre del archivo: </label>
					<br>
		
					<label class="btn btn-warning" for="foto2">Foto tarjeta de propiedad cara 1</label>
					<input onchange="changeFileVehiculo(2)" style="display:none"  accept="image/jpeg" type="file" name="foto2" id="foto2">
					<label id="filename2" for="">Nombre del archivo: </label>
					<br>
		
					<label class="btn btn-warning" for="foto3">Foto tarjeta de propiedad cara 2</label>
					<input onchange="changeFileVehiculo(3)" style="display:none"  accept="image/jpeg" type="file" name="foto3" id="foto3">
					<label id="filename3" for="">Nombre del archivo: </label>
					<br>
		
					<label for="tipo">Tipo de vehículo</label>
					<select name="tipo" id="tipo" class="form-control">
						<option value="carro">Carro</option>
						<option value="moto">Moto</option>
						<option value="otro">Otro</option>
					</select>
					<br>
		
					<label for="marca">Marca</label>
					<input class="form-control" type="text" id="marca" name="marca">
					<br>
		
					<label for="color">Color</label>
					<input class="form-control" type="text" id="color" name="color">
					<br>
		
					<label class="validate-label-2" for="placa">Placa</label>
					<input class="form-control validate-input-2" type="text" id="placa" name="placa">
					<br>
		
					<label class="validate-label-3" for="registra">Propietario del vehículo</label>
					<input class="form-control validate-input-3" type="text" id="registra" name="registra">
					<br>
	  
			  </form>
	  
			</div>
			<div class="modal-footer">
			  <button onclick="guardarVehiculo();" type="button" class="btn btn-primary">Guardar</button>
			</div>
	  
		  </div>
		</div>
	  </div>