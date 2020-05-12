@extends('../layouts.app_dashboard_admin')

@section('title', 'Gestion de Recaudo Factura')

@section('content')
	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('admin') }}">Inicio</a>
		</li>
		<li>
			<a href="{{ asset('gestion_cobros') }}">Gestion de Recaudo</a>
		</li>
	  	<li>Totalidad de Cobros</li>
	</ul>
	<a class="btn btn-default" href="{{ asset('gestion_cobros') }}">
		<i class="fa fa-arrow-left"></i>
	</a>
	@if(isset($gest_cob_consult))
		@if($gest_cob_consult->saldo_total_operativo != $total_recaudado + $suma_de_saldos_favor)
			<button class="btn btn-success">
				Exportar Recibo &nbsp;
				<i class="fa fa-file-pdf-o"></i>
			</button>
		@else
			<button class="btn btn-success disabled">
				Exportar Recibo &nbsp;
				<i class="fa fa-file-pdf-o"></i>
			</button>
		@endif
	@else
		@if($gestion_cobros->saldo_total_operativo != $total_recaudado + $suma_de_saldos_favor)
			<button class="btn btn-success">
				Exportar Recibo &nbsp;
				<i class="fa fa-file-pdf-o"></i>
			</button>
		@else
			<button class="btn btn-success disabled">
				Exportar Recibo &nbsp;
				<i class="fa fa-file-pdf-o"></i>
			</button>
		@endif
	@endif
	<h2>
		Total Recaudado: 
		<strong>
			${{ $total_recaudado}}
		</strong>
	</h2>
	<h2>
		Saldo a Favor:
		<strong>
			${{ $suma_de_saldos_favor}}
		</strong>
	</h2>
	<h2>
		Total
		<strong>
			<span id="total">	
				{{ $total_recaudado + $suma_de_saldos_favor }}
			</span>
		</strong>
	</h2>
	<h2>
		De la unidad: 
		<strong>
			{{ $tipo_unidad->tipo_unidad.' - '.$tipo_unidad->numero_letra }}
		</strong>
	</h2>
	<hr>
	<h2>
		Saldo Operativo:
		<strong>
			@if(isset($gestion_cobros))
			{{-- ******************************************* --}}
				${{ $gestion_cobros->saldo_total_operativo }}
			@elseif(isset($gest_cob_consult))
			{{-- Validaciones op de existencia de variables  --}}
			{{-- ******************************************* --}}
				${{ $gest_cob_consult->saldo_total_operativo }}
			@else
			@endif
			{{-- ******************************************* --}}
		</strong>
	</h2>
	<br>
	{{-- ********************* --}}
	@if(count($multas) > 0)
		<div class="panel-group" id="accordion1">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse1" style="display:block;">
							<i class="fa fa-print"></i>
							&nbsp; Multas
						</a>
					</h4>
				</div>
				<div id="collapse1" class="panel-collapse collapse">
					<div class="panel-body">
						<table class="table datatable">
							<thead>
								<th>Fecha</th>
								<th>Descripcion</th>
								<th>Costo</th>
								<th>Accion</th>
							</thead>
							<tbody>
								@foreach($multas as $mutl)
									<tr>
										<td>{{$mutl->fecha}}</td>
										<td>{{$mutl->descripcion}}</td>
										<td>{{$mutl->costo}}</td>
										<td>
											{{-- Condicional para validar los botones por estados --}}
											{{-- ************************************************ --}}
											@if($mutl->estado_factura != null)
												<a class="btn btn-info disabled">
													<i class="fa fa-check"></i>
													Pagado
												</a>
											@else
												<a class="btn btn-success" onclick="payMulta({{ $mutl->id }})">
													<i class="fa fa-plus"></i>
													Pagar
												</a>
											@endif
											{{-- ************************************************ --}}
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	@else
	@endif
	{{-- ********************* --}}
	@if(count($cuota_adm_ext) > 0)
		<div class="panel-group" id="accordion2">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse2" style="display:block;">
							<i class="fa fa-usd"></i>
							&nbsp; Cuotas Extraordinarias
						</a>
					</h4>
				</div>
				<div id="collapse2" class="panel-collapse collapse">
					<div class="panel-body">
						<table class="table datatable">
							<thead>
								<th>Tipo de Cuota Extraordinaria</th>
								<th>Fecha de Vencimiento</th>
								<th>Descripcion</th>
								<th>Costo</th>
								<th>Accion</th>
							</thead>
							<tbody>
								@foreach($cuota_adm_ext as $extraord)
									<tr>
										<td>{{$extraord->tipo_cuota_extraordinaria}}</td>
										<td>{{$extraord->fecha_vencimiento}}</td>
										<td>{{$extraord->descripcion}}</td>
										<td>{{$extraord->costo}}</td>
										<td>
											{{-- Condicional para validar los botones por estados --}}
											{{-- ************************************************ --}}
											@if($extraord->estado_factura != null)
												<a class="btn btn-info disabled">
													<i class="fa fa-check"></i>
													Pagado
												</a>
											@else
												<a class="btn btn-success" onclick="payCuotExtr({{ $extraord->id }}, {{ $extraord->id_tipo_unidad }})">
													<i class="fa fa-plus"></i>
													Pagar
												</a>
											@endif
											{{-- ************************************************ --}}
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	@else
	@endif
	{{-- ********************* --}}
	@if(count($cuota_adm_ord) > 0)
		<div class="panel-group" id="accordion3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse3" style="display:block;">
							<i class="fa fa-usd"></i>
							&nbsp; Cuotas Ordinarias
						</a>
					</h4>
				</div>
				<div id="collapse3" class="panel-collapse collapse">
					<div class="panel-body">
						<table class="table datatable">
							<thead>
								<th>Saldo Vigente Real</th>
								<th>Periodo</th>
								<th>Accion</th>
							</thead>
							<tbody>
								@foreach($cuota_adm_ord as $ord)
									<tr>
										<td>{{$ord->saldo_vigente_real}}</td>
										<td>{{$ord->periodo}}</td>
										<td>
											{{-- Condicional para validar los botones por estados --}}
											{{-- ************************************************ --}}
											@if($ord->estado_factura != null)
												<a class="btn btn-info disabled">
													<i class="fa fa-check"></i>
													Pagado
												</a>
											@else
												<a class="btn btn-success" onclick="payCuotOrd({{ $ord->id }}, {{ $ord->id_tipo_unidad }})">
													<i class="fa fa-plus"></i>
													Pagar
												</a>
											@endif
											{{-- ************************************************ --}}
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	@else
	@endif
	{{-- ********************* --}}
	@if(count($otros_cobros) > 0)
		<div class="panel-group" id="accordion4">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h4 class="panel-title">
						<a data-toggle="collapse" data-parent="#accordion" href="#collapse4" style="display:block;">
							<i class="fa fa-usd"></i>
							&nbsp; Otros Cobros
						</a>
					</h4>
				</div>
				<div id="collapse4" class="panel-collapse collapse">
					<div class="panel-body">
						<table class="table datatable">
							<thead>
								<th>Costo</th>
								<th>Descripcion</th>
								<th>Accion</th>
							</thead>
							<tbody>
								@foreach($otros_cobros as $otros)
									<tr>
										<td>{{$otros->costo}}</td>
										<td>{{$otros->descripcion}}</td>
										<td>
											{{-- Condicional para validar los botones por estados --}}
											{{-- ************************************************ --}}
											@if($otros->estado_factura != null)
												<a class="btn btn-info disabled">
													<i class="fa fa-check"></i>
													Pagado
												</a>
											@else
												<a class="btn btn-success" onclick="payOtherPay({{ $otros->id }}, {{ $otros->id_tipo_unidad }})">
													<i class="fa fa-plus"></i>
													Pagar
												</a>
											@endif
											{{-- ************************************************ --}}
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	@else
	@endif
	
