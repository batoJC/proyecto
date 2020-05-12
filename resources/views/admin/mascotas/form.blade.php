{{-- Modal para agregar mascotas  --}}
<div id="mascotas" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-md">
		  <div class="modal-content">
	  
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			  </button>
			  <h4 class="modal-title" id="myModalLabel2">Agregar mascota</h4>
			</div>
			<div class="modal-body">
			  <form id="dataMascota" >
					<label class="validate-label-1" for="unidad_id">Unidad</label>
					<select class="form-control validate-input-1" name="unidad_id" id="unidad_id">
						<option value="">Seleccione la unidad</option>
						@foreach ($unidades as $unidad)
							<option value="{{ $unidad->id }}">{{ $unidad->tipo->nombre }} {{ $unidad->numero_letra }}</option>				
						@endforeach
					</select>
					<br>

					<label class="btn btn-warning" for="foto">Foto</label>
					<input onchange="changeFile()" style="display:none"  accept="image/jpeg" type="file" name="foto" id="foto">
					<label id="filename" for="">Nombre del archivo: </label>
					<br>

					<label for="nombre">Nombre</label>
					<input class="form-control" type="text" id="nombre" name="nombre">
					<br>

					<label for="codigo">Código</label>
					<input class="form-control" min="0" type="number" id="codigo" name="codigo">
					<br>

					<label for="raza">Raza</label>
					<input class="form-control" type="text" id="raza" name="raza">
					<br>

					<label for="fecha_nacimiento">Fecha de Nacimiento</label>
					<input class="form-control" type="date" id="fecha_nacimiento" name="fecha_nacimiento">
					<br>

					<label for="descripcion">Descripción</label>
					<textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="10"></textarea>
					<br>

					<label for="tipo_mascota">Tipo de mascota</label>
					<select onchange="checkTipo(this);" class="form-control" name="tipo_mascota" id="tipo_mascota">
						<option value="">Seleccione el tipo</option>
						@foreach ($tipos_mascotas as $tipo)
							<option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
						@endforeach
						<option value="otro">Otro</option>
					</select>
	  
			  </form>
	  
			</div>
			<div class="modal-footer">
			  <button onclick="guardarMascota();" type="button" class="btn btn-primary">Guardar</button>
			</div>
	  
		  </div>
		</div>
	  </div>
	  
	  {{-- Modal para agregar tipos de mascota  --}}
	  <div class="modal fade" id="modalAddTipoMascota" tabindex="-1" role="dialog" data-backdrop="static">
		  <div class="modal-dialog" role="document">
			  <div class="modal-content modal-padding">
				  <div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">x</span>
					  </button>
					  <h4 class="text-center">
						  Agregar Tipo de Mascota
						  &nbsp; 
					  </h4>
				  </div>
				  <div class="modal-body">
					  <form method="post" id="formTipo">
						  @csrf {{ method_field('POST') }}
						  <div class="row">
							  <div class="col-md-4 error-validate-tipo">
								  <i class="fa fa-address-book"></i>
								  <label class="margin-top">
									  Tipo
								  </label>
							  </div>
							  <div class="col-md-8">
								  <input type="text" class="form-control field-tipo" name="tipo" placeholder="Ingrese el nuevo tipo de documento" autocomplete="off" id="tipo">
							  </div>
						  </div>
						  <br>
						  <div class="row">
							  <div class="col-md-12 text-center">
								  <button type="submit" class="btn btn-success">
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