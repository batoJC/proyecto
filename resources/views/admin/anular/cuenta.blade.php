<div class="row borde">
    {{-- {{ dd($cuenta) }} --}}
    <h1 class="text-center">Cuenta  @if ($cuenta['tipo_cobro'] != 'normal')
        en cobro {{$cuenta['tipo_cobro']}}
    @else
        de cobro
    @endif</h1>
    <h4><b>Consecutivo: </b>{{ $cuenta['consecutivo'] }}</h4>
    <h4><b>Fecha: </b>{{ date('d-m-Y',strtotime($datos['fecha'])) }}</h4>
    <h4><b>Fecha pronto pago: </b>{{ $datos['fecha_pronto_pago'] }}</h4>
    <h4><b>Nombre: </b>{{ $cuenta['propietario']->nombre_completo }} - {{ $cuenta['propietario']->numero_cedula }}</h4>
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
            @foreach ($cuenta['cuentas'] as $detalle)
                    <tr data-row="{{ $detalle['tipo'].'_'.$detalle['cuota_id'] }}">
                    <td><i onclick="eliminarCuota('{{ $detalle['tipo'] }}','{{ $detalle['cuota_id'] }}','{{ $detalle['unidad_id'] }}','{{ $cuenta['propietario']->id }}')" class="fa fa-trash" data-toggle="tooltip" data-placement="top" title="Eliminar esta cuenta"></i>  {{ $detalle['vigencia_inicio'] }}</td>
                    <td>{{ $detalle['vigencia_fin'] }}</td>
                    <td>{{ $detalle['referencia'] }}</td>
                    <td>{{ $detalle['concepto'] }}</td>
                    <td>$ {{ number_format($detalle['valor']) }} <i onclick="editarValor('{{ $detalle['tipo'] }}','{{ $detalle['cuota_id'] }}','{{ $detalle['unidad_id'] }}','{{ $cuenta['propietario']->id }}')" class="fa fa-pencil" data-toggle="tooltip" data-placement="top" title="Editar el valor de esta cuenta"></i></td>
                    <td>$ {{ number_format($detalle['interes']) }}</td>
                    <td>$ {{ number_format($detalle['valor']+$detalle['interes']) }}</td>
                </tr>
                @php
                    $total += $detalle['valor']+$detalle['interes'];
                @endphp
            @endforeach
        </tbody>
    </table>
    <h3><b>Total a pagar:  </b>$ {{ number_format($total) }}</h3>
    @if ($cuenta['propietario']->interes() == 0)
        <h3><b>Total a pagar con descuento:  </b>$ {{ number_format($total*(1-($datos['descuento']/100))) }}</h3>
    @endif
    @if ($cuenta['propietario']->saldo()>0)
        <h3><b>Saldo a favor:  </b>$ {{ number_format($cuenta['propietario']->saldo()) }}</h3>
    @endif
</div>
<br>
<div class="container-fluid">
    <div class="row center">
        <div class="col-0 col-md-2"></div>
        <div class="col-12 col-md-8">
            <label for="motivo">Motivo de anulación</label>
            <textarea name="motivo_cuenta" id="motivo_cuenta" cols="30" rows="4" class="form-control"></textarea>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12 text-center"><button id="btn_save_cuenta" onclick="saveCuenta();" class="btn btn-primary">Guardar cuenta</button></div>
</div>

<script>
    $('[data-toggle="tooltip"]').tooltip();

function eliminarCuota(tipo,id,unidad,propietario){
    swal({
        title : 'Advertencia!',
        text : '¿Seguro de querer eliminar esta cuenta?',
        icon : 'warning',
        buttons : true
    }).then(res=>{
        if(res){
            $.ajax({
                type: "POST",
                url: "{{url('eliminarCuota')}}",
                data: {
                    _token : csrf_token,
                    tipo : tipo,
                    id : id,
                    unidad : unidad
                },
                dataType: "json",
                success: function (res) {
                    console.log(res);
                    if (res.res) {
                        swal('Logrado!',res.msg,'success').then(()=>{
                            cargarCuenta();
                        });
                    }else{
                        swal('Error!',res.msg,'error');
                    }
                }
            });
        }
    })
}


function editarValor(tipo,id,unidad,propietario){
    input = document.createElement("div");
    input.innerHTML = '<label class="label_alerta">Ingrese el nuevo valor para la cuota:</label>';
    input.append(document.createElement("br"));
    valor = document.createElement("input");
    valor.id = 'valor';
    valor.className = 'form-control';
    valor.type = 'number'
    input.append(valor);
    valor.placeholder = "valor.";

    swal({
        title: "Nuevo valor.",
        content: input,
        buttons: true                       
    }).then((res)=>{
        if(res){
            valor = $('#valor');
            $.ajax({
                type: "POST",
                url: "{{url('editarCuota')}}",
                data: {
                    _token : csrf_token,
                    tipo : tipo,
                    id : id,
                    unidad : unidad,
                    valor : valor.val()
                },
                dataType: "json",
                success: function (res) {
                    if(res.res){
                        cargarCuenta();
                    }
                }
            });
        }
    });
}

function cargarCuenta(){
    $.ajax({
        type: "POST",
        url: "{{ url('anularLoadCuenta') }}",
        data: {
            _token : csrf_token,
            fecha : fecha_cuenta.value,
            propietario : propietario_cuenta.value,
            consecutivo : consecutivo_cuenta.value,
            fecha_pronto_pago : fecha_pronto_pago.value,
            descuento : descuento.value
        },
        dataType: "html",
        success: function (response) {
            // console.log(response);
            $('#data_cuentas').html(response);
        }
    });
}

function saveCuenta(){
    $.ajax({
        type: "POST",
        url: "{{ url('reemplazarCuenta') }}/"+cuenta_cobro.id,
        data: {
            _token : csrf_token,
            fecha : fecha_cuenta.value,
            propietario : propietario_cuenta.value,
            motivo : motivo_cuenta.value,
            consecutivo : consecutivo_cuenta.value,
            fecha_pronto_pago : fecha_pronto_pago.value,
            descuento : descuento.value
        },
        dataType: "json",
        success: function (response) {
            if(response.res){
                btn_save_cuenta.disabled = true;
                swal('Logrado!',response.msg,'success');
            }else{
                swal('Error!',response.msg,'error');
            }
        }
    });
}


</script>