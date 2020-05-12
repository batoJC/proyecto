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

            b{
                font-weight: 600;
                margin: 2px;
            }

            p{
                margin: 2px;
                font-weight: 100;
                margin-bottom: 15px !important;
                border-bottom: 1px solid red;
            }

            table{
                border-collapse: collapse;
            }

            th,td{
                margin: 0px !important;
                border: 1px solid black;
                font-size: 13px;
                text-align: center;
            }

            main{
                margin: 5px !important;
            }
        
    </style>

    @include('admin.PDF.headFooter')    
    <main>
        
    <h3>Lista de residentes activos con una edad entre {{ $edad_inicio}} y {{$edad_fin}} años el {{ date('d').' del mes '.date('m').' en el año '.date('Y') }}</h3>
    <table>
        <thead>
            <tr>
                <th>Edad</th>
                <th>Nombre completo</th>
                <th>Identificación</th>
                <th>Tipo de residente</th>
                <th>Ocupación</th>
                <th>Lugar de trabajo</th>
                <th>Email</th>
                <th>Genero</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($residentes as $residente)
                <tr>
                    <td>{{ date_diff(
                        date_create(date('Y-m-d')),
                        date_create($residente->fecha_nacimiento)
                    )->format('%y')}}</td>
                    <td>{{$residente->nombre}} {{ $residente->apellido }}</td>
                    <td>{{$residente->documento}}</td>
                    <td>{{$residente->tipo_residente}}</td>
                    <td>{{$residente->ocupacion}}</td>
                    <td>{{$residente->direccion}}</td>
                    <td>{{$residente->email}}</td>
                    <td>{{$residente->genero}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </main>
    </body>
</html>