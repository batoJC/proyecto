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
        		<form method="post" id="form">
        			@csrf {{ method_field('POST') }}
        			{{-- <input type="hidden" name="id" id="id"> --}}
					
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-calendar-check-o"></i>
							<label class="margin-top validate-label-1">
								Vigencia Inicio
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control validate-input-1" name="vigencia_inicio"  id="vigencia_inicio">
						</div>
					</div>
					<br>

					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-calendar-times-o"></i>
							<label class="margin-top">
								Vigencia Fin
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control" name="vigencia_fin"  id="vigencia_fin">
						</div>
					</div>
					<br>
					
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-quote-right"></i>
							<label class="margin-top validate-label-2">
								Concepto
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control validate-input-2" name="concepto"  id="concepto">
						</div>
					</div>
					<br>
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-usd"></i>
							<label class="margin-top validate-label-3">
								Valor
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" onchange="changeValor(this,'valor');" class="form-control validate-input-3" name="valor_aux"  id="valor_aux">
							<input type="hidden" class="form-control" name="valor"  id="valor">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-minus-square-o"></i>
							<label class="margin-top validate-label-4">
								Presupuesto para cargar
							</label>
						</div>
						<div class="col-md-8">
							<select name="presupuesto" id="presupuesto" class="form-control select-2 validate-input-4">
								<option value="">Seleccione...</option>
								@foreach($presupuestos as $presupuesto)
									<option value="{{ $presupuesto->id }}">
										{{ $presupuesto->Tipo_ejecucion_pre->tipo }}
									</option>
								@endforeach
							</select>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-building-o"></i>
							<label class="margin-top validate-label-5">
								Unidad
							</label>
						</div>
						<div class="col-md-8">
							<select name="unidad" id="unidad" class="form-control validate-input-5 select-multiple">
								<option value="">Seleccione...</option>
								@foreach($unidades as $unidad)
									<option value="{{ $unidad->id }}">{{ $unidad->tipo->nombre.' - '.$unidad->numero_letra }}</option>
								@endforeach
							</select>
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
	<div class="modal fade" id="modal-soportes" tabindex="-1" role="dialog" data-backdrop="static">
		<div class="modal-dialog" role="document">
			<div class="modal-content modal-padding">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
					<h4 class="text-center modal-title">
						Soportes presupuesto
					</h4>
				</div>
				<div class="modal-body">
					<table class="table">
						<thead>
							<tr>
								<th>Archivo</th>
								<th>Ver</th>
							</tr>
						</thead>
						<tbody id="soportes">
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>


	<!-- Modal soportes -->
	<div class="modal fade" id="modal-excluidas" tabindex="-1" role="dialog" data-backdrop="static">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content modal-padding">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">x</span>
					</button>
					<h4 class="text-center modal-title">
						Unidades excluidas
					</h4>
				</div>
				<div class="modal-body" id="excluidas">
				</div>
			</div>
		</div>
	</div>