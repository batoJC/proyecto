{{-- Modal para agregar archivos  --}}
<div id="archivos" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-md">
		  <div class="modal-content">

			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			  </button>
			  <h4 class="modal-title" id="myModalLabel2">Agregar Archivo</h4>
			</div>
			<div class="modal-body">
			  <form id="dataArchivo" >
					<br>
					<input type="hidden" name="tipo_unidad" id="tipo_unidad" value="{{$tipo_unidad->id}}">
					<label class="btn btn-warning" for="file">Subir Archivo</label>
					<input onchange="changeFile()" style="display:none"  accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" type="file" name="file" id="file">
					<label id="file_name" for="file">Nombre del archivo: </label>
					<br>

					<label for="nombre">Nombre</label>
					<input class="form-control" type="text" id="fileName" name="fileName">
					<br>

			  </form>

			</div>
			<div class="modal-footer">
			  <button onclick="guardarArchivo();" type="button" class="btn btn-primary">Guardar</button>
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