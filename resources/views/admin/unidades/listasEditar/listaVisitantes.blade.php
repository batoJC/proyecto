{{-- Vista para agregar recidentes a una unidad --}}

<h3>Visitantes Frecuentes</h3>
<br>

{{-- Modal para agregar mascotas  --}}
<div id="visitantes" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" >Editar visitantes</h4>
      </div>
      <div class="modal-body">
        <form id="dataVisitante">
            <input type="hidden" name="id_visitante" id="id_visitante">

            <label class="validate-label-1" for="identificacion_visitante">identificación</label>
            <input class="form-control validate-input-1" type="text" id="identificacion_visitante" name="identificacion_visitante">
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




<button type="button" class="btn btn-primary" onclick="openModalAddVisitante()">Agregar visitante</button>
<br>

<table class="table">
    <thead>
        <tr>
            <th>Identificación</th>
            <th>Nombre completo</th>
            <th>Parentesco / Otro</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody id="data-visitantes">
        @foreach ($unidad->visitantes()->where('estado','Activo')->get() as $visitante)
            <tr data="visitante_{{$visitante->id}}">
                <td>{{ $visitante->identificacion }}</td>
                <td>{{ $visitante->nombre }}</td>
                <td>{{ $visitante->parentesco }}</td>
                <td>
                    <a class="btn btn-lg btn-default" onclick="consultarVisitante({{ $visitante->id }})">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a class="btn btn-lg btn-default" onclick="desactivarVisitante({{ $visitante->id }})">
                        <i class="fa fa-times"></i>
                    </a>                        
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<hr>

<script>

    //variables para controlar la lista de visitantes
    /**********************************************/
    var listaVisitantes = new Array();
    var nroVisitantes = 0;

    function openModalAddVisitante(){
        $('#dataVisitante').trigger('reset');
        $('#id_visitante').val('');
        $('#visitantes').modal('show');
    }



    //metodos de guardado de la lista de visitantes en el cliente
    /**********************************************************/
    function guardarVisitante(){

        if(verificarFormulario('dataVisitante',3)){
            let id = $('#id_visitante').val();
            if(id != ''){
                let auxVisitante = {
                    id: id,
                    action: 'editar',
                    nombre: nombre_visitante.value,
                    identificacion: identificacion_visitante.value,
                    parentesco: parentesco_visitante.value
                }

                //agregar a la lista o reempleazar el ya registrado
                let busqueda = searchVisitante('id',id);
                if(busqueda != null){
                    listaVisitantes.splice(busqueda, 1, auxVisitante);
                }else{
                    listaVisitantes.push(jQuery.extend(true, {}, auxVisitante));
                }
    
    
    
                $(`#data-empleados tr[data=empleado_${id}]`).html(`
                        <td>${auxVisitante.identificacion}</td>
                        <td>${auxVisitante.nombre}</td>
                        <td>${auxVisitante.parentesco}</td>
                        <td>
                            <a class="btn btn-lg btn-default" onclick="consultarVisitante(${id})">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a class="btn btn-lg btn-default" onclick="desactivarVisitante(${id})">
                                <i class="fa fa-times"></i>
                            </a>
                        </td>
                `);    
    
                swal('Logrado!','Visitante editado corretamente','success').then(()=>{
                    $('#dataVisitante').trigger('reset');
                    $('#visitantes').modal('hide');
                });
            }else{
                let auxVisitante = {
                    idVisitante: nroVisitantes,
                    action: 'agregar',
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
    }

    function eliminarVisitante(id){
        swal({
            title : 'Advertencia!',
            text : '¿Seguro de querer eliminar este visitante?',
            icon : 'warning',
            buttons :true
        }).then((res)=>{
            if(res){
                listaVisitantes.splice(searchVisitante('idVisitante',id),1);
                $(`#data-visitantes tr[data=${id}]`).remove();
            }
        });

    }

    function searchVisitante(campo,id){
        for (var i = 0; i < listaVisitantes.length; i++) {
            if (listaVisitantes[i][campo] == id) {
                return i;
            }
        }
        return null;
    }

    function desactivarVisitante(id){
        swal({
            title : 'Advertencia!',
            text : '¿Seguro de querer desactivar este visitante?',
            icon : 'warning',
            buttons :true
        }).then((res)=>{
            if(res){
                listaVisitantes.push({
                    action : 'inactivar',
                    id : id
                });
                swal('Logrado!','Visitante inactivado corretamente','success');
            }
        });
    }


    function consultarVisitante(id){
        $.ajax({
            type: "GET",
            url: "{{ url('visitantes') }}/"+id,
            dataType: "json"
        }).done((data)=>{
            $('#id_visitante').val(data.id);
            $('#identificacion_visitante').val(data.identificacion);
            $('#nombre_visitante').val(data.nombre);
            $('#parentesco_visitante').val(data.parentesco);
            $('#visitantes').modal('show');
        });
    }


</script>