@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/solicitud.png')}}">
@endsection

@section('title-page')
Admin | Pliego Tarifario {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free-5.15.4/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop-files.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

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
    <h1>Registrar Pliego Tarifario</h1>
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
                      <h3 class="card-title">Registrar Pliego Tarifario</h3>
                      <div class="card-tools" id="card-tools">
                        <button type="button" class="btn btn-primary btn-block" onclick="urlback()"><i
                            class="fas fa-arrow-left mr-2"></i> Regresar</button>
                    </div>
                  </div>
                  <div class="card-body">
                      <form id="formPliego" action="" method="POST" enctype="multipart/form-data">
                          <div class="row">
                              <div class="col-md-2"></div>
                              <div class="col-8">
                                  <div class="form-group mb-3">
                                    <label for="inputObsr">Descripción:</label>
                                    <textarea class="form-control text-justify" id="inputObsr" name="inputObsr"
                                        placeholder="Descripción" rows="4" cols="5"></textarea>
                                  </div>
                                  <div class="form-group">
                                    <label for="inputAliasFile">Alias del Documento: <span class="spanlabel">70 caracteres máximo</span></label>
                                    <textarea id="inputAliasFile" name="inputAliasFile" class="form-control text-justify" placeholder="Ingrese un alias" aria-label="Example"
                                      aria-describedby="button-addon1" maxlength="70"></textarea>
                                  </div>
                              </div>
                              <div class="col-md-2"></div>
                          </div>
                          <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                              <div class="form-group mb-3">
                                <span class="spanlabel m-4">Seleccione solo un archivo</span>
                                <div class="container">
                                  <input type="file" name="file[]" id="file" accept="application/pdf, image/*" onchange="preview()" multiple>
                                  <label for="file">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i> Elija un archivo
                                  </label>
                                  <p id="num-of-files">- Ningún archivo seleccionado -</p>
                                  <div id="images"></div>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-3"></div>
                          </div>
                          <div class="row">
                              <div class="col-lg-12 mt-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary savepliego" style="font-size: 16px;" onclick="guardarPliego()">
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
                <p style="font-size: 16px;"> Registrando Pliego Tarifario... </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/drag-drop-files.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/pliego.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>
@endsection