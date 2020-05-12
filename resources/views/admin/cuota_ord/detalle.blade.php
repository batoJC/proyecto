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
            <td>{{ $item['nombre'] }}</td>
            <td>$ {{ number_format($item['valor']) }}</td>
            <td>{{ $item['estado'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>