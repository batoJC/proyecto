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

            .qr_img{
                float: right;
            }
          
            p{
                text-align: justify !important;
            }

            h3{
                text-align: left !important;
            }
            h4{
                margin-top: 2px;
            }
            th{
                text-align: left;
            }


        </style>

        @include('admin.PDF.headFooter')
        <main>
            
            <img class="qr_img" src="{{public_path() }}/qrcodes/qrcode_{{$carta->id}}.png" alt="QR">
                
            <h4>{{ date('d-m-Y',strtotime($carta->fecha)) }}</h4>
            
            <h5>Señores</h5>
            <h5>PORTEROS Y VIGILANTES</h5>
            <h5>Asunto: {{ $carta->unidad->tipo->nombre }} {{ $carta->unidad->numero_letra }}</h5>

            <br><br><br>

            
            <p>{{ $carta->encabezado }}</p>

            @php
                if($carta->residentes_ingreso->count() > 0){
                    $residentes = $carta->residentes_ingreso;
                }else{
                    $residentes = $carta->residentes_retiro;
                }
            @endphp

            @if ($residentes->count() > 0)
                <h4 class="title">Residentes</h4>
            
                <table>
                    <thead>
                        <tr>
                            <th>Nombre Completo</th>
                            <th>Identificación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($residentes as $residente)
                            <tr>
                                <td>{{ $residente->nombre }} {{ $residente->apellido }}</td>
                                <td>{{ $residente->documento }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            
            @php
                if($carta->vehiculos_ingreso->count() > 0){
                    $vehiculos = $carta->vehiculos_ingreso;
                }else{
                    $vehiculos = $carta->vehiculos_retiro;
                }
            @endphp
            
            @if ($vehiculos->count() > 0)
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
                        @foreach ($vehiculos as $vehiculo)
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
            
            @php
                if($carta->mascotas_ingreso->count() > 0){
                    $mascotas = $carta->mascotas_ingreso;
                }else{
                    $mascotas = $carta->mascotas_retiro;
                }
            @endphp
            
            @if ($mascotas->count() > 0)
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
                        @foreach ($mascotas as $mascota)
                            <tr>
                                <td>{{ $mascota->nombre }}</td>
                                <td>{{ $mascota->tipo->tipo }}</td>
                                <td>{{ $mascota->raza }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            <p>{{ $carta->cuerpo }}</p>
            <br>
            <br>
            <br>
            <br>
            @for ($i = 0; $i < strlen($administrador->nombre_completo)*1.5; $i++){{'_'}}@endfor
            <h3>{{ mb_strtoupper($administrador->nombre_completo,'UTF-8') }}</h3>
            <h3>C.c. {{ $administrador->numero_cedula }}</h3>
            <h4>Administrador</h4>
        </main>
    </body>
</html>
