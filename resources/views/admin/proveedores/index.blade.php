@extends('../layouts.app_dashboard_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('home') }}">Inicio</a>
                </li>
                <li>Proveedores</li>
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
        Agregar Proveedor
    </a>
    @include('admin.proveedores.form')
    <br><br>
    <table id="proveedores-table" class="table table-striped">
        <thead>
            <th>Nombre Completo</th>
            <th>Tipo de Documento</th>
            <th>Nro Documento / Nit</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Celular</th>
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
                url: "{{ url('api.proveedores') }}",
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
		var table  = $('#proveedores-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'nombre_completo', name: 'nombre_completo'},
          		{ data: 'tipo_documento', name: 'tipo_documento'},
          		{ data: 'documento', name: 'documento'},
          		{ data: 'email', name: 'email'},
          		{ data: 'telefono', name: 'telefono'},
          		{ data: 'celular', name: 'celular'},
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


        // Mostrar registro
        // ----------------
        function showForm(id){
        	$('#modal-form form')[0].reset(),
        	$('.element-hidden').hide();
            $('.modal-title').text('Proveedor');
            $('#send_form').hide();
            $.ajax({
                url: "{{ url('proveedores') }}" + "/" + id,
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    console.log(data);

                    $('#modal-form').modal('show');
                    // Data....
                    $('#nombre_completo').val(data.nombre_completo);
                    $('#tipo_documento').val(data.tipo_documento);
                    $('#documento').val(data.documento);
                    $('#email').val(data.email);
                    $('#telefono').val(data.telefono);
                    $('#direccion').val(data.direccion);
                    $('#celular').val(data.celular);
                },
                error: function(){
                    swal("Ocurrió un error", "Lo sentimos, Este usuario no existe", "error");
                }
            });
        }

        // Agregar registro 
        // ---------------

        function addForm(){
        	save_method = "add";
        	$('input[name=_method]').val('POST');
            $('#modal-form').modal('show');
            $('.element-hidden').hide();
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Agregar Proveedor');
            $('#send_form').attr('type', 'button');
        }

        // Editar registro
        // ---------------
        function editForm(id){
            save_method = "edit";
            $('input[name="_method"]').val('PUT');
            $('#modal-form form')[0].reset();
            $('.element-hidden').hide();
            $('.modal-title').text('Editar Proveedor');
            $('#send_form').show();
            $('#send_form').attr('type', 'button');
            $.ajax({
                url: "{{ url('proveedores') }}" + "/" + id + "/edit",
                type: "GET",
                dataType: "JSON",
                success: function(data){
                    $('#modal-form').modal('show');
                    // Data....
                    $('#id').val(data.id);
                    $('#nombre_completo').val(data.nombre_completo);
                    $('#tipo_documento').val(data.tipo_documento);
                    $('#documento').val(data.documento);
                    $('#email').val(data.email);
                    $('#telefono').val(data.telefono);
                    $('#direccion').val(data.direccion);
                    $('#celular').val(data.celular);
                },
                error: function(){
                    swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
                }
            });
        }

        // Eliminar registro
        // ---------------
        function deleteData(id){
            var csrf_token = $('meta[name="csrf-token"]').attr('content');
            swal({
              title: "¿Estás seguro?",
              text: "Ten en cuenta que este procedimiento es irreversible. ¿Deseas eliminar este usuario?",
              icon: "warning",
              buttons: true,
              dangerMode: true,
            })
            .then((willDelete) => {
              if (willDelete) {
                $.ajax({
                    url: "{{ url('proveedores') }}" + "/" + id,
                    type: "POST",
                    data: {
                          '_method': 'DELETE',
                          '_token' : csrf_token,
                    }, 
                    success: function(data){
                        swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
                            .then((value) => {
                                $('#modal-form').modal('hide');
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

        // Abrir la modal cuando pase algún evento
        // ---------------------------------------
        $('#modal-form form').on('submit', function(e){
        	e.preventDefault();
        	id = $('#id').val();
        	if(save_method == "add") url = "{{ url('proveedores') }}";
        	else url = "{{ url('proveedores') }}" + "/" + id;

        	$.ajax({
        		url: url,
        		type: 'POST',
        		data: $('#modal-form form').serialize(),
        		success: function(data){
        			$('#modal-form').modal('hide');
        			swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
                        .then((value) => {
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
