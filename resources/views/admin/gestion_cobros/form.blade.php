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
						<div class="col-md-4 error-validate-gestion-1">
							<i class="fa fa-usd"></i>
							<label class="margin-top">
								Recaudo
							</label>
						</div>
						<div class="col-md-8">
							<input type="number" class="form-control field-gestion-1" name="recaudo" placeholder="Ejemplo: 200000" autocomplete="off" id="recaudo">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-gestion-2">
							<i class="fa fa-calendar-check-o"></i>
							<label class="margin-top">
								Fecha de Recaudo
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" class="form-control field-gestion-2" name="fecha_recaudo" placeholder="Ejemplo: Enero - 2018" autocomplete="off" id="fecha_recaudo">
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-gestion-3">
							<i class="fa fa-building-o"></i>
							<label class="margin-top">
								Tipo de unidad
							</label>
						</div>
						<div class="col-md-8">
							<select name="id_tipo_unidad" id="id_tipo_unidad" class="form-control field-gestion-3 select-2">
								<option value="">Seleccione...</option>
								@foreach($tipo_unidad as $aptoCliente)
									<option value="{{ $aptoCliente->id }}">{{ $aptoCliente->tipo_unidad.' - '.$aptoCliente->numero_letra }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<br>
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4 error-validate-gestion-3">
							<i class="fa fa-sort-numeric-asc"></i>
							<label class="margin-top">
								Consecutivo
							</label>
						</div>
						<div class="col-md-8">
							<select name="id_tbl_consecutivo" id="id_tbl_consecutivo" class="form-control field-gestion-3">
								@foreach ($consecutivos as $consecutivo)
									<option value="{{ $consecutivo->id }}">{{ $consecutivo->prefijo }}  {{ $consecutivo->id_consecutivo }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<br><br>
					<div class="row">
						<div class="col-md-12 text-center">
		        			<button type="reset" class="btn btn-danger">
		        				<i class="fa fa-trash"></i>
		        				&nbsp; Limpiar Campos
		        			</button>
							<button type="button" class="btn btn-success" id="send_form_gestion">
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