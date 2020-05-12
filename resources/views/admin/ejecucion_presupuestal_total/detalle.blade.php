<table class="table">
    <thead>
        <tr>
            <th>Tipo de Ejecución</th>
            <th>Porcentaje del Total</th>
            <th>Porcentaje Ejecutado</th>
            <th>Valor Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ejecucionPreIndividual as $individual)
            <tr>
                <td>{{ $individual->Tipo_ejecucion_pre->tipo }}</td>
                <td>{{ $individual->porcentaje_total() }} %</td>
                <td>${{ number_format($individual->totalEjecuado()) }}  ({{ $individual->porcentaje_ejecutado()}} )</td>
                <td>${{ number_format($individual->total) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>