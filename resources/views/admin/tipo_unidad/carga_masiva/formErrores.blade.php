{{-- ***************************************** --}}
<!-- Modal -->
<div class="modal fade" id="modal-errores" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content modal-padding">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="false">x</span>
				</button>
				<h1 class="modal-title">
					<p class="text-center"> CONSOLIDADOS</p>
				</h1>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12 col-md-6 col-lg-6">
						<h3 class="text-center" style="color:green" id="und-procesadas"></h3>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-6">
						<h3 class="text-center" style="color: red;" id="und-fallidas"></h3>
					</div>
				</div>

				<table class="table table-condensed" id="tabla-errores">
					<thead>
						<tr>
							<th># Registro</th>
							<th>Descripcion del Error</th>
						</tr>
					</thead>
					<tbody id="errores-body">

					</tbody>
				</table>
			</div>

			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>