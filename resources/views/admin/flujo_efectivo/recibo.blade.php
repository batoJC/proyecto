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
            }
        
    </style>

    @include('admin.PDF.headFooter')    
    <main>
        <h3>Recibo de traslado de fondo</h3>
        <b>Recibo:</b>
        <p>{{ $flujo->recibo }}</p>
        <b>Fecha:</b>
        <p>{{ date('d-m-Y',strtotime($flujo->fecha)) }}</p>
        <b>Concepto:</b>
        <p>{{ $flujo->concepto }}</p>
        <b>Valor:</b>
        <p>$ {{ number_format($flujo->valor) }}</p>
        <br><br><br>
        @php
            $usuario = Auth::user();
        @endphp
        @for ($i = 0; $i < strlen($usuario->nombre_completo)*1.5; $i++){{'_'}}@endfor
        <h3>{{ mb_strtoupper($usuario->nombre_completo,'UTF-8') }}</h3>
        <h3>C.c. {{ $usuario->numero_cedula }}</h3>
        <h4>Administrador</h4>
    </main>
    </body>
</html>