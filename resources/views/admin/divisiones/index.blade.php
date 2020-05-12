@extends('../layouts.app_dashboard_admin')

@section('title', 'Divisiones')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ asset('admin') }}">Inicio</a>
				</li>
				  <li>Divisiones</li>
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
						<a target="_blanck" href="https://youtu.be/kcMRw_3I2s0">¿Qué puedo hacer?</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<a class="btn btn-success" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Agregar División
	</a>
	@include('admin.divisiones.form')
	@include('admin.divisiones.formTipoDivisiones')
	<br><br>
	<table id="divisiones-table" class="table table-striped">
		<thead>
			<th>Tipo de división</th>
			<th>Numero / Letra</th>
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
                url: "{{ url('api.divisiones') }}",
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
		var table  = $('#divisiones-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'tipo_division', name: 'tipo_division'},
          		{ data: 'numero_letra', name: 'tipo'},
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


		//Mostrar el modal para insertar tipos de documentos
        /***************************************************/
        function checkTipo(e){
            let selected = $(e).val();
            if(selected == 'otro'){
                $('#modalAddTipoDivision').modal('show');
                $('#modal-form').modal('hide');
            }
        }

        //Guardar el tipo de documento
        /****************************/
        $('#formTipo').submit(function (e) { 
            e.preventDefault();

            //validar que el campo tipo no se encuentre vacío
            if($('.field-tipo').val() == ''){
                $('.field-tipo').css({
                    border: '1px solid rgba(244,67,54,.8)',
                });
                $('.error-validate-tipo').css({
                    color: 'rgba(244,67,54,.8)'
                });
                alertify.error("Estos campos son requeridos, por favor completelos.");
                return;
            } else {
                $('.field-tipo').css({
                    border: '1px solid rgba(76,175,80,.9)',
                });
                $('.error-validate-tipo').css({
                    color: 'rgba(76,175,80,.9)'
                });
            }

            $.ajax({
                type: "POST",
                url: "{{ url('tipo_divisiones') }}",
                data: $("#formTipo").serialize(),
                dataType: "json"
            }).done((data)=>{
                swal('Logrado!','tipo de division insertado correctamente','success').then(()=>{
                    $('#id_tipo_division').prepend(`<option value="${data.id}" selected>${data.division}</optio>`);
                    $('#modalAddTipoDivision').modal('hide');
                    $('#modal-form').modal('show');
                });
            }).fail((data)=>{
                // console.log(data);
            });
        });



		// Tabla para listar todos los registros
		// *************************************

		// var table = $('#divisiones-table').DataTable({
		// 	processing: true,
        //   	serverSide: true,
        //   	ajax: "{{ url('api.divisiones') }}",
        //   	columns: [
        //   		{ data: 'tipo_division', name: 'tipo_division'},
        //   		{ data: 'numero_letra', name: 'numero_letra'},
        //   		{ data: 'action', name: 'action', orderable: false, searchable: false,},
        //   	],
		// });

		// Agregar registro
		// ****************
		function addForm(){
			save_method = "add";
			$('input[name="_method"]').val('POST');
			$('#modal-form').modal('show');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Agregar Division');
			$('#send_form').attr('type', 'button');
		}

		// Edit form
		// *********
		function editForm(id){
			save_method = "edit";
			$('input[name="_method"]').val('PUT');
			$('#modal-form form')[0].reset();
			$('.modal-title').text('Editar Division');
			$('#send_form').attr('type', 'button');

			$.ajax({
				url: "{{ url('divisiones') }}" + "/" + id,
				type: "GET",
				dataType: "JSON",
				success: function(data){
					$('#modal-form').modal('show');
					// Data
					$('#id').val(data.id);
					$('#id_tipo_division').val(data.id_tipo_division);
					$('#numero_letra').val(data.numero_letra);
					$('#id_conjunto').val(data.id_conjunto);
				},
				error: function(){
					swal("¡Opps! Ocurrió un error", {
                          icon: "error",
                    });
				}
			});
		}

		// Eliminar registro
		// *****************
		function deleteData(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar esta division?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
          		$.ajax({
					url : "{{ url('divisiones') }}" + "/" + id,
					type: "POST",
					data: {
						'_method': 'DELETE',
						'_token' : csrf_token,
					}, 
					success: function(){
						swal("¡La division ha sido eliminada!", {
	                          icon: "success",
	                        }).then(()=>{
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

		// Abrir modal cuando haya evento submit
		// *************************************
		$('#modal-form form').on('submit', function(e){
			e.preventDefault();
			id = $('#id').val();
			if(save_method == "add") url = "{{ url('divisiones') }}";
			else url = "{{ url('divisiones') }}" + "/" + id;

			$.ajax({
				url : url,
				type: 'POST',
				data: $('#modal-form form').serialize(),
				success: function(data){
					$('#modal-form').modal('hide'),
					swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success").then(()=>{
						table.ajax.reload();
						$('#modal-form').modal('hide');
					});
				},
				error: function(){
					swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
				}
			});
		});
	</script>
@endsection