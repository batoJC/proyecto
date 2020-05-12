@extends('admin.PDF.plantilla')

@section('style')

    <style>
        main{
            margin-left: 0px !important;
            margin-right: 0px !important;
        }

        .borde{
            border-radius: 10px;
            border: 1px solid #333;
            padding: 10px;
            width: 100%;
        }

        .red{
            color: red;
        }

        .text-center{
            text-align: center;
        }

        .text-left{
            text-align: left;
        }

        h1,h4{
            margin: 2px 0px 2px 0px;
            font-weight: 200;
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

        p{
            margin-bottom: 2px;
        }

    </style>
    
@endsection

@section('contenido')
<table class="table">
    <thead>
        <tr>
            <th>Unidad</th>
            <th>Valor</th>
            <th>Estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cuotas as $item)
        <tr>
            <td>{{ $item->tipo->nombre }} {{ $item->numero_letra }}</td>
            <td>$ {{ number_format($item->pivot->valor) }}</td>
            <td>{{ $item->pivot->estado }}</td>
        </tr>
        @endforeach
    </tbody>
</table>



@endsection
