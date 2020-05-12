{{-- ***************************************** --}}
<!-- Modal -->
<div class="modal fade" id="cuotasExt" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">x</span>
		        </button>
		        <h4 id="title_modal_cuotas"class="text-center modal-title">
		        </h4>
      		</div>
      		<div class="modal-body">
        		<form method="post" id="dataCuota">
					@csrf {{ method_field('POST') }}
					<input type="hidden" name="tipo" id="tipo" value="valor">
					<div class="row">
						<div class="col-12 col-md-6 text-center">
							<button type="button" onclick="changeType('coeficiente')" class="btn btn-success"> (%) Por coeficiente</button>
						</div>
						<div class="col-12 col-md-6 text-center">
							<button type="button" onclick="changeType('valor')" class="btn"><i class="fa fa-dolar"></i> Por valor fijo</button>
						</div>
					</div>
					<br>
					{{-- Concepto --}}
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
							<i class="fa fa-usd"></i>
							<label id="label_tipo" class="margin-top validate-label-2">
								Valor
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control validate-input-2" onchange="changeValor(this,'valor');" name="valor_aux" placeholder="Ejemplo: 200000" autocomplete="off" id="valor_aux">
							<input type="hidden" class="form-control" name="valor" id="valor">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-calendar-check-o"></i>
							<label class="margin-top validate-label-3">
								Inicio de vigencia
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control validate-input-3" name="vigencia_inicio" placeholder="Ejemplo: Enero - 2018" autocomplete="off" id="vigencia_inicio">
						</div>
					</div>
					<br>
					{{-- Cada cuanto --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-wrench"></i>
							<label class="margin-top validate-label-4">
								Cada cuantos meses
							</label>
						</div>
						<div class="col-md-8">
							<input type="number" class="form-control validate-input-4" name="cada_cuanto" value="1" id="cada_cuanto">
						</div>
					</div>
					<br>
					{{-- Cada cuanto --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-wrench"></i>
							<label class="margin-top validate-label-5">
								Cuantas veces
							</label>
						</div>
						<div class="col-md-8">
							<input type="number" class="form-control validate-input-5" name="cuantas" value="1" id="cuantas">
						</div>
					</div>
					<br>

					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-building-o"></i>
							<label class="margin-top validate-label-6">
								Unidad(s)
							</label>
						</div>
						<div class="col-md-8">
							<select name="unidades[]" id="unidades" class="form-control validate-input-6 select-multiple">
								<option value="">Seleccione unidades</option>
								<option value="todas">Todas</option>
								@foreach($unidades as $unidad)
									<option value="{{ $unidad->id }}">{{ $unidad->tipo->nombre.' - '.$unidad->numero_letra }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<br>

					{{-- Tipo de presupuesto individual --}}
					<div class="row">
						<div class="col-md-4 error-validate-1">
							<i class="fa fa-minus-square-o"></i>
							<label class="margin-top validate-label-7">
								Presupuesto para cargar
							</label>
						</div>
						<div class="col-md-8">
							<select name="presupuesto_cargar_id" id="presupuesto_cargar_id" class="form-control validate-input-7 select-2">
								<option value="">Seleccione...</option>
								@foreach($presupuestos_ingresos as $presupuesto)
									<option value="{{ $presupuesto->id }}">{{$presupuesto->Tipo_ejecucion_pre->tipo}}</option>
								@endforeach
							</select>
						</div>
					</div>	
					<br>
					{{-- Cada cuanto --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-calendar-times-o"></i>
							<label class="margin-top validate-label-8">
								Día de corte del mes
							</label>
						</div>
						<div class="col-md-8">
							<input type="number" class="form-control validate-input-8" name="dia_corte" value="1" id="dia_corte">
						</div>
					</div>
					<br>

					<div class="row">
						<div class="col-12 text-center">
							<input type="checkbox" value="1" name="interes" id="interes">
							<label for="interes">Aplicar Intereses</label>
						</div>
					</div>
					<br>


					<div class="row">
						<div class="col-md-12 text-center">
							<button id="btn_guardar" onclick="guardarCuota()" type="button" class="btn btn-success">
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
					Detalles Cuota Extraordinaria
				</h4>
			</div>
			<div class="modal-body" id="detalles">
			</div>
		</div>
	</div>
</div>