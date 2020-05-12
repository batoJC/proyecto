{{-- ***************************************** --}}
<!-- Modal -->
<div class="modal fade" id="multas" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">x</span>
		        </button>
		        <h4 id="title_modal_multas" class="text-center modal-title">
		        </h4>
      		</div>
      		<div class="modal-body">
        		<form method="post" id="dataCuota">
					@csrf {{ method_field('POST') }}

					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-usd"></i>
							<label class="margin-top validate-label-1">
								Concepto
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control validate-input-1" name="concepto" autocomplete="off" id="concepto">
						</div>
					</div>
					<br>

					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-font"></i>
							<label class="margin-top validate-label-2">
								Descripción
							</label>
						</div>
						<div class="col-md-8">
							<textarea name="descripcion" id="descripcion" cols="30" rows="5" placeholder="Ejemplo: Se acordó esta multa por la situacion de la empresa" class="form-control validate-input-2" autocomplete="off"></textarea>
						</div>
					</div>
					<br>

					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-usd"></i>
							<label class="margin-top validate-label-5">
								Valor
							</label>
						</div>
						<div class="col-md-8">
							<input type="number" class="form-control validate-input-5" name="valor" placeholder="Ejemplo: 200000" autocomplete="off" id="valor">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-calendar-check-o"></i>
							<label class="margin-top validate-label-6">
								Inicio de vigencia
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control validate-input-6" name="vigencia_inicio" placeholder="Ejemplo: Enero - 2018" autocomplete="off" id="vigencia_inicio">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-12">
							<input onchange="intereses();" type="checkbox" class="element-inline" id="interes" name="interes" value="1">
							<label for="interes" class="element-inline">Aplica Interés</label>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row hide" id="row_vigencia_fin">
						<div class="col-md-4">
							<i class="fa fa-calendar-times-o"></i>
							<label class="margin-top validate-label-7">
								Fin de vigencia
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control validate-input-7" name="vigencia_fin" placeholder="Ejemplo: Enero - 2018" autocomplete="off" id="vigencia_fin">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					{{-- <div class="row">
						<div class="col-md-4">
							<i class="fa fa-folder-open-o"></i>
							<label class="margin-top">
								Actas
							</label>
						</div>
						<div class="col-md-8">
							<select name="acta_id" id="acta_id" class="form-control select-2">
								<option value="">Seleccione...</option>
								@foreach($actas as $acta)
									<option value="{{ $acta->id }}">{{ $acta->titulo}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<br> --}}

					<div class="row" id="row_enviar">
						<div class="col-md-12 text-center">
							<button onclick="guardarCuota()" type="button" class="btn btn-success">
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