<style>
    .contenedor{
        background: white;
        width: 100%;
        color: black;
        font-size: 17px;
    }

    img{
        width: auto;
        height: 100%;
    }

    .bloque{
        display: flex;
        align-items: center;
        justify-content: space-around;
        text-align: center;
        border: 1px solid black;
        margin: 0;
        padding: 0;
        background: white;
    }

    .text-white{
        color: white;
    }

    .w-100{
        width: 100%;
    }

    .h-100{
        height: 100px;
    }

    .h-30{
        height: 30px;
    }

    .grey{
        background: grey;
    }

    .ml-5{
        margin-left: 5px;
    }

</style>


<div class="contenedor">
    {{-- INFORMACIÓN CONJUNTO Y FECHA --}}
    <div class="row">
        <div class="bloque col-md-2 h-100">
            <img src="{{ asset('imgs/logos_conjuntos/'.$conjunto->logo) }}" alt="Logo conjunto">
        </div>
        <div class="bloque col-md-8  h-100">
            <h3 class="text-center" >{{ $conjunto->nombre }} - {{ $conjunto->nit }}</h3>
        </div>
        <div class="bloque col-md-2 h-100">
            <h4>{{ date('d/m/Y') }}</h4>
        </div>
    </div>
    {{-- INFORMACIÓN EMPLEADO --}}
    <div class="row">
        <div class="col-md-2 h-30 bloque">Nombre:</div>
        <div class="col-md-4 h-30 bloque">{{ $empleado->nombre_completo }}</div>
        <div class="col-md-2 h-30 bloque">Identificación:</div>
        <div class="col-md-4 h-30 bloque">{{ $empleado->cedula }}</div>
    </div>
    {{-- INFORMACIÓN LIQUIDACIÓN --}}
    <div class="row">
        <div class="col-md-2 h-30 bloque">Consecutivo:</div>
        <div class="col-md-4 h-30 bloque">{{ $consecutivo->prefijo }}-{{ $consecutivo->numero }}</div>
        <div class="col-md-2 h-30 bloque">Periodo:</div>
        <div class="col-md-4 h-30 bloque">{{ date('d/m/Y',strtotime($fecha_inicio)) }} - {{ date('d/m/Y',strtotime($fecha_fin)) }}</div>
    </div>

    <div class="row">
        {{-- DEVENGOS --}}
        <div class="col-md-6 bloque">
            <div class="col-md-12 h-30 bloque grey">
                <h4 class="text-white">DEVENGOS <i onclick="openModal('devengo')" data-toggle="tooltip" data-placement="right" title="Agregar un devengo" class="ml-5 fa fa-plus"></i></h4>
            </div>
        </div>
    
        {{-- DEDUCCIONES --}}
        <div class="col-md-6 bloque">
            <div class="col-md-12 h-30 bloque grey">
                <h4 class="text-white">DEDUCCIONES <i onclick="openModal('deduccion')" data-toggle="tooltip" data-placement="right" title="Agregar una deducción" class="ml-5 fa fa-plus"></i></h4>
            </div>
        </div>
    </div>

    <div class="row">

        {{-- DEVENGOS --}}
        <div class="col-md-6" id="tabla_devengos">
        </div>

        {{-- DEDUCCIONES --}}
        <div class="col-md-6" id="tabla_deducciones">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-8 h-30 bloque">Total devengado:</div>
                <div class="col-md-4 h-30 bloque">
                    <span id="total_devengado">$ 0</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-8 h-30 bloque">Total descuentos:</div>
                <div class="col-md-4 h-30 bloque">
                    <span id="span_total_descuentos">$ 0</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-8 h-30 bloque">Salario básico:</div>
            <div class="col-md-4 h-30 bloque">$ {{ number_format($empleado->salario,2) }}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-8 h-30 bloque">Neto a pagar:</div>
                <div class="col-md-4 h-30 bloque">
                    <span id="neto_pagar"></span>
                </div>
            </div>
        </div>
    </div>
</div>
<br><br>
<div class="row">
    <div class="col-md-12 text-center">
        <button onclick="guardar();" class="btn btn-success">Guardar <i class="fa fa-save"></i></button>
    </div>
</div>

