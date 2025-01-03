@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/contact-list.png')}}">
@endsection

@section('title-page')
Admin | Usuarios {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<!-- Toastr -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/toastr/toastr.min.css')}}">

<style>
    .dropdown-toggle::after {
      display: none;
    }
</style>
@endsection

@section('navbar')
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{url('home')}}" class="nav-link">Inicio</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Navbar Search -->
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge" id="num-noti-span"></span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="contain-noti">

      </div>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#" title="Ajustes">
        <i class="fas fa-cogs"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">Ajustes</span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
        <i class="fas fa-user-cog mr-2"></i> Perfil
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{route('logout')}}" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
        </a>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="javascript:void(0)" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
  </ul>
</nav>
@endsection

@section('container-header')
<div class="row mb-2">
  <div class="col-sm-12">
    <h1>Usuarios</h1>
  </div>
</div>
@endsection

@section('contenido-body')
<input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="cardsRowTitle">
                            <div class="cardsection">
                              <h3 class="card-title p-2"><i class="fas fa-users mr-3"></i> Usuarios</h3>
                            </div>
                            <div class="cardsection">
                              <button type="button" class="btn btn-primary btn-block" onclick="openmodalAdd()"><i
                                class="far fa-plus-square mr-2"></i> Registrar</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-4" id="divTablaSocialMedia">
                      <table class="table table-hover text-nowrap table-head-fixed table-responsive" id="tablaListadoSM">
                        <thead>
                          <tr style="pointer-events:none;">
                            <th>N°</th>
                            <th>Usuario</th>
                            <th>Nombre Usuario</th>
                            <th>Tipo de Usuario</th>
                            <th>Último Acceso</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                          </tr>
                        </thead>
                        <tbody>
                          @if (count($getusers) > 0)
                          @foreach ($getusers as $item)
                              <tr id="Tr{{$loop->index}}">
                                <td>{{$loop->iteration}}</td>
                                <td>{{$item->user}}</td>
                                <td>{{$item->nombre_usuario}}</td>
                                <td>{{$item->tipo_usuario}}</td>
                                <td>
                                  @if ($item->ultimo_acceso=='')
                                      -Sin especificar-
                                  @else
                                    {{$item->ultimo_acceso}}
                                  @endif
                                </td>
                                <td>
                                  @if ($item->estado=='0')
                                  <span class="badge badge-secondary">Inactivo</span>
                                  @else
                                  <span class="badge badge-success">Activo</span>
                                  @endif
                                </td>
                                <td>
                                  <div class="dropdown show">
                                    <a class="btn btn-secondary dropdown-toggle" href="javascript:void(0)" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                      <i class="fas fa-cog"></i>
                                    </a>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                      <a class="dropdown-item" href="javascript:void(0)" onclick="editarItemUser({{$item->id}})">Editar</a>
                                      <a class="dropdown-item" href="javascript:void(0)" onclick="generarPassword({{$item->id}})">Cambiar Clave</a>
                                      @if ($item->estado=='1')
                                      <a class="dropdown-item" href="javascript:void(0)" onclick="eliminarItemUser({{$item->id}}, {{$loop->index}})">Inactivar</a>
                                      @else
                                      <a class="dropdown-item" href="javascript:void(0)" onclick="activarItemUser({{$item->id}}, {{$loop->index}})">Activar</a>
                                      @endif
                                    </div>
                                  </div>
                                </td>
                              </tr>
                          @endforeach
                          @else
                          <tr>
                            <td class="text-center" colspan="7">No hay resultados</td>
                          </tr>
                          @endif
                        </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>


<!--MODAL AGG CLAVE USUARIO-->
<div class="modal fade" id="modalAggPassword" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Cambiar Clave Usuario</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group mb-3">
              <input type="hidden" name="idperfilusuario" id="idperfilusuario">
              <label for="inputPasswordUser">Clave</label>
                <div class="input-group">
                  <input type="password" class="form-control" placeholder="Clave" id="inputPasswordUser" name="inputPasswordUser" autocomplete="off">
                  <div class="input-group-append showhideinit" onclick="showPassword('inputPasswordUser','spanLock')">
                    <div class="input-group-text" id="spanLock">
                      <span class="fas fa-lock"></span>
                    </div>
                  </div>
                </div>
              <span class="spanNotiRegistro">La clave debe tener una longitud mínima de 8 caracteres</span>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary mb-2 btnsaveprofile" onclick="guardarClavePerfil()">Actualizar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<!-- Fullscreen modal -->
<div class="modal fade modal-full" id="modalCargando" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"
data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="{{asset('assets/administrador/img/gif/load.gif')}}" alt="">
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<!-- Toastr -->
<script src="{{asset('assets/administrador/plugins/toastr/toastr.min.js')}}"></script>

<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/usuarios.js')}}"></script>
<script>
    $('.select2').select2({
        theme: 'bootstrap4',
    });

    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "1800",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
    $('#modalCargando').modal('show');
    setTimeout(() => {
      $('#modalCargando').modal('hide');
    }, 900);
</script>
@endsection