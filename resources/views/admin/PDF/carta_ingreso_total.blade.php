
 <html>
    <body>
        <style>
            *{
                font-family: sans-serif;
            }

            h5{
                margin: 4px 0px 4px 0px;
            }

            table{
                width: 100%;
            }

            h2{
                text-align: center;
                margin-top: 2px;
                margin-bottom: 2px;
            }


            .title{
                margin-bottom: 5px;
                font-weight: 200;
                font-size: 22px;
            }

            .header{
                width: 100%;
                height: 100px;
                text-align: center;
            }

            .header img{
                height: 100%;
                width: auto;
            }

        </style>


        @include('admin.PDF.headFooter')
        <main>

            <h4>{{ date('d-m-Y',strtotime($carta->fecha)) }}</h4>

            <h5>Señores</h5>
            <h5>PORTEROS Y VIGILANTES</h5>
            <h5>Asunto: {{ $carta->unidad->tipo->nombre }} {{ $carta->unidad->numero_letra }}</h5>


            <p>Les manifiesto que el propietario del {{ $carta->unidad->tipo->nombre }} {{ $carta->unidad->numero_letra }} lo alquiló al grupo familiar de las siguientes personas quienes son las autorizadas para ingresar a dicho {{ $carta->unidad->tipo->nombre }}.</p>

            @if ($carta->residentes->count() > 0)
                <h4 class="title">Residentes</h4>

                <table>
                    <thead>
                        <tr>
                            <th>Nombre Completo</th>
                            <th>Identificación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carta->residentes as $residente)
                            <tr>
                                <td>{{ $residente->nombre }} {{ $residente->apellido }}</td>
                                <td>{{ $residente->documento }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif


            @if ($carta->vehiculos->count() > 0)
                <h4 class="title">Vehículos</h4>

                <table>
                    <thead>
                        <tr>
                            <th>PLaca</th>
                            <th>Tipo</th>
                            <th>Marca</th>
                            <th>Color</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carta->vehiculos as $vehiculo)
                            <tr>
                                <td>{{ $vehiculo->placa }}</td>
                                <td>{{ $vehiculo->tipo }}</td>
                                <td>{{ $vehiculo->marca }}</td>
                                <td>{{ $vehiculo->color }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif


            @if ($carta->mascotas->count() > 0)
                <h4 class="title">Mascotas</h4>

                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Raza</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($carta->mascotas as $mascota)
                            <tr>
                                <td>{{ $mascota->nombre }}</td>
                                <td>{{ $mascota->tipo->tipo }}</td>
                                <td>{{ $mascota->raza }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif


            <p>Por lo anterior están autorizadas para efectuar trasteo de bienes de los inquilinos, parcial o totalmente hasta el día @php
                $fecha = $carta->fecha;
                $nuevafecha = strtotime ( '+7 day' , strtotime ( $fecha ) ) ;
                $nuevafecha = date ('Y-m-d' , $nuevafecha );
                echo $nuevafecha;
            @endphp</p>

            <p>{{ $carta->cuerpo }}</p>


            <p>Se le hace entrega el MANUAL DE CONVIVENCIA y se le ilustra sobre el cumplimiento además de las normas del reglamento de la propiedad horizontal.</p>

        </main>
    </body>
</html>