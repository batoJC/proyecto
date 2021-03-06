<html>
    <head>
    </head>
    <body>
        
        <style>
            @page{
                size: A4 landscape;
                margin: 1cm;
                font-size: 15px;
            }

            table{
                /* border-collapse:initial; */
                width: 100%;
            }

            

            th{
                text-align: center;
                border: 1px solid black;
                font-family: sans-serif;
                font-weight: normal !important;
                margin: 2px;
                padding: 3px !important;
                margin: 0px;

            }

            td{
                text-align: center;
                /* border: 1px solid black; */
                font-family: sans-serif;
                margin: 0px;
                padding: 0 !important;
                margin: 0px;
            }

            .grey{
                background: grey;
                color: white;
            }

            main{
                font-family: sans-serif;
            }

            img{
                width: auto;
                height: 100px;
            }

            .border-none{
                border: none;
            }

            h3{
                text-align: left !important;
                margin-top: 2px;
                margin-bottom: 2px;
            }
            h4{
                margin-top: 2px;
            }

            .text-left{
                text-align: left !important;
                padding-left: 5px !important;
                /* border-left: 1px solid black; */
            }

            .text-right{
                text-align: right !important;
                /* border-right: 1px solid black; */

            }
        </style>
        <main>
                <table cellpadding="0" cellspacing="0" style="border-bottom: 1px solid black;">
                <thead>
                    <tr>
                        <th colspan="1">
                            <img src="{{ public_path() }}/imgs/logos_conjuntos/{{ $liquidacion->empleado->conjunto->logo }}" alt="">
                        </th>
                        <th colspan="4">
                            {{ $liquidacion->empleado->conjunto->nombre }} - {{ $liquidacion->empleado->conjunto->nit }}
                        </th>
                        <th colspan="1">
                            <img class="qr_img" src="{{public_path() }}/qrcodes/qrcode_liquidacion_{{$liquidacion->id}}.png" alt="QR">
                        </th>
                    </tr>
                    <tr>
                        <th>Nombre:</th>
                        <th colspan="2">{{ $liquidacion->empleado->nombre_completo }}</th>
                        <th>Identificación:</th>
                        <th colspan="2">{{ $liquidacion->empleado->cedula }}</th>
                    </tr>
                    <tr>
                        <th>Consecutivo:</th>
                        <th colspan="2">{{ $liquidacion->consecutivo }}</th>
                        <th>Periodo:</th>
                        <th colspan="2">{{ $liquidacion->periodo }}</th>
                    </tr>
                    </tr>
                </thead>
                <tbody>
                    <tr class="grey">
                        <td colspan="3">DEVENGOS</td>
                        <td colspan="3">DEDUCCIONES</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="vertical-align:baseline;">
                            <table cellpadding="0" cellspacing="0">
                                <thead>
                                    <tr>
                                        @if ($liquidacion->tipo == 'liquidacion')
                                            <th>Descripción</th>
                                            <th>Horas</th>
                                            <th>Valor</th>
                                        @else
                                            <th colspan="2">Descripción</th>
                                            <th>Valor</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>{{-- con horas --}}
                                    @foreach ($liquidacion->devengos()->where('horas','!=',null)->get() as $devengo)
                                        <tr>
                                            <td class="text-left">{{ $devengo->descripcion }}</td>
                                            <td>{{ $devengo->horas }}</td>
                                            <td>$ {{ number_format($devengo->valor,2) }}</td>
                                        </tr>
                                    @endforeach
                                    @if ($liquidacion->tipo == 'liquidacion')
                                        
                                    {{-- totales --}}
                                    <tr>
                                        <td class="text-left">Total horas</td>
                                        <td>{{ $liquidacion->devengos()->where('horas','!=',null)->sum('horas')  }}</td>
                                        <td>$ {{ number_format($liquidacion->devengos()->where('horas','!=',null)->sum('valor'),2)  }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-left">Subsidio transporte</td>
                                        <td>{{ $liquidacion->dias_transporte }}</td>
                                        <td>$ {{ number_format($liquidacion->dias_transporte*$liquidacion->subsidio_transporte/30,2) }}</td>
                                    </tr>
                                    {{-- sin horas --}}
                                    @endif
                                    @foreach ($liquidacion->devengos()->where('horas','=',null)->get() as $devengo)
                                        <tr>
                                            <td class="text-left" colspan="2">{{ $devengo->descripcion }}</td>
                                            <td>$ {{ number_format($devengo->valor,2) }}</td>
                                        </tr>
                                    @endforeach
                
                                </tbody>
                            </table>
                        </td>
                        <td colspan="3" style="vertical-align:baseline;">
                            <table cellpadding="0" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Descripción</th>
                                        <th>Porcentaje</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($liquidacion->deducciones as $deduccion)
                                        <tr>
                                            <td class="text-left" >{{ $deduccion->descripcion }}</td>
                                            <td>{{ $deduccion->descuento }} % </td>
                                            <td>$ {{ number_format($deduccion->valor,2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="border-top: 1px solid black !important;" class="text-left">Total devengado: </td>
                        <td style="border-top: 1px solid black !important;padding-right:25px !important;" class="text-right" colspan="2">$ {{ number_format($liquidacion->total_devengos(),2) }}</td>
                        <td style="border-top: 1px solid black !important;" class="text-left" colspan="2">Total descuentos: </td>
                        <td style="border-top: 1px solid black !important;padding-right:25px !important;" class="text-right">$ {{ number_format($liquidacion->total_deducciones(),2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-left">Salario básico: </td>
                        <td style="padding-right:25px !important;" class="text-right" colspan="2">$ {{ number_format($liquidacion->salario,2) }}</td>
                        <td class="text-left"  colspan="2">Neto a pagar: </td>
                        <td style="padding-right:25px !important;" class="text-right">$ {{ number_format($liquidacion->total(),2) }}</td>
                    </tr>
                </tbody>
            </table>
            <br>
            <br>
            <br>
            <br>
            @for ($i = 0; $i < strlen($liquidacion->empleado->nombre_completo)*1.5; $i++){{'_'}}@endfor
            <h3>{{ mb_strtoupper($liquidacion->empleado->nombre_completo,'UTF-8') }}</h3>
            <h3>C.c. {{ $liquidacion->empleado->cedula }}</h3>
            <h4>Empleado</h4>
        </main>
    </body>
</html>
