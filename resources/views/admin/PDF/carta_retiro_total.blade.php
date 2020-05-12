<html>
<body>
    <style>
        *{
            font-family: sans-serif;
        }
    
        h5{
            margin: 4px 0px 4px 0px;
        }

        h2{
            text-align: center;
            margin-top: 2px;
            margin-bottom: 2px;
        }
    
        table{
            width: 100%;
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

        <h4>Ref. Salida de bienes</h4>
        
        
        <p>Les manifiesto que el propietario del {{ $carta->unidad->tipo->nombre }} {{ $carta->unidad->numero_letra }} está autorizado para <b>RETIRAR</b> trasteo de bienes propios o de sus residentes, totalmente hasta día @php
            $fecha = $carta->fecha;
            $nuevafecha = strtotime ( '+7 day' , strtotime ( $fecha ) ) ;
            $nuevafecha = date ('Y-m-d' , $nuevafecha );
            echo $nuevafecha;
        @endphp</p>

        <p>{{ $carta->cuerpo }}</p>
    </main>
</body>
</html>
