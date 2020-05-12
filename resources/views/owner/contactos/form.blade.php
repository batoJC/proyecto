{{-- ***************************************** --}}
<!-- Modal -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">x</span>
		        </button>
		        <h4 class="text-center modal-title">
		        	<i class="fa fa-user"></i>
		        	&nbsp; 
		        </h4>
      		</div>
      		<div class="modal-body">
        		<form method="post">
        			@csrf {{ method_field('POST') }}
        			<input type="hidden" name="id" id="id">
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-share"></i>
							<label class="margin-top">
								Correo
							</label>
						</div>
						<div class="col-md-8">
							<input type="text" name="correo" id="correo" class="form-control">
						</div>
					</div>
					<br>
        			{{-- Cada campo --}}
					<div class="row">
						<div class="col-md-4">
							<i class="fa fa-envelope-open-o"></i>
							<label class="margin-top">
								Mensaje
							</label>
						</div>
						<div class="col-md-8">
							<textarea name="mensaje" id="mensaje" cols="30" rows="5" class="form-control"></textarea>
						</div>
					</div>
        		</form>
      		</div>
    	</div>
  	</div>
</div>