@extends('../layouts.app_dashboard_admin')

@section('title', 'Tipo de Unidad')
	
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Tipos de Unidad</li>
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
						<a target="_blanck" href="#">Video 1</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<a class="btn btn-success" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Agregar Tipo de Unidad
	</a>
	@include('admin.tipo_unidad.form')
	@include('admin.tipo_unidad.formResidentes')
	<br><br>
	<table id="tipo_unidad-table" class="table table-stripped">
		<thead>
			<th>Tipo De Unidad</th>
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
                url: "{{ url('api.tipos_unidad') }}",
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
		var table  = $('#tipo_unidad-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'nombre', name: 'nombre'},
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

		
		$('.select-2').select2({
			// Este es el id de la ventana modal #modal-form
			dropdownParent: $('#modal-form')
		});


		// Mostrar Registro
		// ****************
		function showForm(id){
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Tipo de Unidad');
			$('#send_form').hide();

			$.ajax({
				url: "{{ url('tipo_unidad') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					$('#modal-form form').trigger('reset');
					$('.check span').remove()
					var elem = document.querySelectorAll('.js-switch');
					elem.forEach((e)=>{
						new Switchery(e,{color:'#169F85'});
					})

					// Datos
					$('#nombre').val(data[0].nombre);
					$('#id').val(data[0].id);
					data[0].atributos.forEach(e => {
						$(`input[name='${e.nombre}']`).click();
					});
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, Este apartamento no existe", "error");
				}
			});
		}

		// Agregar registro
		// ****************

		function addForm(){
			save_method = "add";
			$('input[name="_method"]').val('POST');
			$('#modal-form').modal('show');
			$('#modal-form form').trigger('reset');
			$('.check span').remove()
			var elem = document.querySelectorAll('.js-switch');
			elem.forEach((e)=>{
				new Switchery(e,{color:'#169F85'});
			});
			$('.modal-title').text('Agregar tipo de unidad');
			$('#send_form').attr('type', 'button');
			$('#send_form').show();
		}

		// Editar Registro
		// ***************
		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Editar Unidad');
			$('#send_form').show();
			$('#send_form').attr('type', 'button');

			$.ajax({
				url: "{{ url('tipo_unidad') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Data
					$('#modal-form form').trigger('reset');
					$('.check span').remove()
					var elem = document.querySelectorAll('.js-switch');
					elem.forEach((e)=>{
						new Switchery(e,{color:'#169F85'});
					})
					$('#nombre').val(data[0].nombre);
					$('#id').val(data[0].id);
					data[0].atributos.forEach(e => {
						$(`input[name='${e.nombre}']`).click();
					});
				},
				error: function(){
					swal("¡Opps! Ocurrió un error", {
                          icon: "error",
                    });
				}
			});
		}

		// Eliminar Registro
		// *****************

		function deleteData(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar este apartamento?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
          		$.ajax({
					url : "{{ url('tipo_unidad') }}" + "/" + id,
					type: "POST",
					data: {
						'_method': 'DELETE',
						'_token' : csrf_token,
					},
					success: function(){
						swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
							.then((value) => {
								table.ajax.reload();
								$('#modal-form').modal('hide');
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

		// evento para guardar el tipo de unidad
        // ----------------------------------------------
		$('#modal-form form').on('submit', function(e){
			e.preventDefault();
			console.log($('#modal-form form').serialize());
			let coeficiente = $('input[name="coeficiente"]').prop('checked');
			let propietarios = $('input[name="propietario"]').prop('checked');
			if(coeficiente){
				if(!propietarios){
					swal('Error!','Si el tipo de unidad tiene un coeficiente debe de incluirse los propietarios.','error');
					return;
				}
			}else if(propietarios){
				swal('Error!','Si el tipo de unidad tiene una lista de propietarios debe de incluirse el coeficiente.','error');
				return;
			}

			id = $('#id').val();
			if(save_method == "add") url = "{{ url('tipo_unidad') }}";
			else url = "{{ url('tipo_unidad') }}" + "/" + id;

			$.ajax({
				url: url,
				type: 'POST',
				data: $('#modal-form form').serialize(),
				success: function(data){
					console.log(data);
					$('#modal-form').modal('hide');
					swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
						.then((value) => {
							table.ajax.reload();
					});
				},
				error: function(data){
					console.log(data);
					swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
				}
			});

		});

	</script>
@endsection