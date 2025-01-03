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
  <div class="col-sm-12">
    <h1>Notificaciones</h1>
  </div>
</div>
@endsection

@section('contenido-body')
<input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">
<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Opciones</h3>
        </div>
        <div class="card-body p-0">
          <ul class="nav nav-pills flex-column">
            <li class="nav-item active">
              <a href="javascript:void(0)" class="nav-link" onclick="cargarNotificaciones();">
                <i class="fas fa-inbox"></i> Recibidos
                <span class="badge bg-primary float-right" id="totalall"></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="javascript:void(0)" class="nav-link" onclick=" cargarNotiCurrent();">
                <i class="fas fa-filter"></i> Hoy
                <span class="badge bg-warning float-right" id="totalhoy"></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="javascript:void(0)" class="nav-link" onclick="cargarNotiRead();">
                <i class="far fa-file-alt"></i> Leídos
              </a>
            </li>
          </ul>
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
    <div class="col-md-9">
      <div class="card card-primary card-outline">
        <div class="card-header">
          <h3 class="card-title" id="title-card-noti">Recibidos</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
          <div class="mailbox-controls">
            <!-- Check all button -->
            <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="far fa-square"></i>
            </button>
            <div class="btn-group">
              <button type="button" class="btn btn-default btn-sm btn-readall" onclick="marcarLeidoNoti()">
                <i class="far fa-file-alt"></i>
              </button>
            </div>
            <!-- /.btn-group -->
            <button type="button" class="btn btn-default btn-sm" onclick="reloadAll()">
              <i class="fas fa-sync-alt"></i>
            </button>
            <div class="float-right mr-3">
              <span id="span-lon-noti"></span>
              <div class="btn-group group-table-nav" style="display:none;">
                <button type="button" class="btn btn-default btn-sm" onclick="previousPage();">
                  <i class="fas fa-chevron-left"></i>
                </button>
                <button type="button" class="btn btn-default btn-sm" onclick="nextPage();">
                  <i class="fas fa-chevron-right"></i>
                </button>
              </div>
              <!-- /.btn-group -->
            </div>
            <!-- /.float-right -->
          </div>
          <div class="table-responsive mailbox-messages">
            <table class="table" id="tablaNotificaciones"><!--table-hover table-striped-->
              <tbody>

              </tbody>
            </table>
            <!-- /.table -->
          </div>
          <!-- /.mail-box-messages -->
        </div>
        <!-- /.card-body -->
      </div>
      <!-- /.card -->
    </div>
    <!-- /.col -->
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

<!-- Fullscreen modal Send -->
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
        <p style="font-size: 16px;"> Aplicando Cambios... </p>
      </div>
    </div>
  </div>
</div>
@endsection

@section('js')
<script src="{{asset('assets/administrador/js/getnotificacion.js')}}"></script>
<script>
  var contarItemsAll=0;
  var typeselec="";

  const arrMeses= ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
  const fecha = new Date();
  var day = fecha.getDate();
  var month = fecha.getMonth();
  var year = fecha.getFullYear();

  var arrayidNotificaciones= [];
  var selitemnoti=[];
  const pageSize = 10;
  let curPage = 1;
  var arrayContenidoNoti= [];

  /** CONFIGURAR LOS BOTONES**/
  const btnleido= document.querySelector(".btn-readall");
  const btntoggle= document.querySelector(".checkbox-toggle");

  $(function () {
    cargarNotificaciones();
    btnleido.setAttribute("disabled","");

    //Enable check and uncheck all functionality
    $('.checkbox-toggle').click(function () {
      var clicks = $(this).data('clicks')
      if (clicks) {
        //Uncheck all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
        $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass('fa-square')
        selitemnoti.splice(0);
        if(selitemnoti.length==0){
          btnleido.setAttribute("disabled","");
        }
        //console.log('Seleccionar: ');
        //console.log(selitemnoti);
      } else {
        //Check all checkboxes
        $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
        $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass('fa-check-square')

        for(var i=0; i<contarItemsAll; i++){
          var element=document.getElementById('check'+(i+1));
          if(document.body.contains(element)){
            //var value= document.getElementById('check'+(i+1)).value;
            var value= element.value;
            value= parseInt(value);
            var index1 = selitemnoti.indexOf(value);
            if (index1 > -1) {}
            else
            {
              //contar1++;
              if(selitemnoti=='')
              {
                selitemnoti=[value];
              }
              else{
                selitemnoti.push(value); 
              }
            }
          }
        }
        if(selitemnoti.length>0){
          btnleido.removeAttribute("disabled");
        }

        //console.log('Seleccionar: ');
        //console.log(selitemnoti);
      }
      $(this).data('clicks', !clicks)
    })
  });
</script>
@endsection