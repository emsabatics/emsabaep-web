@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/libro-de-historia.png')}}">
@endsection

@section('title-page')
Admin | Historia {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/no-data-load.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
  <!-- summernote -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/summernote/summernote-bs4.min.css')}}">
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
    <h1>Historia de la Institución</h1>
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
                <i class="far fa-file-word mr-3" aria-hidden="true"></i>
                Historia de la Institución
              </h3>
          </div>
          <div class="card-body">
            <form id="formInHistoria" method="POST" enctype="multipart/form-data">
              <div class="row">
                <div class="col-lg-12 mt-2">
                  <textarea id="summernote"></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-6 mt-4">
                  <div class="form-group mb-3">
                    <span class="spanlabel">Debe elegir solo una imagen</span>
                    <div class="container">
                      <input type="file" name="file[]" id="file" accept="image/*" onchange="preview()" multiple>
                      <label for="file">
                        <i class="fas fa-cloud-upload-alt mr-2"></i> Elija una imagen
                      </label>
                      <p id="num-of-files">- Ningún archivo seleccionado -</p>
                      <div id="images"></div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-6 mt-4">
                  <div class="card card-secondary">
                    <div class="card-header">
                      <h3 class="card-title">Ubicación de la Imagen en la Página</h3>
                    </div>
                    <div class="card-body">
                      <div class="form-group">
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" value="inicio" id="customRadioInicio" 
                            name="customRadio" onclick="handleClick(this);">
                          <label for="customRadioInicio" class="custom-control-label">Antes del Texto</label>
                        </div>
                        <div class="custom-control custom-radio">
                          <input class="custom-control-input" type="radio" value="end" id="customRadioEnd" 
                            name="customRadio" onclick="handleClick(this);">
                          <label for="customRadioEnd" class="custom-control-label">Después del Texto</label>
                        </div>
                      </div>
                    </div>
                  </div>
                </div><!--COL-LG-6-->
              </div>
              <div class="row">
                <div class="col-lg-12 mt-4 d-flex justify-content-end">
                  <button type="button" class="btn btn-primary button-save-historia" style="font-size: 16px;" onclick="guardarHistoria()">
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
<!-- Summernote -->
<script src="{{asset('assets/administrador/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/summernote/lang/summernote-es-ES.js')}}"></script>
<script src="{{asset('assets/administrador/js/drag-drop.js')}}"></script>
<script src="{{asset('assets/administrador/js/registro_historia.js')}}"></script>
<script>
  //editHistoria();

  // Summernote
  $('#summernote').summernote({
    focus: true,
    lang: 'es-ES',
    toolbar: [
      // [groupName, [list of button]]
      //['font', ['strikethrough', 'superscript', 'subscript']],
      //['color', ['color']],
      //['para', ['ul', 'ol', 'paragraph']],
      //['height', ['height']]
      //['style', ['bold', 'italic', 'underline', 'clear']],
      //['fontsize', ['fontsize']],
      ['style', ['bold', 'italic', 'underline']],
      ['para', ['ul', 'paragraph']]
      //['insert', [ 'picture']]
    ],
    fontNames: ['Arial', 'Arial Black'],
    fontNames: 'Arial',
    fontSize: 18,
    lineHeight: 2.0,
    placeholder: 'Ingrese la información...',
    popover: {
      image: [
        ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
        ['float', ['floatLeft', 'floatRight', 'floatNone']],
        ['remove', ['removeMedia']]
      ]
    }
  });
</script>
@endsection