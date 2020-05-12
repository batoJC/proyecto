{{-- ***************************************** --}}
<!-- Modal -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" data-backdrop="static">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		        	<span aria-hidden="true">x</span>
		        </button>
		        <h4 class="text-center modal-title">
		        	Cuenta de Cobro
		        </h4>
      		</div>
      		<div class="modal-body">
        		<div class="row">
        			<div class="col-4 col-md-4">
        				<label for="">Fecha</label>
        				<input class="form-control" id="fecha" type="text" disabled>
        			</div>
        			<div class="col-4 col-md-4">
        				<label for="">Tipo de Unidad</label>
        				<input class="form-control" id="unidad" type="text" disabled>
        			</div>
        			<div class="col-4 col-md-4">
        				<label for="">Consecutivo</label>
        				<input class="form-control" id="consecutivo" type="text" disabled>
        			</div>
        		</div>
        		<br>
        		<div class="row">
        			<table class="table">
        				<thead>
        					<tr>
                                <th>Fecha Inicio</th>
        						<th>Fecha de Vencimiento</th>
        						<th>Descripcion</th>
        						<th>Valor</th>
                                <th>interes</th>
        						<th>Total</th>
        					</tr>
        				</thead>
        				<tbody id="pagosData"></tbody>
        			</table>
        		</div>
      		</div>
    	</div>
  	</div>
</div>