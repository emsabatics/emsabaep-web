@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/solicitud.png')}}">
@endsection

@section('title-page')
Admin | POA {{getNameInstitucion()}}
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
    <h1>Registro POA</h1>
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
                      <h3 class="card-title">Registrar POA</h3>
                      <div class="card-tools" id="card-tools">
                        <button type="button" class="btn btn-primary btn-block" onclick="urlback()"><i
                            class="fas fa-arrow-left mr-2"></i> Regresar</button>
                    </div>
                  </div>
                  <div class="card-body">
                      <form id="formPOA" action="" method="POST" enctype="multipart/form-data">
                          <div class="row">
                              <div class="col-6">
                                  <div class="form-group">
                                    <label>Año:</label>
                                    <select class="form-control select2" id="selYearPOA" name="selYearPOA">
                                      <optgroup label="Seleccione una Red Social">
                                        <option value="0">-Seleccione una Opción-</option>
                                        @foreach ($dateyear as $item)
                                        <option value="{{$item->id}}">{{$item->nombre}}</option>
                                        @endforeach
                                      </optgroup>
                                    </select>
                                  </div>
                                  <div class="form-group m-3">
                                    <!--<div class="custom-control custom-checkbox">
                                      <input class="custom-control-input" type="checkbox" id="checkboxReform" onclick="seleccionarCheck(this,'si')">
                                      <label for="checkboxReform" class="custom-control-label">General / Consolidado</label>
                                    </div>-->
                                    <div class="custom-control custom-radio">
                                      <input class="custom-control-input" type="radio" id="radioGeneral" name="customRadio" value="general" checked onclick="seleccionarCheck(this)">
                                      <label for="radioGeneral" class="custom-control-label">General</label>
                                    </div>
                                    <!--<div class="custom-control custom-radio">
                                      <input class="custom-control-input" type="radio" id="radioArea" name="customRadio" value="area" onclick="seleccionarCheck(this)">
                                      <label for="radioArea" class="custom-control-label">Área</label>
                                    </div>-->
                                  </div>
                                  <div class="form-group" id="divselareapoa" style="display: none;">
                                    <label>Área:</label>
                                    <select class="form-control select2" id="selArea" name="selArea">
                                      <optgroup label="Seleccione una Opción">
                                        <option value="0">-Seleccione una Opción-</option>
                                        @foreach ($area as $i)
                                        @if ($i['tipo']=="gerencia")
                                        <option value="grc_{{$i['id']}}">{{$i['nombre']}}</option>
                                        @else
                                        <option value="dir_{{$i['id']}}">{{$i['nombre']}}</option>  
                                        @endif
                                        @endforeach
                                      </optgroup>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                      <label for="inputTitulo">Título: <span class="spanlabel">70 caracteres máximo</span></label>
                                      <textarea class="form-control text-justify" id="inputTitulo" name="inputTitulo" placeholder="Ingrese un título"
                                        maxlength="70"></textarea>
                                  </div>
                                  <div class="form-group">
                                    <label for="inputAliasFile">Alias del Documento: <span class="spanlabel">70 caracteres máximo</span></label>
                                    <div class="input-group mb-3">
                                      <button class="btn btn-outline-primary" type="button" onclick="generarAlias()">Generar</button>
                                      <textarea id="inputAliasFile" name="inputAliasFile" class="form-control text-justify noevent" placeholder="Ingrese un alias" aria-label="Example" aria-describedby="button-addon1" maxlength="70"></textarea>
                                    </div>
                                  </div>
                              </div>
                              <div class="col-6">
                                  <div class="form-group mb-3">
                                      <label for="inputObsr">Observación (Opcional):</label>
                                      <textarea class="form-control text-justify" id="inputObsr" name="inputObsr"
                                        placeholder="Observación" rows="4" cols="5"></textarea>
                                  </div>
                              </div>
                          </div>
                          <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-6">
                              <div class="form-group mb-3">
                                <span class="spanlabel m-4">Seleccione solo un archivo</span>
                                <div class="container">
                                  <input type="file" name="file[]" id="file" accept="application/pdf" onchange="preview()" multiple>
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
                                <button type="button" class="btn btn-primary savepoa" style="font-size: 16px;" onclick="guardarPoa()">
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
                <p style="font-size: 16px;"> Registrando POA... </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/drag-drop-files.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/poa.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>

<script>
  $('.select2').select2({
    theme: 'bootstrap4',
  });

  $(document).ready(function () {
    //$('#modalCargando').modal('show');
  });
</script>
@endsection