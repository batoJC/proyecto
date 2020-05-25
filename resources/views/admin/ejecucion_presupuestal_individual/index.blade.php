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
				  <li>Ejecución Presupuestal Individual</li>
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
						<a target="_blanck" href="https://youtu.be/rPsfNK8MmSo">¿Qué puedo hacer?</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<a class="btn btn-success" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Agregar Ejecución Presupuestal Individual
	</a>
	<a href="#" onclick="calcularCuotaAdministracion()" class="btn btn-default">
		<i class="fa fa-bar-chart"></i>
		Calcular cuota de administración
	</a>
	@include('admin.ejecucion_presupuestal_individual.form')
	<br><br>
	<table id="individuales-table" class="table">
		<thead>
			<th>Tipo</th>
			<th>Tipo de Ejecución</th>
			<th>Porcentaje del Total</th>
			<th>Porcentaje Ejecutado</th>
			<th>Valor Total</th>
			<th>Vigencia</th>
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
			$('#total_aux').maskMoney({precision:0});
		});

		var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.presupuesto_individual.admin') }}",
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
		var table  = $('#individuales-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'tipo_t', name: 'tipo_t'},
          		{ data: 'tipo_n', name: 'tipo_n'},
          		{ data: 'porcentaje', name: 'porcentaje'},
          		{ data: 'ejecutado', name: 'ejecutado'},
          		{ data: 'total', name: 'total'},
          		{ data: 'periodo', name: 'periodo'},
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
                dropdownParent: $('#modal-form'),
                multiple: true
            });
        });


		//archivos
		/*************/
		$('.btn-speacial').hide();
		$('#name-file').hide();

		$('.btn-logotype-brand').click(function(event) {
			
			$('.upload').click();

			$('#file-input').change(function(e) {
				// console.log($(this)[0].files.length);
				let cantidad = $(this)[0].files.length;
				if (cantidad > 1){
					$('#name-file').text(`Se han cargado ${$(this)[0].files.length} archivos.`);
				}else{
					$('#name-file').text(`Se ha cargado ${$(this)[0].files.length} archivo.`);
				}
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

		// Select 2
		// ********

		$('.select-2').select2({
			// Este es el id de la ventana modal #modal-form
			dropdownParent: $('#modal-form')
		}); 

		//unidades ecluidas
		/************************************/
		function unidades(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				type: "POST",
				url: "{{ url('unidadesExcluidasPresupuesto') }}/"+id,
				data: {
					_token:csrf_token
				},
				dataType: "html",
				success: function (response) {
					$('#modal-excluidas').modal('show');
					$('#excluidas').html(response);
				}
			});
		}

		//soportes del presupuesto individual
		/***********************************/
		function soportes(id){
			$.ajax({
				url: "{{ url('ejecucion_pre_individual') }}" + "/" +id,
				type: 'GET',
				dataType: 'JSON',
				success: function(data){
					if(data.soportes){
						let archivos = data.soportes.split(';');
						$('#modal-soportes').modal('show');
						$('#soportes').html('');
						archivos.forEach(e => {
							if(e != ""){
								$('#soportes').append(`<tr>
									<td>${e}</td>
									<td><a class="btn btn-default" target="_blank" href="docs/${e}"><i class="fa fa-eye"></i></a></td>
								</tr>`);
							}
						});
					}else{
						swal('Información','No se subió ningún soporte.','info');
					}
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Este registro no existe", "error");
				}
			});
		}

		//calcular cuotas de administración
		/**********************************/
		function calcularCuotaAdministracion(){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				type: "POST",
				url: "calcularCuotaAdministracion",
				data:{_token:csrf_token},
				success: function (response) {
					addForm();
					$('#total_aux').val(response);
				}
			});
		}

		// Editar Registro
		// ***************
		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Editar Ejecución Presupuestal Individual');

			$.ajax({
				url: "{{ url('ejecucion_pre_individual') }}" + "/" +id,
				type: 'GET',
				dataType: 'JSON',
				success: function(data){
					$('#modal-form').modal('show');
					// Datos
					$('#id').val(data.id);
					$('#porcentaje_total').val(data.porcentaje_total).focus();
					$('#id_tipo_ejecucion').val(data.id_tipo_ejecucion).focus();
					$('#id_ejecucion_pre_total').val(data.id_ejecucion_pre_total).focus();
					$('.select-2').select2({
						// Este es el id de la ventana modal #modal-form
						dropdownParent: $('#modal-form')
					});
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Este registro no existe", "error");
				}
			});
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
					url: "{{ url('ejecucion_pre_individual') }}" + "/" + id,
					type: "POST",
					data:{
						'_method': 'DELETE',
						'_token' : csrf_token,
					},
					success: function(){
						swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
							.then((value) => {
								table.ajax.reload();
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


		// Agregar Registro
		// ****************
		function addForm(){
			save_method = "add";
			$('input[name="_method"]').val('POST');
			$('#modal-form form')[0].reset();
			$('#modal-form').modal('show');
			$('.modal-title').text('Agregar Ejecución Presupuestal Individual');
		}

		// Evento submit del form
		// ----------------------

		$('#modal-form form').on('submit', function(e){
			e.preventDefault();
			id = $('#id').val();
			if(save_method == "add") url = "{{ url('ejecucion_pre_individual') }}";
			else url = "{{ url('ejecucion_pre_individual') }}" + "/" + id;

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
						console.log(data);
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
		});
	</script>
@endsection