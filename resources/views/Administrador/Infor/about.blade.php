@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/about.png')}}">
@endsection

@section('title-page')
Admin | Acerca {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/setcards.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/no-data-load.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
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
    <h1>Acerca de la Institución</h1>
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
                  <i class="fas fa-info mr-3"></i>
                  Acerca de la Institución
                </h3>
            </div>
            <div class="card-body">
                <div class="callout callout-info" style="position:relative;">
                    <div><h5>Detalle:</h5></div>
                    <div id="infor-institucion" style="margin: 1.2em 0;">

                    </div>
                    <!--<p class="callout-p"></p>-->
                    <div>
                        <button type="button" class="btn btn-primary btn-block callout-button" onclick="openmodalEditInfor()">
                            <i class="fas fa-edit mr-2"></i>
                            <span class="callout-span-btn">Editar</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
  <div class="col-lg-6" id="rowPicsInd2">
    <div class="card carduppic">
      <div class="card-body">
        <div class="card-title fw-mediumbold">Imagen Subida</div>
        <div class="card-list grid-card-list" id="cardListPicsUp">

        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="card card-default">
      <div class="card-header">
        <h3 class="card-title">Subir Imagen</h3>
        <div class="card-tools">
          <span class="spanlabel">Las Imagen deben tener un alto mayor a 700px y un ancho mayor a 600px</span>
        </div> 
      </div>
      <div class="card-body">
        <form id="formEAbout" action="" method="POST" enctype="multipart/form-data">
        <div class="row">
          <div class="col-lg-12 d-flex justify-content-end">
            <button type="button" class="btn btn-primary" style="height: 40px;" onclick="updatepicsabout(event)">
              <i class="far fa-save mr-2"></i>
                Actualizar Imagen
            </button>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group mb-3">
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
        </div>
        </form>
        <div class="row" id="rowPicsInd">
        </div>
      </div>
    </div>
  </div>
</div>

<!--MODAL AGG / EDIT ABOUT-->
<div class="modal fade" id="modal-edit-about" tabindex="-1" role="dialog"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Acerca de EMSABA</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12">
            <input type="hidden" id="idabout" name="idabout">
            <div class="card card-outline card-info">
              <div class="card-header">
                <h3 class="card-title">
                  Ingrese la Información Institucional
                </h3>
              </div>
              <div class="card-body p-0">
                <textarea id="inputAbout" name="inputAbout" class="form-control text-justify p-3" rows="10" cols="5">{{ old('inputAbout') }}</textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary mb-2 btn-about" onclick="guardarRegistroAbout()">Guardar</button>
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
<script src="{{asset('assets/administrador/js/about.js')}}"></script>
<script src="{{asset('assets/administrador/js/drag-drop.js')}}"></script>
<script src="{{asset('assets/administrador/js/validacion.js')}}"></script>
<script>
    var inforAbout = {{ Illuminate\Support\Js::from($about) }};
    const nameInterfaz = "Acerca de";
    $('#modalCargando').modal('show');
    setTimeout(() => {
      cargar_about(inforAbout);
    }, 500);
</script>
@endsection