{{-- ***************************************** --}}
<!-- Modal -->
<div class="modal fade" id="cuotasOtros" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">x</span>
		        </button>
		        <h4 id="title_modal_otros" class="text-center modal-title">
		        </h4>
      		</div>
      		<div class="modal-body">
        		<form method="post" id="dataCuota">
					@csrf {{ method_field('POST') }}

					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-quote-right"></i>
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
							<label class="margin-top validate-label-3">
								Descripción
							</label>
						</div>
						<div class="col-md-8">
							<textarea name="descripcion" id="descripcion" cols="30" rows="5" placeholder="Ejemplo: Se acordó esta multa por la situacion de la empresa" class="form-control validate-input-3" autocomplete="off"></textarea>
						</div>
					</div>
					<br>

					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-usd"></i>
							<label class="margin-top validate-label-1">
								Valor
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control validate-input-1" onchange="changeValor(this,'valor');" name="valor_aux" placeholder="Ejemplo: 200,000" autocomplete="off" id="valor_aux">
							<input type="hidden" class="form-control" name="valor" id="valor">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-calendar-check-o"></i>
							<label class="margin-top validate-label-2">
								Inicio de vigencia
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control validate-input-2" name="vigencia_inicio" placeholder="Ejemplo: Enero - 2018" autocomplete="off" id="vigencia_inicio">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-12">
							<input onchange="intereses();" type="checkbox" class="element-inline" id="interes" name="interes" value="1">
							<label for="interes" class="element-inline">Aplicar Interés</label>
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

					{{-- Tipo de presupuesto individual --}}
					<div class="row">
						<div class="col-md-4 error-validate-4">
							<i class="fa fa-minus-square-o"></i>
							<label class="margin-top validate-label-4">
								Presupuesto para cargar
							</label>
						</div>
						<div class="col-md-8" id="prueba">
							<select name="presupuesto_cargar_id" id="presupuesto_cargar_id" class="form-control validate-input-4 select-2">
								<option value="">Seleccione...</option>
								@foreach($presupuestos_ingresos as $presupuesto)
									<option value="{{ $presupuesto->id }}">
										{{$presupuesto->Tipo_ejecucion_pre->tipo}}</option>
								@endforeach
							</select>
						</div>
					</div>	
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-building-o"></i>
							<label class="margin-top validate-label-5">
								Unidad(s)
							</label>
						</div>
						<div class="col-md-8">
							<select name="unidades[]" id="unidades" class="form-control validate-input-5 select-multiple">
								<option value="">Seleccione...</option>
								@foreach($unidades as $unidad)
									<option value="{{ $unidad->id }}">{{ $unidad->tipo->nombre.' - '.$unidad->numero_letra }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<br>
					
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