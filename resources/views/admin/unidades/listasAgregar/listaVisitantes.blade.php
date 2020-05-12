{{-- Vista para agregar recidentes a una unidad --}}

<h3>Visitantes Frecuentes</h3>
<br>

{{-- Modal para agregar visitantes  --}}
<div id="visitantes" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" >Agregar visitantes</h4>
      </div>
      <div class="modal-body">
        <form id="dataVisitante">
            

            <label class="validate-label-1" for="identificacion_visitante">identificación</label>
            <input class="form-control validate-label-1" type="text" id="identificacion_visitante" name="identificacion_visitante">
            <br>

            <label class="validate-label-2" for="nombre_visitante">Nombre Completo</label>
            <input class="form-control validate-input-2" type="text" id="nombre_visitante" name="nombre_visitante">
            <br>

            <label class="validate-label-3" for="parentesco_visitante">Parentesco / Otro</label>
            <input type="text" class="form-control validate-input-3" name="parentesco_visitante" id="parentesco_visitante">
            <br>

        </form>

      </div>
      <div class="modal-footer">
        <button onclick="guardarVisitante();" type="button" class="btn btn-primary">Guardar</button>
      </div>

    </div>
  </div>
</div>




<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#visitantes"><i class="fa fa-plus"></i> Agregar visitante</button>
<br>

<table class="table">
    <thead>
        <tr>
            <th>Identificación</th>
            <th>Nombre completo</th>
            <th>Parentesco</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody id="data-visitantes">
    </tbody>
</table>

<hr>

<script>

    //variables para controlar la lista de visitantes
    /**********************************************/
    var listaVisitantes = new Array();
    var nroVisitantes = 0;



    //metodos de guardado de la lista de visitantes en el cliente
    /**********************************************************/
    function guardarVisitante(){

        if(verificarFormulario('dataVisitante',3)){
            

            let auxVisitante = {
                id: nroVisitantes,
                nombre: nombre_visitante.value,
                identificacion: identificacion_visitante.value,
                parentesco: parentesco_visitante.value
            }

            listaVisitantes.push(jQuery.extend(true, {}, auxVisitante));


            $('#data-visitantes').append(`
                <tr data="${nroVisitantes}">
                    <td>${auxVisitante.identificacion}</td>
                    <td>${auxVisitante.nombre}</td>
                    <td>${auxVisitante.parentesco}</td>
                    <td>
                        <a class="btn btn-danger btn-lg" onclick="eliminarVisitante(${nroVisitantes})">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            `);

            nroVisitantes++;

            swal('Logrado!','Visitante agregado corretamente','success').then(()=>{
                $('#dataVisitante').trigger('reset');
                $('#visitantes').modal('hide');
            });

        }
    }

    function eliminarVisitante(id){
        swal({
            title : 'Advertencia!',
            text : '¿Seguro de querer eliminar este visitante?',
            icon : 'warning',
            buttons :true
        }).then((res)=>{
            if(res){
                listaVisitantes.splice(searchVisitante(id),1);
                $(`#data-visitantes tr[data=${id}]`).remove();
            }
        });

    }

    function searchVisitante(id){
        for (var i = 0; i < listaVisitantes.length; i++) {
            if (listaVisitantes[i].id == id) {
                return i;
            }
        }
        return null;
    }


</script>