@extends('layout.home')

@section('content')

<div class="container-scroller">
    <!-- partial:partials/_navbar.html -->

    @include('layout.nav_superior')
    <!-- end encabezado -->
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      
      <!-- partial -->
      <div class="main-panel">
        
        <div class="content-wrapper mt-3">

          <div class="row">
            @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <a href="{{ route('caiieventos.create') }}">
                    <div class="d-flex align-items-center justify-content-center">
                      <div class="highlight-icon bg-info mr-3">
                        <i class="mdi mdi-plus text-white icon-lg"></i>
                      </div>
                      <div class="wrapper">
                        <p class="card-text mb-0">Crear Evento</p>
                        {{-- <div class="fluid-container">
                          <h3 class="card-title mb-0">$65,650</h3>
                        </div> --}}
                      </div>
                    </div>
                  </a>
                </div>
              </div>
            </div>
            @endif

            @foreach ($eventos_datos as $datos)

            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 grid-margin stretch-card">
              <div class="card card-statistics">
                <div class="card-body">
                  <h5 class="card-title">
                    <a href="{{ route('estudiantes.index') }}">{{ $datos->nombre_evento}}</a>
                  </h5>
                  {{-- <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>--}}

                  <a href="{{ route('caii_pg.create', array('id'=>$datos->id))}}" target="_blank" class="btn btn-link">Link Pre-Inscritos</a> 
                  <div class="dropdown float-right">
                    <button class="btn btn-white dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Opciones">
                      <i class="mdi  mdi-format-list-bulleted h3"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                      <a class="dropdown-item" href="#"><i class="mdi mdi-brush"></i> Editar Evento</a>
                      @endif
                      <a class="dropdown-item addActividades" data-id="{{$datos->id}}" href="{{ route('actividades.index')}}" data-toggle="modal"><i class="mdi mdi-message-processing"></i> Crear Actividad</a>
                      <a class="dropdown-item" href="#"><i class="mdi mdi-brush"></i> Crear Plantilla</a>
                      <a class="dropdown-item" href="#"><i class="mdi mdi-plus-circle"></i> Crear Formulario</a>
                      <a class="dropdown-item" href="#"><i class="mdi mdi-delete"></i> Borrar</a>

        

                    </div>
                  </div>
                </div>
              </div>
            </div>
            @endforeach


          </div>
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">
                Eventos CAII
                <a href="{{ route('estudiantes.index') }}" title="Regresar" class="btn btn-link ">Ver Participantes </a>
                {{-- <a href="{{ route('caii.login') }}" target="_blank" class="btn btn-link ">Link Invitados </a> --}}
              </h4>

              <div class="row" id="capBusqueda">
                <div class="col-sm-12">
                  <form>
                    <div class="form-row">
                      <div class=" col-sm-8 col-xs-12">
                        <input type="text" class="form-control" placeholder="BUSCAR" name="s" value="">

                        <?php 
                           if (isset($_GET['s'])){ ?>
                            <a class="ml-2 small btn-cerrar h4" title="Borrar busqueda" href=' {{route('caiieventos.index')}} '><i class='mdi mdi-close text-lg-left'></i></a>
                        <?php } ?>
                      </div>

                      <div class=" col-sm-2 col-xs-12">
                        <button type="submit" class="form-control btn btn-dark mb-2 " id="buscar" >Buscar</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>


              
              
              @if(Session::has('message-import'))
              <p class="alert alert-info">{{ Session::get('message-import') }}</p>
              @endif
              
              <div id="capaEstudiantes" class="row">
                <div class="col-12">

                  {{ Form::open(array('route' => array('caiieventos.eliminarVarios'), 'method' => 'POST', 'role' => 'form', 'id' => 'form-delete','style'=>'display:inline')) }}

                  <div class="row">{{-- cap: opciones --}}
                      
                    <div class="col-xs-12  col-sm-8 text-left mb-4">
                      @if(@isset($permisos['nuevo']['permiso']) and  $permisos['nuevo']['permiso'] == 1)
                        
                        <a href="{{ route('caiieventos.create') }}" title="Nuevo" class="btn btn-dark btn-sm icon-btn ">
                          <i class="mdi mdi-plus text-white icon-md" ></i>
                        </a>

                      @endif

                      @if(@isset($permisos['eliminar']['permiso']) and  $permisos['eliminar']['permiso'] == 1)
                      <button type="submit" class="btn btn-sm btn-secondary" disabled="" id="delete_selec" name="delete_selec"><i class='mdi mdi-close'></i> Borrar</button>
                      @endif
                      
                    </div> {{-- end derecha --}}
                      <div class="col-xs-12 col-sm-4 text-right mb-4">
                        <span class="small pull-left">
                          <strong>Mostrando</strong>
                          {{ $eventos_datos->firstItem() }} - {{ $eventos_datos->lastItem() }} de
                          {{ $eventos_datos->total() }}
                        </span>

                      </div>{{-- end izq --}}
                      
                  </div> {{-- end cap: opciones --}}

                  

                  <div id="order-listing_wrapper" >
                    <div class="row">
                      <div class="table-responsive fixed-height" style="height: 460px; padding-bottom: 49px;">{{-- table-responsive-lg --}}
                        <table id="order-listing" class="table table-hover table-sm">
                          <thead class="thead-dark">
                            <tr role="row">
                              <th style="width: 2%;"><input type="checkbox" name="chooseAll_1" id="chooseAll_1" class="chooseAll_1"></th>
                              <th style="width: 3%;"></th>
                              <th style="width: 2%;">#</th>
                              <th style="width: 25%;">Nombre Evento</th>
                              {{-- <th style="width: 8%;">Fecha</th> --}}
                              <th style="width: 10%;">Invitados</th>
                              <th style="width: 10%;">Hora</th>
                              <th style="width: 10%;">Lugar</th>
                              <th style="width: 10%;">Vacantes</th>
                              <th style="width: 10%;">Inscritos_pre</th>
                              {{-- <th style="width: 10%;">Auto.Conf</th> --}}
                              <th style="width: 5%;">Activo</th>
                              {{-- <th style="width: 5%;">Grupo</th> --}}
                              <th style="width: 5%;">Fecha_Inicio</th>
                              <th style="width: 5%;">Fecha_Final</th>
                              <th style="width: 5%;">FechaFin_Pre-Inscripción</th>
                              <th style="width: 5%;">FechaFin_Pre-Invitación</th>
                              <th style="width: 5%;">Confirmación Email / Cel.</th>
                              <th style="width: 5%;">FechaReg</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($eventos_datos as $datos)
                            <tr role="row" class="odd">
                              <td><input type="checkbox" class="form btn-delete" name="tipo_doc[]" value="{{ $datos->id }}" data-id="{{ $datos->id }}"></td>
                              <td nowrap="">
                                  @if(@isset($permisos['editar']['permiso']) and  $permisos['editar']['permiso'] == 1)
                                      
                                      <a href="{{ route('caiieventos.edit',$datos->id) }}" class="">
                                        <i class="mdi mdi-pencil text-info icon-md" title="Editar"></i>
                                      </a>
                                  @endif
                                  @if(@isset($permisos['mostrar']['permiso']) and  $permisos['mostrar']['permiso'] == 1)
                                  <a href="{{ route('caiieventos.show',$datos->id)}}" class="">
                                    <i class="mdi mdi-eye text-primary icon-md" title="Mostrar"></i>
                                  </a>
                                  @endif
                                  
                                </td>
                                <td>{{ $datos->id }}</td>
                                <td><a href="{{ route('caii_pg.create', array('id'=>$datos->id))}}" title="Compartir evento" target="_blank" >{{ $datos->nombre_evento }}</a></td>
                                <td><a href="{{ route('caii.login', array('id'=>encrypt($datos->id)))}}" title="Compartir evento" target="_blank" >{{ 'Login' }}</a></td>
                                {{-- <td>{{ $datos->fecha_texto }}</td> --}}
                                <td>{{ $datos->hora }}</td>
                                <td>{{ $datos->lugar }}</td>
                                <td class="text-center">{{ $datos->vacantes }}</td>
                                <td class="text-center">{{ $datos->inscritos_pre }}</span></td>
                                {{-- <td>{{ $datos->auto_conf }}</td> --}}
                                <td>
                                  
                                  <span class="badge @if($datos->activo === 1)badge-primary @elseif($datos->activo === 2)badge-secondary @elseif($datos->activo === 3)badge-danger @else($datos->activo === 4)badge-dark @endif ">
                                    @if($datos->activo === 1) SI @else NO @endif
                                  </span>
                                  
                                </td>
                                {{-- <td>{{ $datos->grupo }}</td> --}}
                                <td>{{ \Carbon\Carbon::parse($datos->fechai_evento)->format('d.m.Y') }}</td>{{-- ->format('d.m.Y') --}}
                                {{-- {!! \Carbon\Carbon::parse($datos->fecha_entrega)->format('d-m-Y') !!} --}}
                                <td>{{ \Carbon\Carbon::parse($datos->fechaf_evento)->format('d.m.Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($datos->fechaf_pre_evento)->format('d.m.Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($datos->fechaf_insc_evento)->format('d.m.Y') }}</td>
                                <td>
                                  <i class="mdi mdi-email @if($datos->confirm_email === 1) text-warning @else text-secondary @endif icon-md" title="Email: @if($datos->confirm_email === 1) SI @else NO @endif"></i>
                                  <i class="mdi mdi-message  @if($datos->confirm_msg === 1) text-success @else text-secondary @endif  icon-md" title="Msg: @if($datos->confirm_msg === 1) SI @else NO @endif"></i>
                                </td>
                                {{-- <td>{{ $datos->created_at->toFormattedDateString() }}</td> --}}
                                {{-- <td>{{ $datos->created_at->diffForHumans() }}</td> --}}

                                <td>{{ $datos->created_at->format('d.m.Y') }}</td>
                                
                                {{-- <td>
                                  @if($datos->estado === "1")
                                    <span class="badge badge-success">Activo</span>
                                  @else
                                    <span class="badge badge-secondary">Inactivo</span>
                                  @endif
                                </td> --}}
                                
                            </tr>
                            @endforeach

                          </tbody>
                        </table>


                        {!! $eventos_datos->appends(request()->query())->links() !!}
                      </div>
                    </div>
                  </div>

                  {{ Form::close() }} {{-- end close form --}}

                </div>
              </div> {{-- end cap_form_list --}}



              {{-- Detalle programación --}}

              <div class="modal modalAct fade" id="modalAct" tabindex="-1" role="dialog" aria-labelledby="heTitle" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">{{-- modal-lg --}}
                  <form class="" id="detalleProgramacion" action="{{ route('caiieventos.enviar') }}" method="post" >
                        {!! csrf_field() !!}
                    <div class="modal-content">
                      
                      <div class="modal-header">
                        <h5 class="modal-title" id="heTitle">Crear Actividades </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span> 
                        </button>
                      </div>
                      <div class="modal-body pt-0">
                          <div class="form-group row">
                            
                            <div class="col-md-12" id="detActividad">
                              <ul>
                                <li><a href="#">19/09/2019</a> 
                                  <a href="#" class="addAct bg-light ml-3 rounded-circle" onclick="eximForm()" data-id='1' title='Crear Actividad'>
                                        <i class="mdi mdi-plus text-black icon-sm"></i>
                                      </a>
                                </li>
                                <li><a href="#">20/09/2019</a> 
                                  <a href="#" class="addAct bg-light ml-3 rounded-circle" onclick="eximForm()" data-id='2' title='Crear Actividad'>
                                        <i class="mdi mdi-plus text-black icon-sm"></i>
                                      </a>
                                </li>
                              </ul>
                              
                              {{-- <table class="table table_his">
                                <thead class="thead-inverse">
                                  <tr>
                                    <th>COD.PROG</th>
                                    <th>NOMBRE</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>
                                      <a href="#">19/09/2019</a>
                                    </td>
                                    <td>
                                      <a href="#" class="addAct bg-light ml-3 rounded-circle" data-id='1' title='Crear Actividad'>
                                        <i class="mdi mdi-plus text-black icon-sm"></i>
                                      </a>
                                    </td>
                                  </tr>
                                  <tr>
                                    <td>
                                      <a href="#">20/09/2019</a>
                                    </td>
                                    <td>
                                      <a href="#" class="addAct bg-light ml-3 rounded-circle" data-id='1' title='Crear Actividad'>
                                        <i class="mdi mdi-plus text-black icon-sm"></i>
                                      </a>
                                    </td>
                                  </tr>
                                </tbody>
                              </table> --}}

                            </div>
                          </div>
                        



                      </div>
                      <div class="modal-footer">
                        <a href="{{route('actividades.index')}}" class="btn btn-link" title='Crear Actividad'>
                          Ver actividades
                        </a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        {{-- <button type="submit" class="btn btn-primary" id="enviar_det_programacion">Asignar CódProgramacìón</button> --}}
                      </div>
                      
                    </div>
                  </form>
                </div>
              </div>
              {{--  --}}
              <div class="modal fade ass" id="Modal_estudiantes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <form  id="f_cargar_datos_estudiantes" name="f_cargar_datos_estudiantes" method="post"  action="{{ route('estudiantes.import') }}" class="formarchivo" >
                        {!! csrf_field() !!}
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Creación de la Actividad</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span> 
                      </button>
                    </div>
                    <div class="modal-body pt-0">

                      {{-- <form class="forms-sample pr-4 pl-4" id="caiieventosForm" action="{{ route('caiieventos.store') }}" method="post">
                      {!! csrf_field() !!} --}}
                    
                      <div class="form-group row">
                        <label for="titulo" class="col-sm-2 col-form-label d-block">Título <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="text" required="" class="form-control" name="titulo" placeholder="Título de la actividad *" value="{{ old('titulo') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="subtitulo" class="col-sm-2 col-form-label d-block">Subtítulo</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="subtitulo" placeholder="Título de la actividad *" value="{{ old('subtitulo') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="desc_actividad" class="col-sm-2 col-form-label d-block">Descripción Actividad</label>
                        <div class="col-sm-10">
                          <textarea placeholder="Descripción Actividad" class="form-control" name="desc_actividad" id="" cols="30" rows="5">{{ old('desc_actividad') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            5,000 caracteres
                          </div>
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="desc_ponentes" class="col-sm-2 col-form-label d-block">Descripción Ponentes</label>
                        <div class="col-sm-10">
                          <textarea placeholder="Descripción Ponentes" class="form-control" name="desc_ponentes" id="" cols="30" rows="5">{{ old('desc_ponentes') }}</textarea>
                          <div class="col alert alert-light border-0 mb-0 text-right">
                            5,000 caracteres
                          </div>
                        </div>
                      </div>
                    
                      <div class="form-group row">
                        <label for="hora_inicio" class="col-sm-2 col-form-label">Hora Inicio <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="time" required="" class="form-control" name="hora_inicio" placeholder="Hora" value="{{ old('hora_inicio') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="hora_final" class="col-sm-2 col-form-label">Hora Final <span class="text-danger">*</span></label>
                        <div class="col-sm-10">
                          <input type="time" required="" class="form-control" name="hora_final" placeholder="Hora" value="{{ old('hora_final') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="ubicacion" class="col-sm-2 col-form-label">Ubición</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="ubicacion" placeholder="Ubición" value="{{ old('ubicacion') }}" />
                        </div>
                      </div>

                      <div class="form-group row">
                        <label for="vacantes" class="col-sm-2 col-form-label">Vacantes</label>
                        <div class="col-sm-10">
                          <input type="text" class="form-control" name="vacantes" placeholder="Cantidad de vacantes" value="{{ old('vacantes') }}" />
                        </div>
                      </div>
                      <div class="form-group row">
                        <label for="imagen" class="col-sm-2 col-form-label">Imagen</label>
                        <div class="col-sm-10">
                          <div class="form-group">
                            <input type="file" name="img[]" class="file-upload-default" accept="image/x-png,image/jpeg">
                            <div class="input-group col-xs-12">
                              <input type="text" class="form-control file-upload-info" accept="image/x-png,image/jpeg"  placeholder="Solo formatos: jpg o png">
                              <span class="input-group-append">
                                <button class="file-upload-browse btn btn-info" type="button">Upload</button>
                              </span>
                            </div>
                          </div>
                        
                        </div>
                      </div>

                   {{--  <div class="form-group row">
                      <div class="col-sm-12 text-center mt-4">
                        <button id="actionSubmit" value="Guardar" type="submit" class="btn btn-dark mr-2">Guardar y Continuar Paso 2</button>
                        
                        <a href="{{ route('caii.index') }}" class="btn btn-light">Volver al listado</a>
                      </div>

                    </div>
 --}}
                  {{-- </form> --}}
                        
                        
                      <div id="cargador_excel" class="content-wrapper p-0 d-none" align="center">  {{-- msg cargando --}}
                        <div class="card bg-white" style="background:#f3f3f3 !important;" >
                          <div class="">
                            <label >&nbsp;&nbsp;&nbsp;Espere... &nbsp;&nbsp;&nbsp;</label>
                            <img src="{{ asset('images/cargando.gif') }}" width="32" height="32" align="middle" alt="cargador"> &nbsp;<label style="color:#ABB6BA">Cargando registros excel...</label>
                          </div>
                        </div>
                      </div>{{-- msg cargando --}}



                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                      <button type="submit" class="btn btn-dark" id="saveActividades">Guardar</button>{{-- btnImport1 --}}
                    </div>
                    </form>
                  </div>
                </div>
              </div>
              {{-- fin modal --}}





            </div>
          </div>
        </div> <!-- end listado table -->

        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        @include('layout.footer')
        <!-- end footer.php -->
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->


@endsection