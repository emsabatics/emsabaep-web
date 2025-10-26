@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/actualizarp.png')}}">
@endsection

@section('title-page')
Admin | Medios de Verificación {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free-5.15.4/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/setcards.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/no-data-load.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop-files.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2/css/select2.min.css')}}"> 
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<!-- Toastr -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/toastr/toastr.min.css')}}">

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

  .formEdit{
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
    <h1>Medios de Verificación</h1>
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
            <h3 class="card-title">Editar Medios de Verificación</h3>
            <div class="card-tools row-options-header">
              <button type="button" class="btn btn-info btn-set1" onclick="urlback()"><i class="fas fa-arrow-left mr-3"></i></span>Regresar</button>
              <button type="button" class="btn btn-primary btn-block btn-set2" onclick="updateonlytextnews()"><i
                class="far fa-save mr-2"></i> Actualizar Texto</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    @foreach ($filemediosv as $p)
    <div class="row">
      <div class="col-4">
        <div class="card card-default">
          <div class="card-body">
            <div class="form-group noevent">
              <input type="hidden" name="idmediosv" id="idmediosv" value="{{$p->id}}">
              <label>Año:</label>
              <select class="form-control select2" id="selYearEditMediosv" name="selYearEditMediosv">
                <optgroup label="Seleccione una Opción">
                  <option value="0">-Seleccione una Opción-</option>
                  @foreach ($anio as $item) 
                  @if ($p->id_anio == $item->id)
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
              <textarea class="form-control text-justify" id="inputETitulo" placeholder="Ingrese un título" 
                maxlength="70">{{$p->titulo}}</textarea>
            </div>
          </div>    
        </div>
      </div>
      <div class="col-lg-8" id="rowPicsInd2">
        <div class="card carduppic">
          <div class="card-body">
            <div class="card-title fw-mediumbold">Archivos Subidos</div>
            <div class="card-list grid-card-list" id="cardListPicsUp">
              @if (count($archivosmv)>0)
              <?php $isFiles=true; ?>
              @foreach ($archivosmv as $ifi)
                <div class="item-list grid-item-list" id="divpics{{$loop->index}}">
                  <div style="grid-row: 1/2;">
                    <div class="avatar">
                      <img src='/assets/administrador/img/icons/icon-pdf-color.svg' alt='File' class='avatar-img' style="width: 78px;height: 78px;">
                      <div class="status">{{$ifi->titulo}}</div>
                    </div>
                  </div>
                  <div style="grid-row: 1/2;">
                    <button class="btn btn-icon btn-info btn-round btn-xs" onclick="verFile({{$ifi->id}})">
                      <i class="fas fa-folder"></i>
                    </button>
                    <button class="btn btn-icon btn-danger btn-round btn-xs" onclick="eliminarFile({{$ifi->id}}, {{$loop->index}})">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>
                  <div style="grid-row: 1/2;">
                    <div class="info-user">
                      <div class="status"></div>
                    </div>
                  </div>
                </div>
              @endforeach
              @else
              <?php $isFiles=false; ?>
              <div class="row nonews">
                <div class="col-lg-12 no-data">
                  <div class="imgadvice">
                    <img src="/assets/administrador/img/icons/no-content-img.png" alt="Contenido">
                  </div>
                  <span class="mensaje-noticia mt-4 mb-4">No hay <strong>archivos</strong> disponibles por el momento...</span>
                </div>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <form id="formEMediosV" action="" method="POST" enctype="multipart/form-data">
    <div class="row">
      <div class="col-lg-6">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Subir Archivos</h3>
            <div class="card-tools">
              <span class="spanlabel">Seleccione los Archivos</span>
            </div> 
          </div>
          <div class="card-body">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group mb-3">
                    <input type="hidden" name="idnoticiapics" id="idnoticiapics">
                    <div class="container">
                      <input type="file" name="file[]" id="file" accept="application/pdf, video/mp4" onchange="previewMediosV()" multiple>
                      <label for="file">
                        <i class="fas fa-cloud-upload-alt mr-2"></i> Elija una imagen
                      </label>
                      <p id="num-of-files">- Ningún archivo seleccionado -</p>
                    </div>
                  </div>
                </div>
              </div>
            <div class="row" id="rowPicsInd"></div>
          </div>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Listado de Archivos a Subir</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-primary updatemediosv" style="height: 40px;" onclick="updatefilesmediosv(event)">
                <i class="far fa-save mr-2"></i>
                  Guardar Archivos
              </button>
            </div> 
          </div>
          <div class="card-body">
            <div id="images"></div>
          </div>
        </div>
      </div>
    </div>
    </form>
    @endforeach
  </div>
</section>

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

<div id="modalFullSendEdit" class="modal fade modal-full" tabindex="-1" role="dialog"
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
                <p style="font-size: 16px;"> Actualizando... </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/drag-drop-files.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/mediosverificacion.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/validacion.js')}}"></script>
<script>
  $('.select2').select2({
    theme: 'bootstrap4',
  });
  const nameInterfaz = "Medios de Verificación";
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

  var isFiles = @json($isFiles);
  var contadorHash = 0;

  function val_data(){
    if(isFiles==false){
      var element= document.getElementById('cardListPicsUp');
      element.style.cssText= 'grid-template-columns: 1fr';
      element.innerHTML= html;
    }
  }

  $(document).ready(function(){
    $('#modalCargando').modal('show');
    setTimeout(() => {
      val_data();
      $('#modalCargando').modal('hide');
    }, 1200);
  });
</script>
@endsection