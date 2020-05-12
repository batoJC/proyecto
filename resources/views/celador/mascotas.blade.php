@extends('../layouts.app_dashboard_celador')

@section('content')  
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('admin') }}">Inicio</a>
                </li>
                  <li>Mascotas</li>
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
    @include('celador.modalInfo')
    
    <br><br>
    <table id="mascotas-table" class="table table-stripped">
        <thead>
            <th>Unidad</th>
            <th>Código</th>
            <th>Foto</th>
            <th>Tipo</th>
            <th>Nombre</th>
            {{-- <th>Estado</th> --}}
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
                url: "{{ url('api.mascotas.porteria') }}",
                data: data,
                dataType: "json",
                success: function (response) {
                    callback(
                        response
                    );
                    $('[data-toggle="tooltip"]').tooltip({
                        container: 'body'
					});
					let filas = $('tbody [role="row"]');
					for (let i = 0; i < filas.length; i++) {
						let data = JSON.parse(filas[i].cells[2].innerText);
						if(data != null){
							filas[i].cells[2].innerHTML =  `<img class="foto show_img" src="imgs/private_imgs/${data}" alt="Foto">`;
							$('.show_img').click(function(e) {
								$('#show_image img')[0].src = $(e)[0].currentTarget.src;
								$('#show_image').css("display", "flex")
							.hide().fadeIn(400);
							});
						}else{
							filas[i].cells[2].innerText = 'No tiene';
						}
					}
                }
            });
        }
        
        // Listar los registros
		// *************************************
		var table  = $('#mascotas-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'unidad', name: 'unidad'},
          		{ data: 'codigo', name: 'codigo'},
          		{ data: 'foto', name: 'foto', orderable: false, searchable: false},
          		{ data: 'tipo', name: 'tipo'},
          		{ data: 'nombre', name: 'nombre'},
          		// { data: 'estado', name: 'estado'},
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

       

    </script>
@endsection