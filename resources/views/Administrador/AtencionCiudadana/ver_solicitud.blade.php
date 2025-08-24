@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/doc-administrativa.png')}}">
@endsection

@section('title-page')
Admin | Solicitudes {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free-5.15.4/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop-files.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/setcards.css')}}">
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

  .col-nombre {
    word-wrap: break-word;
    white-space: normal;
    width: 55%;
    text-align: justify;
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
    <h1>Solicitud de Cliente</h1>
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
                      <h3 class="card-title">Detalle Solicitud</h3>
                      <div class="card-tools" id="card-tools">
                          <div class="btn-toolbar" role="toolbar" aria-label="Toolbar">
                            <div class="btn-group mr-2" role="group" aria-label="First group">
                            @foreach ($solicitudes as $s)
                              @if ($s['estado_solicitud']=='En Trámite')
                                <button type="button" class="btn btn-danger btn-block" onclick="endsolicitudindividual()">
                                  Finalizar Solicitud
                                </button>
                              @else
                                <button type="button" class="btn btn-secondary btn-block" onclick="tramsolicitudindividual()">
                                  Cambiar estado a En Trámite
                                </button>
                              @endif
                            @endforeach
                            </div>
                            <div class="btn-group" role="group" aria-label="Second group">
                              <button type="button" class="btn btn-primary btn-block" onclick="urlback()"><i
                                class="fas fa-arrow-left mr-2"></i> Regresar</button>
                            </div>
                          </div>
                    </div>
                  </div>
                  <div class="card-body">
                      <!--<form id="formsolicitude" action="" method="POST" enctype="multipart/form-data">-->
                        @foreach ($solicitudes as $p)
                          <div class="row">
                              <div class="col-6">
                                <div class="form-group noevent">
                                  <input type="hidden" name="idregistrosoli" id="idregistrosoli" value="{{$p['id']}}">
                                  <label>Cuenta:</label>
                                  @if(strlen($p['cuenta'])>0)
                                  <input type="text" class="form-control" value="{{$p['cuenta']}}"/>
                                  @else
                                  <input type="text" class="form-control" value="Sin Registro"/>
                                  @endif
                                </div>
                                <div class="form-group noevent">
                                  <label>Nombres:</label>
                                  <input type="text" class="form-control" value="{{$p['nombres']}}"/>
                                </div>
                              </div>
                              <div class="col-6">
                                <div class="form-group noevent">
                                  <label>Email:</label>
                                  @if(strlen($p['email'])>0)
                                  <input type="email" class="form-control" value="{{$p['email']}}"/>
                                  @else
                                  <input type="email" class="form-control" value="Sin Registro"/>
                                  @endif
                                </div>
                                <div class="form-group noevent">
                                  <label>telefono:</label>
                                  @if(strlen($p['telefono'])>0)
                                  <input type="text" class="form-control" value="{{$p['telefono']}}"/>
                                  @else
                                  <input type="text" class="form-control" value="Sin Registro"/>
                                  @endif
                                </div>
                              </div>
                          </div>

                          <div class="row">
                            <div class="col-12">
                              <div class="form-group noevent">
                                  <label for="inputEDocTitle">Detalle: <span class="spanlabel"></span></label>
                                  @php
                                  $cadena = explode("//", $p['detalle']);
                                  @endphp

                                  @foreach ($cadena as $c)
                                    <p>{{trim($c)}}</p>
                                  @endforeach
                              </div>
                            </div>
                          </div>

                          <!-- /.row -->
                          <div class="row mt-2">
                            <div class="col-12">
                              <div class="card">
                                <div class="card-header">
                                  <h3 class="card-title">Observaciones</h3>
                                  <div class="card-tools">
                                    
                                  </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body table-responsive p-0" style="height: 300px;">
                                  <table class="table table-head-fixed text-nowrap" id="TableHistorySolicitud">
                                    <thead>
                                      <tr>
                                        <th>Nro</th>
                                        <th>Ingresado Por</th>
                                        <th>Fecha</th>
                                        <th style="width: 70px;">Observaciones</th>
                                        <th></th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @if (count($p['observaciones']) > 0)
                                        @foreach ($p['observaciones'] as $o)
                                          <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$o['usuario']}}</td>
                                            <td>{{$p['fecha']}}</td>
                                            <td class="col-nombre">{{$o['detalleobs']}}</td>
                                            <td></td>
                                          </tr>
                                        @endforeach
                                      @else
                                        <tr id="fila-form">
                                          <td colspan="4" style="text-align: center;"><span style="font-size: 18px;font-family: -webkit-body;font-weight: 700;">No hay registros</span></td>
                                        </tr>
                                      @endif
                                      {{-- Fila con inputs para nuevo registro --}}
                                      @foreach ($solicitudes as $s)
                                        @if ($s['estado_solicitud']=='En Trámite')
                                          <tr>
                                            <td colspan="3">
                                              <input type="text" id="observaciones_new"  class="form-control" autocomplete="off">
                                            </td>
                                            <td>
                                              <button id="btn-agregar" class="btn btn-success">Agregar</button>
                                            </td>
                                          </tr>
                                        @endif
                                      @endforeach
                                    </tbody>
                                  </table>
                                </div>
                                <!-- /.card-body -->
                              </div>
                              <!-- /.card -->
                            </div>
                          </div>
                          <!-- /.row -->

                          <div class="row">
                            <div class="col-lg-12 col-12 mt-4 d-flex justify-content-end">
                              
                            </div>
                          </div>
                        @endforeach
                      <!--</form>-->
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
                <p style="font-size: 16px;"> Actualizando Documento... </p>
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
<script src="{{asset('assets/administrador/js/atciudadana.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/validacion.js')}}"></script>

<script>
  $('.select2').select2({
    theme: 'bootstrap4',
  });

  const nameInterfaz = "Solicitudes";

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

  var isDocAdministrativo= true;

  $(document).ready(function () {
    //$('#modalCargando').modal('show');
    /*setTimeout(() => {
      scrollToUltimaFila('TableHistorySolicitud');
    }, 500);*/
  });
</script>
@endsection