<style>
    .foto{
        height: 50px;
        cursor: pointer;
    }
</style>
<div class="container-fluid">
    <div class="row text-left" id='info' >
        <h1 id="name_unidad" class="text-center">{{ ucfirst(strtolower($unidad->tipo->nombre)) }} {{ $unidad->numero_letra }}</h1>
        <div class="col-md-6">
            <h4>
                División: {{ $unidad->division->tipo_division->division }} {{ $unidad->division->numero_letra }}
            </h4>

            @if (in_array('coeficiente', $atributos) and Auth::user()->id_rol == 2)
                <h4>
                    Coeficiente(%): {{ $unidad->coeficiente }}
                </h4>
            @endif

            @if (Auth::user()->id_rol == 2)
                <h4>
                    Referencia de pago: {{ ($unidad->referencia )? $unidad->referencia : 'No aplica' }}
                </h4>
            @endif

            @if (in_array('unidad_id', $atributos))

            @endif
            @if (in_array('observaciones', $atributos))
                <h4>Observaciones:</h4>
                <h5>{{ $unidad->observaciones }}</h5>
            @endif
        </div>
        <div class="col-md-6">
            {{-- Propietario --}}
            @if (in_array('propietario', $atributos))
                <h2>Propietario</h2>
                <h4>Nombre Completo: {{ $nombre_propietario }}</h4>
                <h4>Documento: {{ $documento_propietario }}</h4>
                <h4>Correo: {{ $email_propietario }}</h4>
                <h4>Dirección: {{ $direccion_propietario }}</h4>
            @endif
        </div>
        <br>
    </div>
</div>


