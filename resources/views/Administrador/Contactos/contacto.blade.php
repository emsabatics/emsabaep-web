@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/contact-list.png')}}">
@endsection

@section('title-page')
Admin | Contactos {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<script src='https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css' rel='stylesheet' />

<!-- Toastr -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/toastr/toastr.min.css')}}">
<style>
  #map canvas, #map .mapboxgl-canvas {
    height: 100% !important;
    width: 100% !important;
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
    <h1>Contactos</h1>
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
                        <h3 class="card-title p-2"><i class="fas fa-address-card mr-3"></i> Listado de Contactos de la Institución</h3>
                        <div class="card-tools" id="card-tools">
                          <button type="button" id="btn_insert_info" class="btn btn-primary btn-block" onclick="routewritecontact()"><i
                            class="far fa-plus-square mr-2"></i> Registrar</button>
                          <button type="button" class="btn btn-info btn-block" onclick="addlocationmap()"><i
                              class="far fa-plus-square mr-2"></i> Agg Infor Mapa</button>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-4" id="divTablaContact">
                      <table class="table table-hover text-nowrap table-head-fixed" id="tablaListadoContact">
                        <thead>
                          <tr style="pointer-events:none;">
                            <th>N°</th>
                            <th>Tipo de Contacto</th>
                            <th>Detalle</th>
                            <th>Opciones</th>
                          </tr>
                        </thead>
                        <tbody>

                        </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>


<!--MODAL EDIT CONTACTO GEOLOCALIZACION-->
<div class="modal fade" id="modalEditContactGeo" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Contacto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                      <div style='width: 100%; height: 400px;'>
                        <div id='map' style='width: 100%; height: 100%;'></div>
                        <pre id="coordinates" class="coordinates"></pre>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group mb-3">
                        <label for="inputEditNameLocation">Nombre</label>
                        <textarea class="form-control text-justify" id="inputEditNameLocation" placeholder="Ingrese un Nombre" rows="2" cols="5" maxlength="270"></textarea>
                      </div>
                      <div class="form-group mb-3">
                        <label for="inputEditDireccionLocation">Dirección</label>
                        <textarea class="form-control text-justify" id="inputEditDireccionLocation" placeholder="Ingrese una dirección" rows="2" cols="5" maxlength="270"></textarea>
                      </div>
                      <div class="form-group mb-3">
                          <input type="hidden" id="idcontactogeo">
                          <label for="inputELatitud">Latitud:</label>
                          <input type="text" id="inputELatitud" class="form-control" placeholder="Latitud" autocomplete="off" readonly>
                      </div>
                      <div class="form-group mb-3">
                          <label for="inputELongitud">Longitud:</label>
                          <input type="text" id="inputELongitud" class="form-control" placeholder="Longitud" autocomplete="off" readonly>
                      </div>
                    </div>
                </div>
                <div class="row" style="height: 100px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" onclick="cerrarModalGeo()">Cerrar</button>
                <button type="button" class="btn btn-primary mb-2" onclick="actualizarRegistroGeo()">Actualizar</button>
            </div>
        </div>
    <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>


<!--MODAL EDIT CONTACTO-->
<div class="modal fade" id="modalEditContact" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Contacto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                    <input type="hidden" id="idcontactoditeem">
                    <div id="formContactEdit"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary mb-2" onclick="actualizarRegistroDiTeEm()">Actualizar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>

<!--MODAL EDIT CONTACTO HORARIO-->
<div class="modal fade" id="modalEditContactHour" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Editar Contacto</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                    <input type="hidden" id="idcontactohour">
                    <div class="form-group">
                        <label>Horario de Atención</label>
                        <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="inputDesdeEdit">Desde: </label>
                            <input type="text" class="form-control form-control-border border-width-2" id="inputDesdeEdit" value="Lunes" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="inputHastaEdit">Hasta: </label>
                            <input type="text" class="form-control form-control-border border-width-2" id="inputHastaEdit" value="Viernes" readonly>
                            </div>
                        </div>
                        </div>
                        <div class="row">
                        <div class="col-md-6">
                            <label>Hora de Apertura:</label><br>
                            <input type="time" name="inputHourA" id="inputHourA" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label>Hora de Cierre:</label><br>
                            <input type="time" name="inputHourC" id="inputHourC" class="form-control">
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary mb-2" onclick="actualizarRegistroHour()">Actualizar</button>
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
<script src="{{asset('assets/administrador/js/contactos.js')}}"></script>
<script>
    $('.select2').select2({
        theme: 'bootstrap4',
    });

    /*mapboxgl.accessToken = 'pk.eyJ1IjoiamNsb3BlejE0IiwiYSI6ImNqemE2cjI4ZzAwbmEzamxveXU1OG8za3UifQ.BadDhjV5YpOq3cG4c7sTbw';*/
    mapboxgl.accessToken = 'pk.eyJ1IjoiamVhbmNsIiwiYSI6ImNtMGZpbmpjazA0YzAybHBucWhzOWluNnkifQ.DPKfnyD9t1DbHroX-OJ1Fg';
    var map=null;

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

    var resultadoArray = {{Illuminate\Support\Js::from($contactos)}};
    $('#modalCargando').modal('show');
    setTimeout(() => {
        getListadoContactos(resultadoArray);
      //$('#modalCargando').modal('hide');
    }, 300);
</script>
@endsection