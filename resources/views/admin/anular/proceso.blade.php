<style>
    .borde{
        border: 2px solid grey;
        margin: 5px;
        border-radius: 10px;
        padding: 15px;
    }
</style>
<div class="row borde">
    <h1 class="text-center">Cuenta @if ($cuenta['tipo_cobro'] != 'normal')
        en cobro {{$cuenta['tipo_cobro']}}
    @else
        de cobro
    @endif</h1>
    <h4><b>Consecutivo: </b>{{ $cuenta->consecutivo }}</h4>
    <h4><b>Fecha: </b>{{ date('d-m-Y',strtotime($cuenta->fecha))  }}</h4>
    <h4><b>Fecha pronto pago: </b>{{ date('d-m-Y',strtotime($cuenta->fecha_pronto_pago)) }}</h4>
    <h4><b>Nombre: </b>{{ $cuenta->propietario->nombre_completo }} - {{ $cuenta->propietario->numero_cedula }}</h4>
    <br>
    <h3 class="text-center">Detalles</h3>
    <table class="table">
        <thead>
            <th>Vigencia inicio</th>
            <th>Vigencia fin</th>
            <th>Referencia de pago</th>
            <th>Concepto</th>
            <th>Valor</th>
            <th>Interes</th>
            <th>Total</th>
        </thead>
        <tbody>
            @php
                $total = 0;
            @endphp
            @foreach ($cuenta->detalles as $detalle)
                <tr>
                    <td>{{ date('d-m-Y',strtotime($detalle->vigencia_inicio)) }}</td>
                    <td>{{ date('d-m-Y',strtotime($detalle->vigencia_fin)) }}</td>
                    <td>{{ $detalle->referencia }}</td>
                    <td>{{ $detalle->concepto }}</td>
                    <td>$ {{ number_format($detalle->valor) }}</td>
                    <td>$ {{ number_format($detalle->interes) }}</td>
                    <td>$ {{ number_format($detalle->valor+$detalle->interes) }}</td>
                </tr>
                @php
                    $total += $detalle->valor+$detalle->interes;
                @endphp
            @endforeach
        </tbody>
    </table>
    <h3><b>Total a pagar:  </b>$ {{ number_format($total) }}</h3>
    @if ($cuenta->propietario->interes() == 0)
        <h3><b>Total a pagar con descuento:  </b>$ {{ number_format($total*(1-($cuenta->descuento/100))) }}</h3>
    @endif
    @if ($cuenta->propietario->saldo()>0)
        <h3><b>Saldo a favor:  </b>$ {{ number_format($cuenta->propietario->saldo()) }}</h3>
    @endif
</div>

@if (!$cuenta->anulada)
    <div class="row">
        <div class="col-12 text-center"><button onclick="modificarCuenta(this)" class="btn btn-primary"><i class="fa fa-wrench"></i> Modificar cuenta de cobro</button></div>
    </div>    
    <div class="row" id="change_cuenta"></div>
@endif



<br><hr><br>


@if ($cuenta->recaudo)
    <div class="row borde">
    
        <h1 class="text-center">Recibo de pago</h1>
        <h4><b>Consecutivo: </b>{{ $cuenta->recaudo->consecutivo }}</h4>
        <h4><b>Fecha: </b>{{ date('d-m-Y',strtotime($cuenta->recaudo->fecha)) }}</h4>
        <h4><b>Valor recaudado: </b> $ {{ number_format($cuenta->recaudo->valor) }}</h4>
        <h4><b>Nombre: </b>{{ $cuenta->recaudo->propietario->nombre_completo }} - {{ $cuenta->recaudo->propietario->numero_cedula }}</h4>
        <h4><b>Tipo de pago: </b>{{ $cuenta->recaudo->tipo_de_pago }}</h4>
        <br>
        <h3 class="text-center">Detalles</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = 0;
                @endphp
                @foreach ($cuenta->recaudo->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->concepto() }}</td>
                        <td>$ {{ number_format($detalle->valor) }}</td>
                    </tr>
                    @php
                        $total += $detalle->valor;
                    @endphp
                @endforeach
            </tbody>
        </table>
        <br>
        <h3 class="text-left"><b>Total pagado:  </b>$ {{ number_format($total) }}</h3>
        <h3 class="text-left"><b>Saldo a favor:  </b>$ {{ number_format($cuenta->recaudo->saldo_favor) }}</h3>
    </div>

    <div class="row">
        <div class="col-12 text-center" id="btn_change_status">
            @if (!$cuenta->recaudo->anulada)
                <button onclick="anularPagos(this)" class="btn btn-primary"><i class="fa fa-ban"></i> Anular pagos</button>
            @else
                <button onclick="restablecerPagos(this)" class="btn btn-primary"><i class="fa fa-repeat"></i> Restablecer pagos</button>
            @endif
        </div>
    </div>



    @if (!$cuenta->recaudo->anulada)
        <div class="row">
            <div class="col-12 text-center">
                <button onclick="modificarRecaudo(this)" class="btn btn-primary"><i class="fa fa-wrench"></i> Modificar recaudo</button>
            </div>
        </div>

        <div class="row" id="change_recaudo"></div>
    @endif
    