<br>
<br>
<br>
@include('admin.liquidador.form-liquidacion')
<script src="{{ asset('js/jquery.maskMoney.min.js') }}"></script>
    <script>
    $(document).ready(function () {
        $('#valor_devengo_aux').maskMoney({precision:0});
        //agregar devengos TODO
        actualizarTotales();
        @php
            $total_valor_devengos = 0;
        @endphp
        @foreach ($prestaciones as $name => $value)
            devengos.add({
                id:devengos.id,
                descripcion : "{{ $name }}",
                valor : parseInt('{{ $value }}'),
                retencion : false
            });
            devengos.id++;
            @php
                $total_valor_devengos += $value;
            @endphp    
        @endforeach
        deducciones.pintar();
    });


    var total_con_recargo = 0;
    var total_sin_recargo = 0;
    var total_descuentos = 0;

    function removeElement(arr,id){
        for (var i = 0; i < arr.length; i++) {
            if (arr[i]['id'] == id) {
                let aux = arr[i];
                arr.splice( i, 1 );
                return aux;
            }
        }
        return null;
    }


    var devengos = {
        id : 1,
        data : new Array(),
        add : (data) => {
            devengos.data.push({
                id: data.id,
                descripcion : data.descripcion,
                valor : data.valor,
                retencion : data.retencion,
            });
            $('#tabla_devengos').append(`
                <div id="devengo-${devengos.id}" class="row">
                    <div class="col-md-9 h-30 bloque">
                        ${data.descripcion}
                        <i onclick="devengos.eliminar(${devengos.id})" class="fa fa-trash-o text-danger"></i>
                    </div>
                    <div class="col-md-3 h-30 bloque">${formatter.format(data.valor)}</div>
                </div>
            `);

            //falta genera retención
            devengos.id++;
            if(data.retencion){
                total_con_recargo += parseInt(data.valor);
                deducciones.pintar();
            }else{
                total_sin_recargo += parseInt(data.valor);
            }
            actualizarTotales();
        },
        agregar : () =>{
            if(verificarFormulario('data_devengo',2)){
                let data = {
                    id:devengos.id,
                    descripcion : descripcion_devengo.value,
                    valor : parseInt(valor_devengo.value),
                    retencion : retencion.checked
                }
                devengos.add(data);
                $('#data_devengo').trigger('reset');
                $('#modal-devengo').modal('hide');
            }
        },
        eliminar : (id) =>{
            swal('Alerta!','¿Seguro de querer eliminar este devengo?','warning',{buttons:true}).then(res=>{
                if(res){
                    let aux = removeElement(devengos.data,id);
                    console.log(aux);
                    if(aux){
                        if(aux.retencion){
                            total_con_recargo -= aux.valor;
                        }else{
                            total_sin_recargo -= aux.valor;
                        }
                        $(`#devengo-${id}`).remove();
                        actualizarTotales();
                    }

                }
            });
        }
    };

    var deducciones = {
        id : 1,
        data : new Array(),
        agregar : () =>{
            if(verificarFormulario('data_deduccion',2)){
                let aux = valor_deduccion.value.split('%');
                let valor = 0;
                let porcentaje = 0;
                if(aux.length > 1){
                    valor = total_con_recargo * (aux[0]/100);
                    porcentaje = aux[0];
                }else{
                    valor = parseInt(valor_deduccion.value);
                }

                deducciones.data.push({
                    id: deducciones.id,
                    descripcion : descripcion_deduccion.value,
                    porcentaje : porcentaje,
                    valor : valor,
                });

                deducciones.id++;
                deducciones.pintar();
                $('#data_deduccion').trigger('reset');
                $('#modal-deduccion').modal('hide');
            }
        },
        eliminar : (id) =>{
            swal('Alerta!','¿Seguro de querer eliminar esta deduccion?','warning',{buttons:true}).then(res=>{
                if(res){
                    removeElement(deducciones.data,id)
                    deducciones.pintar();
                    //eliminar de la vista
                }
            });
        },
        pintar : () => {
            total_descuentos = 0;
            $('#tabla_deducciones').html(`
                <div class="row">
                    <div class="col-md-6 h-30 bloque">Descripción</div>
                    <div class="col-md-2 h-30 bloque">Porcentaje</div>
                    <div class="col-md-4 h-30 bloque">Valor</div>
                </div>`);
            deducciones.data.forEach(e => {
                if(e.porcentaje != 0){
                    e.valor = total_con_recargo * (e.porcentaje/100);
                }
                total_descuentos += e.valor;
                $('#tabla_deducciones').append(`
                <div class="row">
                    <div class="col-md-6 h-30 bloque">
                        ${e.descripcion}
                        <i onclick="deducciones.eliminar(${e.id})" class="fa fa-trash-o text-danger"></i>
                    </div>
                    <div class="col-md-2 h-30 bloque">${e.porcentaje} % </div>
                    <div class="col-md-4 h-30 bloque">${formatter.format(e.valor)}</div>
                </div>`);
            });
            actualizarTotales();
        }
    };

    function openModal(id){
        console.log('Abriendo..',id);
        $(`#modal-${id}`).modal('show');
        $(`#form_${id}`).trigger('reset');
    }

    // function actualizarValorTransporte(){
    //     actualizarTotales();
    // }

    function actualizarTotales(){
        total_devengado.innerText = formatter.format(total_con_recargo + total_sin_recargo);
        span_total_descuentos.innerText = formatter.format(total_descuentos);
        neto_pagar.innerText = formatter.format(total_con_recargo + total_sin_recargo - total_descuentos);
    }

    function guardar(){

        let data = new FormData();
        data.append('_token',csrf_token);
        data.append('consecutivo',consecutivo.value);
        data.append('empleado',empleado);
        data.append('fecha_inicio',fecha_inicio.value);
        data.append('fecha_fin','{{ $fecha_fin }}');
        data.append('devengos',JSON.stringify(devengos.data));
        data.append('deducciones',JSON.stringify(deducciones.data));

        $.ajax({
            type: "POST",
            url: "{{url('guardarPrestaciones')}}",
            contentType : false,
            processData: false,
            data: data,
            dataType: "json",
            success: function (response) {
                if(response.res){
                    swal('Logrado!',response.msg,'success').then(res=>{
                        window.location = '../../listaLiquidaciones/'+empleado;
                    });
                }else{
                    swal('Error!',response.msg,'error');
                }
            }
        });

    }


</script>