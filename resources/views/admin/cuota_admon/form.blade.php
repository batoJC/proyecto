{{-- ***************************************** --}}
<!-- Modal -->
<div class="modal fade" id="cuotas_admon" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-padding ">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">x</span>
		        </button>
		        <h4 id="title_cuotas_admon" class="text-center modal-title">
		        	<i class="fa fa-user"></i>
		        	&nbsp; 
		        </h4>
      		</div>
      		<div class="modal-body">
				<h1>Calcular: </h1>

				<div class="text-center">
					{{-- Formas de calcular cuotas de administración --}}
					<button onclick="seleccionarCalculo(1);" data-toggle="tooltip" data-placement="top" title="Se calcula la cuota de administración de acuerdo al coeficiente y a un presupuesto de ingreso seleccionado" class="btn btn-success">Por Presupuesto</button>
	
					<button onclick="seleccionarCalculo(2);" data-toggle="tooltip" data-placement="top" title="Se calcula de acuerdo al total de gastos y al coeficiente" class="btn btn-default">Por total de gastos</button>
	
					<button onclick="seleccionarCalculo(3);" data-toggle="tooltip" data-placement="top" title="Se da el mismo valor para todas las unidades privadas" class="btn btn-success">Por valor fijo</button>
	
					<button onclick="seleccionarCalculo(4);" data-toggle="tooltip" data-placement="top" title="Se incremeta o decrementa por un Porcentaje la cuota anterior de administración" class="btn btn-default">Ingrementar por porcentaje</button>
	
					<button onclick="seleccionarCalculo(5);" data-toggle="tooltip" data-placement="top" title="Se incremeta o decrementa por un valor fijo la cuota anterior de administración" class="btn btn-success">Ingrementar por valor</button>
				</div>

				{{-- POR PRESUPUESTO --}}
				<form method="post" id="dataCuota">
					@csrf {{ method_field('POST') }}
					<input type="hidden" value="0" name="tipo_calculo" id="tipo_calculo">
					<h3 id="text_tipo_calculo">Por presupuesto</h3>
					{{-- Tipo de presupuesto individual --}}
					<div class="row hide" id="row_presupuesto_calcular">
						<div class="col-md-4">
							<i class="fa fa-minus-square-o"></i>
							<label class="margin-top">
								Presupuesto para calcular
							</label>
						</div>
						<div class="col-md-8">
							<select name="presupuesto_calcular_id" id="presupuesto_calcular_id" class="form-control select-2">
								<option value="">Seleccione...</option>
								@foreach($presupuestos_ingresos as $presupuesto)
									<option value="{{ $presupuesto->id }}">
										{{											$presupuesto->Tipo_ejecucion_pre->tipo
										}}</option>
								@endforeach
							</select>
						</div>
					</div>	

					{{-- Cada campo --}}
					<div class="row hide" id="row_valor_fijo">
						<div class="col-md-4">
							<i class="fa fa-dollar"></i>
							<label class="margin-top ">
								Valor fijo
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control" onchange="changeValor(this,'valor_fijo');" name="valor_fijo_aux" placeholder="Ejemplo: $100,000" autocomplete="off" id="valor_fijo_aux">
							<input type="hidden" class="form-control" name="valor_fijo" autocomplete="off" id="valor_fijo">
						</div>
					</div>

					{{-- Cada campo --}}
					<div class="row hide" id="row_incremento_porcentual">
						<div class="col-md-4">
							<i class="fa fa-dollar"></i>
							<label class="margin-top">
								Incremento porcentual (%)
							</label>
						</div>
						<div class="col-md-8">
							<input type="number" class="form-control" name="incremento_porcentual" placeholder="Ejemplo: 10%" autocomplete="off" id="incremento_porcentual">
						</div>
					</div>

						{{-- Cada campo --}}
					<div class="row hide" id="row_incremento_fijo">
						<div class="col-md-4">
							<i class="fa fa-dollar"></i>
							<label class="margin-top">
								Ingremento valor fijo
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control" onchange="changeValor(this,'incremento_valor_fijo');" name="incremento_valor_fijo_aux" placeholder="Ejemplo: $10,000" autocomplete="off" id="incremento_valor_fijo_aux">
							<input type="hidden" class="form-control" name="incremento_valor_fijo" autocomplete="off" id="incremento_valor_fijo">
						</div>
					</div>
					<br>
					

					{{-- Tipo de presupuesto individual --}}
					<div class="row">
						<div class="col-md-4 error-validate-1">
							<i class="fa fa-minus-square-o"></i>
							<label class="margin-top validate-label-1">
								Presupuesto para cargar
							</label>
						</div>
						<div class="col-md-8">
							<select name="presupuesto_cargar_id" id="presupuesto_cargar_id" class="form-control validate-input-1 select-2">
								<option value="">Seleccione...</option>
								@foreach($presupuestos_ingresos as $presupuesto)
									<option value="{{ $presupuesto->id }}">
										{{											$presupuesto->Tipo_ejecucion_pre->tipo
										}}</option>
								@endforeach
							</select>
						</div>
					</div>	
					<br>

					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-2">
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
						<div class="col-md-4 error-validate-3">
							<i class="fa fa-calendar-times-o"></i>
							<label class="margin-top validate-label-3">
								Fin de vigencia
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control validate-input-3" name="vigencia_fin" placeholder="Ejemplo: Enero - 2018" autocomplete="off" id="vigencia_fin">
						</div>
					</div>
					<br>

					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-building-o"></i>
							<label class="margin-top validate-label-4">
								Unidad(s)
							</label>
						</div>
						<div class="col-md-8">
							<select name="unidades[]" id="unidades" class="form-control validate-input-4 select-multiple">
								<option value="">Seleccione unidades</option>
								<option value="todas">Todas</option>
								@foreach($unidades as $unidad)
									<option value="{{ $unidad->id }}">{{ $unidad->tipo->nombre.' - '.$unidad->numero_letra }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-wrench"></i>
							<label class="margin-top validate-label-5">
								Redondear a:
							</label>
						</div>
						<div class="col-md-8">
							<input type="number" class="form-control validate-input-5" name="redondear" value="1" placeholder="Ejemplo: 500" autocomplete="off" id="redondear">
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

<!-- Modal soportes -->
<div class="modal fade" id="modal-detalles" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">x</span>
				</button>
				<h4 class="text-center modal-title">
					Detalles Cuota de administración
				</h4>
			</div>
			<div class="modal-body" id="detalles">
			</div>
		</div>
	</div>
</div>