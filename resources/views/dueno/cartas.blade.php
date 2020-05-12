@extends('../layouts.app_dashboard_dueno')
<style>
    iframe{
        width: 100%;
        height: 600px;
    }

</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('home') }}">Inicio</a>
                </li>
                  <li>Cartas de ingreso y retiro</li>
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
    @include('admin.unidades.modalInfo')
    
    
    <br><br>
    <table id="cartas-table" class="table table-stripped">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Unidad</th>
                <th>Cuerpo</th>
                <th>Ver pdf</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>    
</div>

@endsection
@section('ajax_crud')

    <script>



        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        $(document).ready(function () {
            $('select').select2({
                dropdownParent: $('#cartas'),
            });

            var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.cartas.dueno') }}",
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
		var table  = $('#cartas-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'fecha', name: 'fecha'},
          		{ data: 'unidad', name: 'unidad'},
          		{ data: 'cuerpo', name: 'cuerpo'},
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

        });

        function openModalAddCarta(){
            $('#dataCarta').trigger('reset');
            $('#cartas').modal('show');
        }

        function loadDataCarta(id){
            $('#titulo_modal').text('Carta');
            $('#body_info').html(`<iframe id="frame" title="Pdf de la carta"
                src="{{url('cartas')}}/${id}?_token=${csrf_token}">
            </iframe>`);
            $('.info').modal('show');
        }

        function guardarCarta(){
            if(verificarFormulario('dataCarta',1)){
                $.ajax({
                    type: "POST",
                    url: "{{url('cartas')}}",
                    dataType: "json",
                    data: $('#dataCarta').serialize(),
                }).done((res)=>{
                    if(res.res){
                        swal('Logrado!',res.msg,'success').then(()=>{
                            $('#cartas').modal('hide');
                            table.ajax.reload();
                        });
                    }else{
                        swal('Error!',res.msg,'error');
                    }
                }).fail((res)=>{
                    swal('Error!','Ocurrió un error en el servidor, por favor intentelo más tarde.','error');
                });
            }

        }

    </script>

@endsection