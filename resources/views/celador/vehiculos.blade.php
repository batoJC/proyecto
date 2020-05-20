@extends('../layouts.app_dashboard_celador')

@section('content')  
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('dueno') }}">Inicio</a>
                </li>
                <li>Vehículos</li>
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
                        <a target="_blanck" href="https://youtu.be/rQyfv4zwHNg">¿Qué puedo hacer?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @include('celador.modalInfo')
    
    <br><br>
    <table id="vehiculos-table" class="table table-stripped">
        <thead>
            <tr>
                <th>Unidad</th>
                <th>Placa</th>
                <th>Foto vehículo</th>
                <th>Foto tarjeta 1</th>
                <th>Foto tarjeta 2</th>
                <th>Tipo</th>
                <th>Marca</th>
                <th>Color</th>
                {{-- <th>Estado</th> --}}
                <th>Opciones</th>
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
                url: "{{ url('api.vehiculos.porteria') }}",
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
						}else{
							filas[i].cells[2].innerText = 'No hay foto del vehículo';
						}

						//tarjeta 1
						data = JSON.parse(filas[i].cells[3].innerText);
						if(data != null){
							filas[i].cells[3].innerHTML =  `<img class="foto show_img" src="imgs/private_imgs/${data}" alt="Foto">`;
						}else{
							filas[i].cells[3].innerText = 'No registrada';
						}
						//tarjeta 2
						data = JSON.parse(filas[i].cells[4].innerText);
						if(data != null){
							filas[i].cells[4].innerHTML =  `<img class="foto show_img" src="imgs/private_imgs/${data}" alt="Foto">`;
						}else{
							filas[i].cells[4].innerText = 'No registrada';
						}
						$('.show_img').click(function(e) {
							$('#show_image img')[0].src = $(e)[0].currentTarget.src;
							$('#show_image').css("display", "flex").hide().fadeIn(400);
						});
					}
                }
            });
        }
        
        // Listar los registros
		// *************************************
		var table  = $('#vehiculos-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'unidad', name: 'unidad'},
          		{ data: 'placa', name: 'placa'},
          		{ data: 'foto_vehiculo', name: 'foto_vehiculo',orderable: false, searchable: false},
          		{ data: 'foto_tarjeta_1', name: 'foto_tarjeta_1',orderable: false, searchable: false},
          		{ data: 'foto_tarjeta_2', name: 'foto_tarjeta_2',orderable: false, searchable: false},
          		{ data: 'tipo', name: 'tipo'},
          		{ data: 'marca', name: 'marca'},
          		{ data: 'color', name: 'color'},
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