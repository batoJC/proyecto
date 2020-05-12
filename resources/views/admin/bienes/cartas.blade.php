@extends('../layouts.app_dashboard_admin')
<style>
    iframe{
        width: 100%;
        height: 600px;
    }

</style>
@section('content')


{{-- Modal para agregar cartas  --}}
<div id="cartas" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
      
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <h4 class="modal-title" >Agregar carta</h4>
            </div>
            <div class="modal-body">
              <form id="dataCarta">
                @csrf()

                <label class="validate-label-1" for="encabezado">Encabezado de la carta</label>
                <textarea class="form-control validate-input-1" name="encabezado" id="encabezado" cols="30" rows="10"></textarea>
                <br>

                <label class="validate-label-2" for="cuerpo">Cuerpo de la carta</label>
                <textarea class="form-control validate-input-2" name="cuerpo" id="cuerpo" cols="30" rows="10"></textarea>
                <br>

                <label for="unidad_id">Unidad</label>
                <select class="form-control select-2" name="unidad_id" id="unidad_id">
                    @foreach ($unidades as $unidad)
                <option value="{{ $unidad->id }}">{{ $unidad->tipo->nombre }} {{ $unidad->numero_letra }}</option>
                    @endforeach
                </select>
                <br>

              </form>
      
            </div>
            <div class="modal-footer">
              <button onclick="guardarCarta();" type="button" class="btn btn-primary">Guardar</button>
            </div>
      
        </div>
    </div>
</div>
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
    
    <button type="button" class="btn btn-success" onclick="openModalAddCarta()"><i class="fa fa-plus"></i> Agregar carta</button>
    <br>
    
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

        var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.cartas.admin') }}",
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


        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        $(document).ready(function () {
            $('.select-2').select2({
                dropdownParent: $('#dataCarta'),
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
                            table.ajax.reload();
                            $('#cartas').modal('hide');
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