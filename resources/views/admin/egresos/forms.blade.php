{{-- Modal para agregar recordatorios de mantenimientos  --}}
<div id="buscar" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
	
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" >Buscar egreso</h4>
		</div>
		<div class="modal-body">
			<form id="buscarForm" class="container-fluid">
				@csrf			
				
				<div class="row">
					<div class="col-12 col-md-6">
						<label class="validate-label-1" for="prefijo">Prefijo</label>
						<input class="form-control validate-input-1" type="text" id="prefijo" name="prefijo">
					</div>
					<div class="col-12 col-md-6">
						<label class="validate-label-2" for="numero">Número</label>
						<input class="form-control validate-input-2" type="text" id="numero" name="numero">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-12 text-center">
						<button type="button" onclick="buscar()"class="btn btn-success">Buscar</button>
					</div>
				</div>

			</form>
	
		</div>
	
		</div>
	</div>
</div>




<div id="listar" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
	
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" >Listar egresos</h4>
		</div>
		<div class="modal-body">
			<form id="listarForm" class="container-fluid">
				@csrf			
				
				<div class="row">
					<div class="col-12 col-md-6">
						<label class="validate-label-1" for="fecha_inicio">Fecha inicio</label>
						<input class="form-control validate-input-1" type="date" value="{{ $fecha_inicio }}" id="fecha_inicio" name="fecha_inicio">
					</div>
					<div class="col-12 col-md-6">
						<label class="validate-label-2" for="fecha_fin">Fecha fin</label>
						<input class="form-control validate-input-2" type="date" value="{{ $fecha_fin }}" id="fecha_fin" name="fecha_fin">
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-12 text-center">
						<button onclick="listar()" type="button" class="btn btn-success">Listar</button>
					</div>
				</div>

			</form>
	
		</div>
	
		</div>
	</div>
</div>



