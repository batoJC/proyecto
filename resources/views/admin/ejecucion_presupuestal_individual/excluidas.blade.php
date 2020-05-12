<table class="table">
    <thead>
        <tr>
            <th>División</th>
            <th>Unidad</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($unidades as $unidad)
            <tr>
                <td>{{ $unidad->division->tipo_division->division }} {{ $unidad->division->numero_letra }}</td>
                <td>{{ $unidad->tipo->nombre }} {{ $unidad->numero_letra }}</td>
            </tr>
        @endforeach
    </tbody>
</table>