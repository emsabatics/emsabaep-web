@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/servicio-agua.png')}}">
@endsection

@section('title-page')
Admin | Servicios {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free-5.15.4/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<!-- Toastr -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/toastr/toastr.min.css')}}">
<!-- summernote -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/summernote/summernote-bs4.min.css')}}">

<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop.css')}}">

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
    <h1>Servicios</h1>
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
                      <h3 class="card-title">Registrar Servicio</h3>
                      <div class="card-tools" id="card-tools">
                        <button type="button" class="btn btn-primary btn-block" onclick="urlback()"><i
                            class="fas fa-arrow-left mr-2"></i> Regresar</button>
                    </div>
                  </div>
                  <div class="card-body">
                      <form id="formServicio" action="" method="POST" enctype="multipart/form-data">
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label>Tipo de Servicio:</label>
                                <select class="form-control select2" id="selTypeService" name="selTypeService">
                                  <optgroup label="Seleccione una Opción">
                                    <option value="0">-Seleccione una Opción-</option>
                                    <option value="interno">Interno</option>
                                    <option value="externo">Externo</option>
                                  </optgroup>
                                </select>
                              </div>
                              <div class="form-group" id="divEnlaceService" style="display: none;">
                                <label for="inputLinkService">Enlace Externo del Servicio:</label>
                                <textarea class="form-control text-justify" id="inputLinkService" name="inputLinkService" 
                                  placeholder="Ingrese el Enlace" rows="4" cols="5"></textarea>
                              </div>
                            </div>
                            <div class="col-lg-8">
                              <div class="form-group mt-4">
                                <label for="inputTitleService">Título del Servicio: <span class="spanlabel">50 caracteres máximo</span></label>
                                <textarea class="form-control text-justify" id="inputTitleService" name="inputTitleService" placeholder="Ingrese un Título"
                                  maxlength="50" rows="6" cols="5"></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label for="inputDescShortService">Descripción corta: <span class="spanlabel">270 caracteres
                                    máximo</span></label>
                                <textarea class="form-control text-justify" id="inputDescShortService"
                                  placeholder="Ingrese una breve descripción" rows="5" cols="5" maxlength="270"></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-12">
                              <div class="form-group">
                                <label style="width: 100%;">Descripción del Servicio: 
                                  <!--<span class="spanlabel">200 caracteres máximo</span>
                                  <span id="maxContentPost" class="spanlabel" style="text-align:right"> 200 caracteres disponibles</span>-->
                                </label>
                                <textarea id="summernote"></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-6">
                              <div class="form-group mb-3">
                                <label for="file">Imagen del Servicio: </label>
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
                            <div class="col-lg-6">
                              <div class="form-group mb-3">
                                <label for="fileIcon">ícono del Servicio: </label>
                                <div class="container">
                                  <input type="file" name="fileIcon[]" id="fileIcon" accept="image/*" onchange="previewIcon()" multiple>
                                  <label for="fileIcon">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i> Elija una imagen
                                  </label>
                                  <p id="num-of-files-icon">- Ningún archivo seleccionado -</p>
                                  <div id="images-icon"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-12 mt-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary saveservice" style="font-size: 16px;" onclick="guardarServicio()">
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
                <p style="font-size: 16px;"> Registrando Servicio... </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('assets/administrador/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/drag-drop-services.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/servicios.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/validacion.js')}}"></script>

<script>
  const nameInterfaz = "Servicios";
  var typeService='';

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

  $(function () {
    // Summernote
    $('#summernote').summernote({
      height: 400,
      focus: true,
      toolbar: [
        // [groupName, [list of button]]
        //['font', ['strikethrough', 'superscript', 'subscript']],
        //['color', ['color']],
        //['para', ['ul', 'ol', 'paragraph']],
        //['height', ['height']]
        //['style', ['bold', 'italic', 'underline', 'clear']],
        //['fontsize', ['fontsize']],
        ['style', ['bold', 'italic', 'underline']],
        //['insert', ['picture']],
        ['para', ['ul', 'paragraph']],
      ],
      fontNames: ['Arial', 'Arial Black'],
      fontNames: 'Arial',
      fontSize: 18,
      lineHeight: 2.0,
      placeholder: 'Ingrese la descripción...',
      /*callbacks: {
        onKeydown: function (e) { 
          var t = e.currentTarget.innerText; 
          if (t.trim().length >= 200) {
            //delete keys, arrow keys, copy, cut, select all
            if (e.keyCode != 8 && !(e.keyCode >=37 && e.keyCode <=40) && e.keyCode != 46 && !(e.keyCode == 88 && e.ctrlKey) && !(e.keyCode == 67 && e.ctrlKey) && !(e.keyCode == 65 && e.ctrlKey))
              e.preventDefault(); 
            } 
          },
          onKeyup: function (e) {
            var t = e.currentTarget.innerText;
            $('#maxContentPost').text(200 - t.trim().length + " caracteres disponibles");
          },
          onPaste: function (e) {
            var t = e.currentTarget.innerText;
            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
            e.preventDefault();
            var maxPaste = bufferText.length;
            if(t.length + bufferText.length > 200){
              maxPaste = 200 - t.length;
            }
            if(maxPaste > 0){
              document.execCommand('insertText', false, bufferText.substring(0, maxPaste));
            }
          $('#maxContentPost').text(200 - t.length);
        }
      }*/
    });
  });
</script>

@endsection