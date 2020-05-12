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
        		<form method="post" enctype="multipart/form-data">
        			@csrf {{ method_field('POST') }}
        			<input type="hidden" name="id" id="id">
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-5">
							<i class="fa fa-paw"></i>
							<label class="margin-top">
								Tipo de mascota
							</label>
						</div>
						<div class="col-md-8">
							<select name="tipo" id="tipo" class="form-control field-5">
								<option value="default">Seleccione...</option>
								<option value="Perro">Perro</option>
								<option value="Gato">Gato</option>
								<option value="Hamster">Hamster</option>
								<option value="Conejo">Conejo</option>
							</select>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-1">
							<i class="fa fa-font"></i>
							<label class="margin-top">
								Nombre
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="nombre" id="nombre" class="form-control field-1" placeholder="Ejemplo: Panchito" autocomplete="off">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-text-width"></i>
							<label class="margin-top">
								Descripción
							</label>
						</div>
						<div class="col-md-8">
							<textarea name="descripcion" id="descripcion" cols="30" rows="5" placeholder="Ejemplo: No me la porteria por que es muy pequeña para que alguien trabaje ahi" class="form-control" autocomplete="off"></textarea>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-file-image-o"></i>
							<label class="margin-top">
								Foto
							</label>
						</div>
						<div class="col-md-8">
							<button type="button" class="btn btn-logotype-brand">
								<i class="fa fa-plus icon-plus-brand-logo"></i>
							</button>
							<input type="file" name="foto" id="file-input" class="upload hidden">
							<img id="imgSalida" class="text-center" alt="Image..." />
							<button type="button" class="btn btn-default btn-speacial">
								Quitar La foto
								<i class="fa fa-trash"></i>
							</button>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-8">
							<i class="fa fa-building-o"></i>
							<label class="margin-top">
								Tipo de unidad
							</label>
						</div>
						<div class="col-md-8">
							<select name="id_tipo_unidad" id="id_tipo_unidad" class="form-control field-8">
								<option value="{{ $aptoCliente->id }}">{{ $aptoCliente->tipo_unidad.' - '.$aptoCliente->numero_letra }}</option>
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