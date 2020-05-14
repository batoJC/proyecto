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
						<div class="col-md-4 error-validate-1">
							<i class="fa fa-square-o"></i>
							<label class="margin-top">
								Tipo de Ejecución
							</label>
						</div>
						<div class="col-md-8">
							<select name="id_tipo_ejecucion" id="id_tipo_ejecucion" class="form-control field-1 select-2">
								<option value="">Seleccione...</option>
								@foreach($tipoEjecucionPre as $tipo)
									<option value="{{ $tipo->id }}">
										{{ $tipo->tipo }}
									</option>
								@endforeach
							</select>
						</div>
					</div>
					<br>
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-2">
							<i class="fa fa-plus-square-o"></i>
							<label class="margin-top">
								Ejecución Pres.. Total
							</label>
						</div>
						<div class="col-md-8">
							<select name="id_ejecucion_pre_total" id="id_ejecucion_pre_total" class="form-control field-2 select-2">
								<option value="">Seleccione...</option>
								@foreach($ejecucionPreTotal as $total)
								<optgroup label="{{' Tipo: '.$total->tipo }}">
										<option value="{{ $total->id }}">
												{{ 'Vigencia: '.date('d M Y', strtotime($total->fecha_inicio)).'-'.date('d M Y', strtotime($total->fecha_fin))}}
										</option>
								</optgroup>
								@endforeach
							</select>
						</div>
					</div>
					<br>
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-dollar"></i>
							<label class="margin-top">
								Valor
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" class="form-control" onchange="changeValor(this,'total');" name="total_aux" placeholder="Ejemplo: 800,000" autocomplete="off" id="total_aux">
							<input type="hidden" class="form-control" value="0" name="total" placeholder="Ejemplo: 800,000" autocomplete="off" id="total">
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-building-o"></i>
							<label class="margin-top validate-label-5">
								Unidad(s) Excluida(s)
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
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-file-pdf-o"></i>
							<label class="margin-top">
								Soportes
							</label>
						</div>
						<div class="col-md-8">
							<button type="button" class="btn btn-logotype-brand">
								<i class="fa fa-plus icon-plus-brand-logo"></i>
							</button>
							<input type="file" name="soportes[]" id="file-input" class="upload hidden" multiple>
							<p id="name-file"></p>
							<button type="button" class="btn btn-default btn-speacial">
								Quitar el Archivo&nbsp;
								<i class="fa fa-trash"></i>
							</button>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					{{-- <div class="row">
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
					<br> --}}
					{{-- Cada campo --}}
					{{-- <div class="row">
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
					<br> --}}
					{{-- Cada campo --}}
					{{-- <div class="row">
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