<div class="row">
    <div class="x_content">
        <div class="" role="tabpanel" data-example-id="togglable-tabs">
          <ul id="myTab1" class="nav nav-tabs bar_tabs right" role="tablist">
            <li role="presentation" class="active"><a href="#activos" id="home-tabb" role="tab" data-toggle="tab" aria-controls="home" aria-expanded="false">Activos</a>
            </li>
            @if (Auth::user()->id_rol == 2)
                <li role="presentation" class=""><a href="#historico" role="tab" id="profile-tabb" data-toggle="tab" aria-controls="profile" aria-expanded="true">Histórico</a>
                </li>
                <li role="presentation" class=""><a href="#novedades" role="tab" id="profile-tabb" data-toggle="tab" aria-controls="profile" aria-expanded="true">Novedades</a>
                </li>
            @endif

          </ul>
          <div id="myTabContent2" class="tab-content">
            <div role="tabpanel" class="tab-pane fade active in" id="activos" aria-labelledby="home-tab">
                {{-- activos --}}
                @if (Auth::user()->id_rol == 2)
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-11 text-right">
                                <button data-toggle="tooltip" data-placement="top" title="Descargar esta información en pdf" onclick="exportarActivo({{ $unidad->id }})" class="btn btn-large" >
                                    <i  class="fa fa-file-pdf-o"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- lista de residentes si tiene estado --}}
                @if (in_array('lista_residentes', $atributos) and $unidad->residentes()->where('estado','Activo')->count() > 0)
                    <h3>Listado de residentes</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Tipo</th>
                                <th>Nombre completo</th>
                                <th>Ocupación</th>
                                <th>Lugar de trabajo</th>
                                <th>Fecha Nacimiento</th>
                                <th>Género</th>
                                <th>Fecha ingreso</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unidad->residentes()->where('estado','Activo')->get() as $residente)
                                <tr>
                                    <td>{{ $residente->documento }}</td>
                                    <td>{{ $residente->tipo_residente }}</td>
                                    <td>{{ $residente->nombre }} {{ $residente->apellido }}</td>
                                    <td>{{ $residente->ocupacion }}</td>
                                    <td>{{ $residente->direccion }}</td>
                                    <td>{{ date('d-m-Y',strtotime($residente->fecha_nacimiento)) }}</td>
                                    <td>{{ $residente->genero }}</td>
                                    <td>{{ date('d-m-Y',strtotime($residente->fecha_ingreso)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                @endif
                {{-- lista de mascotas se puede usar la fecha_retiro --}}
                @if (in_array('lista_mascotas', $atributos) and $unidad->mascotas()->where('estado','Activo')->count() > 0)
                    <h3>Listado de mascotas</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>Código</th>
                                <th>Fecha Nacimiento</th>
                                <th>Nombre</th>
                                <th>Tipo</th>
                                <th>Raza</th>
                                <th>Descripcion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unidad->mascotas()->where('estado','Activo')->get() as $mascota)
                                <tr>
                                    <td><img class="foto show_img" src="{{ ($mascota->foto != '')? asset('imgs/private_imgs/'.$mascota->foto) : '' }}" alt=""></td>
                                    <td>{{ $mascota->codigo }}</td>
                                    <td>{{ ($mascota->fecha_nacimiento)? date('d-m-Y',strtotime($mascota->fecha_nacimiento)) : 'No aplica' }}</td>
                                    <td>{{ $mascota->nombre }}</td>
                                    <td>{{ $mascota->tipo->tipo }}</td>
                                    <td>{{ $mascota->raza }}</td>
                                    <td>{{ $mascota->descripcion }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                @endif
                {{-- lista de vehículos no la tienen --}}
                @if (in_array('lista_vehiculos', $atributos) and $unidad->vehiculos()->where('estado','Activo')->count() > 0)
                    <h3>Listado de vehículos</h3>
                    <table class="table" id="prueba">
                        <thead>
                            <tr>
                                <th>Placa</th>
                                <th>Foto vehículo</th>
                                <th>Foto tarjeta 1</th>
                                <th>Foto tarjeta 2</th>
                                <th>Tipo</th>
                                <th>Marca</th>
                                <th>Color</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unidad->vehiculos()->where('estado','Activo')->get() as $vehiculo)
                                <tr>
                                    <td>{{ $vehiculo->placa }}</td>
                                    <td><img class="foto show_img" src="{{ ($vehiculo->foto_vehiculo != '')? asset('imgs/private_imgs/'.$vehiculo->foto_vehiculo) : '' }}" alt=""></td>
                                    <td><img class="foto show_img" src="{{ ($vehiculo->foto_tarjeta_1)? asset('imgs/private_imgs/'.$vehiculo->foto_tarjeta_1) : '' }}" alt=""></td>
                                    <td><img class="foto show_img" src="{{ ($vehiculo->foto_tarjeta_2)? asset('imgs/private_imgs/'.$vehiculo->foto_tarjeta_2) : '' }}" alt=""></td>
                                    <td>{{ $vehiculo->tipo }}</td>
                                    <td>{{ $vehiculo->marca }}</td>
                                    <td>{{ $vehiculo->color }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                @endif
                {{-- lista de empleados estan --}}
                @if (in_array('lista_empleados', $atributos) and $unidad->empleados()->where('estado','Activo')->count() > 0)
                    <h3>Listado de empleados</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Nombre completo</th>
                                <th>Género</th>
                                <th>Fecha ingreso</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unidad->empleados()->where('estado','Activo')->get() as $empleado)
                                <tr>
                                    <td>{{ $empleado->documento }}</td>
                                    <td>{{ $empleado->nombre }} {{ $empleado->apellido }}</td>
                                    <td>{{ $empleado->genero }}</td>
                                    <td>{{ date('d-m-Y',strtotime($empleado->fecha_ingreso)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                @endif
                {{-- lista de visitantes frecuentes colocar activos--}}
                @if (in_array('lista_visitantes', $atributos) and $unidad->visitantes()->where('estado','Activo')->count() > 0)
                    <h3>Listado de visitantes</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Identificación</th>
                                <th>Nombre completo</th>
                                <th>Parentesco</th>
                                <th>Fecha ingreso</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unidad->visitantes()->where('estado','Activo')->get() as $visitante)
                                <tr>
                                    <td>{{ $visitante->identificacion }}</td>
                                    <td>{{ $visitante->nombre }}</td>
                                    <td>{{ $visitante->parentesco }}</td>
                                    <td>{{ date('d-m-Y',strtotime($visitante->fecha_ingreso)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                @endif
                {{-- fin activos --}}


            </div>
            @if (Auth::user()->id_rol == 2)
            <div role="tabpanel" class="tab-pane fade" id="historico" aria-labelledby="profile-tab">

              {{-- histórico --}}
                <div class="container">
                    <div class="row">
                        <div class="col-sm-11 text-right">
                            <button data-toggle="tooltip" data-placement="top" title="Descargar esta información en pdf" onclick="exportarHistorico({{ $unidad->id }})" class="btn btn-large" >
                                <i  class="fa fa-file-pdf-o"></i>
                            </button>
                        </div>
                    </div>
                </div>
                {{-- lista de propietarios falta poner la fecha de retiro y el estado en la tabla relacional --}}
              @if (in_array('propietario', $atributos) and $unidad->propietarios->where('pivot.estado','Inactivo')->count() > 0)
                <h3>Listado de propietarios quienes se les facturaba</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Documento</th>
                            <th>Nombre completo</th>
                            <th>Correo</th>
                            <th>Fecha ingreso</th>
                            <th>Fecha retiro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($unidad->propietarios->where('pivot.estado','Inactivo') as $propietario)
                            <tr>
                                <td>{{ $propietario['numero_cedula'] }}</td>
                                <td>{{ $propietario['nombre_completo'] }}</td>
                                <td>{{ $propietario['email'] }}</td>
                                <td>{{ date('d-m-Y',strtotime($propietario['pivot']['fecha_ingreso'])) }}</td>
                                <td>{{ date('d-m-Y',strtotime($propietario['pivot']['fecha_retiro'])) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
              @endif

              {{-- lista de residentes si tiene estado --}}
              @if (in_array('lista_residentes', $atributos) and $unidad->residentes()->where('estado','Inactivo')->count() > 0)
                  <h3>Listado de residentes</h3>
                  <table class="table">
                      <thead>
                          <tr>
                              <th>Documento</th>
                              <th>Tipo</th>
                              <th>Nombre completo</th>
                              <th>Fecha Nacimiento</th>
                              <th>Género</th>
                              <th>Fecha ingreso</th>
                              <th>Fecha retiro</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($unidad->residentes()->where('estado','Inactivo')->get() as $residente)
                              <tr>
                                  <td>{{ $residente->documento }}</td>
                                  <td>{{ $residente->tipo_residente }}</td>
                                  <td>{{ $residente->nombre }} {{ $residente->apellido }}</td>
                                  <td>{{ date('d-m-Y',strtotime($residente->fecha_nacimiento)) }}</td>
                                  <td>{{ $residente->genero }}</td>
                                  <td>{{ date('d-m-Y',strtotime($residente->fecha_ingreso)) }}</td>
                                  <td>{{ date('d-m-Y',strtotime($residente->fecha_salida)) }}</td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
                  <br>
              @endif

              {{-- lista de mascotas se puede usar la fecha_retiro --}}
              @if (in_array('lista_mascotas', $atributos) and $unidad->mascotas()->where('estado','Inactivo')->count() > 0)
                  <h3>Listado de mascotas</h3>
                  <table class="table">
                      <thead>
                          <tr>
                              <th>Foto</th>
                              <th>Código</th>
                              <th>Fecha Nacimiento</th>
                              <th>Nombre</th>
                              <th>Tipo</th>
                              <th>Raza</th>
                              <th>Descripcion</th>
                              <th>Fecha ingreso</th>
                              <th>Fecha retiro</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($unidad->mascotas()->where('estado','Inactivo')->get() as $mascota)
                              <tr>
                                  <td><img class="foto show_img" src="{{ ($mascota->foto != '')? asset('imgs/private_imgs/'.$mascota->foto) : '' }}" alt=""></td>
                                  <td>{{ $mascota->codigo }}</td>
                                  <td>{{ ($mascota->fecha_nacimiento)? date('d-m-Y',strtotime($mascota->fecha_nacimiento)) : 'No aplica' }}</td>
                                  <td>{{ $mascota->nombre }}</td>
                                  <td>{{ $mascota->tipo->tipo }}</td>
                                  <td>{{ $mascota->raza }}</td>
                                  <td>{{ $mascota->descripcion }}</td>
                                  <td>{{  date('d-m-Y',strtotime($mascota->fecha_ingreso))  }}</td>
                                  <td>{{  date('d-m-Y',strtotime($mascota->fecha_retiro))  }}</td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
                  <br>
              @endif

              {{-- lista de vehículos no la tienen --}}
              @if (in_array('lista_vehiculos', $atributos))
                <h3>Listado de vehículos</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Placa</th>
                            <th>Foto vehículo</th>
                            <th>Foto tarjeta 1</th>
                            <th>Foto tarjeta 2</th>
                            <th>Tipo</th>
                            <th>Marca</th>
                            <th>Color</th>
                            <th>Fecha ingreso</th>
                            <th>Fecha retiro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($unidad->vehiculos()->where('estado','inactivo')->get() as $vehiculo)
                            <tr>
                                <td>{{ $vehiculo->placa }}</td>
                                <td><img class="foto show_img" src="{{ ($vehiculo->foto_vehiculo != '')?asset('imgs/private_imgs/'.$vehiculo->foto_vehiculo):'' }}" alt=""></td>
                                <td><img class="foto show_img" src="{{ ($vehiculo->foto_tarjeta_1 != '')?asset('imgs/private_imgs/'.$vehiculo->foto_tarjeta_1):'' }}" alt=""></td>
                                <td><img class="foto show_img" src="{{ ($vehiculo->foto_tarjeta_2 != '')?asset('imgs/private_imgs/'.$vehiculo->foto_tarjeta_2):'' }}" alt=""></td>
                                <td>{{ $vehiculo->tipo }}</td>
                                <td>{{ $vehiculo->marca }}</td>
                                <td>{{ $vehiculo->color }}</td>
                                <td>{{  date('d-m-Y',strtotime($vehiculo->fecha_ingreso))  }}</td>
                                <td>{{  date('d-m-Y',strtotime($vehiculo->fecha_retiro))  }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
              @endif

              {{-- lista de empleados estan --}}
              @if (in_array('lista_empleados', $atributos) and $unidad->empleados()->where('estado','Inactivo')->count() > 0)
                    <h3>Listado de empleados</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Nombre completo</th>
                                <th>Género</th>
                                <th>Fecha ingreso</th>
                                <th>Fecha retiro</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unidad->empleados()->where('estado','Inactivo')->get() as $empleado)
                                <tr>
                                    <td>{{ $empleado->documento }}</td>
                                    <td>{{ $empleado->nombre }} {{ $empleado->apellido }}</td>
                                    <td>{{ $empleado->genero }}</td>
                                    <td>{{ date('d-m-Y',strtotime($empleado->fecha_ingreso)) }}</td>
                                    <td>{{ date('d-m-Y',strtotime($empleado->fecha_retiro)) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                @endif

              {{-- lista de visitantes frecuentes colocar activos--}}
              @if (in_array('lista_visitantes', $atributos) and $unidad->visitantes()->where('estado','Inactivo')->count() > 0)
                <h3>Listado de visitantes</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Identificación</th>
                            <th>Nombre completo</th>
                            <th>Parentesco</th>
                            <th>Fecha ingreso</th>
                            <th>Fecha retiro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($unidad->visitantes()->where('estado','Inactivo')->get() as $visitante)
                            <tr>
                                <td>{{ $visitante->identificacion }}</td>
                                <td>{{ $visitante->nombre }}</td>
                                <td>{{ $visitante->parentesco }}</td>
                                <td>{{ date('d-m-Y',strtotime($visitante->fecha_ingreso)) }}</td>
                                <td>{{ date('d-m-Y',strtotime($visitante->fecha_retiro)) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br>
              @endif
              {{-- fin historico --}}
            </div>
            @endif


            @if (Auth::user()->id_rol == 2)
                {{-- novedades --}}
                <div role="tabpanel" class="tab-pane fade" id="novedades" aria-labelledby="home-tab">
                    <div class="container">
                        <div class="row">
                            <div class="col-sm-11 text-right">
                                <button data-toggle="tooltip" data-placement="top" title="Descargar esta información en pdf" onclick="exportarNovedades({{ $unidad->id }})" class="btn btn-large" >
                                    <i  class="fa fa-file-pdf-o"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <h4>Listado de novedades</h4>
                    <table class="table" id="novedades-data">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th></th>
                                <th>Novedad</th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($unidad->novedades as $novedad)
                                <tr>
                                    <td colspan="1">{{ date('d-m-Y',strtotime($novedad->fecha)) }}</td>
                                    <td colspan="9">{{ $novedad->contenido }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

            @endif

        </div>
    </div>
</div>
<div style="display:none;" id="auxData"></div>
<script src="{{ asset('js/jspdf.min.js') }}"></script>
<script src="{{ asset('js/autotable.min.js') }}"></script>

@if (Auth::user()->id_rol == 2)
    <script>
        function exportarActivo(id){
            var doc = new jsPDF('L');
            var elementHTML = $('#info').html();
            var specialElementHandlers = {
                '#elementH': function (element, renderer) {
                    return true;
                }
            };
            doc.fromHTML(elementHTML, 15, 40, {
                'width': 180,
                'elementHandlers': specialElementHandlers
            });

            var logo = new Image()
            logo.onload = function() {
                // do some stuff like rendering image to a canvas
                var canvas = document.createElement('canvas'),
                ctx = canvas.getContext('2d');

                //calcular alto y ancho de la imagen
                let aux = $(logo)[0];
                let width = aux.width;
                let height = 90;
                let heightAux = aux.height;
                width = (width*height)/heightAux;

                canvas.width = width;
                canvas.height = height;
                ctx.fillStyle = "white";
                ctx.fillRect(0, 0, width, height);
                ctx.fill();

                ctx.drawImage(logo, 0, 0, width, height);
                var url = canvas.toDataURL();
                var jpeg = canvas.toDataURL("image/jpeg");
                doc.addImage(jpeg,'JPEG', 20, 8);

                let nombre_nit = '{{ $conjunto->nombre.' - '.$conjunto->nit }}';

                doc.text(nombre_nit,(width*0.5) ,((height*0.5)/2));


                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                //insertar las tablas de la consulta
                $.ajax({
                    type: "POST",
                    url: `{{ url('unidades') }}/pdf/Activo/${id}`,
                    data: { '_token' : csrf_token },
                    dataType: "html",
                    success: function (response) {
                        $('#auxData').html(response);
                        let tablas = $('.infoUnidad');
                        let primera = true;
                        let alto = $('#info').height();
                        for (let i = 0; i < tablas.length; i++) {
                            if(primera){
                                doc.autoTable({ startY: alto, html: '#'+tablas[i].id });
                                primera = false;
                            }else{
                                doc.autoTable({ startY: doc.previousAutoTable.finalY + 5, html: '#'+tablas[i].id });
                            }
                        }
                        doc.save(`activos.pdf`);
                    }
                });
            }
            @if($conjunto->logo)
                logo.src = '{{asset('imgs/logos_conjuntos/'.$conjunto->logo)}}';
            @else
                swal('Error!','Debe de asignar primero un logo para el conjunto','error');
            @endif
        }

        function exportarHistorico(id){
            var doc = new jsPDF('L');

            var logo = new Image()
            logo.onload = function() {
                // do some stuff like rendering image to a canvas
                var canvas = document.createElement('canvas'),
                ctx = canvas.getContext('2d');

                //calcular alto y ancho de la imagen
                let aux = $(logo)[0];
                let width = aux.width;
                let height = 90;
                let heightAux = aux.height;
                width = (width*height)/heightAux;

                canvas.width = width;
                canvas.height = height;
                ctx.fillStyle = "white";
                ctx.fillRect(0, 0, width, height);
                ctx.fill();

                ctx.drawImage(logo, 0, 0, width, height);
                var url = canvas.toDataURL();
                var jpeg = canvas.toDataURL("image/jpeg");
                doc.addImage(jpeg,'JPEG', 20, 8);

                let nombre_nit = '{{ $conjunto->nombre.' - '.$conjunto->nit }}';
                doc.text(nombre_nit,(width*0.5) ,((height*0.5)/2));

                doc.text(15,40,'Histórico '+$('#name_unidad').text());

                var csrf_token = $('meta[name="csrf-token"]').attr('content');
                $('#auxData').html('');
                //insertar las tablas de la consulta
                $.ajax({
                    type: "POST",
                    url: `{{ url('unidades') }}/pdf/Inactivo/${id}`,
                    data: { '_token' : csrf_token },
                    dataType: "html",
                    success: function (response) {
                        $('#auxData').html(response);
                        let tablas = $('.infoUnidad');
                        let alto = 45;
                        for (let i = 0; i < tablas.length; i++) {

                            if(doc.previousAutoTable.finalY != undefined ){
                                alto = doc.previousAutoTable.finalY + 10;
                            }

                            doc.autoTable({ startY: alto, html: '#'+tablas[i].id });

                        }
                        doc.save(`historico.pdf`);
                    }
                });
            }
            @if($conjunto->logo)
                logo.src = '{{asset('imgs/logos_conjuntos/'.$conjunto->logo)}}';
            @else
                swal('Error!','Debe de asignar primero un logo para el conjunto','error');
            @endif
        }

        function exportarNovedades(){
            var doc = new jsPDF('p');

            var logo = new Image()
            logo.onload = function() {
                // do some stuff like rendering image to a canvas
                var canvas = document.createElement('canvas'),
                ctx = canvas.getContext('2d');

                //calcular alto y ancho de la imagen
                let aux = $(logo)[0];
                let width = aux.width;
                let height = 90;
                let heightAux = aux.height;
                width = (width*height)/heightAux;

                canvas.width = width;
                canvas.height = height;
                ctx.fillStyle = "white";
                ctx.fillRect(0, 0, width, height);
                ctx.fill();

                ctx.drawImage(logo, 0, 0, width, height);
                var url = canvas.toDataURL();
                var jpeg = canvas.toDataURL("image/jpeg");
                doc.addImage(jpeg,'JPEG', 20, 8);

                let nombre_nit = '{{ $conjunto->nombre.' - '.$conjunto->nit }}';

                doc.setFontSize(12);
                doc.text(nombre_nit,(width*0.5) ,((height*0.5)/2));

                doc.text(10,40,'Novedades '+$('#name_unidad').text());
                doc.autoTable({ startY: 50, html: '#novedades-data' });
                doc.save(`novedades.pdf`);
            }
            @if($conjunto->logo)
                logo.src = '{{asset('imgs/logos_conjuntos/'.$conjunto->logo)}}';
            @else
                swal('Error!','Debe de asignar primero un logo para el conjunto','error');
            @endif

        }

    </script>
@endif

