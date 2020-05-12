{{-- Modal para agregar recordatorios de mantenimientos  --}}
<div id="documentos" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
	
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title text-center" >Agregar Documento</h4>
		</div>
		<div class="modal-body">
			<form id="dataDocumento" enctype="multipart/form-data">
				@csrf			
	
				{{-- Nombre --}}
				<div class="row validate-label-1">
					<div class="col-md-4">
						<i class="fa fa-font"></i>
						<label class="margin-top">
							Nombre
						</label>
					</div>
					<div class="col-md-8">
						<input type="text" name="nombre" id="nombre" class="form-control validate-input-1" placeholder="Ejemplo: Reglamento del conjunto..." autocomplete="off">
					</div>
				</div>
				<br>
				{{-- Descripción --}}
				<div class="row">
					<div class="col-md-4">
						<i class="fa fa-font"></i>
						<label class="margin-top">
							Descripción
						</label>
					</div>
					<div class="col-md-8">
						<textarea name="descripcion" id="descripcion" class="form-control" placeholder="Ejemplo: Pdfs con el reglamento del conjunto...." cols="30" rows="10"></textarea>
					</div>
				</div>
				<br>

				{{-- Porteria --}}
				<div class="row">
					<div class="col-md-12">
						<input type="checkbox" class="element-inline" id="porteria" name="porteria" value="1">
						<label for="porteria" class="element-inline">Visible para porteria</label>
					</div>
				</div>
				<br>

				{{-- Propietario --}}
				<div class="row">
					<div class="col-md-12">
						<input type="checkbox" class="element-inline" id="propietario" name="propietario" value="1">
						<label for="propietario" class="element-inline">Visible para propietarios</label>
					</div>
				</div>
				<br>


				<label class="btn btn-primary" for="archivos">Seleccionar archivos</label>
				<input onchange="show_info_files()" type="file" name="archivos[]" id="archivos" class="upload hidden" multiple>
				<br>
				<label id="archivos_load" for="">Archivos:</label>
				<br>
	
                <div class="col-12 text-center" id="btnGuardar">
                    <button onclick="guardar();" type="button" class="btn btn-success">Guardar</button>
                </div>
	
			</form>
	
		</div>
	
		</div>
	</div>
</div>


{{-- Modal para mostrar la información de un documento --}}
<div class="modal fade" id="info_documento" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">x</span>
		        </button>
		        <h4 class="text-center modal-title">
					Información del Documento
		        	&nbsp; 
		        </h4>
      		</div>
      		<div class="modal-body" id="body_info_documento">
			</div>
    	</div>
  	</div>
</div>
