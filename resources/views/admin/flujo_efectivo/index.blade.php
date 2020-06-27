@extends('../layouts.app_dashboard_admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <h4>Flujos Efectivo</h4>
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
                        <a target="_blanck" href="https://youtu.be/W2oLlu7m29I">¿Qué puedo hacer?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-6 col-md-1 text-center">
            <button onclick="openModalAdd(0)" class="btn btn-success">
                <h2>Ingreso</h2>
            </button>
        </div>
        <div class="col-6 col-md-1 text-center">
            <button onclick="openModalAdd(1)" class="btn btn-danger">
                <h2>Egreso</h2>
            </button>
        </div>
    </div>
    <br>
    <h3><span id="saldo_color"  class="@if ($saldoActual < 0)
        red
    @else
        green
    @endif">Saldo Actual: <span id="saldo_texto">${{ number_format($saldoActual) }}</span><span></h3>
    <br>
    
    
    {{-- ***************************************** --}}
    <!-- Modal -->
    <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content modal-padding">
                <div class="modal-header">
                    <button onclick="closeModal()" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                    <h4 class="text-center modal-title">
        
                    </h4>
                </div>
                <div class="modal-body">
                    <form id="formData" method="post">
                        @csrf {{ method_field('POST') }}
                        <input type="hidden" name="id" id="id">
                        <input type="hidden" name="tipo" id="tipo">
                        {{-- Cada campo --}}
                        <div class="row">
                            <div class="col-md-4 error-validate-1">
                                <i class="fa fa-calendar-check-o"></i>
                                <label class="margin-top">
                                    Fecha
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="date" class="form-control field-1" name="fecha" autocomplete="off" id="fecha">
                            </div>
                        </div>
                        <br>
                        {{-- Cada campo --}}
                        <div class="row">
                            <div class="col-md-4 error-validate-6">
                                <i class="fa fa-lock"></i>
                                <label class="margin-top">
                                    Concepto
                                </label>
                            </div>
                            <div class="col-md-8">
                                <textarea class="form-control field-6" placeholder="Ejemplo: Andrea Giraldo - Cuota Administrativa" name="concepto" id="concepto" cols="20" rows="6"></textarea>
                            </div>
                        </div>
                        <br>
                        {{-- Cada campo --}}
                        <div class="row">
                            <div class="col-md-4 error-validate-9">
                                <i class="fa fa-barcode"></i>
                                <label class="margin-top">
                                    Recibo
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control field-9" name="recibo" placeholder="Ejemplo: 0000001" autocomplete="off" id="recibo">
                            </div>
                        </div>
                        <br>
                        {{-- Cada campo --}}
                        <div class="row">
                            <div class="col-md-4 error-validate-5">
                                <i class="fa fa-usd"></i>
                                <label class="margin-top">
                                    Valor
                                </label>
                            </div>
                            <div class="col-md-8">
                                <input type="text" class="form-control field-5" onchange="changeValor(this,'valor');" name="valor_aux" placeholder="Ejemplo: 200000" autocomplete="off" id="valor_aux">
                                <input type="hidden" class="form-control" name="valor" id="valor">
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12 text-center">
                                <button type="button" class="btn btn-success" id="send_form">
                                    <i class="fa fa-send"></i>
                                    &nbsp; Enviar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <table id="flujos-table" class="table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Concepto</th>
                <th>Recibo</th>
                <th>Valor</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
    
@endsection

@section('ajax_crud')

    <script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#valor_aux').maskMoney({precision:0});
        });

        var actualizarTabla = (data,callback,settings) => {
            $.ajax({
                type: "GET",
                url: "{{ url('api.flujos.admin') }}",
                data: data,
                dataType: "json",
                success: function (response) {
                    callback(
                        response
                    );
                    $('[data-toggle="tooltip"]').tooltip({
                        container: 'body'
                    });
                    $.ajax({
                        type: "POST",
                        url: "{{url('api.saldo_actual.admin')}}",
                        data: {},
                        dataType: "json",
                        success: function (response) {
                            if(response.texto >= 0){
                                $('#saldo_color').removeClass('red');
                                $('#saldo_color').addClass('green');
                            }else{
                                $('#saldo_color').addClass('red');
                                $('#saldo_color').removeClass('green');
                            }
                            saldo_texto.innerHTML = response.texto;
                        }
                    });
                }
            });
        }
        
        // Listar los registros
		// *************************************
		var table  = $('#flujos-table').DataTable({
			processing: true,
          	serverSide: true,
          	ajax : actualizarTabla,
          	columns: [
          		{ data: 'fecha', name: 'fecha'},
          		{ data: 'concepto', name: 'concepto'},
          		{ data: 'recibo', name: 'recibo'},
          		{ data: 'valor', name: 'valor'},
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


    var actionModal = 'add';

    $(document).ready(function () {
        console.log($('[name=csrf-token]').attr('content'));
        $.ajaxSetup({
            headers: {'X-CSRF-Token': $('[name=csrf-token]').attr('content')}
        });
    });


    openModalAdd = (tipo) => {
        let title = (tipo == 1) ? 'Registrar flujo de egreso' : 'Registrar flujo de egreso' ;
        $('.modal-title').text(title);
        $('#tipo').val(tipo);
        $('#modal-form').modal('show');
        actionModal = 'add';
    }

    openModalEdit = (id) =>{
        $.ajax({
            type: "post",
            url: "{{url('showFlujo')}}/"+id,
            dataType: "json"
        }).done((data)=>{
            actionModal = 'edit';
            $('#id').val(data.id);
            $('#tipo').val(data.tipo);
            $('#fecha').val(data.fecha);
            $('#concepto').val(data.concepto);
            $('#recibo').val(data.recibo);
            $('#valor_aux').val(Math.round(data.valor));
            $('#modal-form').modal('show');
        });
    }

    eraser = (id) =>{
        swal({
            icon : 'warning',
            title : 'Advertencia!',
            text: '¿Seguro de querer eliminar este registro?',
            buttons: true
        }).then((res)=>{
            if(res){
                $.ajax({
                    type: "post",
                    url: "{{url('deleteFlujo')}}/"+id,
                    dataType: "json"
                }).done((data)=>{
                    console.log(data)
                    if(data == 1){
                        swal('Logrado','Registro Eliminado','success').then(()=>{
                            table.ajax.reload();
                            closeModal();
                        });
                    }else{
                        swal('Error!','No se logró eliminar el Registro','error');
                    }
                }).fail((data)=>{
                    console.log(data)
                });
            }else{
                swal('Information','Operación Cancelada','warning');
            }
        });
    }

    closeModal = () =>{
        $('#modal-form').modal('hide');
        $('#modal-form #formData').trigger('reset');
    }

    $('#modal-form #formData').on('submit', function(e){
			e.preventDefault();

            if(actionModal == 'add'){
                $.ajax({
                    type: "POST",
                    url: "{{ url('addFlujo') }}",
                    data: $('#formData').serialize(),
                    dataType: "json"
                }).done((data)=>{
                    console.log(data)
                    if(data == 1){
                        swal('Logrado!','Flujo de efectivo registrado','success').then(()=>{
                            table.ajax.reload();
                            closeModal();
                        });
                    }else{
                        swal('Error!','Ocurrio un Error al registrar','error');
                    }
                }).fail((data)=>{
                    console.error(data)
                });
            }else{
                $.ajax({
                    type: "POST",
                    url: "{{ url('editFlujo') }}",
                    data: $('#formData').serialize(),
                    dataType: "json"
                }).done((data)=>{
                    if(data == 1){
                        swal('Logrado!','Flujo de efectivo editado','success').then(()=>{
                            table.ajax.reload();
                            closeModal();
                        });
                    }else{
                        swal('Error!','Ocurrio un Error al editar','error');
                    }
                }).fail((data)=>{
                    console.error(data)
                });
            }

    });

</script>
@endsection