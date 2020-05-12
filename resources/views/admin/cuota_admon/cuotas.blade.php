@extends('../layouts.app_dashboard_admin')
@section('title', 'Cuota Administrativa')

@section('content')
	<ul class="breadcrumb">
		<li>
			<a href="{{ asset('admin') }}">Inicio</a>
		</li>
		<li><a href="{{ url('cuota_admon') }}">Generar cuentas de cobro</a></li>
	  	<li>
	  		@switch($tipo)
	  		    @case(1)
	  		        Cobros Administrativos
	  		        @break
	  		    @case(2)
	  		        Cobros Extraordinarios
	  		        @break
	  		    @case(3)
	  		        Otros Cobros
	  		        @break
	  		    @case(4)
	  		        Multas
	  		        @break
	  		@endswitch	  		
	  	</li>
	</ul>
	@switch($tipo)
	    @case(1)
	    	{{-- CUOTAS ADMINITRATIVAS --}}
	        <table class="table datatable">
	        	<thead>
	        		<tr>
	        			<th>Costo</th>
						<th>Fecha de inicio (Vigencia)</th>
						<th>Fecha de fin (Vigencia)</th>
						<th>Acta</th>
						<th>Acciones</th>
	        		</tr>
	        	</thead>
	        	<tbody>
	        		@foreach($cuotas as $cuota)
				<tr>
					<td>{{ $cuota->cuotaOrd->costo }}</td>
					<td>{{ $cuota->cuotaOrd->fecha_vigencia_inicio }}</td>
					<td>{{ $cuota->cuotaOrd->fecha_vigencia_fin }}</td>
					<td>
						@if($cuota->cuotaOrd->id_acta != null)
							<a href="{{ asset('docs/'.$cuota->cuotaOrd->acta->archivo) }}" target="_blank">
								Visualizar &nbsp;
								<i class="fa fa-eye"></i>
							</a>
						@else
							No aplica
						@endif
					</td>
					<td>
						<a onclick="deleteData('{{ $cuota->id }}')" class="btn btn-default">
							<i class="fa fa-trash"></i>
						</a>
					</td>
				</tr>
			@endforeach
	        	</tbody>
	        </table>
	        @break
	    @case(2)
	    	{{-- CUOTAS EXTRORDINARIAS --}}
	        <table class="table datatable">
	        	<thead>
					<th>Costo (Iva incluido)</th>
					<th>Fecha de Vencimiento</th>
					<th>Tipo de Cuota Extraordinaria</th>
					<th>Estado</th>
					<th>Acta</th>
					<th>Acciones</th>
				</thead>
				<tbody>
					@foreach($cuotas as $cuota)
						<tr>
							<td>{{ $cuota->cuotaExt->costo }}</td>
							<td>{{ $cuota->cuotaExt->fecha_vencimiento }}</td>
							<td>
								@if($cuota->cuotaExt->tipo_cuota_extraordinaria != null)
									{{$cuota->cuotaExt->tipo_cuota_extraordinaria}}
								@else
									No aplica
								@endif
							</td>
							<td>{{ $cuota->cuotaExt->estado }}</td>
							<td>
								@if($cuota->cuotaExt->id_acta != null)
									<a href="{{ asset('docs/'.$cuota->cuotaExt->acta->archivo) }}" target="_blank">
										Visualizar &nbsp;
										<i class="fa fa-eye"></i>
									</a>
								@else
									No aplica
								@endif
							</td>
							<td>
								<a onclick="deleteData('{{ $cuota->id }}')" class="btn btn-default">
									<i class="fa fa-trash"></i>
								</a>
							</td>
						</tr>
					@endforeach
				</tbody>
	        </table>
	        @break
	    @case(3)
	    	{{-- OTROS COBROS --}}
	        <table class="table datatable">
	        	<thead>
					<th>Costo</th>
					<th>Fecha de Vencimiento</th>
					<th>Descipción (Concepto)</th>
					<th>Estado</th>
					<th>Acciones</th>
				</thead>
				<tbody>
					@foreach($cuotas as $cuota)
						<tr>
							<td>{{ $cuota->costo }}</td>
							<td>
								@if($cuota->fecha_vencimiento != null)
									{{ $cuota->fecha_vencimiento }}
								@else
									No aplica
								@endif
							</td>
							<td>{{ str_limit($cuota->descripcion, 50) }}</td>
							<td>{{ $cuota->estado }}</td>
							<td>
								<a onclick="deleteData('{{ $cuota->id }}')" class="btn btn-default">
									<i class="fa fa-trash"></i>
								</a>
							</td>
						</tr>
					@endforeach
				</tbody>
	        </table>
	        @break
	    @case(4)
	    	{{-- MULTAS --}}
	        <table class="table datatable">
	        	<thead>
					<th>Fecha</th>
					<th>Fecha de Vencimiento</th>
					<th>Descripcion</th>
					<th>Costo</th>
					<th>Resolucion</th>
					<th>Estado</th>
					<th>Usuario Multado</th>
					<th>Número de acta</th>
					<th>Acciones</th>
				</thead>
				<tbody>
					@foreach($cuotas as $cuota)
						<tr>
							<td>{{ $cuota->fecha }}</td>
							<td>
								@if($cuota->fecha_vencimiento != null)
									{{ $cuota->fecha_vencimiento }}
								@else
									No aplica
								@endif
							</td>
							<td>
								@if($cuota->descripcion != null)
									{{ str_limit($cuota->descripcion, 20) }}
								@else
									No aplica
								@endif
							</td>
							<td>{{ $cuota->costo }}</td>
							<td>
								@if($cuota->resolucion != null)
									{{ $cuota->resolucion }}
								@else
									No aplica
								@endif
							</td>
							<td>{{ $cuota->estado }}</td>
							<td>{{ $cuota->user->nombre_completo }}</td>
							<td>
								@if($cuota->id_acta != null)
									<a href="{{ asset('docs/'.$cuota->acta->archivo) }}" target="_blank">
										Visualizar &nbsp;
										<i class="fa fa-eye"></i>
									</a>
								@else
									No aplica
								@endif
							</td>
							<td>
								<a onclick="deleteData('{{ $cuota->id }}')" class="btn btn-default">
									<i class="fa fa-trash"></i>
								</a>
							</td>
						</tr>
					@endforeach
				</tbody>
	        </table>
	        @break
	@endswitch	
	

@endsection
@section('ajax_crud')
	<script>
		@switch($tipo)
  		    @case(1)
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
							url : "{{ url('cuota_ord') }}" + "/" + id,
							type: "POST",
							data: {
								'_method': 'DELETE',
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
  		        @break
  		    @case(2)
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
							url : "{{ url('cuota_ext_ord') }}" + "/" + id,
							type: "POST",
							data: {
								'_method': 'DELETE',
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
  		        @break
  		    @case(3)
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
							url : "{{ url('otros_cobros') }}" + "/" + id,
							type: "POST",
							data: {
								'_method': 'DELETE',
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
  		        @break
  		    @case(4)
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
							url : "{{ url('multas') }}" + "/" + id,
							type: "POST",
							data: {
								'_method': 'DELETE',
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
  		        @break
  		@endswitch
	</script>
@endsection
