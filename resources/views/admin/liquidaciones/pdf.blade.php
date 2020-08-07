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
                margin: 0px;
                padding: 0 !important;
                margin: 0px;

            }

            td{
                text-align: center;
                border: 1px solid black;
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

        </style>
        <main>
            <table cellpadding="0" cellspacing="0">
                <thead>
                    <tr>
                        <th colspan="1">
                            <img src="{{ public_path() }}/imgs/logos_conjuntos/{{ $liquidacion->empleado->conjunto->logo }}" alt="">
                        </th>
                        <th colspan="4">
                            {{ $liquidacion->empleado->conjunto->nombre }} - {{ $liquidacion->empleado->conjunto->nit }}
                        </th>
                        <th colspan="1">
                            {{ date('d/m/Y',strtotime($liquidacion->fecha)) }}
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
                                        <th>Descripción</th>
                                        <th>Horas</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead>
                                <tbody>{{-- con horas --}}
                                    @foreach ($liquidacion->devengos()->where('horas','!=',null)->get() as $devengo)
                                        <tr>
                                            <td>{{ $devengo->descripcion }}</td>
                                            <td>{{ $devengo->horas }}</td>
                                            <td>$ {{ number_format($devengo->valor,2) }}</td>
                                        </tr>
                                    @endforeach
                                    {{-- totales --}}
                                        <tr>
                                            <td>Total horas</td>
                                            <td>{{ $liquidacion->devengos()->where('horas','!=',null)->sum('horas')  }}</td>
                                            <td>$ {{ number_format($liquidacion->devengos()->where('horas','!=',null)->sum('valor'),2)  }}</td>
                                        </tr>
                                        <tr>
                                            <td>Subsidio transporte</td>
                                            <td>{{ $liquidacion->dias_transporte }}</td>
                                            <td>$ {{ number_format($liquidacion->dias_transporte*$liquidacion->subsidio_transporte/30,2) }}</td>
                                        </tr>
                                    {{-- sin horas --}}
                                    @foreach ($liquidacion->devengos()->where('horas','=',null)->get() as $devengo)
                                        <tr>
                                            <td colspan="2">{{ $devengo->descripcion }}</td>
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
                                        <th>Valor porcentual</th>
                                        <th>Valor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($liquidacion->deducciones as $deduccion)
                                        <tr>
                                            <td>{{ $deduccion->descripcion }}</td>
                                            <td>{{ $deduccion->descuento }} % </td>
                                            <td>$ {{ number_format($deduccion->valor,2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>Total devengado: </td>
                        <td colspan="2">$ {{ number_format($liquidacion->total_devengos(),2) }}</td>
                        <td colspan="2">Total descuentos: </td>
                        <td>$ {{ number_format($liquidacion->total_deducciones(),2) }}</td>
                    </tr>
                    <tr>
                        <td>Salario básico: </td>
                        <td colspan="2">$ {{ number_format($liquidacion->salario,2) }}</td>
                        <td  colspan="2">Neto a pagar: </td>
                        <td>$ {{ number_format($liquidacion->total(),2) }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
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
