@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/rol.svg')}}">
@endsection

@section('title-page')
Admin | Permisos de Usuario {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/toggle.css')}}">

<!-- Toastr -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/toastr/toastr.min.css')}}">

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/data-tables/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">

<style>
    .dropdown-toggle::after {
      display: none;
    }

    .pnodata{
      font-size: 17px;
      font-family: math;
      font-weight: 700;
      text-align: center;
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
    <h1>Permisos de Usuario</h1>
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
                        <!--<h3 class="card-title p-2"><i class="fas fa-icons mr-3"></i> Listado de Redes Sociales de la Institución</h3>
                        <div class="card-tools" id="card-tools">
                            <button type="button" class="btn btn-primary btn-block" onclick="openmodalAdd()"><i
                                class="far fa-plus-square mr-2"></i> Registrar</button>
                        </div>-->
                        <div class="cardsRowTitle">
                            <div class="cardsection">
                              <h3 class="card-title p-2"><i class="fas fa-list mr-3"></i> Configurar permisos de Usuarios al Sistema</h3>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive p-4" id="divDocPermisos">
                      @include('Administrador.Permisos.tabla', ['permisos' => $permisos])
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
    </div>
</section>

<!--MODAL AJUSTES PERMISOS-->
<div class="modal fade" id="modalSettings" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Asignar Módulo</h4>
                <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>-->
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group mb-3">
                    <input type="hidden" name="id_usuariop" id="id_usuariop">
                    <label for="inputUsuariop">Usuario:</label>
                    <input type="text" class="form-control" name="inputUsuariop" id="inputUsuariop" autocomplete="off" placeholder="Nombre" maxlength="150">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group mb-3" id="divDocSelectPermisos">
                    @include('Administrador.Permisos.select', ['pmodulos' => $pmodulos])
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div id="tabla_permiso">

                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12 m-2">
                  <div id="div_respuesta">
                    
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" onclick="cerrarModal()">Cerrar</button>
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

<!-- DataTables  & Plugins -->
<script src="{{asset('assets/administrador/plugins/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/permisos.js')}}"></script>
<script>
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
    
    $(document).ready(function () {
      $('#modalCargando').modal('show');
      setTimeout(() => {
        showInfoPermisos();
      }, 1500);
    });

    $('#modalAggModulo').on('shown.bs.modal', function() {
        $(document).off('focusin.modal');
    });
</script>
@endsection