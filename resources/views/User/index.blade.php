@extends('principal')
@section('contenido')

            <!-- Breadcrumb -->
            {{-- <ol class="breadcrumb">
                <li class="breadcrumb-item active"><a href="/">Sistema control de asistencia</a></li>
            </ol> --}}
            <div class="container-fluid">
                <!-- Ejemplo de tabla Listado -->
                <div class="card mt-1">
                    <div class="card-header">

                       <h2>Listado de Usuarios</h2><br/>
                      
                        <button class="btn btn-primary btn-md" type="button" data-toggle="modal" data-target="#abrirmodal">
                            <i class="fa fa-plus"></i>&nbsp;&nbsp;Agregar Usuario
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-md-6">
                            {!!Form::open(array('url'=>'user','method'=>'GET','autocomplete'=>'off','role'=>'search'))!!} 
                                  <div class="input-group"> 
                                   
                                    <input type="text" name="buscarTexto" class="form-control" placeholder="Buscar texto" value="{{$buscarTexto}}">
                                    <button type="submit"  class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                                </div>
                             {{Form::close()}} 
                            </div>
                        </div>
                        <div class="table-responsive"> 
                        <table class="table table-striped table-sm">
                            <thead>
                                <tr class="bg-primary">
                                   
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Codigo SIS</th>
                                    <th>CI</th>
                                    {{-- <th>Tipo Usuario</th> --}}
                                    <th>Email</th>
                                    {{-- <th>Usuario</th> --}}
                                    <th>Rol</th>
                                    {{-- <th>Estado</th> --}}
                                    <th class="text-center">Eliminar</th>
                                    {{-- <th>Cambiar Estado</th> --}}
                                </tr>
                            </thead>
                            <tbody>

                               @foreach($usuarios as $user)
                               
                                <tr>
                                    
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->nombre}}</td>
                                    <td>{{$user->apellido}}</td>
                                    <td>{{$user->codsis}}</td>
                                    <td>{{$user->ci}}</td>
                                    {{-- <td>{{$user->rol}}</td> --}}
                                    {{-- <td>{{$user->telefono}}</td> --}}
                                    <td>{{$user->email}}</td>
                                    {{-- <td>{{$user->usuario}}</td> --}}
                                    <td>{{$user->rol}}</td>
                            
                                 

                                    {{-- <td>
                                      
                                      @if($user->condicion=="1")
                                        <button type="button" class="btn btn-success btn-md">
                                    
                                          <i class="fa fa-check fa-2x"></i> Activo
                                        </button>

                                      @else

                                        <button type="button" class="btn btn-danger btn-md">
                                    
                                          <i class="fa fa-check fa-2x"></i> Desactivado
                                        </button>

                                       @endif
                                       
                                    </td> --}}
                            
                                    <td class="text-center">
                                        <form action="{{route('user.destroy',$user->id)}}" method="post">
                                            {{csrf_field()}}
                                           {{method_field('delete')}}
                                           <button type="submit" class="btn btn-outline-danger btn-circle"><i class="fa fa-trash"></i></button>
                                       </form>
                               </td>

                                    
                                    {{-- <td>

                                       @if($user->condicion)

                                        <button type="button" class="btn btn-danger btn-sm" data-id_usuario="{{$user->id}}" data-toggle="modal" data-target="#cambiarEstado">
                                            <i class="fa fa-times fa-2x"></i> Desactivar
                                        </button>

                                        @else

                                         <button type="button" class="btn btn-success btn-sm" data-id_usuario="{{$user->id}}" data-toggle="modal" data-target="#cambiarEstado">
                                            <i class="fa fa-lock fa-2x"></i> Activar
                                        </button>

                                        @endif
                                       
                                    </td> --}}

                                    
                                </tr>

                                @endforeach
                               
                            </tbody>
                        </table>
                    </div>
                            
                            {{$usuarios->render("pagination::bootstrap-4")}}

                    </div>
                </div>
                <!-- Fin ejemplo de tabla Listado -->
            </div>
            <!--Inicio del modal agregar-->
            <div class="modal fade" id="abrirmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Agregar usuario</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                       
                        <div class="modal-body">
                             

                            <form action="{{route('user.store')}}" method="post" class="form-horizontal" enctype="multipart/form-data" >
                               
                                {{csrf_field()}}
                                
                                @include('User.form')

                            </form>
                        </div>
                        
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->


             <!--Inicio del modal actualizar-->
             <div class="modal fade" id="abrirmodalEditar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-primary modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Actualizar usuario</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>
                       
                        <div class="modal-body">
                             

                            <form action="{{route('user.update','test')}}" method="post" class="form-horizontal" enctype="multipart/form-data">
                                
                                {{method_field('patch')}}
                                {{csrf_field()}}

                                <input type="hidden" id="id_usuario" name="id_usuario" value="">
                                
                                @include('User.form')

                            </form>
                        </div>
                        
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            <!--Fin del modal-->

            
             {{-- <!-- Inicio del modal Cambiar Estado del usuario -->
             <div class="modal fade" id="cambiarEstado" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
                <div class="modal-dialog modal-danger" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Cambiar Estado del Usuario</h4>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">×</span>
                            </button>
                        </div>

                    <div class="modal-body">
                        <form action="{{route('user.destroy','test')}}" method="POST">
                         {{method_field('delete')}}
                         {{csrf_field()}} 

                            <input type="hidden" id="id_usuario" name="id_usuario" value="">

                                <p>Estas seguro de cambiar el estado?</p>
        

                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times fa-2x"></i>Cerrar</button>
                                <button type="submit" class="btn btn-success"><i class="fa fa-lock fa-2x"></i>Aceptar</button>
                            </div>

                         </form>
                    </div>
                    <!-- /.modal-content -->
                   </div>
                <!-- /.modal-dialog -->
             </div>
            <!-- Fin del modal Eliminar --> --}}

@endsection