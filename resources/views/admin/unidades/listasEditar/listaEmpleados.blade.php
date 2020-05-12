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
            <input type="hidden" name="id_empleado" id="id_empleado">

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




<button type="button" class="btn btn-primary" onclick="openModalAddEmpleado()">Agregar empleado</button>
<br>

<table class="table">
    <thead>
        <tr>
            <th>Documento</th>
            <th>Nombre completo</th>
            <th>Género</th>
            <th>Opciones</th>
        </tr>
    </thead>
    <tbody id="data-empleados">
        @foreach ($unidad->empleados()->where('estado','Activo')->get() as $empleado)
            <tr data="empleado_{{$empleado->id}}">
                <td>{{ $empleado->documento }}</td>
                <td>{{ $empleado->nombre }} {{ $empleado->apellido }}</td>
                <td>{{ $empleado->genero }}</td>
                <td>
                    <a class="btn btn-lg btn-default" onclick="consultarEmpleado({{ $empleado->id }})">
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a class="btn btn-lg btn-default" onclick="desactivarEmpleado({{ $empleado->id }})">
                        <i class="fa fa-times"></i>
                    </a>                        
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<hr>

<script>

    //variables para controlar la lista de empleados
    /**********************************************/
    var listaEmpleados = new Array();
    var nroEmpleados = 0;


    function openModalAddEmpleado(){
        $('#dataEmpleado').trigger('reset');
        $('#id_empleado').val('');
        $('#empleados').modal('show');
    }


    //metodos para el manejo de empleados en el cliente
    /**********************************************************/
    function guardarEmpleado(){

        if(verificarFormulario('dataEmpleado',3)){
            let id = $('#id_empleado').val();
            if(id != ''){
                let auxEmpleado = {
                    action: 'editar',
                    id: id,
                    nombre: nombre_empleado.value,
                    apellido: apellido_empleado.value,
                    genero: genero_empleado.value,
                    documento: documento_empleado.value,
                    tipo_documento : id_tipo_documento_empleado.value
                }

                //agregar a la lista o reempleazar el ya registrado
                let busqueda = searchEmpleado('id',id);
                if(busqueda != null){
                    listaEmpleados.splice(busqueda, 1, auxEmpleado);
                }else{
                    listaEmpleados.push(jQuery.extend(true, {}, auxEmpleado));
                }
    
    
    
                $(`#data-empleados tr[data=empleado_${id}]`).html(`
                        <td>${auxEmpleado.documento}</td>
                        <td>${auxEmpleado.nombre} ${auxEmpleado.apellido}</td>
                        <td>${auxEmpleado.genero}</td>
                        <td>
                            <a class="btn btn-lg btn-default" onclick="consultarEmpleado(${id})">
                                <i class="fa fa-pencil"></i>
                            </a>
                            <a class="btn btn-lg btn-default" onclick="desactivarEmpleado(${id})">
                                <i class="fa fa-times"></i>
                            </a>
                        </td>
                `);    
    
                swal('Logrado!','Empleado editado corretamente','success').then(()=>{
                    $('#dataEmpleado').trigger('reset');
                    $('#empleados').modal('hide');
                });
            }else{
                let auxEmpleado = {
                    idCliente: nroEmpleados,
                    action: 'agregar',
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
    }

    function eliminarEmpleado(id){
        swal({
            title : 'Advertencia!',
            text : '¿Seguro de querer eliminar este empleado?',
            icon : 'warning',
            buttons :true
        }).then((res)=>{
            if(res){
                listaEmpleados.splice(searchEmpleado('idCliente',id),1);
                $(`#data-empleados tr[data=${id}]`).remove();
            }
        });
    }

    function searchEmpleado(campo,id){
        for (var i = 0; i < listaEmpleados.length; i++) {
            if (listaEmpleados[i][campo] == id) {
                return i;
            }
        }
        return null;
    }


    function desactivarEmpleado(id){
        swal({
            title : 'Advertencia!',
            text : '¿Seguro de querer desactivar este empleado?',
            icon : 'warning',
            buttons :true
        }).then((res)=>{
            if(res){
                listaEmpleados.push({
                    action : 'inactivar',
                    id : id
                });
                swal('Logrado!','Empleado inactivado corretamente','success');
            }
        });
    }


    function consultarEmpleado(id){
        $.ajax({
            type: "GET",
            url: "{{ url('empleados') }}/"+id,
            dataType: "json"
        }).done((data)=>{
            $('#id_empleado').val(data.id);
            $('#nombre_empleado').val(data.nombre);
            $('#apellido_empleado').val(data.apellido);
            $('#genero_empleado').val(data.genero);
            $('#id_tipo_documento_empleado').val(data.tipo_documento_id);
            $('#documento_empleado').val(data.documento);
            $('#empleados').modal('show');
        });
    }
</script>