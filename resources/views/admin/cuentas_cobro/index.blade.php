@extends('../layouts.app_dashboard_admin')

@section('title', 'Consecutivos')
<style>
	iframe{
		height: 500px;
		width: 100%;
	}
	textarea{
        width: 100% !important;
    }
</style>
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Listar cuentas de cobro</li>
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
						<a target="_blanck" href="https://youtu.be/zMXDHQEhBv0">¿Qué son?</a>
						<a target="_blanck" href="https://youtu.be/HO9BUO6ve7Q">¿Cómo anular cuenta de cobro?</a>
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
						<input class="form-control" type="text" name="consecutivo" id="consecutivo">
					</div>
					<div class="col-md-2">
						<button type="button" onclick="verCuentaConsecutivo();" class="btn btn-success">
							<i class="fa fa-list-ol"></i>
							Listar
						</button>
					</div>
				</div>
				<div class="col-md-6 text-center">
					<div class="col-md-4 text-center validate-label-1">
						<i class="fa fa-user"></i>
						<label class="margin-top">
							Propietario
						</label>
					</div>
					<div class="col-md-6">
						<select name="propietario" id="propietario" class="form-control select-2">
							<option value="">Seleccione un propietario</option>
							@foreach($propietarios as $propietario)
								<option value="{{ $propietario->id }}">
									{{ $propietario->nombre_completo }} {{ $propietario->numero_cedula }}
								</option>
							@endforeach
						</select>
					</div>
					<div class="col-md-2">
						<button type="button" onclick="listarCuentasPropietario()" class="btn btn-success">
							<i class="fa fa-list-ol"></i>
							Listar
						</button>
					</div>
				</div>
			</div>
		</form>
		<div id="data_cuentas"></div>
		<br>
		<br>
		<br>
		<br>
	</div>
</div>

	

	
@endsection
@section('ajax_crud')
	<script>

		$('.select-2').select2();

		var csrf_token = $('meta[name="csrf-token"]').attr('content');

		/**
			$('#titulo_modal').text('Carta');
            $('#body_info').html(`<iframe id="frame" title="Pdf de la carta"
                src="{{url('cartas')}}/${id}?_token=${csrf_token}">
            </iframe>`);
            $('.info').modal('show');		
		*/

		function verCuentaConsecutivo(){
			if(consecutivo.value != ''){
				$.ajax({
					type: "POST",
					url: "{{url('searchCuentaCobro')}}",
					data: {
						consecutivo : consecutivo.value,
						_token : csrf_token
					},
					dataType: "json",
					success: function (res) {
						if(res.res){
							$('#data_cuentas').html(`<iframe id="frame" title="Pdf de la carta"
								src="{{url('pdfCuentasCobros')}}/${res.id}?_token=${csrf_token}">
							</iframe>`);
						}else{
							swal('','No existe ninguna cuenta de cobro con ese consecutivo.','info');
						}
					}
				}).fail(res=>{
					$('#data_cuentas').html(res);
				});
			}else{
				swal('Error!','Debe de ingresar el consecutivo de la cuenta de cobro!','error').then(()=>{
					consecutivo.focus();
				});
			}
		}

		function listarCuentasPropietario(){
			if(propietario.value != ''){
				$.ajax({
					type: "POST",
					url: "{{url('listarCuentasPropietario')}}/"+propietario.value,
					data: {
						_token : csrf_token
					},
					dataType: "html",
					success: function (response) {
						$('#data_cuentas').html(response);
						$('.table').DataTable({
							language: {
								"processing": "Procesando...",
								"search": "Buscar:",
								"lengthMenu": "Mostrando _MENU_ por página",
								"info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
								"infoEmpty": "Mostrando 0 de 0 registros",
								"infoFiltered": "(se han filtrado _MAX_ registros)",
								"infoPostFix": "",
								"loadingRecords": "Cargando...",
								"zeroRecords": "Ningún registro coincide con la búsqueda",
								"emptyTable": "Sin registros",
								"paginate": {
									"first": "Primero",
									"previous": "Anterior",
									"next": "Siguiente",
									"last": "Último"
								},
								"aria": {
									"sortAscending": ": aktywuj, by posortować kolumnę rosnąco",
									"sortDescending": ": aktywuj, by posortować kolumnę malejąco"
								}
							}
						});
						$('[data-toggle="tooltip"]').tooltip({
							container: 'body'
						});
					}
				});
			}else{
				swal('Error!','Debe de seleccionar un propietario!','error').then(()=>{
					propietario.focus();
				});
			}
		}


	</script>
	{{-- <script>
        let idCuentaCobro = null;
        let input;
        function deshacer(id){
            idCuentaCobro = id;
            input = document.createElement("textarea");
            input.placeholder = "Ingrese por favor el motivo de deshacer la anulación de esta cuenta de cobro.";

            swal({
                title: "Ingrese el motivo.",
                content: input,
                buttons: {
                    cancel:{
                        text:'Cancelar',
                        visible: true,
                        value: false
                    },
                    confirm: {
                        text: 'Guardar',
                        value: true
                    }
                }                       
            }).then((res)=>{
                if(res){
                    $('#loading').css("display", "flex")
                    .hide().fadeIn(800,()=>{
                        $.ajax({
                            type: "POST",
                            url: "{{url('deshacerAnulacionCuentaCobro')}}/"+idCuentaCobro,
                            data: {
                                _token : csrf_token,
                                motivo : input.value
                            },
                            dataType: 'json',
                            success: function (response) {
                                console.log(response);
                                $('#loading').fadeOut(800);
                            }
                        }).fail(res=>{
                            $('#loading').fadeOut(800);
                        });
                    })
                }
            });
            input.focus();
        }
    </script> --}}
@endsection
