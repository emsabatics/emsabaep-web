@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/notificacion.png')}}">
@endsection

@section('title-page')
Admin | Notificaciones {{getNameInstitucion()}}
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
<style>
  .loadInfo {
    width: 10rem !important;
    height: 10rem !important;
  }
  .spanlabel {
    padding-left: 22px;
    font-size: 12.5px;
    font-weight: bold;
  }

  .leido{
    background-color: rgba(0,0,0,.05);
    /*background-color: #2361a7;
    color: #fff;*/
  }

  tr:hover {
    background-color: #ffff99;
    cursor: pointer;
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
  <div class="col-sm-6">
    <h1>Notificaciones</h1>
  </div>
  <div class="col-sm-6">
    <button type="button" class="btn btn-info float-sm-right" onclick="regresarNoti()"><i class="fas fa-arrow-left mr-3"></i></span>Regresar</button>
  </div>
</div>
@endsection

@section('contenido-body')
<input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      @foreach($notificacion as $n)
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title">Buzón de Sugerencias / Quejas</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="mailbox-read-info">
            <h5>Solicitud en Línea</h5>
            <h6 class="mt-2">De: {{$n['nombres']}}
              <span class="mailbox-read-time float-right">{{$n['tiempo']}}</span></h6>
            <h6>Email: {{$n['email']}}</h6>
            <h6>Teléfono: {{$n['telefono']}}</h6>
            <h6>Cuenta: {{$n['cuenta']}}</h6>
          </div>
          <!-- /.mailbox-read-info -->
          <div class="mailbox-read-message">
            @foreach ($n['descripcion'] as $ds => $value)
              <p>{{$value}}</p>
            @endforeach
            
          </div>
          <!-- /.mailbox-read-message -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer bg-white">
        </div>
        <!-- /.card-footer -->
        <div class="card-footer"></div>
        <!-- /.card-footer -->
      </div>
      @endforeach
      <!-- /.card -->
    </div>
  </div>
</section>
<!-- /.content -->

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
<script src="{{asset('assets/administrador/js/getnotificacion.js')}}"></script>
<script>
  $(document).ready(function () {
    $('#modalCargando').modal('show');
    setTimeout(() => {
      $('#modalCargando').modal('hide');
    }, 1500);
  });
</script>
@endsection