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
        		<form method="post" id="dataForm" enctype="multipart/form-data">
        			@csrf {{ method_field('POST') }}
        			<input type="hidden" name="id" id="id">
        			{{-- Nombre --}}
					<div class="row validate-label-1">
						<div class="col-md-4">
							<i class="fa fa-font"></i>
							<label class="margin-top">
								Nombre
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="nombre" id="nombre" class="form-control validate-input-1" placeholder="Ejemplo: Equipo de Sonido, Sillas..." autocomplete="off">
						</div>
					</div>
					<br>
					{{-- Ubicación --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-map-marker"></i>
							<label class="margin-top">
								Ubicación
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="ubicacion" id="ubicacion" class="form-control" placeholder="Ejemplo: Sobre la via, en el salón solcial...." autocomplete="off">
						</div>
					</div>
					<br>
					{{-- Descripcion --}}
					<div class="row validate-label-2">
						<div class="col-md-4">
							<i class="fa fa-font"></i>
							<label class="margin-top">
								Descripción
							</label>
						</div>
						<div class="col-md-8">
							<textarea name="descripcion" id="descripcion" cols="30" rows="5" placeholder="Ejemplo: Artículo de uso en la coopropiedad para..." class="form-control validate-input-2" autocomplete="off"></textarea>
						</div>
					</div>
					<br>
					{{-- Condición --}}
					<div class="row validate-label-3">
						<div class="col-md-4">
							<i class="fa fa-flag"></i>
							<label class="margin-top">
								Condición
							</label>
						</div>
						<div class="col-md-8">
							<textarea name="condicion" id="condicion" cols="30" rows="5" placeholder="Ejemplo: El artículo se encuentra en estado..." class="form-control validate-input-3" autocomplete="off"></textarea>
						</div>
					</div>
					<br>
					{{-- Valor --}}
					<div class="row validate-label-4">
						<div class="col-md-4">
							<i class="fa fa-usd"></i>
							<label class="margin-top">
								Valor
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control validate-input-4" onchange="changeValor(this,'valor');" name="valor_aux" placeholder="Ejemplo: 200,000" autocomplete="off" id="valor_aux">
							<input type="hidden" class="form-control validate-input-4" name="valor" placeholder="Ejemplo: 200000" autocomplete="off" id="valor">
						</div>
					</div>
					<br>

					{{-- Garantía --}}
					<div class="row">
						<div class="col-md-12">
							<input onchange="garantiaF();" type="checkbox" class="element-inline" id="garantia" name="garantia" value="1">
							<label for="garantia" class="element-inline">Tiene Garantía</label>
						</div>
					</div>
					<br>
					{{-- Garantia valida hasta --}}
					<div class="row hide" id="row_valido_hasta">
						<div class="col-md-4">
							<i class="fa fa-calendar"></i>
							<label class="margin-top">
								Valida hasta
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control" name="valido_hasta" placeholder="Ejemplo: día/mes/año" autocomplete="off" id="valido_hasta">
						</div>
					</div>
					<br>
					{{-- Fecha de compra --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-calendar"></i>
							<label class="margin-top">
								Fecha compra
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control" name="fecha_compra" placeholder="Ejemplo: día/mes/año" autocomplete="off" id="fecha_compra">
						</div>
					</div>
					<br>
					{{-- Fabricante --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-building-o"></i>
							<label class="margin-top ">
								Fabricante
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control" name="fabricante" placeholder="Ejemplo: Toyota" autocomplete="off" id="fabricante">
						</div>
					</div>
					<br>
					{{-- Estilo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-crop"></i>
							<label class="margin-top ">
								Estilo
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control" name="estilo" autocomplete="off" id="estilo">
						</div>
					</div>
					<br>
					{{-- No. Serie --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-barcode"></i>
							<label class="margin-top ">
								No. Serie
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control" name="numero_serie" autocomplete="off" id="numero_serie">
						</div>
					</div>
					<br>
					{{-- Observaciones --}}
					<div class="row validate-label-5">
						<div class="col-md-4">
							<i class="fa fa-font"></i>
							<label class="margin-top">
								Observaciones
							</label>
						</div>
						<div class="col-md-8">
							<textarea name="observaciones" id="observaciones" cols="30" rows="5" placeholder="Ejemplo: Comunica acometida general con red de suministro..." class="form-control validate-input-5" autocomplete="off"></textarea>
						</div>
					</div>
					<br>

					<label class="btn btn-primary" for="fotos">Seleccionar Fotos</label>
					<input onchange="show_info_files()" type="file" name="fotos[]" id="fotos" class="upload hidden" multiple>
					<br>
					<label id="archivos_load" for="">Archivos:</label>


					
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



{{-- Modal para mostrar la información de un artículo --}}
<div class="modal fade" id="info_articulo" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">x</span>
		        </button>
		        <h4 class="text-center modal-title">
					Información del artículo
		        	&nbsp; 
		        </h4>
      		</div>
      		<div class="modal-body" id="body_info_articulo">
			</div>
    	</div>
  	</div>
</div>
