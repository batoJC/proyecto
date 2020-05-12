{{-- Modal para mostrar toda la info de una unidad --}}
<!-- modals -->
<!-- Large modal -->

<div class="modal fade info" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title" id="myModalLabel">Información de la unidad</h4>
        </div>
        <div id="body_info" class="modal-body">
        </div>
        </div>
    </div>
</div>



<script>

    function loadData(id){
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "GET",
            url: "{{ url('unidades') }}/"+id,
            data: {'_token':csrf_token},
            dataType: "html",
            success: function (response) {
                $('#body_info').html(response);
                $('.info').modal('show');
                $('.show_img').click(function(e) {
                    $('#show_image img')[0].src = $(e)[0].currentTarget.src;
                    $('#show_image').css("display", "flex")
                .hide().fadeIn(400);
                });
                $('[data-toggle="tooltip"]').tooltip();
            }
        });
    }

</script>