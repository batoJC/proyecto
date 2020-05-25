@extends('../layouts.app_dashboard_admin')

@section('title', 'Detalles de Egresos')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-11 col-md-11">
			<ul class="breadcrumb">
				<li>
					<a href="{{ url('admin') }}">Inicio</a>
				</li>
				  <li>Descuentos</li>
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
						<a target="_blanck" href="https://youtu.be/qqitLIY-ekw">¿Qué puedo hacer?</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<a class="btn btn-success" onclick="addForm()">
		<i class="fa fa-plus"></i>
		Agregar un descuento
	</a>
	<br><br>
	@include('admin.descuentos.form')
	<table id="descuentos-table" class="table">
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Unidad</th>
				<th>Propietario</th>
				<th>Valor</th>
				<th>Eliminar</th>
			</tr>
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
                url: "{{ url('api.descuentos.admin') }}",
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
		var table  = $('#descuentos-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'fecha', name: 'fecha'},
          		{ data: 'unidad', name: 'unidad'},
          		{ data: 'propietario', name: 'propietario'},
          		{ data: 'valor', name: 'valor'},
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

        var csrf_token = $('meta[name="csrf-token"]').attr('content');

		$('.select-2').select2({
			// Este es el id de la ventana modal #modal-form
			dropdownParent: $('#descuentos')
		});

		// Agregar registro
		// ****************
		function addForm(){
			$('#descuentos').modal('show');
			$('#descuentos form')[0].reset();
			$('.modal-title').text('Agregar Descuento');
		}


        function guardarRegistro(){
            if(verificarFormulario('descuentos',4)){
                $.ajax({
                    type: "POST",
                    url: "{{ url('descuentos') }}",
                    data: new FormData($('#descuentos form')[0]),
                    processData: false,
                    contentType: false,
                    dataType: "jaon"
                }).done(res=>{
                    console.log(res);
                }).fail(res=>{
                    console.log(res);
                });
            }
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
					url : "{{ url('descuentos') }}" + "/" + id,
					type: "POST",
					data: {
						'_method': 'DELETE',
						'_token' : csrf_token,
					},
					success: function(res){
						if(res.res){
							swal("Operación Exitosa", res.msg, "success")
								.then((value) => {
								table.ajax.reload();
							});
						}else{
							swal("Error", res.msg, "error");
						}
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


        function loadUnidades(){
            let propietario_id = user_id.value;
            $.ajax({
                type: "POST",
                url: "{{ url('unidadesPropietario') }}/"+propietario_id,
                data: {
                    _token:csrf_token
                },
                dataType: "json"
            }).done(res=>{
				$('#unidad_id').html('');
				interes.innerText = '';
				res.forEach(unidad => {
					$('#unidad_id').append(`<option value="${unidad.id}">${unidad.nombre}</option>`);
				});
				$('#unidad_id').select2({
					// Este es el id de la ventana modal #modal-form
					dropdownParent: $('#descuentos')
				});
				loadInteresesUnidad();
            }).fail(res=>{
                console.log(res);
            });
        }


		function loadInteresesUnidad(){
            let unidad = unidad_id.value;
			if(unidad == '') return;
			$.ajax({
				type: "POST",
				url: "{{ url('interesUnidad') }}/"+unidad,
				data: {
                    _token:csrf_token
                },
				dataType: "json"
			}).done(res=>{
				interes.innerText = `Los intereses en esa unidad suman: ${res.valor}`;
            }).fail(res=>{
                console.log(res);
            });
		}

		function guardar(){
			if(verificarFormulario('descuentos',3)){
				$.ajax({
					type: "POST",
					url: "{{ url('calcularValorDescuento') }}",
					data: new FormData(descuento),
					processData: false,
					contentType: false,
					dataType: "json"
				}).done(res=>{
					swal({
						title:'Advertencia',
						text:'¿Seguro de querer descontar $' + res.valor + ' a ' + res.propietario,
						icon: 'warning',
						buttons:true
					}).then(resp => {
						if(resp){
							$.ajax({
								type: "POST",
								url: "{{ url('descuentos') }}",
								data: new FormData(descuento),
								processData: false,
								contentType: false,
								dataType: "json"
							}).done(res=>{
								if(res.res){
									swal('Logrado!',res.msg,'success').then(()=>{
										table.ajax.reload();
										$('#descuentos').modal('hide');
									});
								}else{
									swal('Error!',res.msg,'error');
								}
							});
						}
					});
				});
			}
		}
		

	</script>
@endsection
