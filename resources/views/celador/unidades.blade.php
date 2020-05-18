@extends('../layouts.app_dashboard_celador')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url('home') }}">Inicio</a>
                </li>
                  <li>
                      <a href="{{ url('unidades_porteria') }}">Tipos de unidad</a>
                </li>
                <li>{{ ucfirst(strtolower($tipo->nombre)) }}</li>
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
    <h1 class="text-center">
        Listado de {{ strtolower($tipo->nombre) }}
    </h1>
    
    @include('celador.modalInfo')
    
    
    <div class="container-fluid">
        <br><br>
        <table id="unidades-table" class="table table-striped">
            <thead>
                <tr>
                    <th>Número / letra</th>
                    <th>División</th>
                    @if (in_array('observaciones', $atributos))
                        <th>Observaciones</th>
                    @endif
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
                url: "{{ url('api.unidades.porteria',['tipo'=>$tipo->id]) }}",
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
		var table  = $('#unidades-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'numero_letra', name: 'numero_letra'},
          		{ data: 'division', name: 'division'},
                @if (in_array('observaciones', $atributos))
                    { data: 'observaciones', name: 'observaciones'},
                @endif
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