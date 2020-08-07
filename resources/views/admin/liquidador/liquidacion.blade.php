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
            <div class="row">
                <div class="col-md-7 h-30 bloque">Descripción</div>
                <div class="col-md-2 h-30 bloque">Horas</div>
                <div class="col-md-3 h-30 bloque">Valor</div>
            </div>
            @php
                $hora_basico = ($empleado->salario / App\Variable::find('horas_jornada')->value);
                $total_horas = 0;
                $total_valor_horas = 0;
            @endphp
            <div class="row">
                <div class="col-md-7 h-30 bloque">Salario</div>
                <div class="col-md-2 h-30 bloque">{{ $jornadas->sum('HOD') }}</div>
                <div class="col-md-3 h-30 bloque">$ {{ number_format($jornadas->sum('HOD')*$hora_basico,2) }}</div>
                @php
                    $total_horas += $jornadas->sum('HOD');
                    $total_valor_horas += $jornadas->sum('HOD')*$hora_basico;
                @endphp
            </div>
            <div class="row">
                <div class="col-md-7 h-30 bloque">Horas Ordinarias Nocturnas</div>
                <div class="col-md-2 h-30 bloque">{{ $jornadas->sum('HON') }}</div>
                @php
                    $incrementar = App\Variable::find('recargo_ordinario_nocturno')->value + 1;
                    $incrementar /= 100;
                @endphp
                <div class="col-md-3 h-30 bloque">$ {{ number_format($jornadas->sum('HON')*$hora_basico*$incrementar,2) }}</div>
                @php
                    $total_horas += $jornadas->sum('HON');
                    $total_valor_horas += $jornadas->sum('HON')*$hora_basico*$incrementar;
                @endphp
            </div>
            <div class="row">
                <div class="col-md-7 h-30 bloque">Horas Ordinarias Diurnas Festivas</div>
                <div class="col-md-2 h-30 bloque">{{ $jornadas->sum('HODF') }}</div>
                @php
                    $incrementar = App\Variable::find('recargo_ordinario_diurno_festivo')->value + 1;
                    $incrementar /= 100;
                @endphp
                <div class="col-md-3 h-30 bloque">$ {{ number_format($jornadas->sum('HODF')*$hora_basico*$incrementar,2) }}</div>
                @php
                    $total_horas += $jornadas->sum('HODF');
                    $total_valor_horas += $jornadas->sum('HODF')*$hora_basico*$incrementar;
                @endphp
            </div>
            <div class="row">
                <div class="col-md-7 h-30 bloque">Horas Ordinarias Nocturnas Festivas</div>
                <div class="col-md-2 h-30 bloque">{{ $jornadas->sum('HONF') }}</div>
                @php
                    $incrementar = App\Variable::find('recargo_ordinario_nocturno_festivo')->value + 1;
                    $incrementar /= 100;
                @endphp
                <div class="col-md-3 h-30 bloque">$ {{ number_format($jornadas->sum('HONF')*$hora_basico*$incrementar,2) }}</div>
                @php
                    $total_horas += $jornadas->sum('HONF');
                    $total_valor_horas += $jornadas->sum('HONF')*$hora_basico*$incrementar;
                @endphp
            </div>
            <div class="row">
                <div class="col-md-7 h-30 bloque">Horas Extras Ordinarias Diurnas</div>
                <div class="col-md-2 h-30 bloque">{{ $jornadas->sum('HEDO') }}</div>
                @php
                    $incrementar = App\Variable::find('hora_extra_ordinaria_diurna')->value;
                @endphp
                <div class="col-md-3 h-30 bloque">$ {{ number_format($jornadas->sum('HEDO')*$hora_basico*$incrementar,2) }}</div>
                @php
                    $total_horas += $jornadas->sum('HEDO');
                    $total_valor_horas += $jornadas->sum('HEDO')*$hora_basico*$incrementar;
                @endphp
            </div>
            <div class="row">
                <div class="col-md-7 h-30 bloque">Horas Extras Ordinarias Nocturnas</div>
                <div class="col-md-2 h-30 bloque">{{ $jornadas->sum('HENO') }}</div>
                @php
                    $incrementar = App\Variable::find('hora_extra_ordinaria_nocturna')->value;
                @endphp
                <div class="col-md-3 h-30 bloque">$ {{ number_format($jornadas->sum('HENO')*$hora_basico*$incrementar,2) }}</div>
                @php
                    $total_horas += $jornadas->sum('HENO');
                    $total_valor_horas += $jornadas->sum('HENO')*$hora_basico*$incrementar;
                @endphp
            </div>
            <div class="row">
                <div class="col-md-7 h-30 bloque">Horas Extras Ordinarias Diurnas Festivas</div>
                <div class="col-md-2 h-30 bloque">{{ $jornadas->sum('HEDF') }}</div>
                @php
                    $incrementar = App\Variable::find('hora_extra_ordinaria_diurna_fesiva')->value;
                @endphp
                <div class="col-md-3 h-30 bloque">$ {{ number_format($jornadas->sum('HEDF')*$hora_basico*$incrementar,2) }}</div>
                @php
                    $total_horas += $jornadas->sum('HEDF');
                    $total_valor_horas += $jornadas->sum('HEDF')*$hora_basico*$incrementar;
                @endphp
            </div>
            <div class="row">
                <div class="col-md-7 h-30 bloque" style="font-size: 16px;">Horas Extras Ordinarias Nocturnas Festivos</div>
                <div class="col-md-2 h-30 bloque">{{ $jornadas->sum('HENF') }}</div>
                @php
                    $incrementar = App\Variable::find('hora_extra_ordinaria_nocturna_festiva')->value;
                @endphp
                <div class="col-md-3 h-30 bloque">$ {{ number_format($jornadas->sum('HENF')*$hora_basico*$incrementar,2) }}</div>
                @php
                    $total_horas += $jornadas->sum('HENF');
                    $total_valor_horas += $jornadas->sum('HENF')*$hora_basico*$incrementar;
                @endphp
            </div>
            <div class="row">
                <div class="col-md-7 h-30 bloque">Total horas:</div>
                <div class="col-md-2 h-30 bloque">{{ $total_horas }}</div>
                <div class="col-md-3 h-30 bloque">$ {{ number_format($total_valor_horas,2) }}</div>
            </div>
            <div class="row">
                <div class="col-md-7 h-30 bloque">Subsidio de transporte:</div>
                <div class="col-md-2 h-30 bloque">
                    <input onchange="actualizarValorTransporte()" value="30" style="text-align: right;" class="w-100" type="number" name="dias_transporte" id="dias_transporte">
                </div>
                <div class="col-md-3 h-30 bloque">
                    <span id="valor_transporte">${{ number_format(App\Variable::find('subsidio_transporte')->value,2) }}</span>
                </div>
            </div>            
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
                    <span id="total_devengado">${{ number_format((App\Variable::find('subsidio_transporte')->value) + $total_valor_horas ,2) }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-8 h-30 bloque">Total descuentos:</div>
                <div class="col-md-4 h-30 bloque">
                    <span id="total_descuentos">$ 0</span>
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
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-8 h-30 bloque">Subsidio de transporte:</div>
                <div class="col-md-4 h-30 bloque">$ {{ number_format(App\Variable::find('subsidio_transporte')->value,2) }}</div>
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
        let valor = total_con_recargo * (4/100);
        let porcentaje = 4;
        deducciones.data.push({
            id: deducciones.id,
            descripcion : 'Seguridad Social Salud',
            porcentaje : porcentaje,
            valor : valor
        });
        deducciones.id++;
        deducciones.data.push({
            id: deducciones.id,
            descripcion : 'Seguridad Social Pension',
            porcentaje : porcentaje,
            valor : valor
        });
        deducciones.id++;
        deducciones.pintar();
    });


    var total_con_recargo = {{ $total_valor_horas }};
    var total_sin_recargo = {{ App\Variable::find('subsidio_transporte')->value }};
    var subsidio_transporte = total_sin_recargo;
    var valor_subsidio_transporte = total_sin_recargo;
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
        agregar : () =>{
            if(verificarFormulario('data_devengo',2)){
                devengos.data.push({
                    id:devengos.id,
                    descripcion : descripcion_devengo.value,
                    valor : parseInt(valor_devengo.value),
                    retencion : retencion.checked
                });
                $('#tabla_devengos').append(`
                    <div id="devengo-${devengos.id}" class="row">
                        <div class="col-md-9 h-30 bloque">
                            ${descripcion_devengo.value}
                            <i onclick="devengos.eliminar(${devengos.id})" class="fa fa-trash-o text-danger"></i>
                        </div>
                        <div class="col-md-3 h-30 bloque">${formatter.format(valor_devengo.value)}</div>
                    </div>
                `);

                //falta genera retención
                devengos.id++;
                if(retencion.checked){
                    total_con_recargo += parseInt(valor_devengo.value);
                    deducciones.pintar();
                }else{
                    total_sin_recargo += parseInt(valor_devengo.value);
                }
                actualizarTotales();
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
                    <div class="col-md-2 h-30 bloque">Valor porcentual</div>
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

    function actualizarValorTransporte(){
        total_sin_recargo -= valor_subsidio_transporte;
        valor_subsidio_transporte = subsidio_transporte*dias_transporte.value/30;
        total_sin_recargo += valor_subsidio_transporte;
        valor_transporte.innerText = formatter.format(valor_subsidio_transporte);
        actualizarTotales();
    }

    function actualizarTotales(){
        total_devengado.innerText = formatter.format(total_con_recargo + total_sin_recargo);
        total_descuentos.innerText = formatter.format(total_descuentos);
        neto_pagar.innerText = formatter.format(total_con_recargo + total_sin_recargo - total_descuentos);
    }

    function guardar(){

        let data = new FormData();
        data.append('_token',csrf_token);
        data.append('consecutivo',consecutivo.value);
        data.append('empleado',empleado);
        data.append('fecha_inicio',fecha_inicio.value);
        data.append('fecha_fin',fecha_fin.value);
        data.append('dias_transporte',dias_transporte.value);
        data.append('devengos',JSON.stringify(devengos.data));
        data.append('deducciones',JSON.stringify(deducciones.data));

        $.ajax({
            type: "POST",
            url: "{{url('liquidacion')}}",
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