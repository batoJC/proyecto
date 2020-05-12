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
        			<input type="hidden" name="id_egresos" id="id_egresos" value="{{ $egresos->id }}">
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-1">
							<i class="fa fa-user"></i>
							<label class="margin-top">
								Ejecución Presupuestal (Individual)
							</label>
						</div>
						<div class="col-md-8">
							<select name="id_presup_individual" id="id_presup_individual" class="form-control field-1 select-2">
								<option value="">Seleccione...</option>
								@foreach($presup_individual as $individual)
									<option value="{{ $individual->id }}">{{ $individual->Tipo_ejecucion_pre->tipo }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<br>
					@if($egresos->aiu != null)
						<h5 class="text-center">Recuerda que ya hay un AIU previamente definido, por lo tanto esto cambiará la lógica de la operación</h5>
						<br>
						{{-- Cada campo --}}
						<div class="row">
							<div class="col-md-4">
								<i class="fa fa-usd"></i>
								<label class="margin-top">
									AIU
								</label>
							</div>
							<div class="col-md-8">
								<input type="number" class="form-control" name="aiu_cost" id="aiu_cost" value="{{ $egresos->aiu }}" autocomplete="off" readonly="true">
							</div>
						</div>
						<br>
					@endif
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-2">
							<i class="fa fa-usd"></i>
							<label class="margin-top">
								Valor sin iva
							</label>
						</div>
						<div class="col-md-8">
							<input type="number" class="form-control field-2" name="sub_valor_antes_iva" placeholder="Ejemplo: 200000" autocomplete="off" id="sub_valor_antes_iva">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-3">
							<i class="fa fa-percent"></i>
							<label class="margin-top">
								Porcentaje Iva
							</label>
						</div>
						<div class="col-md-8">
							<div class="input-group">
								<input type="text" class="form-control field-3" name="iva" placeholder="Ejemplo: 22.22" autocomplete="off" id="iva" step="0.01">
								<div class="input-group-addon">%</div>
							</div>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-4">
							<i class="fa fa-area-chart"></i>
							<label class="margin-top">
								Concepto Retención
							</label>
						</div>
						<div class="col-md-8">
							<select name="id_conceptos_retencion" id="id_conceptos_retencion" class="form-control field-4 select-2">
								<option value="">Seleccione...</option>
								@foreach($conceptos_retencion as $conceptos)
									<option value="{{ $conceptos->id }}">{{ $conceptos->descripcion.' - '.$conceptos->porcentaje.' % ' }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-font"></i>
							<label class="margin-top">
								Descripción
							</label>
						</div>
						<div class="col-md-8">
							<textarea name="descripcion" id="descripcion" cols="30" rows="5" placeholder="Ejemplo: Esto es una descripcion" class="form-control" autocomplete="off"></textarea>
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