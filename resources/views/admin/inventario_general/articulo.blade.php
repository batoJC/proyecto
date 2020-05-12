
Fotos:
<br>
@foreach (explode(';', $articulo->foto) as $foto)
<img class="foto show_img" src="/imgs/private_imgs/{{ $foto }}" alt="">
@endforeach


<h4><b>Nombre:</b>  {{ $articulo->nombre }}</h4>
<h4><b>Ubicación:</b>  {{ $articulo->ubicacion }}  </h4>
<h4><b>Descripción:</b>  {{ $articulo->descripcion }}</h4>
<h4><b>Condición:</b>  {{ $articulo->condicion }}</h4>
<h4><b>Valor:</b>  ${{ number_format($articulo->valor) }}</h4>
<h4><b>Garantía:</b>  {{ ($articulo->garantia)? 'Tiene garantía' : 'No tiene garantía'}}</h4>
@if ($articulo->garantia)
    <h4><b>Valida hasta:</b>  {{ date('d-m-Y',strtotime($articulo->valido_hasta)) }}</h4>
@endif
<h4><b>Fecha de compra:</b>  {{ date('d-m-Y',strtotime($articulo->fecha_compra)) }}</h4>
<h4><b>Fabricante:</b>  {{ $articulo->fabricante }}</h4>
<h4><b>Estilo:</b>  {{ $articulo->estilo }}</h4>
<h4><b>No. Serie:</b>  {{ $articulo->numero_serie }}</h4>
<h4><b>Observaciones:</b>  {{ $articulo->observaciones }}</h4>


