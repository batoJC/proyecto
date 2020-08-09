@extends('../layouts.app_dashboard_admin')

@section('title', 'Generar liquidación')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-11 col-md-11">
                <ul class="breadcrumb">
                    <li>
                        <a href="{{ asset('admin') }}">Inicio</a>
                    </li>
                    <li>
                        <a href="{{ url('empleados_conjunto') }}">Empleados conjunto</a>
                    </li>
                    <li>
                        <a href="{{ url('liquidador',['empleado' => $empleado->id]) }}">Liquidador de nómina</a>
                    </li>
                      <li>Lista de liquidaciones</li>
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
        <div class="container-fluid bg-white">
            <h3>{{ $empleado->nombre_completo }}</h3>
            <p>
                Identificación: {{ $empleado->cedula }} <br>
                Salario: ${{ number_format($empleado->salario) }}
            </p>
            <br>
            <table id="liquidaciones-table" class="table table-stripped">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Consecutivo</th>
                        <th>Periodo</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>

    </div>

@endsection
@section('ajax_crud')
	<script>

    var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.liquidaciones.admin') }}/{{ $empleado->id }}",
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
		var table  = $('#liquidaciones-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'fecha', name: 'fecha'},
          		{ data: 'tipo', name: 'tipo'},
          		{ data: 'consecutivo', name: 'consecutivo'},
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


        // Eliminar registro
		// *****************
		function deleteData(id){
			var csrf_token = $('meta[name="csrf-token"]').attr('content');
			swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar esta liquidación?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
	          	$.ajax({
					url: "{{ url('liquidacion') }}" + "/" + id,
					type: "POST",
					dataType: 'json',
					data:{
						'_method': 'DELETE',
						'_token' : csrf_token,
					},
					success: function(data){
						if(data.res == 1){
							swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
								.then((value) => {
									table.ajax.reload();
							});
						}else{
							swal("Error!", data.msg, "error");
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
     

    </script>
@endsection
