@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/estructura-jerarquica.png')}}">
@endsection

@section('title-page')
Admin | Estructura {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/no-data-load.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/setcards.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/no-data-load.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
  <!-- summernote -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/summernote/summernote-bs4.min.css')}}">
<style>
  .loadInfo {
    width: 10rem !important;
    height: 10rem !important;
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
    <h1>Estructura de la Institución</h1>
  </div>
</div>
@endsection

@section('contenido-body')
<input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex p-0">
                <h3 class="card-title p-3">
                  <i class="fas fa-sitemap mr-3"></i>
                  Estructura de la Institución
                </h3>
            </div>
            <div class="card-body">
                <!--<textarea id="summernote">
                    Place <em>some</em> <u>text</u> <strong>here</strong>
                </textarea>-->
                <div class="buttonStructure">
                    <input type="hidden" id="idEstructura" name="idEstructura">
                    <button id="edit" class="btn btn-primary" onclick="edit()" type="button"> <i class="fa fa-edit mr-2"></i> Editar</button>
                    <button id="save" class="btn btn-primary" onclick="save()" type="submit"><i class="fa fa-save mr-2"></i>Guardar</button>
                </div>
                
                <div class="mt-2" id="divEstructura"></div>
            </div>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-lg-6" id="rowPicsInd2">
    <div class="card carduppic">
      <div class="card-body">
        <div class="card-title fw-mediumbold">Archivo Subido</div>
        <div class="card-list grid-card-list" id="cardListPicsUp">

        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card card-default">
      <div class="card-header">
        <h3 class="card-title">Subir Archivo</h3>
        <div class="card-tools">
          <!--La imagen debe tener un alto mayor a 1000px y un ancho mayor a 1900px-->
          <span class="spanlabel">Solo debe subir un archivo</span>
        </div> 
      </div>
      <div class="card-body">
        <form id="formEstructuraI" action="" method="POST" enctype="multipart/form-data">
        <div class="row">
          <div class="col-lg-12 d-flex justify-content-end">
            <button type="button" class="btn btn-primary" style="height: 40px;" onclick="updatepicsnews(event)">
              <i class="far fa-save mr-2"></i>
                Actualizar Archivo
            </button>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group mb-3">
              <input type="hidden" name="idstructurapics" id="idstructurapics">
              <div class="container">
                <input type="file" name="file[]" id="file" accept="image/*, application/pdf" onchange="preview()" multiple>
                <label for="file">
                  <i class="fas fa-cloud-upload-alt mr-2"></i> Elija una archivo
                </label>
                <p id="num-of-files">- Ningún archivo seleccionado -</p>
                <div id="images"></div>
              </div>
            </div>
          </div>
        </div>
        </form>
        <div class="row" id="rowPicsInd">
        </div>
      </div>
    </div>
  </div>
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
                <p style="font-size: 16px;"> Actualizando Archivo... </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- Summernote -->
<script src="{{asset('assets/administrador/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/drag-drop-files.js')}}"></script>
<script src="{{asset('assets/administrador/js/estructura.js')}}"></script>
<script>
    var elementEdit = document.querySelector('#edit');

    var elementSave = document.querySelector('#save');
    elementSave.setAttribute("disabled", "");
    elementSave.style.pointerEvents = "none";

    var inforEstructura = {{ Illuminate\Support\Js::from($estructura) }};

    $(document).ready(function(){
      $('#modalCargando').modal('show');
      setTimeout(() => {
        cargar_estructura(inforEstructura);
      }, 500);
    });

    // Summernote
    //$('#summernote').summernote();
</script>
@endsection