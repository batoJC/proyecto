@extends('admin.PDF.plantilla')

@section('style')
    <style>
        @page {
            size: A4 landscape;
        }

        /* @page {
            size: A4 portrait;
        } */

        table{
            margin-left: -25px;
            margin-right: -25px; 
        	border-collapse: collapse;
        	width: 100%;
        	border-top: 2px solid black;
        	border-bottom: 2px solid black;
        }

        th{
            text-align: left;
            font-size: 11px;
            text-align: center;
        	border: 2px solid black;
        }

        td{
            border: 1px solid grey;
            font-size: 11px;
            text-align: center;
        }

        .imagen{
            width: auto;
            height: 180px;
            display: inline-block;
        }

    </style>
    
@endsection

@section('contenido')
<h2>Inventario general</h2>
<br>
<table>
    <thead>
        <tr>
            <th>Nombre</th>
            <th>Ubicación</th>
            <th>Descripción</th>
            <th>Condición</th>
            <th>Valor</th>
            <th>Fecha garantía</th>
            <th>Fecha compra</th>
            <th>Fabricante</th>
            <th>Estilo</th>
            <th>N° serie</th>
            <th>Observaciones</th>
            <th>Foto</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($inventario as $articulo)
            <tr>
                <td>{{ $articulo->nombre }}</td>
                <td>{{ $articulo->ubicacion }}</td>
                <td>{{ $articulo->descripcion }}</td>
                <td>{{ $articulo->condicion }}</td>
                <td>${{ number_format($articulo->valor) }}</td>
                <td>{{ ($articulo->valido_hasta)? date('d-m-Y',strtotime($articulo->valido_hasta)) : 'No aplica' }}</td>
                <td>{{ ($articulo->fecha_compra)? date('d-m-Y',strtotime($articulo->fecha_compra)) : 'No aplica' }}</td>
                <td>{{ $articulo->fabricante }}</td>
                <td>{{ $articulo->estilo }}</td>
                <td>{{ $articulo->numero_serie }}</td>
                <td>{{ $articulo->observaciones }}</td>
                <td>
                    @php
                        $fotos = explode(';',$articulo->foto);
                        $n = 0;
                        @endphp
                    <div class="div_imagenes">
                        <br><br>
                        @foreach ($fotos as $foto)
                            foto_{{$articulo->id}}{{ $n }}.{{ explode('.',$foto)[1] }}
                            @php
                                $n++;
                            @endphp
                        @endforeach
                    </div>
                </td>

            </tr>
        @endforeach
    </tbody>
</table>

{{-- @foreach ($inventario as $articulo)
    <p>
        <b>Nombre:</b> {{ $articulo->nombre }} <br>
        <b>Ubicación:</b> {{ $articulo->ubicacion }} <br>
        <b>Descripción:</b> {{ $articulo->descripcion }} <br>
        <b>Condición:</b> {{ $articulo->condicion }} <br>
        <b>Valor:</b> ${{ number_format($articulo->valor) }} <br>
        <b>Fecha garantía:</b> {{ ($articulo->valido_hasta)? date('d-m-Y',strtotime($articulo->valido_hasta)) : 'No aplica' }} <br>
        <b>Fecha compra:</b> {{ ($articulo->fecha_compra)? date('d-m-Y',strtotime($articulo->fecha_compra)) : 'No aplica' }} <br>
        <b>Fabricante:</b> {{ $articulo->fabricante }} <br>
        <b>Estilo:</b> {{ $articulo->estilo }} <br>
        <b>Número de serie:</b> {{ $articulo->numero_serie }} <br>
        <b>Observaciones:</b> {{ $articulo->observaciones }} <br>
        @php
            $fotos = explode(';',$articulo->foto);
            @endphp
        <div class="div_imagenes">
            <b>Fotos:</b><br><br><br>
            @foreach ($fotos as $foto)
                <img class="imagen" src="{{ public_path("imgs/private_imgs/{$foto}") }}" alt="">
            @endforeach
        </div>
    </p>
    <hr>
    <br>
@endforeach --}}


@endsection