@else
<div class="row">
    <div class="col-12 text-center"><button onclick="agregarRecaudo(this)" class="btn btn-primary">Agregar recaudo</button></div>
</div>

<div class="row" id="change_recaudo"></div>

@endif

<script>

    var cuenta_cobro = @json($cuenta);
    var recaudo = @json($cuenta->recaudo);

    function guardarProceso(callback){

        //alertar si solo modifico una de las dos entidades
        swal({
            title : 'Advertencia!',
            text : 'Recuerde que no podra devolverse. \n\r ¿Seguro de querer continuar?',
            icon : 'warning',
            buttons : true
        }).then(res=>{
            callback(res);
        });

    }

    function modificarCuenta(btn){
        btn.disabled = true;
        $.ajax({
            type: "POST",
            url: "{{url('anularModificarCuenta')}}/"+cuenta_cobro.id,
            data: {
                _token : csrf_token
            },
            dataType: "html",
            success: function (response) {
                $('#change_cuenta').html(response);
            }
        });
    }

    function modificarRecaudo(btn){
        btn.disabled = true;
        $.ajax({
            type: "POST",
            url: "{{url('anularModificarRecaudo')}}/"+recaudo.id,
            data: {
                _token : csrf_token,
            },
            dataType: "html",
            success: function (response) {
                $('#change_recaudo').html(response);
            }
        });
    }

    function agregarRecaudo(btn){
        btn.disabled = true;
        $.ajax({
            type: "POST",
            url: "{{url('anularAgregarRecaudo')}}/"+cuenta_cobro.id,
            data: {
                _token : csrf_token,
            },
            dataType: "html",
            success: function (response) {
                $('#change_recaudo').html(response);
            }
        });
    }

    @if ($cuenta->recaudo)
        function anularPagos(btn){
            btn.disabled = true;
            swal({
                title : 'Advertencia!',
                text:'¿Seguro de querer eliminar estos pagos?',
                icon :'warning',
                buttons : true
            }).then(res=>{
                if(res){
                    $.ajax({
                        type: "POST",
                        url: "{{ url('anularPagosRecaudo') }}/{{ $cuenta->recaudo->id }}",
                        data: {
                            _token:csrf_token
                        },
                        dataType: "json"
                    }).done((res)=>{
                        console.log(res);
                        if(res.res){
                            $('#btn_change_status').html('<button onclick="restablecerPagos(this)" class="btn btn-primary"><i class="fa fa-repeat"></i> Restablecer pagos</button>');
                            swal('Logrado!',res.msg,'success');
                        }else{
                            swal('Error!',res.msg,'error');
                        }
                    }).fail(()=>{
                        swal('Error!','ocurrió un error en el servidor','error');
                    });
                }else{
                    btn.disabled = false;
                }
            });
        }

        function restablecerPagos(btn){
            btn.disabled = true;
            swal({
                title : 'Advertencia!',
                text:'¿Seguro de querer eliminar estos pagos?',
                icon :'warning',
                buttons : true
            }).then(res=>{
                if(res){
                    $.ajax({
                        type: "POST",
                        url: "{{ url('restablecerPagosRecaudo') }}/{{ $cuenta->recaudo->id }}",
                        data: {
                            _token:csrf_token
                        },
                        dataType: "json"
                    }).done((res)=>{
                        if(res.res){
                            $('#btn_change_status').html('<button onclick="anularPagos(this)" class="btn btn-primary"><i class="fa fa-ban"></i> Anular pagos</button>');
                            swal('Logrado!',res.msg,'success');
                        }else{
                            swal('Error!',res.msg,'error');
                        }
                    }).fail(()=>{
                        swal('Error!','ocurrió un error en el servidor','error');
                    });
                }else{
                    btn.disabled = false;
                }
            });
        }

    @endif


</script>