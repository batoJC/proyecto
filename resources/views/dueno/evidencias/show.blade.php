<style>
	.images{
		display: table;
	}

	img.foto_e {
		height: 100px !important;
		width: auto !important;
		display: unset !important;
		margin: 5px !important;
		cursor: pointer;
	}
</style>

{{-- Modal para ver la evidencia  --}}
<div id="infoEvidencia" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
	
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" >Evidencia del conjunto</h4>
			</div>
			<div class="modal-body">
				<h2><b>Fecha: </b> <p id="fecha_info">2019-23-43</p> </h2>
				<div class="images">
					{{-- <img class="foto show_img" src="" alt=""> --}}
				</div>
				<h4 id="contenido_info">

				</h4>
			</div>	
		</div>
	</div>
</div>

<script>
    function ver(id){
        $('#infoEvidencia').modal('show');
        $.ajax({
            type: "GET",
            url: "{{ url('evidencias') }}/"+id,
            data: {
                '_token' : $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "json"
        }).done(res => {
            console.log(res);
            fecha_info.innerText = res.fecha;
            contenido_info.innerText = res.contenido;
            let aux = res.fotos.split(';');
            $('.images').html('')
            aux.forEach(e => {
                $('.images').append(`<img class="foto_e show_img" src="{{ asset('') }}imgs/private_imgs/${e}" alt="foto">`);
            });
            $('.show_img').click(function(e) {
                $('#show_image img')[0].src = $(e)[0].currentTarget.src;
                $('#show_image').css("display", "flex")
            .hide().fadeIn(400);
            });
        });
    }

</script>