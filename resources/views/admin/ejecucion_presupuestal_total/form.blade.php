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
					{{-- <div class="row">
						<div class="col-md-4 error-validate-1">
							<i class="fa fa-usd"></i>
							<label class="margin-top">
								Total
							</label>
						</div>
						<div class="col-md-8">
							<input type="number" class="form-control field-1" name="valor_total" placeholder="Ejemplo: 200000000" autocomplete="off" id="valor_total">
						</div>
					</div>
					<br> --}}
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-6">
							<i class="fa fa-calendar-check-o"></i>
							<label class="margin-top">
								Inicio de vigencia
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control field-6" name="fecha_inicio" placeholder="Ejemplo: Enero - 2018" autocomplete="off" id="fecha_inicio">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-9">
							<i class="fa fa-calendar-times-o"></i>
							<label class="margin-top">
								Fin de vigencia
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control field-9" name="fecha_fin" placeholder="Ejemplo: Enero - 2018" autocomplete="off" id="fecha_fin">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-5">
							<i class="fa fa-lock"></i>
							<label class="margin-top">
								Tipo
							</label>
						</div>
						<div class="col-md-8">
							<select name="tipo" class="form-control field-5" id="tipo">
								<option value="default">Seleccione..</option>
								<option value="ingreso">Ingreso</option>
								<option value="egreso">Egreso</option>
							</select>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					{{-- <div class="row">
						<div class="col-md-4">
							<i class="fa fa-file-pdf-o"></i>
							<label class="margin-top">
								Archivo
							</label>
						</div>
						<div class="col-md-8">
							<button type="button" class="btn btn-logotype-brand">
								<i class="fa fa-plus icon-plus-brand-logo"></i>
							</button>
							<input type="file" name="archivo" id="file-input" class="upload hidden">
							<p id="name-file"></p>
							<button type="button" class="btn btn-default btn-speacial">
								Quitar el Archivo&nbsp;
								<i class="fa fa-trash"></i>
							</button>
						</div>
					</div>
					<br> --}}
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


<!-- Modal soportes -->
<div class="modal fade" id="modal-detalles" tabindex="-1" role="dialog" data-backdrop="static">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content modal-padding">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
					<h4 class="text-center modal-title">
						Detalles presupuesto
					</h4>
				</div>
				<div class="modal-body" id="detalles">
				</div>
			</div>
		</div>
	</div>