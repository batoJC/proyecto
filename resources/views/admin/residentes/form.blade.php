{{-- ***************************************** --}}
<!-- Modal -->
<div id="residentes" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
	  <div class="modal-content">
  
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
		  </button>
		  <h4 class="modal-title" >Agregar residentes</h4>
		</div>
		<div class="modal-body">
		  <form id="dataResidente">
				@csrf
				<label class="validate-label-1" for="unidad_id">Unidad</label>
				<select class="form-control validate-input-1" name="unidad_id" id="unidad_id">
					<option value="">Seleccione la unidad</option>
					@foreach ($unidades as $unidad)
						<option value="{{ $unidad->id }}">{{ $unidad->tipo->nombre }} {{ $unidad->numero_letra }}</option>				
					@endforeach
				</select>
			  
			  <label for="tipo_residente">Tipo de residente</label>
			  <select class="form-control" name="tipo_residente" id="tipo_residente">
				  <option value="inquilino">Inquilino</option>
				  <option value="familiar">Familiar</option>
				  <option value="propietario">Propietario</option>
			  </select>
			  <br>
  
			  <label class="validate-label-2" for="nombre">Nombres</label>
			  <input class="form-control validate-input-2" type="text" id="nombre" name="nombre">
			  <br>
  
			  <label class="validate-label-3" for="apellido">Apellidos</label>
			  <input class="form-control validate-input-3" type="text" id="apellido" name="apellido">
			  <br>
  
			  <label for="fecha_nacimiento">Fecha de Nacimiento</label>
			  <input class="form-control" type="date" id="fecha_nacimiento" name="fecha_nacimiento">
			  <br>
  
			  <label for="ocupacion">Ocupación</label>
			  <input class="form-control" type="text" id="ocupacion" name="ocupacion">
			  <br>  

			  <label for="profesion">Profesión</label>
			  <input class="form-control" type="text" id="profesion" name="profesion">
			  <br>

			    
			  <label for="direccion">Lugar de trabajo</label>
			  <input class="form-control" type="text" id="direccion" name="direccion">
			  <br>
  
			  <label for="email">Email</label>
			  <input class="form-control" type="email" id="email" name="email">
			  <br>

  
			  <label for="genero">Género</label>
			  <select class="form-control" name="genero" id="genero">
				  <option value="masculino">Masculino</option>
				  <option value="femenino">Femenino</option>
				  <option value="me abstengo">Me abstengo</option>
			  </select>
			  <br>
  
			  <label for="tipo_documento_id">Tipo de documento</label>
			  <select class="form-control" name="tipo_documento_id" id="tipo_documento_id">
				  @foreach ($tipos_documentos as $tipo)
					  <option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
				  @endforeach
			  </select>
			  <br>
  
			  <label class="validate-label-4" for="documento">Documento</label>
			  <input type="text" class="form-control validate-input-4" name="documento" id="documento">
			  <br>
			  
			  <label class="validate-label-5" for="fecha_ingreso">Fecha de ingreso</label>
			  <input class="form-control validate-input-5" type="date" name="fecha_ingreso" id="fecha_ingreso">
			  <br>
  
		  </form>
  
		</div>
		<div class="modal-footer">
		  <button onclick="guardarResidente();" type="button" class="btn btn-primary">Guardar</button>
		</div>
  
	  </div>
	</div>
  </div>


  <div id="exportarResidentes" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
	  <div class="modal-content">
  
		<div class="modal-header">
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
		  </button>
		  <h4 class="modal-title" >Exportar residentes</h4>
		</div>
		<div class="modal-body">
		<form id="dataResidente" target="_blanck" method="POST" action="{{ url('exportarResidentes') }}">
			@csrf  
			<label class="validate-label-4" for="edad_inicio">Edad inicio</label>
			<input type="number" class="form-control validate-input-4" name="edad_inicio" id="edad_inicio" min="0" max="200" requiered>
			<br>
			
			<label class="validate-label-5" for="edad_fin">Edad fin</label>
			<input class="form-control validate-input-5" type="number" name="edad_fin" id="edad_fin" min="0" max="200" requiered>
			<br>
			<div class="row">
				<div class="col-12 text-center">
					<input class="btn btn-success" type="submit" value="Exportar">
				</div>
			</div>
		  </form>
		</div>
	  </div>
	</div>
  </div>