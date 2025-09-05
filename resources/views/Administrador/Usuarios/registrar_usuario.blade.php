@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/registro.png')}}">
@endsection

@section('title-page')
Admin | Usuarios {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop.css')}}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/daterangepicker/daterangepicker.css')}}">

<style>
    .bguardar {
      float: right;
      display: flex;
      flex-direction: column;
      margin-top: 13vh;
    }

    .container .dropify-wrapper {
      height: 295px;
    }

    .spanlabel {
      padding-left: 22px;
      font-size: 12.5px;
      font-weight: bold;
    }

    .loadInfo {
      width: 10rem !important;
      height: 10rem !important;
    }

    .formEdit {
      display: block;
      width: 50%;
      height: calc(1.5em + 0.75rem + 2px);
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
      font-weight: 400;
      line-height: 1.5;
      color: #495057;
      background-color: #ffffff;
      background-clip: padding-box;
      border: 1px solid #dee2e6;
      border-radius: 0.25rem;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
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
                <div class="card card-default">
                    <div class="card-header">
                      <h3 class="card-title">Registrar Usuario</h3>
                      <div class="card-tools" id="card-tools">
                        <button type="button" class="btn btn-primary btn-block" onclick="urlback()"><i
                            class="fas fa-arrow-left mr-2"></i> Regresar</button>
                      </div>
                    </div>
                    <div class="card-body">
                        <form id="formNewUsuario" action="" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-2"></div>
                                <div class="col-8">
                                    <div class="form-group">
                                      <label for="inputNameUser">Nombres Completos</label>
                                      <input type="text" class="form-control" id="inputNameUser" placeholder="Nombres Completos"
                                          autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                      <label for="inputUsuario">Usuario</label>
                                      <input type="text" class="form-control" id="inputUsuario" placeholder="Usuario"
                                          autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                      <label for="inputPassword">Contraseña</label>
                                      <div class="input-group">
                                        <input type="password" class="form-control" placeholder="Clave" id="inputPassword" name="inputPassword" autocomplete="off">
                                        <div class="input-group-append showhideinit" onclick="showPassword('inputPassword','spanLock')">
                                            <div class="input-group-text" id="spanLock">
                                            <span class="fas fa-lock"></span>
                                            </div>
                                        </div>
                                      </div>
                                      <span class="spanNotiRegistro">La clave debe tener una longitud mínima de 8 caracteres</span>
                                    </div>
                                    <div class="form-group">
                                      <label for="inputRePassword">Repita la Contraseña</label>
                                      <div class="input-group">
                                        <input type="password" class="form-control" placeholder="Clave" id="inputRePassword" name="inputRePassword" autocomplete="off">
                                        <div class="input-group-append showhideinit" onclick="showPassword('inputRePassword','spanLock2')">
                                          <div class="input-group-text" id="spanLock2">
                                            <span class="fas fa-lock"></span>
                                          </div>
                                        </div>
                                      </div>
                                      <span class="spanNotiRegistro">La clave debe tener una longitud mínima de 8 caracteres</span>
                                    </div>
                                    <div class="form-group">
                                      <label for="selectTypeUser">Tipo Usuario</label>
                                      <select name="selectTypeUser" id="selectTypeUser" class="form-control">
                                        <option value="0">-Seleccione una Opción-</option>
                                        @foreach ($perfiluser as $item)
                                          <option value="{{$item->id}}">{{$item->nombre}}</option>
                                        @endforeach
                                      </select>
                                    </div>
                                </div>
                                <div class="col-2"></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mt-4 d-flex justify-content-end">
                                  <button type="button" class="btn btn-primary" style="font-size: 16px;" onclick="guardarUsuario()">
                                    <i class="far fa-save mr-2"></i>
                                    Guardar
                                  </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="modalFullSend" class="modal fade modal-full" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <button aria-label="" type="button" class="close px-2" data-dismiss="modal" aria-hidden="true">
        <!--<span aria-hidden="true">×</span>-->
    </button>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border loadInfo mr-3 text-primary" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
                <br><br>
                <p style="font-size: 16px;"> Registrando Usuario... </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/usuarios.js')}}"></script>
<script src="{{asset('assets/administrador/js/validacion.js')}}"></script>
<script>
  const nameInterfaz = "Usuarios";
</script>
@endsection