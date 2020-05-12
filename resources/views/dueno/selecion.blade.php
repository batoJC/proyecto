<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Seleccione su conjunto</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/seleccion.css') }}">
</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<h1>
				Seleccione el conjunto
			</h1>
			<div class="owl-carousel team-content">
				@foreach($conjuntos as $conj)
					<div class="item">
					    <form action="{{ url('selecciono_user/'.$conj->id) }}" method="POST">
					        @csrf
					        <h3 class="text-center">{{ $conj->nombre }}</h3>
					        <br>
					    	<button class="btn btn-default btn-block">
					    		<i class="fa fa-send"></i>
					    		&nbsp; Seleccionar
					    	</button>
					    </form>
	                </div>
				@endforeach
            </div>
		</div>
	</div>
	<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script>
    	// Galeria 1
        // *********
        $('.owl-carousel').owlCarousel({
            autoplay: true,
            autoplayTimeout: 2500,
            autoplayHoverPause:true,
            loop:true,
            margin:50,
            dots: false,
            dotsEach: false,
            navElement: false,
            responsiveClass:true,
            responsive:{
                0:{
                    items:2,
                    navElement:true
                },
                600:{
                    items:4,
                    navElement:false
                },
                1000:{
                    items:4,
                    navElement:true,
                    loop:true,
                },
            }
        });
    </script>
</body>
</html>