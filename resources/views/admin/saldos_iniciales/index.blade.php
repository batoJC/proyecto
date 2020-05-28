@extends('../layouts.app_dashboard_admin')

@section('title', 'Ejecución Presupuestal Individual')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Saldos iniciales</li>
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
						<a target="_blanck" href="https://youtu.be/e21dZi-_EIk">¿Qué puedo hacer?</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	@if(session('status'))
        <div class="alert alert-success-original alert-dismissible" role="alert">
            <button type="button" class="close btn_close_alert" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">x</span>
            </button>
            {{-- {!! html_entity_decode(session('status')) !!}
            {!! html_entity_decode(session('last')) !!} --}}
            <h4>
                {{ session('status') }}
            </h4>
            <h4>
                {{ session('last') }}
            </h4>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger-original alert-dismissible" role="alert">
            <button type="button" class="close btn_close_alert" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">x</span>
            </button>
            <h4>
                {{ session('error') }}
            </h4>
            <h4>
                {{ session('last') }}
            </h4>
        </div>
    @endif

	<a class="btn btn-success" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Agregar saldo inicial
	</a>
	<a class="btn btn-default" href="{{ url('masivo_saldos') }}"><i class="fa fa-upload"></i> Carga masiva</a>
	@include('admin.saldos_iniciales.form')
	<br><br>
	<table id="saldos-table" class="table">
		<thead>
			<th>Vigencia inicio</th>
			<th>Vigencia fin</th>
			<th>Concepto</th>
			<th>Unidad</th>
			<th>Valor</th>
			<th>Iteres</th>
			<th>Estado</th>
			<th>Acciones</th>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

					

@endsection
@section('ajax_crud')
	<script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
	<script>
		$(document).ready(function () {
			$('#valor_aux').maskMoney({precision:0});
		});
		
		var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.saldos.admin') }}",
                data: data,
                dataType: "json",
                success: function (response) {
                    callback(
                        response
                    );
                    $('[data-toggle="tooltip"]').tooltip({
                        container: 'body'
                    });
                }
            });
        }
        
        // Listar los registros
		// *************************************
		var table  = $('#saldos-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'vigencia_inicio', name: 'vigencia_inicio'},
          		{ data: 'vigencia_fin', name: 'vigencia_fin'},
          		{ data: 'concepto', name: 'concepto'},
          		{ data: 'unidad', name: 'unidad'},
          		{ data: 'valor', name: 'valor'},
          		{ data: 'interes', name: 'interes'},
          		{ data: 'estado', name: 'estado'},
          		{ data: 'action', name: 'action', orderable: false, searchable: false},
          	],
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

		


		 $(document).ready(function () {
            $('.select-multiple').select2({
                dropdownParent: $('#modal-form')
            });
        });

		// Select 2
		// ********

		$('.select-2').select2({
			// Este es el id de la ventana modal #modal-form
			dropdownParent: $('#modal-form')
		}); 

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
					url: "{{ url('saldos_iniciales') }}" + "/" + id,
					type: "POST",
					dataType: 'json',
					data:{
						'_method': 'DELETE',
						'_token' : csrf_token,
					},
					success: function(data){
						if(data.res){
							$('#modal-form').modal('hide');
							swal("Operación Exitosa", data.msg, "success")
								.then((value) => {
									table.ajax.reload();
							});
						}else{
							swal("Ocurrió un error", data.msg, "error");
						}
					}
				});
              } else {
                swal("Operación cancelada", "Lo sentimos, vuelve a intentarlo", "error");
              }
            });
		}


		// Agregar Registro
		// ****************
		function addForm(){
			save_method = "add";
			$('input[name="_method"]').val('POST');
			$('#modal-form form')[0].reset();
			$('#modal-form').modal('show');
			$('.modal-title').text('Agregar Saldo inicial');
		}

		// Evento submit del form
		// ----------------------

		$('#modal-form form').on('submit', function(e){
			e.preventDefault();

			if(verificarFormulario('form',5)){
				id = $('#id').val();
				if(save_method == "add") url = "{{ url('saldos_iniciales') }}";
				else url = "{{ url('saldos_iniciales') }}" + "/" + id;

				$.ajax({
					url: url,
					type: 'POST',
					data: new FormData($('#modal-form form')[0]),
					contentType: false,
					processData: false,
					dataType: 'json',
					success: function(data){
						// console.log(data);
						if(data.res){
							$('#modal-form').modal('hide');
							swal("Operación Exitosa", data.msg, "success")
								.then((value) => {
									$('#modal-form').modal('hide');
									table.ajax.reload();
							});
						}else{
							swal("Ocurrió un error", data.msg, "error");
						}
					}, 
					error: function(){
						swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
					}
				});
			}

		});
	</script>
@endsection