{{-- ***************************************** --}}
<!-- Modal -->
<div class="modal fade" id="descuentos" tabindex="-1" role="dialog" data-backdrop="static">
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
        		<form id="descuento">
        			@csrf {{ method_field('POST') }}
					{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-calendar-check-o"></i>
							<label class="margin-top validate-label-1">
								Fecha
							</label>
						</div>
						<div class="col-md-8">
							<input class="form-control validate-input-1" type="date" name="fecha" id="fecha">
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-user"></i>
							<label class="margin-top validate-label-2">
								Propietario
							</label>
						</div>
						<div class="col-md-8">
							<select onchange="loadUnidades()" name="user_id" id="user_id" class="form-control select-2 validate-input-2">
								<option value="">Seleccione...</option>
								@foreach ($propietarios as $propietario)
							<option value="{{ $propietario->id }}">{{ $propietario->numero_cedula }}-{{ $propietario->nombre_completo }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-building"></i>
							<label class="margin-top">
								Unidades
							</label>
						</div>
						<div class="col-md-8">
							<select onchange="loadInteresesUnidad()" name="unidad_id" id="unidad_id" class="form-control">
							</select>
						</div>
					</div>
					<br>
					<label id="interes"></label>
					<br>
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-usd"></i>
							<label class="margin-top validate-label-3">
								Valor
							</label>
						</div>
						<div class="col-md-8">
							<input class="form-control validate-input-3" type="text" name="valor" id="valor" placeholder="80000 ó 50%">
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-12 text-center">
							<button type="button" onclick="guardar()" class="btn btn-success">Guardar</button>
						</div>
					</div>
        		</form>
      		</div>
    	</div>
  	</div>
</div>