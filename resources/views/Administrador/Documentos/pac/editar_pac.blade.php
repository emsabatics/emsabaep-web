@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/solicitud.png')}}">
@endsection

@section('title-page')
Admin | PAC {{getNameInstitucion()}}
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
<link rel="stylesheet" href="{{asset('assets/administrador/css/setcards.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/no-data-load.css')}}">

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

  .noshow{
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
    <h1>Editar PAC</h1>
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
                      <h3 class="card-title">Editar PAC</h3>
                      <div class="card-tools" id="card-tools">
                        <button type="button" class="btn btn-primary btn-block" onclick="urlback()"><i
                            class="fas fa-arrow-left mr-2"></i> Regresar</button>
                      </div>
                  </div>
                  <div class="card-body">
                      <form id="formE_PAC" action="" method="POST" enctype="multipart/form-data">
                          @foreach ($filepac as $p)
                          <div class="row">
                              <div class="col-6">
                                  <div class="form-group noevent">
                                    <input type="hidden" name="idpac" id="idpac" value="{{$p->id}}">
                                    <label>Año:</label>
                                    <select class="form-control select2" id="selYearEPac" name="selYearEPac">
                                      <optgroup label="Seleccione una Red Social">
                                        <option value="0">-Seleccione una Opción-</option>
                                        @foreach ($dateyear as $item)
                                        @if ($item->id == $p->id_anio)
                                          <option value="{{$item->id}}" selected>{{$item->nombre}}</option>
                                        @else
                                          <option value="{{$item->id}}">{{$item->nombre}}</option>
                                        @endif
                                        @endforeach
                                      </optgroup>
                                    </select>
                                  </div>
                                  <div class="form-group">
                                      <label for="inputETitulo">Título: <span class="spanlabel">70 caracteres máximo</span></label>
                                      <textarea class="form-control text-justify noevent" id="inputETitulo" name="inputETitulo" placeholder="Ingrese un título"
                                        maxlength="70">{{$p->titulo}}</textarea>
                                  </div>
                                  <div class="form-group">
                                    <label for="inputEAliasFile">Alias del Documento: <span class="spanlabel">70 caracteres máximo</span></label>
                                    <textarea class="form-control text-justify noevent" id="inputEAliasFile" name="inputEAliasFile" placeholder="Ingrese un alias"
                                      maxlength="70">{{substr($p->archivo, 0, -4);}}</textarea>
                                  </div>

                                  <div class="card-list grid-card-list" id="cardListPac">
                                    <div class='item-list grid-item-list' id='divpics'>
                                      <div style='grid-row: 1/2;'>
                                        <div class='avatar' style="text-align: center;height: 78px;">
                                          <img src='/assets/administrador/img/icons/icon-pdf-color.svg' alt='File' class='avatar-img' style="width: 78px;height: 78px;">
                                        </div>
                                      </div>
                                      <div style='grid-row: 1/2;'>
                                        <button class='btn btn-icon btn-danger btn-round btn-xs' onclick='eliminarFile(event, "pac")'>
                                          <i class='fas fa-trash'></i>
                                        </button>
                                      </div>
                                      <div style='grid-row: 2/2;'>
                                        <div class="info-user">
                                          <div class='status'>{{$p->archivo}}</div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                              </div>
                              <div class="col-6">
                                  <div class="form-group mb-3">
                                      <label for="inputEObsr">Observación (Opcional):</label>
                                      <textarea class="form-control text-justify" id="inputEObsr" name="inputEObsr"
                                        placeholder="Observación" rows="4" cols="5">{{$p->observacion}}</textarea>
                                  </div>
                                  <div class="form-group mb-3 noshow" id="divfilepac">
                                    <span class="spanlabel m-4">Seleccione solo un archivo</span>
                                    <div class="container">
                                      <input type="file" name="fileEdit[]" id="fileEdit" accept="application/pdf" onchange="previewEdit()" multiple>
                                      <label for="fileEdit">
                                        <i class="fas fa-cloud-upload-alt mr-2"></i> Elija un archivo
                                      </label>
                                      <p id="num-of-files-edit">- Ningún archivo seleccionado -</p>
                                      <div id="imagesEdit"></div>
                                    </div>
                                </div>
                              </div>
                          </div>
                          <div class="row" style="border-top: 2mm ridge #32a1ce;padding-top: 15px;">
                            <div class="col-12">
                              <label for="">INFORMACIÓN ADICIONAL</label>
                            </div>
                            <div class="col-6">
                              <div class="form-group">
                                <label for="inputEResolucion">Resolución Administrativa: <span class="spanlabel"></span></label>
                                <textarea class="form-control text-justify" id="inputEResolucion" name="inputEResolucion" placeholder="Ingrese un título"
                                  maxlength="70">{{$p->resol_admin}}</textarea>
                              </div>
                              <div class="form-group">
                                <label for="inputEAliasFileRA">Alias del Documento: <span class="spanlabel">70 caracteres máximo</span></label>
                                <div class="input-group mb-3">
                                  <button class="btn btn-outline-primary" type="button" onclick="generarAliasE()">Generar</button>
                                  <textarea id="inputEAliasFileRA" name="inputEAliasFileRA" class="form-control text-justify noevent" placeholder="Ingrese un alias" aria-label="Example" 
                                    aria-describedby="button-addon1" maxlength="70">{{substr($p->archivo_resoladmin, 0, -4);}}</textarea>
                                </div>
                              </div>
                              <div class="card-list grid-card-list" id="cardListRa">
                                <div class='item-list grid-item-list' id='divpics'>
                                  <div style='grid-row: 1/2;'>
                                    <div class='avatar' style="text-align: center;height: 78px;">
                                      <img src='/assets/administrador/img/icons/icon-pdf-color.svg' alt='File' class='avatar-img' style="width: 78px;height: 78px;">
                                    </div>
                                  </div>
                                  <div style='grid-row: 1/2;'>
                                    <button class='btn btn-icon btn-danger btn-round btn-xs' onclick='eliminarFile(event, "ra")'>
                                      <i class='fas fa-trash'></i>
                                    </button>
                                  </div>
                                  <div style='grid-row: 2/2;'>
                                    <div class="info-user">
                                      <div class='status'>{{$p->archivo_resoladmin}}</div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="form-group mt-4">
                                <div class="custom-control custom-checkbox">
                                  <input class="custom-control-input" type="checkbox" id="checkReforma" onclick="getChecked()">
                                  <label for="checkReforma" class="custom-control-label">Reforma PAC</label>
                                </div>
                              </div>
                            </div>
                            <div class="col-6">
                              <div class="form-group mb-3 noshow" id="divfilera">
                                <span class="spanlabel m-4">Seleccione solo un archivo</span>
                                <div class="container">
                                  <input type="file" name="fileEra[]" id="fileEra" accept="application/pdf" onchange="previewopcionalra()" multiple>
                                  <label for="fileEra">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i> Elija un archivo
                                  </label>
                                  <p id="num-of-files-ra-ed">- Ningún archivo seleccionado -</p>
                                  <div id="imagesraE"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-12 mt-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary" style="font-size: 16px;" onclick="actualizarPac()">
                                  <i class="far fa-save mr-2"></i>
                                  Actualizar
                                </button>
                              </div>
                          </div>
                          @endforeach
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
                <p style="font-size: 16px;"> Actualizando PAC... </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/drag-drop-files.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/pac.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>

<script>
  $('.select2').select2({
    theme: 'bootstrap4',
  });
  
  var isPac= true;
  var isRa= true;
  var isReforma= false;
</script>
@endsection