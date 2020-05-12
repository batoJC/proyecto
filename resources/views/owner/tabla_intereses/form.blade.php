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
        		<form method="post">
        			@csrf {{ method_field('POST') }}
        			<input type="hidden" name="id" id="id">
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-1">
							<i class="fa fa-calendar"></i>
							<label class="margin-top">
								Periodo
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-1" name="periodo" placeholder="Ejemplo: Enero - 2018" autocomplete="off" id="periodo">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-6">
							<i class="fa fa-calendar-check-o"></i>
							<label class="margin-top">
								Inicio de vigencia
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control field-6" name="fecha_vigencia_inicio" placeholder="Ejemplo: Enero - 2018" autocomplete="off" id="fecha_vigencia_inicio">
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
							<input type="date" class="form-control field-9" name="fecha_vigencia_fin" placeholder="Ejemplo: Enero - 2018" autocomplete="off" id="fecha_vigencia_fin">
						</div>
					</div>
					<br>
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-2">
							<i class="fa fa-sort-numeric-desc"></i>
							<label class="margin-top">
								Nro Resolución
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control field-2" name="numero_resolucion" placeholder="Ejemplo: 10232-2010" autocomplete="off" id="numero_resolucion">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-3">
							<i class="fa fa-sort-numeric-desc"></i>
							<label class="margin-top">
								Tasa Efectiva Anual
							</label>
						</div>
						<div class="col-md-8">
							<div class="input-group">
								<input type="text" class="form-control field-3" name="tasa_efectiva_anual" placeholder="Ejemplo: 22.22" autocomplete="off" id="tasa_efectiva_anual">
								<div class="input-group-addon">%</div>
							</div>
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