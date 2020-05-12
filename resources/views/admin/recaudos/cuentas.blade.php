<style>
    .borde{
        border: 2px solid grey;
        margin: 5px;
        border-radius: 10px;
        padding: 15px;
    }

    i{
        cursor: pointer;
    }

    i:hover{
        color: #1ABB9C;
    }

</style>
<div class="row borde">
    <h1 class="text-center">Cuenta @if ($cuenta['tipo_cobro'] != 'normal')
        en cobro {{$cuenta['tipo_cobro']}}
    @else
        de cobro
    @endif</h1>
    <h4><b>Consecutivo: </b>{{ $cuenta->consecutivo }}</h4>
    <h4><b>Fecha: </b>{{ date('d-m-Y',strtotime($cuenta->fecha)) }}</h4>
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
                <tr id="detalle_{{ $detalle->id }}">
                    <td>{{ date('d-m-Y',strtotime($detalle->vigencia_inicio)) }}</td>
                    <td>{{ date('d-m-Y',strtotime($detalle->vigencia_fin)) }}</td>
                    <td>{{ $detalle->referencia }}</td>
                    <td>{{ $detalle->concepto }}</td>
                    <td class="text-right">
                        $ {{ number_format($detalle->valor) }}
                        @if ($detalle->valor > 0)
                            <i onclick="pagarValor('{{ $detalle->id }}')" class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" title="Pagar el valor de la cuenta"></i>
                        @endif

                    </td>
                    <td class="text-right">
                        $ {{ number_format($detalle->interes) }}
                        @if ($detalle->interes > 0)
                            <i onclick="pagarInteres('{{ $detalle->id }}')" class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" title="Pagar el interes de la cuenta"></i>  
                        @endif
                    </td>
                    <td class="text-right">
                        $ {{ number_format($detalle->valor+$detalle->interes) }}
                        <i onclick="pagarTodo('{{ $detalle->id }}')" class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" title="Pagar el valor y interes de la cuenta"></i>
                    </td>
                </tr>
                @php
                    $total += $detalle->valor+$detalle->interes;
                @endphp
            @endforeach
        </tbody>
    </table>
    <h3><b>Total a pagar:  </b><span id="total_pagar"> $ {{ number_format($total) }} </span> <i class="fa fa-credit-card" data-toggle="tooltip" onclick="pagarTodas()" data-placement="top" title="Pagar todas las cuentas"></i></h3>
    @if ($cuenta->interes() == 0 && (date_diff(date_create(date('Y-m-d')), date_create($cuenta->fecha_pronto_pago))->format('%R%a') >= 0))
        <h3><b>Total a pagar con descuento:  </b>$ {{ number_format($total*(1-($cuenta->descuento/100))) }} <i onclick="guardarProntoPago();" class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" title="Pagar todas las cuentas con descuento por pronto pago"></i></h3>
    @endif
    @if ($cuenta->propietario->saldo() > 0)
    <h3><b>Saldo a favor:  </b>$ {{ number_format($cuenta->propietario->saldo()) }}</h3>
@endif
</div>
<br>
    <h3>Total pagado: <span id="total_pagado">$ 0</span></h3>
    <h3>Efectivo sobrante: <span id="efectivo_sobrante"></span></h3>
<br>
@if (count($cuenta->detalles) > 0)
<div class="row borde">
    <h1 class="text-center">Recibo de pago</h1>
    <h3 class="text-center">Detalles</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Cuenta</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody id="data_pagos">

        </tbody>
    </table>
</div>
    <br>
    <br>
    <div class="row">
        <div class="col text-center">
            <button onclick="guardar()" class="btn btn-success save" onclick="">Guardar</button>
            <button onclick="guardarDescargar()" class="btn btn-default save">Guardar y generar pdf <i class="fa fa-download"></i></button>
            {{-- <form action="{{ url('guardarGenerarPago') }}"> --}}
            {{-- </form> --}}
        </div>
    </div>
    <br>
    <br>
@endif