@endsection
@section('ajax_crud')
	<script>
		{{-- ******************************************* --}}
		@isset($gestion_cobros)
			id_gestion_cobros = "{{ $gestion_cobros->id }}";
		@endisset
		{{-- Validaciones op de existencia de variables  --}}
		{{-- ******************************************* --}}
		@isset($gest_cob_consult)
			id_gestion_cobros = "{{ $gest_cob_consult->id }}";
		@endisset
		{{-- ******************************************* --}}

		total = "{{ $total_recaudado + $suma_de_saldos_favor }}";

		// Ajax de las multas una a una
		// ****************************
		function payMulta(id){

			url = "{{ url('multas_una_una') }}";

			$.ajax({
				url: url,
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: {
					id : id,
					id_gestion_cobros : id_gestion_cobros,
					total : total
				},
				success:function(data){
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
		}

		// Ajax de las cuotas ordinarias una a una
		// ***************************************
		function payCuotOrd(id, id_tipo_unidad){

			url = "{{ url('cuotas_ord_una_una') }}";

			$.ajax({
				url: url,
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: {
					id : id,
					id_tipo_unidad: id_tipo_unidad,
					id_gestion_cobros : id_gestion_cobros,
					total : total
				},
				success:function(data){
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
		}

		// Ajax de las cuotas extra una a una
		// **********************************

		function payCuotExtr(id, id_tipo_unidad){
			
			url = "{{ url('cuotas_extr_una_una') }}";

			$.ajax({
				url: url,
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: {
					id: id,
					id_tipo_unidad: id_tipo_unidad,
					id_gestion_cobros : id_gestion_cobros,
					total : total
				},
				success:function(data){
					swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
							.then((value) => {
							location.reload();
						});
					console.log(data);
				},
				error: function(){
					swal("¡Opps! Ocurrió un error", {
	                      icon: "error",
	                    });
				}
			});
		}

		// Ajax de las cuotas otros cobros uno a uno
		// *****************************************

		function payOtherPay(id, id_tipo_unidad){

			url = "{{ url('otros_cobros_una_una') }}";

			$.ajax({
				url: url,
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: {
					id: id,
					id_tipo_unidad: id_tipo_unidad,
					id_gestion_cobros : id_gestion_cobros,
					total : total
				},
				success:function(data){
					swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
							.then((value) => {
							location.reload();
						});
					console.log(data);
				},
				error: function(){
					swal("¡Opps! Ocurrió un error", {
	                      icon: "error",
	                    });
				}
			});
		}

	</script>
@endsection