{{-- Vista para agregar empleados a una unidad --}}

<h3>Empleados</h3>
<br>

{{-- Modal para agregar empleados  --}}
<div id="empleados" class="modal fade" style="overflow:auto;" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title" >Agregar empleado</h4>
      </div>
      <div class="modal-body">
        <form id="dataEmpleado">
            

            <label class="validate-label-1" for="nombre_empleado">Nombre</label>
            <input class="form-control validate-input-1" type="text" id="nombre_empleado" name="nombre_empleado">
            <br>

            <label class="validate-label-2" for="apellido_empleado">Apellido</label>
            <input class="form-control validate-input-2" type="text" id="apellido_empleado" name="apellido_empleado">
            <br>

            <label for="genero_empleado">Género</label>
            <select class="form-control" name="genero_empleado" id="genero_empleado">
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
                <option value="me abstengo">Me abstengo</option>
            </select>
            <br>

            <label for="id_tipo_documento_empleado">Tipo de documento</label>
            <select class="form-control" name="id_tipo_documento_empleado" id="id_tipo_documento_empleado">
                @foreach ($tipos_documentos as $tipo)
                    <option value="{{ $tipo->id }}">{{ $tipo->tipo }}</option>
                @endforeach
            </select>
            <br>

            <label class="validate-label-3" for="documento_empleado">Documento</label>
            <input type="text" class="form-control validate-input-3" name="documento_empleado" id="documento_empleado">
            <br>

        </form>

      </div>
      <div class="modal-footer">
        <button onclick="guardarEmpleado();" type="button" class="btn btn-primary">Guardar</button>
      </div>

    </div>
  </div>
</div>




<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#empleados"><i class="fa fa-plus"></i> Agregar empleado</button>
<br>

<table class="table">
    <thead>
        <tr>
            <th>Documento</th>
            <th>Nombre completo</th>
            <th>Género</th>
            <th>Eliminar</th>
        </tr>
    </thead>
    <tbody id="data-empleados">
    </tbody>
</table>

<hr>

<script>

    //variables para controlar la lista de empleados
    /**********************************************/
    var listaEmpleados = new Array();
    var nroEmpleados = 0;



    //metodos de guardado de la lista de empleados en el cliente
    /**********************************************************/
    function guardarEmpleado(){

        if(verificarFormulario('dataEmpleado',3)){
            

            let auxEmpleado = {
                id : nroEmpleados,
                nombre: nombre_empleado.value,
                apellido: apellido_empleado.value,
                genero: genero_empleado.value,
                documento: documento_empleado.value,
                tipo_documento : id_tipo_documento_empleado.value
            }

            listaEmpleados.push(jQuery.extend(true, {}, auxEmpleado));


            $('#data-empleados').append(`
                <tr data="${nroEmpleados}">
                    <td>${auxEmpleado.documento}</td>
                    <td>${auxEmpleado.nombre} ${auxEmpleado.apellido}</td>
                    <td>${auxEmpleado.genero}</td>
                    <td>
                        <a class="btn btn-danger btn-lg" onclick="eliminarEmpleado(${nroEmpleados})">
                            <i class="fa fa-trash"></i>
                        </a>
                    </td>
                </tr>
            `);

            nroEmpleados++;

            swal('Logrado!','Empleado agregado corretamente','success').then(()=>{
                $('#dataEmpleado').trigger('reset');
                $('#empleados').modal('hide');
            });

        }
    }

    //verificar algunos datos del empleado
    function verficarEmpleado(){ 
        return true;
    }

    function eliminarEmpleado(id){
        swal({
            title : 'Advertencia!',
            text : '¿Seguro de querer eliminar este empleado?',
            icon : 'warning',
            buttons :true
        }).then((res)=>{
            if(res){
                listaEmpleados.splice(searchEmpleado(id),1);
                $(`#data-empleados tr[data=${id}]`).remove();
            }
        });

    }

    function searchEmpleado(id){
        for (var i = 0; i < listaEmpleados.length; i++) {
            console.log(listaEmpleados[i].id)
            if (listaEmpleados[i].id == id) {
                return i;
            }
        }
        return null;
    }


</script>