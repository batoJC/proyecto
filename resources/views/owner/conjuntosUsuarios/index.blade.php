@extends('../layouts.app_dashboard')

@section('title', 'Usuarios')

@section('content')
	<div class="right_col" role="main">
        <div class="row">
            <div class="col-md-12">
            	<ul class="breadcrumb">
					<li>
						<a href="{{ asset('home') }}">Inicio</a>
					</li>
					<li>
						<a href="{{ asset('usuarios') }}">Administradores</a>
					</li>
				  	<li>Administrador a Conjuntos</li>
				</ul>
				<a class="btn btn-default" href="{{ url('usuarios') }}">
					<i class="fa fa-arrow-left"></i>
				</a>				
				<a class="btn btn-success" onclick="addForm()">
					<i class="fa fa-plus"></i>
					Agregar Administrador a Conjunto
				</a>
				@include('owner.conjuntosUsuarios.form')
				<br><br>	
				<table class="table datatable">
					<thead>
                        <tr>
                            <th>Administrador</th>
                            <th>Conjunto</th>
                            <th>Acciones</th>
                        </tr>
					</thead>
					<tbody>
                        @foreach($conjuntos_usuarios as $conjuntUsuari)
                            <tr>
                                <td>{{ $conjuntUsuari->usuario->nombre_completo }}</td>
                                <td>{{ $conjuntUsuari->conjunto->nombre }}</td>
                                <td>
                                    <a onclick="deleteData({{$conjuntUsuari->id}})" class="btn btn-default">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
					</tbody>
				</table>
            </div>
        </div>
    </div>
@endsection
@section('ajax_crud')
	<script>
		// Select 2
		$('.select-2').select2({
			// Este es el id de la ventana modal #modal-form
			dropdownParent: $('#modal-form')
		});
		
		// Agregar registro 
        // ---------------
        function addForm(){
        	save_method = "add";
	        $('input[name=_method]').val('POST');
            $('.password-div').show();
            $('input[name="password"]').addClass('field-3');
            $('#modal-form').modal('show');
            $('.element-hidden').hide();
            $('#modal-form form')[0].reset();
            $('.modal-title').text('Agregar');
            $('#send_form').attr('type', 'button');
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
                    url: "{{ url('conjuntos_usuarios') }}" + "/" + id,
                    type: "POST",
                    data: {
                          '_method': 'DELETE',
                          '_token' : csrf_token,
                    }, 
                    success: function(data){
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
        // Abrir la modal cuando pase algún evento
        // ---------------------------------------
        $('#modal-form form').on('submit', function(e){
        	e.preventDefault();
        	id = $('#id').val();
        	if(save_method == "add") url = "{{ url('conjuntos_usuarios') }}";

        	$.ajax({
        		url: url,
        		type: 'POST',
        		data: $('#modal-form form').serialize(),
        		success: function(data){
        			$('#modal-form').modal('hide');
        			// Validador si los registros están duplicados
        			// *******************************************
					if(data == 'Error'){
						swal("Operación Exitosa", "Ya hay un registro con estas características, por favor verifique", "error");
					} else {
	        			swal("Operación Exitosa", "El procedimiento se ha llevado acabo con éxito", "success")
							.then((value) => {
							location.reload();
						});
					}
        		},
        		error: function(){
	        		swal("Ocurrió un error", "Lo sentimos, vuelve a intentarlo", "error");
        		}
        	});
        });
	</script>
@endsection