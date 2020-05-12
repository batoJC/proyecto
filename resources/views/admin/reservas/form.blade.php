{{-- ***************************************** --}}
<!-- Modal -->
<div class="modal fade" id="infoReserva" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">x</span>
		        </button>
		        <h4 class="text-center modal-title">
		        	Información de la reserva
		        	&nbsp; 
		        </h4>
      		</div>
      		<div id="info_reserva" class="modal-body">
        		
      		</div>
    	</div>
  	</div>
</div>

<div class="modal fade" id="modal_reserva" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">x</span>
		        </button>
		        <h4 class="text-center modal-title">
		        	Agregar reserva
		        	&nbsp;
		        </h4>
      		</div>
      		<div class="modal-body">
        		<form method="post" id="formReserva">
					@csrf {{ method_field('POST') }}
					{{-- Zona común --}}
					<div class="row validate-label-6">
						<div class="col-md-4">
							<i class="fa fa-cube"></i>
							<label class="margin-top">
								Zona social
							</label>
						</div>
						<div class="col-md-8">
							@if (isset($zonas_comunes))
								<select class="form-control select-2 validate-input-6" name="zona_comun" id="zona_comun">
									@foreach ($zonas_comunes as $zona_comun)
										<option value="{{ $zona_comun->id }}">{{ $zona_comun->nombre }}</option>
									@endforeach
								</select>
							@endif
						</div>
					</div>
					<br>

					{{-- Propietario --}}
					<div class="row validate-label-7">
						<div class="col-md-4">
							<i class="fa fa-user"></i>
							<label class="margin-top">
								Propietario
							</label>
						</div>
						<div class="col-md-8">
							@if (isset($propietarios))
								<select class="form-control select-2 validate-input-7" name="propietario" id="propietario">
									@foreach ($propietarios as $propietario)
										<option value="{{ $propietario->id }}">{{ $propietario->nombre_completo }}</option>
									@endforeach
								</select>
							@endif
						</div>
					</div>
					<br>

					{{-- Motivo --}}
					<div class="row validate-label-1">
						<div class="col-md-4">
							<i class="fa fa-font"></i>
							<label class="margin-top">
								Motivo
							</label>
						</div>
						<div class="col-md-8">
							<textarea name="motivo" id="motivo" cols="30" rows="6" placeholder="Por favor escriba el motivo por el cual desea reservar esta zona social" class="form-control validate-input-1" autocomplete="off"></textarea>
						</div>
					</div>
					<br>
					{{-- Asistentes --}}
					<div class="row validate-label-8">
						<div class="col-md-4">
							<i class="fa fa-users"></i>
							<label class="margin-top">
								Número asistentes
							</label>
						</div>
						<div class="col-md-8">
							<input name="asistentes" id="asistentes" type="number" class="form-control validate-input-8">
						</div>
					</div>
					<br>
        			{{-- Fecha inicio--}}
					<div class="row validate-label-2">
						<div class="col-md-4">
							<i class="fa fa-calendar-check-o"></i>
							<label class="margin-top">
								Fecha Inicio
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control validate-input-2">
						</div>
					</div>
					<br>
					{{-- Hora inicio--}}
					<div class="row validate-label-3">
						<div class="col-md-4">
							<i class="fa fa-clock-o"></i>
							<label class="margin-top">
								Hora Inicio
							</label>
						</div>
						<div class="col-md-8">
							<select class="form-control validate-input-3" name="hora_inicio" id="hora_inicio">
								@for ($i = 0; $i < 24; $i++)
									<option value="{{ ($i<=9)? '0'.$i : $i  }}:00:00">{{ ($i<=9)? '0'.$i : $i  }}:00:00</option>
									<option value="{{ ($i<=9)? '0'.$i : $i  }}:30:00">{{ ($i<=9)? '0'.$i : $i  }}:30:00</option>
								@endfor
							</select>
						</div>
					</div>
					<br>
					{{-- Fecha fin--}}
					<div class="row validate-label-4">
						<div class="col-md-4">
							<i class="fa fa-calendar-times-o"></i>
							<label class="margin-top">
								Fecha Fin
							</label>
						</div>
						<div class="col-md-8">
							<input type="date" name="fecha_fin" id="fecha_fin" class="form-control validate-input-4">
						</div>
					</div>
					<br>

					{{-- Hora fin--}}
					<div class="row validate-label-5">
						<div class="col-md-4">
							<i class="fa fa-clock-o"></i>
							<label class="margin-top">
								Hora Fin
							</label>
						</div>
						<div class="col-md-8">
							<select class="form-control validate-input-5" name="hora_fin" id="hora_fin">
								@for ($i = 0; $i < 24; $i++)
									<option value="{{ ($i<=9)? '0'.$i : $i  }}:00:00">{{ ($i<=9)? '0'.$i : $i  }}:00:00</option>
									<option value="{{ ($i<=9)? '0'.$i : $i  }}:30:00">{{ ($i<=9)? '0'.$i : $i  }}:30:00</option>
								@endfor
							</select>
						</div>
					</div>
					<br>
					<div class="row">
						<div class="col-md-12 text-center">
							<button onclick="return registrarReserva();" type="button" class="btn btn-success">
		        				<i class="fa fa-send"></i>
		        				&nbsp; Guardar
		        			</button>
						</div>
					</div>
        		</form>
      		</div>
    	</div>
  	</div>
</div>