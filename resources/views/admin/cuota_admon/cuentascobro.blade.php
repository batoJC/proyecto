@extends('../layouts.app_dashboard_admin')
@section('title', 'Cuota Administrativa')

@section('content')
	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('admin') }}">Inicio</a>
		</li>
	  	<li>Cuentas de cobro</li>
	</ul>
	{{-- <a class="btn btn-warning" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Generar Cuotas Masivas
	</a> --}}
	{{-- lista de apartamentos --}}
	<form id="formulario" method="POST" action="{{ url('crearCuentaCobro') }}">
		@csrf
		<input type="hidden" id="cuentas" name="cuentas">
		<div class="row justify-content-center">
			<div class="col-0 col-md-2"></div>
			<div class="col-1 col-md-2 error-validate-8">
				<i class="fa fa-building-o"></i>
				<label class="margin-top">
					Tipo de unidad
				</label>
			</div>
			<div class="col-11 col-md-6">
				<select name="id_tipo_unidad" id="id_tipo_unidad" onchange="cargar(this)" class="form-control field-8 select-2">
					<option value="">Seleccione...</option>
					@foreach($unidades as $unidad)
						<option value="{{ $unidad->id }}">{{ $unidad->tipo_unidad.' - '.$unidad->numero_letra }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<br><br>
		<div class="row">
			<div class="col-12 col-md-2">
				<i class="fa fa-building-o"></i>
				<label class="margin-top">
					Consecutivo
				</label>
			</div>
			<div class="col-12 col-md-4">
				<select name="consecutivo" id="consecutivo" class="form-control field-8 select-2">
					<option value="">Seleccione...</option>
					@foreach($consecutivos as $consecutivo)
						<option value="{{ $consecutivo->id }}">{{ $consecutivo->prefijo.' - '.$consecutivo->id_consecutivo }}</option>
					@endforeach
				</select>
			</div>
			<div class="col-12 col-md-2">
				<i class="fa fa-building-o"></i>
				<label class="margin-top">
					Fecha
				</label>
			</div>
			<div class="col-12 col-md-4">
				<input id="fecha" name="fecha" type="date" class="form-control">
			</div>
		</div>

		<br>
		<br>
		<br>
		<table class="table" style="display: none">
			<thead>
				<tr>
					<th>Fecha de vencimiento</th>
					<th>Descripción</th>
					<th>Costo</th>
					<th>Interes</th>
				</tr>
			</thead>
			<tbody id="datos"></tbody>
		</table>

		<div class="row">
			<div class="col-12 text-center">
				<button onclick="return verificar();" class="btn btn-success">Generar Cuenta</button>
			</div>
		</div>
	</form>
@endsection
@section('ajax_crud')
	<script>
		$(document).ready(function() {
			$('#id_tipo_unidad').select2();
			$.ajaxSetup({
			    headers: {
			        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			    }
			});
		});


		const cargar = (unidad) => {
			if (unidad.value != "") {
				//ocultar la tabla
				$('.table').fadeOut(30,()=>{
					$('#datos').html('');
				});

				$('#cuentas').val('');

				//obtener los datos de las cuotas
				$.ajax({
					url: 'verCuotas/'+unidad.value,
					type: 'POST',
					dataType: 'json',
				})
				.done(function(data) {
					

					//agregar todas las cuotas
					for (var i = 0; i < data.cuotas.length; i++) {
						agregarCuota(data.cuotas[i]);
					}

					//agregar total
					$('#datos').append(`<tr><td colspan="3">Total a pagar</td><td>$${new Intl.NumberFormat('COP').format(data.total)}</td></tr>`);

					//mostrar la tabla
					$('.table').fadeIn(300);
					console.log(data);
					console.log("success");
				})
				.fail(function(data) {
					console.log(data);
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
			}else{
				$('#cuentas').val('');
				$('#datos').html('');
			}
		}

		//funcion para agregar datos en la tabla
		const agregarCuota = (cuota) =>{
			$('#datos').append(`<tr>
							<td>${cuota.fecha}</td>
							<td>${cuota.descripcion}</td>
							<td>$${new Intl.NumberFormat('COP').format(cuota.costo)}</td>
							<td>$${new Intl.NumberFormat('COP').format(cuota.interes)}</td>
						</tr>`);
			$('#cuentas').val($('#cuentas').val()+`${cuota.id},${cuota.tipo})(`);
		}


		const verificar = () => {
			//verificar que se selleccione 
			//fecha
			let fecha = $('#fecha').val();
			//consecutivo
			let consecutivo = $('#consecutivo').val();
			//tipo de unidad
			let tipo_unidad = $('#id_tipo_unidad').val();
			//cuentas
			let cuentas = $('#cuentas').val();

			if (fecha == "") {
				swal('Advertencia','Para generar una cuenta de cobro debe de seleccionar una fecha','warning');
				return false;
			}

			if (consecutivo == "") {
				swal('Advertencia','Para generar una cuenta de cobro debe de seleccionar una consecutivo','warning');
				return false;
			}

			if (tipo_unidad == "") {
				swal('Advertencia','Para generar una cuenta de cobro debe de seleccionar un tipo de unidad','warning');
				return false;
			}

			if (cuentas == "") {
				swal('Advertencia','Para generar una cuenta de cobro el tipo de unidad seleccionada debe de tener cuentas en deuda','warning');
				return false;
			}

			return true;
		}

	</script>
@endsection
