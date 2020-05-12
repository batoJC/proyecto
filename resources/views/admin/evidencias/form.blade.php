{{-- Modal para agregar recordatorios de mantenimientos  --}}
<div id="evidencias" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
	
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" >Evidencia del conjunto</h4>
		</div>
		<div class="modal-body">
			<form id="dataEvidencia" enctype="multipart/form-data">
				@csrf			
	
				<label class="validate-label-1" for="fecha">Fecha</label>
				<input class="form-control validate-input-1" type="date" id="fecha" name="fecha">
                <br>
                
                <label class="validate-label-1" for="contenido">Contenido</label>
				<textarea class="form-control validate-input-2" type="text" id="contenido" name="contenido"></textarea>
				<br>

				<label for="noticia">Noticia</label>
				<select class="form-control select-2" name="noticia" id="noticia">
					<option value="">Seleccione una noticia</option>
					@foreach ($noticias as $noticia)
						<option value="{{ $noticia->id }}">{{ $noticia->titulo }}</option>
					@endforeach
				</select>
				<br>
				<br>

				<label for="">Imagenes</label>
				<button type="button" class="btn btn-logotype-brand">
					<i class="fa fa-plus icon-plus-brand-logo"></i>
				</button>
				<input type="file" name="fotos[]" id="file-input" class="upload hidden" multiple>
				<img id="imgSalida" class="text-center" alt="Image..." />
				<button type="button" class="btn btn-default btn-speacial">
					Quitar La foto
					<i class="fa fa-trash"></i>
				</button>
	
                <div class="col-12 text-center" id="btnGuardar">
                    <button onclick="guardar();" type="button" class="btn btn-primary">Guardar</button>
                </div>
	
			</form>
	
		</div>
	
		</div>
	</div>
</div>

<style>
	.images{
		display: table;
	}

	img.foto {
		height: 100px;
		width: auto;
		display: unset;
		margin: 5px;
	}
</style>

{{-- Modal para ver la evidencia  --}}
<div id="infoEvidencia" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
	
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" >Evidencia del conjunto</h4>
			</div>
			<div class="modal-body">
				<h2><b>Fecha: </b> <p id="fecha_info">2019-23-43</p> </h2>
				<div class="images">
					{{-- <img class="foto show_img" src="" alt=""> --}}
				</div>
				<h4 id="contenido_info">

				</h4>
			</div>	
		</div>
	</div>
</div>