@extends('../layouts.app_dashboard_admin')

@section('title', 'Ejecución Presupuestal Total')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Ejecución Presupuestal Total</li>
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
						<a target="_blanck" href="https://youtu.be/5PESDJA6KFY">¿Qué puedo hacer?</a>
						<a target="_blanck" href="https://youtu.be/_WUt7I2-FQU">¿Cómo cargar desde csv?</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<a class="btn btn-success" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Agregar Presupuestal Total
	</a>
	@include('admin.ejecucion_presupuestal_total.form')
	<br><br>
	<table id="presupuestos-table" class="table">
		<thead>
			<th>Tipo</th>
			<th>Fecha de Inicio (Vigencia)</th>
			<th>Fecha de Fin (Vigencia)</th>
			<th>Valor Total</th>
			<th>Total Ejecutado(%)</th>
			<th>Acciones</th>
		</thead>
		<tbody>
		</tbody>
	</table>
</div>

					
@endsection
@section('ajax_crud')
	<script>

		var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.presupuesto_total.admin') }}",
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
		var table  = $('#presupuestos-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'tipo', name: 'tipo'},
          		{ data: 'vigencia_inicio', name: 'vigencia_inicio'},
          		{ data: 'vigencia_fin', name: 'vigencia_fin'},
          		{ data: 'valor_total', name: 'valor_total'},
          		{ data: 'total_ejecutado', name: 'total_ejecutado'},
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

			
		});

		//carga de csv
		/**************/
		function cargarCSV(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			let data = new FormData();
			let archivo = $('#csv'+id)[0];
			data.append('id',id);
			data.append('archivo',archivo.files[0]);
			data.append('_token',csrf_token);
			$.ajax({
				type: "POST",
				url: "cargarPresupuestoCSV",
				data: data,
				contentType: false,
				processData: false,
				dataType: "json",
				success: function (res) {
					archivo.value = '';
					if(res.res){
						swal('Logrado!',res.msg,'success').then(res=>{
							table.ajax.reload();
						});
					}else{
						swal('Error!',res.msg,'error');
					}
				},
				error: function (){
					archivo.value = '';
				}
			});
		}


		// cargar detalles
		/*****************/
		function detalles(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			$.ajax({
				type: "POST",
				url: "{{url('detallePresupuestoTotal')}}"+"/"+id,
				data: {_token:csrf_token},
				dataType: "html",
				success: function (response) {
					$('#detalles').html(response);
					$('#modal-detalles').modal('show');
				}
			});
		}

		// Editar registro
		// ***************

		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Editar Ejecución Presupuestal Total');
			$('#send_form').show();
			$('#send_form').attr('type', 'button');
			$('.btn-logotype-brand').hide();
			$('.btn-speacial').show();

			$.ajax({
				url: "{{ url('ejecucion_pre_total') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Datos
					$('#id').val(data.id);
					$('#valor_total').val(data.valor_total);
					$('#fecha_inicio').val(data.fecha_inicio);
					$('#fecha_fin').val(data.fecha_fin);
					$('#tipo').val(data.tipo);
					$('#archivo').val(data.archivo);
					$('#name-file').text(data.archivo);
					$('#name-file').show();
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
					url: "{{ url('ejecucion_pre_total') }}" + "/" + id,
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


		// Agregar registro
		// ****************

		function addForm(){
			save_method = "add";
			$('input[name="_method"]').val('POST');
			$('#modal-form').modal('show');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Agregar Presupuestal Total');
			$('#send_form').show();
			$('#send_form').attr('type', 'button');
		}

		// Evento Submit del formulario
		// ----------------------------

		$('#modal-form form').on('submit', function(e){
			e.preventDefault();
			id = $('#id').val();
			if(save_method == "add") url = "{{ url('ejecucion_pre_total') }}";
			else url = "{{ url('ejecucion_pre_total') }}" + "/" + id;

			$.ajax({
				url: url,
				type: 'POST',
				// Envio de formulario en objeto
				// *****************************
				data: new FormData($('#modal-form form')[0]),
				contentType: false,
				processData: false,
				success: function(data){
					// console.log(data);
					$('#modal-form').modal('hide');
					console.log(data);
					swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
						.then((value) => {
							$('#modal-form').modal('hide');
							table.ajax.reload();
					});
				}, 
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
				}
			});
		});
	</script>
@endsection
