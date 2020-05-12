@extends('../layouts.app_dashboard_admin')
<style>
    textarea{
        width: 100% !important;
    }
</style>
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('admin') }}">Inicio</a>
                </li>
                  <li>Listar recaudos</li>
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
    <div class="row">
        <div class="col-md-6 text-center">
            <div class="col-md-6 text-center validate-label-1">
                <i class="fa fa-sort-numeric-asc"></i>
                <label class="margin-top">
                    Fecha inicio
                </label>
            </div>
            <div class="col-md-6">
                <input class="form-control" type="date" name="fecha_inicio" id="fecha_inicio">
            </div>
            <br>
            <br>
            <br>
            <div class="col-md-6 text-center validate-label-1">
                <i class="fa fa-sort-numeric-asc"></i>
                <label class="margin-top">
                    Fecha fin
                </label>
            </div>
            <div class="col-md-6">
                <input class="form-control" value="{{ date('Y-m-d') }}" type="date" name="fecha_fin" id="fecha_fin">
            </div>
            <br><br>
            <br>
            <div class="col-md-12">
                <button type="button" onclick="consultarRecaudos('fecha');" class="btn btn-success">
                    <i class="fa fa-list-ol"></i>
                    Listar
                </button>
            </div>
        </div>
        <div class="col-md-6 text-center">
            <div class="col-md-4 text-center validate-label-1">
                <i class="fa fa-user"></i>
                <label class="margin-top">
                    Propietario
                </label>
            </div>
            <div class="col-md-6">
                <select name="propietario" id="propietario" class="form-control select-2">
                    <option value="">Seleccione un propietario</option>
                    @foreach($propietarios as $propietario)
                        <option value="{{ $propietario->id }}">
                            {{ $propietario->nombre_completo }} {{ $propietario->numero_cedula }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" onclick="consultarRecaudos('propietario')" class="btn btn-success">
                    <i class="fa fa-list-ol"></i>
                    Listar
                </button>
            </div>
        </div>
    </div>
    
    <div class="container-fluid" id="recaudos">
    
    </div>
</div>

	

@endsection
@section('ajax_crud')
    <script>
        
        //inicializar los select
        $('.select-2').select2();

        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        function consultarRecaudos(tipo){
            let data;

            //validar
            if(tipo == 'fecha'){
                data = {
                    tipo : 'fechas',
                    _token : csrf_token,
                    fecha_inicio : fecha_inicio.value,
                    fecha_fin : fecha_fin.value
                }
            }else{
                data = {
                    tipo : 'propietario',
                    _token : csrf_token,
                    propietario : propietario.value
                }
            }

            $.ajax({
                type: "POST",
                url: "{{url('consultarRecaudos')}}",
                data: data,
                dataType: "html",
                success: function (response) {
                    // $('#recaudos').html(response).wait((res)=>{
                    //     console.log(res);
                    // });
                    document.querySelector('#recaudos').innerHTML = response;
                    iniciarTabla();
                }
            }).always(res=>{
                // iniciarTabla();
            });

        }

        function iniciarTabla(){
            $('table').DataTable({
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
            $('[data-toggle="tooltip"]').tooltip({
                container: 'body'
            });
        }

        
    </script>
    {{-- <script>
        let idRecaudo = null;
        let input;
        function deshacer(id){
            idRecaudo = id;
            input = document.createElement("textarea");
            input.placeholder = "Ingrese por favor el motivo de deshacer la anulación de este recaudo.";

            swal({
                title: "Ingrese el motivo.",
                content: input,
                buttons: {
                    cancel:{
                        text:'Cancelar',
                        visible: true,
                        value: false
                    },
                    confirm: {
                        text: 'Guardar',
                        value: true
                    }
                }                       
            }).then((res)=>{
                if(res){
                    $('#loading').css("display", "flex")
                    .hide().fadeIn(800,()=>{
                        $.ajax({
                            type: "POST",
                            url: "{{url('deshacerAnulacionRecaudo')}}/"+idRecaudo,
                            data: {
                                _token : csrf_token,
                                motivo : input.value
                            },
                            dataType: 'json',
                            success: function (response) {
                                console.log(response);
                                $('#loading').fadeOut(800);
                            }
                        }).fail(res=>{
                            $('#loading').fadeOut(800);
                        });
                    })
                }
            });
            input.focus();
        }
    </script> --}}
@endsection