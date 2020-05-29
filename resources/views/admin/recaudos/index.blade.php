@extends('../layouts.app_dashboard_admin')

@section('content')



<div class="container-fluid">
    <div class="row">
        <div class="col-11 col-md-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ asset('admin') }}">Inicio</a>
                </li>
                  <li>Recaudos</li>
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
                        <a target="_blanck" href="https://youtu.be/-VNfTEp6nrk">¿Cómo agregar recaudo?</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @if ($error == 'fecha')
        <div class="alert alert-success-original alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">x</span>
            </button>
            <h1 class="text-center">Aún no se han generado ninguna cuenta de cobro.</h1>
        </div>
    @else
        <form id="data" class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-4 text-left">
                        <i class="fa fa-calendar"></i>
                        <label class="margin-top">
                            Fecha
                        </label>
                    </div>
                    <div class="col-md-8">
                    <input min="{{ $fecha }}" id="fecha" name="fecha" type="date" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-4 text-left validate-label-1" >
                        <i class="fa fa-sort-numeric-asc"></i>
                        <label class="margin-top">
                            Consecutivo
                        </label>
                    </div>
                    <div class="col-md-8">
                        <select name="consecutivo" id="consecutivo" class="form-control validate-input-1">
                            <option value="">Seleccione un consecutivo</option>
                            @foreach ($consecutivos as $consecutivo)
                                <option value="{{ $consecutivo->id }}">{{ $consecutivo->prefijo.'-'.$consecutivo->numero }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-4 text-left validate-label-2">
                        <i class="fa fa-building-o"></i>
                        <label class="margin-top">
                            Unidades
                        </label>
                    </div>
                    <div class="col-md-8">
                        <select name="propietario" id="propietario" class="form-control select-2 validate-input-2">
                            <option value="">Seleccione una unidad del propietario</option>
                            @foreach ($propietarios as $propietario)
                                @foreach ($propietario->unidades()->where('estado','Activo')->get() as $unidad)
                                    <option value="{{ $propietario->id }}">Ref: {{$unidad->referencia.' - '.$unidad->tipo->nombre.'  '.$unidad->numero_letra }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="col-md-4 text-left validate-label-3">
                        <i class="fa fa-institution"></i>
                        <label class="margin-top">
                            Tipo de pago
                        </label>
                    </div>
                    <div class="col-md-8">
                        <select onchange="mostrarBanco(this)" name="tipo_de_pago" id="tipo_de_pago" class="form-control validate-input-3">
                            <option value="Pago por Transferencia Bancaria">Pago por Transferencia Bancaria</option>
                            <option value="Pago en Efectivo">Pago en Efectivo</option>
                            <option value="Consignación">Consignación</option>
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <div class="col-md-4 text-left validate-label-4">
                        <i class="fa fa-money"></i>
                        <label class="margin-top">
                            Valor recibido
                        </label>
                    </div>
                    <div class="col-md-8">
                        <input class="form-control validate-input-4" onchange="changeValor(this,'valor');" type="text" name="valor_aux" id="valor_aux">
                        <input type="hidden" name="valor" id="valor">
                    </div>
                </div>
                <div class="col-md-6 text-center" id="div_banco">
                    <div class="col-md-4 text-left">
                        <i class="fa fa-institution"></i>
                        <label class="margin-top">
                              Banco
                        </label>
                    </div>
                    <div class="col-md-8">
                        <select name="banco" id="banco" class="form-control select-2 validate-input-2">
                            <option value="">Seleccione un banco</option>
                            @foreach ($bancos as $banco)
                                <option value="{{ $banco->banco.' '.$banco->nro_cuenta.' '.$banco->tipo }}">{{ $banco->banco.' '.$banco->nro_cuenta.' '.$banco->tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-6 text-center">
                    <button class="btn btn-success " onclick="cargarCuentas();" type="button">Cargar cuentas</button>
                </div>
            </div>
        </form>
        <br>
        <div class="container-fluid" id="data_container">
    
        </div>
    @endif
</div>

	



@endsection
@section('ajax_crud')

@if (!($error == 'fecha'))
    <script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#valor_aux').maskMoney({precision:0});
        });
        
        //inicializar los select
        $('.select-2').select2();

        var csrf_token = $('meta[name="csrf-token"]').attr('content');

        function mostrarBanco(select){
            if(select.value != 'Pago en Efectivo'){
                $('#div_banco').removeClass('hide');
            }else{
                $('#div_banco').addClass('hide');
            }
        }

        function cargarCuentas(){
            if(propietario.value == ""){
                swal('Error!','Debe de seleccionar un propietario','error').then(res=>{
                    propietario.focus();
                });
                return;
            }

            if(tipo_de_pago.value != 'Pago en Efectivo' && banco.value == ''){
                swal('Error!','Debe de ingresar el banco','error').then(res=>{
                    banco.focus();
                });
                return;
            }

            if(verificarFormulario('data',4)){
                $.ajax({
                    type: "POST",
                    url: "{{url('ultimaCuentaCobro')}}/"+propietario.value,
                    data: {
                        _token : csrf_token
                    },
                    dataType: "html",
                    success: function (response) {
                        $('#data_container').html(response);
                    }
                });
            }
        }

        
    </script>
@endif

@endsection