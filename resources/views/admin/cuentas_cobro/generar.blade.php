@extends('../layouts.app_dashboard_admin')

@section('title', 'Consecutivos')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Generar cuentas de Cobro</li>
			</ul>
		</div>
		<div class="col-1 col md-1 text-right">
			<div class="btn-group">
				<i  data-placement="left" 
					title="Ayuda" 
					data-toggle="dropdown" 
					type="button" 
					aria-expanded="false"
					class="fa blue fa-question-circle-o ayuda">
				</i>
				<ul role="menu" class="dropdown-menu pull-right">
					<li>
						<a target="_blanck" href="https://youtu.be/XzGd2Ht2GV4">¿Cómo generar cuentas de cobro?</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	{{-- Variables de estado --}}
	{{-- ******************* --}}
	@if(session('status'))
		<div class="alert alert-success-original alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">x</span>
			</button>
			{!! html_entity_decode(session('status')) !!}	
		</div>
	@endif
	{{-- ******************* --}}
	<br><br>
	{{-- @include('admin.cuentas_cobro.form') --}}
	
	<div class="container">
		<form id="data">
			<div class="row">
				<div class="col-md-6 text-center">
					<div class="col-md-4 text-center validate-label-1">
						<i class="fa fa-sort-numeric-asc"></i>
						<label class="margin-top">
							Consecutivo
						</label>
					</div>
					<div class="col-md-6">
						<select name="consecutivo" id="consecutivo" class="form-control select-2 validate-input-1">
							<option value="">Seleccione un consecutivo</option>
							@foreach($consecutivos as $consecutivo)
								<option value="{{ $consecutivo->id }}">
									{{ $consecutivo->prefijo }} {{ $consecutivo->numero }}
								</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="col-md-6 text-center">
						<input onchange="showDivProntoPago(this);" onchange="" type="checkbox" name="pronto_pago" id="pronto_pago">
						<label for="pronto_pago" class="margin-top">
							Habilitar pronto pago
						</label>
				</div>
			</div>
			<br>
			<div class="row hide" id="div_pronto_pago">
				<div class="col-md-6 text-center">
					<div class="col-md-4 text-center validate-label-2">
						<i class="fa fa-calendar-check-o"></i>
						<label class="margin-top">
							Fecha pronto pago
						</label>
					</div>
					<div class="col-md-6">
						<input name="fecha_pronto_pago" id="fecha_pronto_pago" type="date" class="form-control  validate-input-2">
					</div>
				</div>
				<div class="col-md-6 text-center">
					<div class="col-md-7 text-center validate-label-3">
						<i class="">%</i>
						<label class="margin-top">
							Porcentaje de descuento
						</label>
					</div>
					<div class="col-md-5">
						<input name="descuento" id="descuento" type="number" class="form-control validate-input-3">
					</div>
				</div>
			</div>
			<br>
		</form>
		<div class="row">
			<div class="col-12 text-center">
				<button onclick="visualizar();" class="btn btn-success">Generar todas las cuentas de cobro</button>
			</div>
		</div>
		<div id="data_cuentas"></div>
	</div>
</div>



	
@endsection
@section('ajax_crud')
	<script>

		$('.select-2').select2();

		var csrf_token = $('meta[name="csrf-token"]').attr('content');

		// generar vista de las cuentas de cobro
		//--------------------------------------
		function visualizar(){

			let campos = (pronto_pago.checked)? 3: 1;

			if(verificarFormulario('data',campos)){
				$.ajax({
					type: "POST",
					url: "{{ url('visualizarCuentasCobro') }}",
					data: {
						_token : csrf_token,
						consecutivo : consecutivo.value,
						fecha_pronto_pago : fecha_pronto_pago.value,
						descuento : descuento.value
					},
					dataType: "html",
					success: function (response) {
						// console.log(response);
						$('#data_cuentas').html(response);
					}
				});
			}
		}


		function showDivProntoPago(check){
			if(check.checked){
				div_pronto_pago.classList.remove('hide');
			}else{
				div_pronto_pago.classList.add('hide');
			}
		}



		// Eliminar registro
		// *****************
		function deleteData(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar este registro?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
          		$.ajax({
					url : "{{ url('eliminarCuentaCobro') }}" + "/" + id,
					type: "POST",
					data: {
						'_token' : csrf_token,
					},
					success: function(){
						swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
							.then((value) => {
							location.reload();
						});
					},
					error: function(){
						swal("¡Opps! Ocurrió un error", {
		                      icon: "error",
		                    });
					}
				});
              } else {
                swal("Operación cancelada", "Lo sentimos, vuelve a intentarlo", "error");
              }
            });
		}

		// cargar cuentas de cobro
		//*************************
		function loadData(id,unidad,consecutivo){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				url: 'consultarCobro/'+id,
				type: 'POST',
				dataType: 'json',
				data: {'_token': csrf_token},
			})
			.done(function(data) {
				$('#pagosData').html('');
				console.log(data);
				$('#fecha').val(data.cuenta.fecha);
				$('#unidad').val(unidad);
				$('#consecutivo').val(consecutivo);
				for (var i = 0; i < data.cuotas.length; i++) {
					let cuenta = data.cuotas[i];
					let total = parseInt(cuenta.costo) + parseInt(cuenta.interes);
					$('#pagosData').append(`
						<tr>
							<td>${cuenta.fecha1}</td>
							<td>${cuenta.fecha2}</td>
							<td>${cuenta.descripcion}</td>
							<td>$${new Intl.NumberFormat('COP').format(cuenta.costo)}</td>
							<td>$${new Intl.NumberFormat('COP').format(cuenta.interes)}</td>
							<td>$${new Intl.NumberFormat('COP').format((total))}</td>
						</tr>`);
				}
				$('#pagosData').append(`
						<tr>
							<td colspan="4" style="text-align:right;">Total a pagar:</td>
							<td colspan="2" style="text-align:right;">$${new Intl.NumberFormat('COP').format(data.deuda)}</td>
						</tr>`);
			})
			.fail(function(data) {
				// console.log(data);
				console.log("error");
			})
			.always(function() {
				console.log("complete");
			});
			
			$('#modal-form').modal('show');
		}

	</script>
@endsection
