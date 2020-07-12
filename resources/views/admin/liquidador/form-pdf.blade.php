{{-- Modal para agregar recordatorios de mantenimientos  --}}
<div id="modal-pdf" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
	
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			<h4 class="modal-title" >Descargar jornadas en pdf</h4>
		</div>
		<div class="modal-body">
            <form id="data_pdf" method="POST" action="{{ url('pdfJornadas') }}">
                @csrf
                <input type="hidden" name="empleado" value="{{ $empleado->id }}">
                <label class="validate-label-1" for="fecha_inicio">Fecha inicio</label>
                <input class="form-control validate-input-1" type="date" id="fecha_inicio" name="fecha_inicio">
                <br>

                <label class="validate-label-2" for="fecha_fin">Fecha fin</label>
                <input class="form-control validate-input-2" type="date" id="fecha_fin" name="fecha_fin">
                <div class="modal-footer">
                    <button type="submit" onclick="return verificarFormulario('data_pdf',2);" type="button" class="btn btn-success form-control">Descargar pdf <i class="fa fa-download"></i></button>
                </div>	
			</form>
	
		</div>
	
		</div>
	</div>
</div>