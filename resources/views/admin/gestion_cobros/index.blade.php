@extends('../layouts.app_dashboard_admin')

@section('title', 'Gestion de Recaudo')

@section('content')
	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('admin') }}">Inicio</a>
		</li>
	  	<li>Gestión de Recaudo</li>
	</ul>				
	<a class="btn btn-success" onclick="addForm()" id="addForm">
		<i class="fa fa-plus"></i>
		Gestionar Recaudo
	</a>
	{{-- <a class="btn btn-info" onclick="addFormMasivo()">
		<i class="fa fa-file-archive-o"></i>
		&nbsp;
		Gestionar Recaudo Masivo
	</a> --}}
	@include('admin.gestion_cobros.form')
	@include('admin.gestion_cobros.masivo')

	<div class="row">
		<div class="col-12 col-md-6">
			<div class="col-md-4 error-validate-gestion-3">
				<i class="fa fa-building-o"></i>
				<label class="margin-top">
					Tipo de unidad
				</label>
			</div>
			<div class="col-md-8">
				<select name="unidad" id="unidad" class="form-control" onchange="changeUnidad(this);">
					<option value="">Seleccione...</option>
					@foreach($tipo_unidad as $aptoCliente)
						<option value="{{ $aptoCliente->id }}">{{ $aptoCliente->tipo_unidad.' - '.$aptoCliente->numero_letra }}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="col-12 col-md-6">
			<div class="col-md-4 error-validate-gestion-3">
				<i class="fa fa-sort-numeric-asc"></i>
				<label class="margin-top">
					Consecutivo
				</label>
			</div>
			<div class="col-md-8">
				<select name="consecutivo" id="consecutivo" class="form-control field-gestion-3">
					@foreach ($consecutivos as $consecutivo)
						<option value="{{ $consecutivo->id }}">{{ $consecutivo->prefijo }}  {{ $consecutivo->id_consecutivo }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
	<br><br>

	<div class="row">
		<div class="col-12 col-md-8">
			<h2>Saldo a Favor: <data id="saldo"></data></h2>
			<h2>Total a pagar: <data id="deuda"></data></h2>
		</div>
		<div class="col-12 col-md-4">
			<button id="pagoAutomatico" class="btn btn-success" style="display:none" onclick="cartera.pagarAutomatico()">Pagar Automático</button>
		</div>
		<div class="col-12">
			<h3 class="text-center">Cuentas por pagar</h3>
			<table class="table">
				<thead>
					<tr>
						<th>Fecha Vencimiento</th>
						<th>Descripción</th>
						<th>Costo</th>
						<th>Interes</th>
						<th>Operaciones</th>
					</tr>
				</thead>
				<tbody id="datosDeudas"></tbody>
			</table>
		</div>
		<br>
		<hr>
		<div class="col-12">
			<h3 class="text-center">Cuentas pagadas</h3>
			<table class="table">
				<thead>
					<tr>
						<th>Fecha</th>
						<th>Tipo</th>
						<th>Capital</th>
						<th>Interes</th>
						<th>Eliminar</th>
					</tr>
				</thead>
				<tbody id="datosPagos"></tbody>
			</table>
		</div>
		<br>
		<div class="col-12 text-center">
			<button onclick="guardarPagos();" class="btn btn-success">Guardar Pagos</button>
		</div>
	</div>
	<form id="data" style="display: none">
		<input type="hidden" id="form_unidad" name="unidad">
		<input type="hidden" id="form_consecutivo" name="consecutivo">
		<input type="hidden" id="form_pagos" name="pagos">
	</form>
	<br><br>
@endsection
@section('ajax_crud')
	<script>
		$(document).ready(function($) {
			// $('#addForm').click();
			$('#unidad').select2();
			$.ajaxSetup({
			  headers: {
			      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			  }
			});
		});
		
		// Eventos para el logo de la brand.
		// **********************************

		$('.btn-speacial').hide();
		$('#name-file').hide();

		$('.btn-logotype-brand').click(function(event) {
			
			$('.upload').click();

			$('#file-input').change(function(e) {
				console.log($(this).val());
				$('#name-file').text($(this).val());
				$('#name-file').fadeIn(400);
				$('.btn-speacial').fadeIn(600);
			    $('.btn-logotype-brand').fadeOut(200);
			});
		});

		$('.btn-speacial').click(function(event){
			$('#name-file').fadeOut(300);
			$('.btn-logotype-brand').fadeIn(200);
			$('.btn-speacial').hide();
			$('#file-input').val('');
		});


		// Validador de opciones
		// *********************

		$('#primero').on('change', function(event){
			valor_select_primero = $(this).val();
			$('#segundo option[value="'+valor_select_primero+'"]').hide();
			$('#tercero option[value="'+valor_select_primero+'"]').hide();
			$('#cuarto option[value="'+valor_select_primero+'"]').hide();
			$('#quinto option[value="'+valor_select_primero+'"]').hide();
		});

		$('#segundo').on('change', function(event){
			valor_select_segundo = $(this).val();
			if(valor_select_segundo != 'No aplica'){
				$('#primero option[value="'+valor_select_segundo+'"]').hide();
				$('#tercero option[value="'+valor_select_segundo+'"]').hide();
				$('#cuarto option[value="'+valor_select_segundo+'"]').hide();
				$('#quinto option[value="'+valor_select_segundo+'"]').hide();
			}
		});

		$('#tercero').on('change', function(event){
			valor_select_tercero = $(this).val();
			if(valor_select_tercero != 'No aplica'){
				$('#segundo option[value="'+valor_select_tercero+'"]').hide();
				$('#cuarto option[value="'+valor_select_tercero+'"]').hide();
				$('#primero option[value="'+valor_select_tercero+'"]').hide();
				$('#quinto option[value="'+valor_select_tercero+'"]').hide();
			}
		});

		$('#cuarto').on('change', function(event){
			valor_select_cuarto = $(this).val();
			if(valor_select_cuarto != 'No aplica'){
				$('#segundo option[value="'+valor_select_cuarto+'"]').hide();
				$('#tercero option[value="'+valor_select_cuarto+'"]').hide();
				$('#quinto option[value="'+valor_select_cuarto+'"]').hide();
				$('#primero option[value="'+valor_select_cuarto+'"]').hide();
			}
		});

		// Select 2
		// ********

		$('.select-2').select2({
			// Este es el id de la ventana modal #modal-form
			dropdownParent: $('#modal-form')
		});

		// Mostrar registro
		// ****************

		function addForm(){
			save_method = "add";
			$('input[name="_method"]').val('POST');
			$('#modal-form').modal('show');
			// ¿Qué pasará con este?
			// $('#modal-form form')[0].reset();
			$('.modal-title').text('Agregar Recaudo');
			$('#send_form').attr('type', 'button');
		}

		// Evento Submit del formulario
		// ****************************

		$('#modal-form form').on('submit', function(e){
			e.preventDefault();
			id = $('#id').val();

			if(save_method == "add") url = "{{ url('gestion_cobros') }}";
			else url = "{{ url('gestion_cobros') }}" + "/" + id;

			$.ajax({
				url: url,
				type: 'POST',
				data: $('#modal-form form').serialize(),
				success: function(data){
					if (data == "") {
						$('#modal-form').modal('hide');
						swal('Logrado!','Ingreso registrado correctamente','success').then(()=>{
							location.reload();
						});
					}
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
				}
			});
		});

		// Evento Reset del formulario
		// ****************************

		$('#modal-form form').on('reset', function(e){
			// Reiniciar las opciones para aparecer de nuevo
			// *********************************************
			$('#primero option').show();
			$('#segundo option').show();
			$('#tercero option').show();
			$('#cuarto option').show();
		});

		// Eventos de la modal masiva
		// **************************

		function addFormMasivo(){
			save_method = "masivo";
			$('input[name="_method"]').val('POST');
			$('#modal-form-masivo').modal('show');
			$('.modal-title').text('Agregar de Recaudo Masivo');
		}

		// Evento Submit del formulario
		// ****************************

		$('#modal-form-masivo form').on('submit', function(e){
			// Cargue del gif
			$('#gif-loading').fadeIn(200);

			e.preventDefault();

			if(save_method == "masivo") url = "{{ url('gestion_masivos') }}";

			$.ajax({
				url: url,
				type: 'POST',
				// Envio de formulario en objeto MUY OP
				// ************************************
				data: new FormData($('#modal-form-masivo form')[0]),
				contentType: false,
				processData: false,
				// ************************************
				success: function(data){
					$('#gif-loading').fadeOut(100);
					console.log(data);
					$('#modal-form').modal('hide');
					swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
						.then((value) => {
						location.replace("{{ url('gestion_masivos_insert') }}" + "/" + data);
					});
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
				}
			});
		});


		//cargar los cobros y saldo a favor para realizar los pagos
		//*********************************************************
		const changeUnidad = (unidad) => {
			cartera.limpiar();
			if (unidad.value != "") {
				$.ajax({
					url: 'cuentasRecaudo/'+unidad.value,
					type: 'POST',
					dataType: 'json',
				})
				.done(function(data) {
					console.log(data);
					cartera.deuda = data.deuda;
					cartera.saldo = data.saldo;
					$('#deuda').html(`$${new Intl.NumberFormat('COP').format(data.deuda)}`);
					$('#saldo').html(`$${new Intl.NumberFormat('COP').format(data.saldo)}`);
					$('#pagoAutomatico').css('display','block')
					for (var i = 0; i < data.cuotas.length; i++) {
						cartera.cargarCuenta(i+1,data.cuotas[i]);
					}
				})
				.fail(function(data) {
					console.log(data);
					console.log("error");
				})
				.always(function() {
					console.log("complete");
				});
			}else{
				
			}
		}

		//objeto para gestionar todos los pagos en la interfaz
		//****************************************************
		var cartera = {
			deuda: 0,
			saldo : 0,
			cuentasPagadas : new Array(),
			cuentas: new Array(),
			pagarAutomatico : () => {
				let indice = 0;
				while (indice < cartera.cuentas.length && cartera.saldo > 0){
					let cuenta = cartera.cuentas[indice];
					for (let i = 1; i < 4; i++) {
						if (cartera.saldo == 0) {
							break;
						}

						if((cuenta.interes+cuenta.saldo) == 0 ){
							break;
						}

						cartera.pagar(cuenta.serial,i);

					}
					indice++;
				}
				if (cartera.saldo == 0) {
					swal('Error!','El saldo a favor el insuficiente','error');
				}
			},
			pagar : (cuentaid,como) => {
				if (cartera.saldo <= 0) {
					swal('Error!','El saldo a favor el insuficiente','error');
					return;
				}
				let auxCuenta = cartera.buscarCuenta(cuentaid);
				
				switch(como) {
					case 1://todo
						if (cartera.saldo > (auxCuenta.saldo + auxCuenta.interes)) {
							cartera.saldo -= auxCuenta.saldo + auxCuenta.interes;
							cartera.deuda -= auxCuenta.saldo + auxCuenta.interes;
							let pago = cartera.buscarCuentaPagada(auxCuenta.serial);
							if (pago != null) {
								pago.saldo += auxCuenta.saldo;
								pago.interes += auxCuenta.interes;
								cartera.actualizarCuentaPagada(pago);
							}else{
								aux = Object.create(pago);
								aux.serial = auxCuenta.serial;
								aux.id = auxCuenta.id;
								aux.saldo = auxCuenta.saldo;
								aux.interes = auxCuenta.interes;
								aux.descripcion = auxCuenta.descripcion;
								aux.tipo = auxCuenta.tipo;
								aux.fecha = fechaHoy;
								cartera.cargarCuentaPagada(aux);					
							}
							auxCuenta.saldo = 0;
							auxCuenta.interes = 0;
						}else{
							swal('Error!','El saldo a favor el insuficiente','error');
							return;
						}
						break;
					case 2://interes
						if (cartera.saldo > auxCuenta.interes) {
							cartera.saldo -= auxCuenta.interes;
							cartera.deuda -= auxCuenta.interes;
							let pago = cartera.buscarCuentaPagada(auxCuenta.serial);
							if (pago != null) {
								pago.interes += auxCuenta.interes;
								cartera.actualizarCuentaPagada(pago);
							}else{
								aux = Object.create(pago);
								aux.serial = auxCuenta.serial;
								aux.id = auxCuenta.id;
								aux.interes = auxCuenta.interes;
								aux.saldo = 0;
								aux.fecha = fechaHoy;
								aux.descripcion = auxCuenta.descripcion;
								aux.tipo = auxCuenta.tipo;
								cartera.cargarCuentaPagada(aux);					
							}
							auxCuenta.interes = 0;
						}else{
							auxCuenta.interes -= cartera.saldo;
							cartera.deuda -= cartera.saldo;
							let pago = cartera.buscarCuentaPagada(auxCuenta.serial);
							if (pago != null) {
								pago.interes += cartera.saldo;
								cartera.actualizarCuentaPagada(pago);
							}else{
								aux = Object.create(pago);
								aux.serial = auxCuenta.serial;
								aux.id = auxCuenta.id;
								aux.interes = cartera.saldo;
								aux.saldo = 0;
								aux.fecha = fechaHoy;
								aux.descripcion = auxCuenta.descripcion;
								aux.tipo = auxCuenta.tipo;
								cartera.cargarCuentaPagada(aux);					
							}
							cartera.saldo = 0;
						}
						break;
					case 3://saldo
						if (cartera.saldo > auxCuenta.saldo ) {
							cartera.saldo -= auxCuenta.saldo;
							cartera.deuda -= auxCuenta.saldo;
							let pago = cartera.buscarCuentaPagada(auxCuenta.serial);	
							if (pago != null) {
								pago.saldo += auxCuenta.saldo;
								cartera.actualizarCuentaPagada(pago);
							}else{
								aux = Object.create(pago);
								aux.serial = auxCuenta.serial;
								aux.id = auxCuenta.id;
								aux.saldo = auxCuenta.saldo;
								aux.descripcion = auxCuenta.descripcion;
								aux.tipo = auxCuenta.tipo;
								aux.interes = 0;
								aux.fecha = fechaHoy;
								cartera.cargarCuentaPagada(aux);					
							}
							auxCuenta.saldo = 0;
						}else{
							auxCuenta.saldo -= cartera.saldo;
							cartera.deuda -= cartera.saldo;
							let pago = cartera.buscarCuentaPagada(auxCuenta.serial);	
							if (pago != null) {
								pago.saldo += cartera.saldo;
								cartera.actualizarCuentaPagada(pago);
							}else{
								aux = Object.create(pago);
								aux.serial = auxCuenta.serial;
								aux.id = auxCuenta.id;
								aux.saldo = cartera.saldo;
								aux.descripcion = auxCuenta.descripcion;
								aux.tipo = auxCuenta.tipo;
								aux.interes = 0;
								aux.fecha = fechaHoy;
								cartera.cargarCuentaPagada(aux);					
							}
							cartera.saldo = 0;
						}
						break;
				}

				$('#deuda'+auxCuenta.serial).html('');
				cartera.actualizarCuenta(auxCuenta);
				cartera.actualizar();

			},
			actualizar: () =>{
				$('#deuda').html(`$${new Intl.NumberFormat('COP').format(cartera.deuda)}`);
				$('#saldo').html(`$${new Intl.NumberFormat('COP').format(cartera.saldo)}`);
			},
			borrarPago : (serial) => {
				$('#pago'+serial).html('');
				let pago = cartera.buscarCuentaPagada(serial);
				let deuda = cartera.buscarCuenta(serial);
				cartera.saldo += pago.saldo + pago.interes;
				cartera.deuda += pago.saldo + pago.interes;
				deuda.saldo += pago.saldo;
				deuda.interes += pago.interes;
				pago.saldo = 0;
				pago.interes = 0;
				cartera.actualizar();
				cartera.actualizarCuentaPagada(pago);
				cartera.actualizarCuenta(deuda);
			},
			cargarCuenta: (serial,datos) => {
				let aux = Object.create(cuenta);
				aux.id = datos.id;
				aux.serial = serial;
				aux.saldo = datos.costo;
				aux.interes = datos.interes;
				aux.fecha = datos.fecha;
				aux.tipo = datos.tipo;
				aux.descripcion = datos.descripcion;
				cartera.cuentas.push(aux);
				`$${new Intl.NumberFormat('COP').format(data.deuda)}`
				$('#datosDeudas').append(
					`<tr id="deuda${aux.serial}">
						<td>${aux.fecha}</td>
						<td>${aux.descripcion}</td>
						<td>$${new Intl.NumberFormat('COP').format(aux.saldo)}</td>
						<td>$${new Intl.NumberFormat('COP').format(aux.interes)}</td>
						<td>
							<a onclick="cartera.pagar(${aux.serial},1)" class="btn btn-default">
								Pagar Todo
							</a>
							<a onclick="cartera.pagar(${aux.serial},2)" class="btn btn-default">
								Pagar Interes
							</a>
							<a onclick="cartera.pagar(${aux.serial},3)" class="btn btn-default">
								Pagar Saldo
							</a>
						</td>
					</tr>`);
			},
			cargarCuentaPagada: (datos) => {
				cartera.cuentasPagadas.push(datos);
				$('#datosPagos').append(
					`<tr id="pago${datos.serial}">
						<td>${datos.fecha}</td>
						<td>${datos.descripcion}</td>
						<td>$${new Intl.NumberFormat('COP').format(datos.saldo)}</td>
						<td>$${new Intl.NumberFormat('COP').format(datos.interes)}</td>
						<td>
							<a onclick="cartera.borrarPago(${datos.serial},3)" class="btn btn-default">
								Eliminar
							</a>
						</td>
					</tr>`);
			},
			actualizarCuenta: (datos) => {
				if ((datos.saldo  + datos.interes) == 0) {
					return;
				}
				$('#deuda'+datos.serial).html(
					`<td>${datos.fecha}</td>
					<td>${datos.descripcion}</td>
					<td>$${new Intl.NumberFormat('COP').format(datos.saldo)}</td>
					<td>$${new Intl.NumberFormat('COP').format(datos.interes)}</td>
					<td>
						<a onclick="cartera.pagar(${datos.serial},1)" class="btn btn-default">
							Pagar Todo
						</a>
						<a onclick="cartera.pagar(${datos.serial},2)" class="btn btn-default">
							Pagar Interes
						</a>
						<a onclick="cartera.pagar(${datos.serial},3)" class="btn btn-default">
							Pagar Saldo
						</a>
					</td>`);
				
			},
			actualizarCuentaPagada: (datos) => {
				if ((datos.saldo  + datos.interes) == 0) {
					return;
				}
				$('#pago'+datos.serial).html(
					`<td>${datos.fecha}</td>
					<td>${datos.descripcion}</td>
					<td>$${new Intl.NumberFormat('COP').format(datos.saldo)}</td>
					<td>$${new Intl.NumberFormat('COP').format(datos.interes)}</td>
					<td>
						<a onclick="cartera.borrarPago(${datos.serial},3)" class="btn btn-default">
							Eliminar
						</a>
					</td>`);
			},
			buscarCuenta: (serial) => {
				for (var i = 0; i < cartera.cuentas.length; i++) {
					if (cartera.cuentas[i].serial == serial) {
						return cartera.cuentas[i];
					}
				}
				return null;
			},
			buscarCuentaPagada: (serial) => {
				for (var i = 0; i < cartera.cuentasPagadas.length; i++) {
					if (cartera.cuentasPagadas[i].serial == serial) {
						return cartera.cuentasPagadas[i];
					}
				}
				return null;
			},
			limpiar: () => {
				cartera.deuda = 0;
				cartera.saldo = 0;
				cartera.cuentasPagadas = new Array();
				cartera.cuentas = new Array();
				$('#datosDeudas').html('');
				$('#datospagos').html('');
				$('#saldo').html('');
				$('#deuda').html('');
			},
			exportarOperaciones: () => {
				let pagos = '';
				for (var i = 0; i < cartera.cuentasPagadas.length; i++) {
					let e = cartera.cuentasPagadas[i];
					if ((e.saldo + e.interes) > 0) {
						pagos += `${e.tipo},${e.id},${e.saldo},${e.interes}][`;
					}
				}
				return pagos;
			}
		}

		var cuenta = {
			serial: 0,
			id: 1,
			saldo: 0,
			interes: 0,
			tipo: 0,
			fecha: "",
			descripcion: ""
		}

		var pago = {
			serial: 0,
			id: 1,
			saldo: 0,
			interes : 0,
			descripcion: '',
			tipo: 0,
			fecha: ''
		}

		var fechaHoy = '{{ date('Y M d') }}';

		function guardarPagos(){
			//verificar los daos
			var unidad = $('#unidad').val();
			var consecutivo = $('#consecutivo').val();
			var pagos = cartera.exportarOperaciones();

			if (unidad == "") {
				swal('Error!','Debe de seleccionar un tipo de unidad','error');
				return;
			}

			if (consecutivo == "") {
				swal('Error!','Debe de seleccionar un consecutivo','error');
				return;
			}

			if (pagos == "") {
				swal('Error!','Debe de realizar algún pago','error');
				return;
			}

			$('#form_unidad').val(unidad);
			$('#form_consecutivo').val(consecutivo);
			$('#form_pagos').val(pagos);

			swal({
				icon: 'info',
				title: 'Advertencia!',
				text: '¿Seguro de querer guardar los pagos realizados?',
				buttons: true
			}).then((res)=>{
				if (res) {
					$.ajax({
						url: 'guardarPagos',
						type: 'POST',
						dataType: 'json',
						formatType:false,
      					processData: false,
						data: $('#data').serialize(),
					})
					.done(function(data) {
						if (data == 1) {
							swal('Logrado!','Pagos guardados Correctamente','success').then(()=>{
								location.reload();
							});
						}
					});
					
				}
			});

		}


	</script>
@endsection