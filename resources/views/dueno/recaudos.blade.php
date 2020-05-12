@extends('../layouts.app_dashboard_dueno')

@section('title', 'Mis recibos')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('dueno') }}">Inicio</a>
                </li>
                  <li>Mis recibos</li>
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
        {{-- Variable de session para actualización --}}
        {{-- ************************************** --}}
        @if(session('status'))
            <div class="alert alert-error alert-dismissible" role="alert">
                <button type="button" class="close btn_close_alert" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">x</span>
                </button>
                {!! html_entity_decode(session('status')) !!}
            </div>
        @endif
        <br>
        {{-- ************************************** --}}
        
        <table id="recibos-table" class="table">
            <thead>
                <tr>
                    <th>Consecutivo</th>
                    <th>Fecha</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <thead>
            </thead>
        </table>
</div>


    
@endsection
@section('ajax_crud')
    <script>
        var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.recibos.dueno') }}",
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
						if((filas[i].cells[2].children[0].attributes.data.value=='1')){
							filas[i].className = 'bg-red';
						}
					}
                }
            });
        }
        
        // Listar los registros
		// *************************************
		var table  = $('#recibos-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'consecutivo', name: 'consecutivo'},
          		{ data: 'fecha', name: 'fecha'},
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
