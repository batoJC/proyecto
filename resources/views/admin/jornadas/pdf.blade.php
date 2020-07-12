@extends('admin.PDF.plantilla')

@section('style')
    <style>
         main{
            margin-left: 10px !important;
            margin-right: 10px !important;
            bottom: 20px !important;
        }

        table{
        	border-collapse: collapse;
        	width: 100%;
        }
        
        th,td{
            border: 1px solid black;
            text-align: center;
        }

        .text-center{
            text-align: center;
            font-size: 25px;
            font-weight: 100;
            font-family: sans-serif;
        }

    </style>
    
@endsection

@section('contenido')
<p><b>Periodo: </b> {{ $periodo }}</p>
<br>
<h3 class="text-center">Información Empleado</h3>
<p>
    <b>Nombre:</b> {{ $empleado->nombre_completo }}<br>
    <b>Identificación:</b> {{ $empleado->cedula }}<br>
    <b>Cargo:</b> {{ $empleado->cargo }}<br>
    <b>Dirección:</b> {{ $empleado->direccion }}<br>
</p>
<br>

<h3 class="text-center">Información jornadas</h3>
<br>
<table class="table">
    <thead>
        <tr>
            <th>Fecha</th>
            <th>Entrada</th>
            <th>Salida</th>
            <th>HOD</th>
            <th>HON</th>
            <th>HODF</th>
            <th>HONF</th>
            <th>HEDO</th>
            <th>HENO</th>
            <th>HEDF</th>
            <th>HENF</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($jornadas as $jornada)
            <tr>
                <td>{{ date('d/m/Y',strtotime($jornada->fecha)) }}</td>
                <td>{{ date('h:i A',strtotime($jornada->entrada)) }}</td>
                <td>{{ date('h:i A',strtotime($jornada->salida)) }}</td>
                <td>{{ $jornada->HOD }}</td>
                <td>{{ $jornada->HON }}</td>
                <td>{{ $jornada->HODF }}</td>
                <td>{{ $jornada->HONF }}</td>
                <td>{{ $jornada->HEDO }}</td>
                <td>{{ $jornada->HENO }}</td>
                <td>{{ $jornada->HEDF }}</td>
                <td>{{ $jornada->HENF }}</td>
            </tr>
        @endforeach

        <tr>
            <td colspan="3">Totales: </td>
            <td>{{ $jornadas->sum('HOD') }}</td>
            <td>{{ $jornadas->sum('HON') }}</td>
            <td>{{ $jornadas->sum('HODF') }}</td>
            <td>{{ $jornadas->sum('HONF') }}</td>
            <td>{{ $jornadas->sum('HEDO') }}</td>
            <td>{{ $jornadas->sum('HENO') }}</td>
            <td>{{ $jornadas->sum('HEDF') }}</td>
            <td>{{ $jornadas->sum('HENF') }}</td>
        </tr>

    </tbody>
</table>

<P style="font-size: 12px;">
    <b>HOD: </b> Horas Ordinarias Diurnas. <br>
    <b>HON: </b> Horas Ordinarias Nocturnas. <br>
    <b>HODF: </b> Horas Ordinarias Diurnas Festivas. <br>
    <b>HONF: </b> Horas Ordinarias Nocturnas Festivas. <br>
    <b>HEDO: </b> Horas Extra Diurnas Ordinarias. <br>
    <b>HENO: </b> Horas Extra Nocturnas Ordinarias. <br>
    <b>HEDF: </b> Horas Extra Diurnas Festivas. <br>
    <b>HENF: </b> Horas Extra Nocturnas Festivas. <br>
</P>

@endsection