<script>

    function guardar(){

        let pagosRealizados = '';
        pagos.forEach((e)=>{
            pagosRealizados += e+';';
        });

        let datos = new FormData(data);
        datos.append('pagos',pagosRealizados.substring(0,pagosRealizados.length-1));
        datos.append('cuenta',cuenta);
        datos.append('fecha',fecha.value);
        datos.append('_token',csrf_token);

        $('.save')[0].disabled = true;
        $('.save')[1].disabled = true;

        $.ajax({
            type: "POST",
            url: "{{url('recaudos')}}",
            data: datos,
            dataType: "json",
            contentType: false,
            processData: false
        }).done((res)=>{
            console.log(res);
            if (res.res) {
                swal('Logrado!',res.msg,'success').then(res=>{
                    location.reload();
                });
            }else{
                swal('Error!',res.msg,'error');
                $('.save')[0].disabled = false;
                $('.save')[1].disabled = false;
            }
        }).fail((res)=>{
            console.log(res);
        });
    }

    var respuesta = null;

    function guardarDescargar(){

        let pagosRealizados = '';
        pagos.forEach((e)=>{
            pagosRealizados += e+';';
        });

        let datos = new FormData(data);
        datos.append('pagos',pagosRealizados.substring(0,pagosRealizados.length-1));
        datos.append('fecha',fecha.value);
        datos.append('cuenta',cuenta);
        datos.append('_token',csrf_token);

        $.ajax({
            type: "POST",
            url: "{{url('recaudos')}}",
            data: datos,
            dataType: "json",
            contentType: false,
            processData: false,
            success: (res)=>{
                respuesta = res;
                if (res.res) {
                    swal('Logrado!',res.msg,'success').then(res=>{
                        var win = window.open('{{ url('pdfPago') }}/'+respuesta.pago.id, '_blank');
                        location.reload();
                        win.focus();
                    });
                }else{
                    swal('Error!',res.msg,'error');
                }
            }
        }).fail((res)=>{
            // console.log(res);
        });
    }

    @if ($cuenta->interes() == 0 && (date_diff(date_create(date('Y-m-d')), date_create($cuenta->fecha_pronto_pago))->format('%R%a') >= 0))

        function guardarProntoPago(){

            swal({
                title : 'Avertencia!',
                text:'¿Seguro de querer guardar este pago con el porcentaje de descuento?',
                icon:'warning',
                buttons:true
            }).then(res=>{
                if(res){
                    let datos = new FormData(data);
                    datos.append('fecha',fecha.value);
                    datos.append('cuenta',cuenta);
                    datos.append('_token',csrf_token);

                    $.ajax({
                        type: "POST",
                        url: "{{url('recaudosProntoPago')}}",
                        data: datos,
                        dataType: "json",
                        contentType: false,
                        processData: false,
                        success: (res)=>{
                            respuesta = res;
                            if (res.res) {
                                swal('Logrado!',res.msg,'success').then(res=>{
                                    var win = window.open('{{ url('pdfPago') }}/'+respuesta.pago.id, '_blank');
                                    location.reload();
                                    win.focus();
                                });
                            }else{
                                swal('Error!',res.msg,'error');
                            }
                        }
                    }).fail((res)=>{
                        swal('Error!','Ocurrió un error en el servidor.','error');
                    });
                }
            });
            

        }

    @endif

    var cuenta = {{ $cuenta->id }};
    var cuentas = new FormData();

    $.ajax({
        type: "POST",
        url: "{{url('detallesCuenta')}}/"+cuenta,
        data: {
            _token : csrf_token
        },
        dataType: "json",
        success: function (response) {
            response.forEach(e => {
                cuentas.append(e.id,JSON.stringify(e));
            });
        }
    });

    var valorRecibido = parseInt(valor.value) + {{ $cuenta->propietario->saldo() }};
    console.log(valorRecibido);
    var valorPagado = 0;
    var totalPagar = {{ $total }};
    efectivo_sobrante.innerText = `$${new Intl.NumberFormat('COP').format(valorRecibido).replace('.',',')}`;
    var comprobar = '(valorPagado + valor) <= valorRecibido';


    var pagos = new FormData();
    var nro_pagos = 0;

    //inicializar tootltip
    $('[data-toggle="tooltip"]').tooltip();

    //funcion para pagar todo 
    function pagarTodas(){
        cuentas.forEach(e=>{
            pagarTodo(JSON.parse(e).id);
        });
    }


    //función para pagar todo con descuento
    function pagarTodo(id){
        let valor_cuota = calcularCuota(id,'ambos');


        if((valorPagado + valor_cuota) <= valorRecibido){

            let interes = calcularCuota(id,'interes');
            let valor = calcularCuota(id,'valor');

            let response = JSON.parse(cuentas.get(id));
            valorPagado +=  parseInt(valor_cuota);
            if(valor > 0){
                pagos.append(nro_pagos,JSON.stringify({detalle:response.id,tipo:'valor',valor:valor}));
                $('#data_pagos').append(`
                    <tr>
                        <td>${response.concepto}</td>
                        <td>$${new Intl.NumberFormat('COP').format(valor).replace('.',',')}</td>
                    </tr>
                `);
            }
            nro_pagos++;
            if(interes > 0){
                pagos.append(nro_pagos,JSON.stringify({detalle:response.id,tipo:'interes',valor:interes}));
                $('#data_pagos').append(`
                    <tr>
                        <td>INTERESES ${response.concepto}</td>
                        <td>$${new Intl.NumberFormat('COP').format(interes).replace('.',',')}</td>
                    </tr>
                `);
            }
            nro_pagos++;
            refrescarCuenta(response.id);
        }else{
            setTimeout(()=>{
                let valor = valorRecibido - valorPagado;
                swal('Error!',`No hay suficiente dinero para pagar la cuenta quedan $${new Intl.NumberFormat('COP').format(valor).replace('.',',')} pesos`,'error');
            },100);
        }
    }

    function pagarInteres(id){
        let valor_cuota = calcularCuota(id,'interes');

        valorPagar(valor_cuota,(_valor)=>{

            if((valorPagado + _valor) <= valorRecibido){
                let response = JSON.parse(cuentas.get(id));
                valorPagado +=  parseInt(_valor);
                pagos.append(nro_pagos,JSON.stringify({detalle:response.id,tipo:'interes',valor:_valor}));
                $('#data_pagos').append(`
                    <tr>
                        <td>INTERESES ${response.concepto}</td>
                        <td>$${new Intl.NumberFormat('COP').format(_valor).replace('.',',')}</td>
                    </tr>
                `);
                nro_pagos++;
                refrescarCuenta(response.id);
            }else{
                setTimeout(()=>{
                    let valor = valorRecibido - valorPagado;
                    swal('Error!',`No hay suficiente dinero para pagar la cuenta quedan $${new Intl.NumberFormat('COP').format(valor).replace('.',',')} pesos`,'error');
                },100);
            }
        });
    }

    function pagarValor(id){
        
        let valor_cuota = calcularCuota(id,'valor');

        valorPagar(valor_cuota,(_valor)=>{

            if((valorPagado + _valor) <= valorRecibido){
                let response = JSON.parse(cuentas.get(id));
                valorPagado +=  parseInt(_valor);
                pagos.append(nro_pagos,JSON.stringify({detalle:response.id,tipo:'valor',valor:_valor}));
                $('#data_pagos').append(`
                    <tr>
                        <td>${response.concepto}</td>
                        <td>$${new Intl.NumberFormat('COP').format(_valor).replace('.',',')}</td>
                    </tr>
                `);
                nro_pagos++;
                refrescarCuenta(response.id);
            }else{
                setTimeout(()=>{
                    let valor = valorRecibido - valorPagado;
                    swal('Error!',`No hay suficiente dinero para pagar la cuenta quedan $${new Intl.NumberFormat('COP').format(valor).replace('.',',')} pesos`,'error');
                },100);
            }
        });
    }

    function calcularCuota(id,tipo){
        var salida = 0;

        switch (tipo) {
            case 'valor':
                salida += JSON.parse(cuentas.get(id)).valor;
                break;
            case 'interes':
                salida += JSON.parse(cuentas.get(id)).interes;
                break;
            case 'ambos':
                salida += JSON.parse(cuentas.get(id)).valor +  JSON.parse(cuentas.get(id)).interes;
                break;
        }

        pagos.forEach(e=>{
            let data = JSON.parse(e);
            switch (tipo) {
                case 'valor':
                    if(data.tipo == 'valor' && data.detalle==id){
                        salida -= data.valor;
                    }
                    break;
                case 'interes':
                    if(data.tipo == 'interes' && data.detalle==id){
                        salida -= data.valor;
                    }
                    break;
                case 'ambos':
                    if(data.tipo == 'valor' && data.detalle==id){
                        salida -= data.valor;
                    }
                    if(data.tipo == 'interes' && data.detalle==id){
                        salida -= data.valor;
                    }
                    break;
            }
        });
        return salida;
    }


    function valorPagar(valor, callback){
        input = document.createElement("div");
        input.innerHTML = '<label class="label_alerta">Ingrese el valor a pagar:</label>';
        input.append(document.createElement("br"));
        valor_pago = document.createElement("input");
        valor_pago.id = 'valor_pago';
        valor_pago.className = 'form-control';
        valor_pago.type = 'number';
        input.append(valor_pago);
        // valor.placeholder = "valor.";
        if(valor > (valorRecibido - valorPagado)){
            valor = valorRecibido - valorPagado;
        }
        valor_pago.value = valor;

        swal({
            title: "Valor de pago.",
            content: input,
            buttons: true                       
        }).then((res)=>{
            if(res){
                callback(parseInt(valor_pago.value));
            }
        });
    }


    function refrescarCuenta(id){
        let valor = calcularCuota(id,'valor');
        let interes = calcularCuota(id,'interes');
        let total = valor + interes;
        let data = JSON.parse(cuentas.get(id));
        if(total > 0){

            let contenido = `
                <td>${data.vigencia_inicio}</td>
                <td>${data.vigencia_fin}</td>
                <td>${data.referencia}</td>
                <td>${data.concepto}</td>
            `;

            if(valor > 0){
                contenido += `
                <td class="text-right">
                    $${new Intl.NumberFormat('COP').format(valor).replace('.',',')}
                    <i onclick="pagarValor('${data.id}')" class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" title="Pagar el valor de la cuenta"></i>
                </td>
                `;
            }else{
                contenido += `
                <td class="text-right">
                    $${new Intl.NumberFormat('COP').format(valor).replace('.',',')}
                </td>
                `;
            }

            if(interes > 0){
                contenido += `
                <td class="text-right">
                    $${new Intl.NumberFormat('COP').format(interes).replace('.',',')}
                    <i onclick="pagarInteres('${data.id}')" class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" title="Pagar el interes de la cuenta"></i>  
                </td>
                `;
            }else{
                contenido += `
                <td class="text-right">
                    $${new Intl.NumberFormat('COP').format(interes).replace('.',',')}
                </td>
                `;
            }

            contenido += `
                    <td class="text-right">
                        $${new Intl.NumberFormat('COP').format(total).replace('.',',')}
                        <i onclick="pagarTodo('${data.id}')" class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" title="Pagar el valor y interes de la cuenta"></i>
                    </td>
            `; 
            
            $(`#detalle_${id}`).html(contenido);
        }else{
            $(`#detalle_${id}`).html('');
        }

        total_pagado.innerText = `$${new Intl.NumberFormat('COP').format(valorPagado).replace('.',',')}`;
        efectivo_sobrante.innerText = `$${new Intl.NumberFormat('COP').format(valorRecibido-valorPagado).replace('.',',')}`;
        total_pagar.innerText = `$${new Intl.NumberFormat('COP').format(totalPagar-valorPagado).replace('.',',')}`;


        $('[data-toggle="tooltip"]').tooltip();
    }


    //function for confirmation the action
    function confirmar(funcion){
        swal({
            title : 'Confirmar acción',
            text : '¿Seguro de realizar esta acción?',
            icon : 'warning',
            buttons : true
        }).then(res=>{
            if(res){
                funcion();
            }
        });
    }


</script>