@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/doc-administrativa.png')}}">
@endsection

@section('title-page')
Admin | Atención Ciudadana {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free-5.15.4/css/all.min.css')}}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/daterangepicker/daterangepicker.css')}}">

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/data-tables/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">

<style>
  .gridFilter{
    display: grid;
    /*grid-template-columns: repeat(3, 1fr);*/
    grid-template-columns: 1fr 1fr auto;
    grid-template-rows: 2fr;
    row-gap: 1em;
    column-gap: 1em;
  }

  .btn-flotante {
    position: fixed;
    width: 60px;
    height: 60px;
    bottom: 40px;
    right: 40px;
    background-color: #007bff; /* Azul Bootstrap */
    color: white;
    border-radius: 50%;
    text-align: center;
    font-size: 28px;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
    z-index: 1000;
    line-height: 60px;
    transition: 0.3s;
  }
  .btn-flotante:hover {
      background-color: #0056b3;
      text-decoration: none;
      color: white;
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
    <h1>Solicitudes de Atención al Cliente</h1>
  </div>
</div>
@endsection

@section('contenido-body')
<input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-default">
          <div class="card-header"></div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups" style="justify-content: flex-end;">
                  <div class="btn-group mr-2" role="group" aria-label="First group">
                    <button class="btn btn-outline-danger" style="float: right;d" type="button" onclick="downloadPDFSolicitudes()">
                      <i class="fas fa-file-pdf mr-2"></i> Descargar PDF
                    </button>
                  </div>
                  <div class="btn-group mr-2" role="group" aria-label="Second group">
                    <button class="btn btn-outline-success" style="float: right;d" type="button" onclick="downloadExcelSolicitudes()">
                      <i class="fas fa-file-excel mr-2"></i> Descargar Excel
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
      <div class="row">
          <div class="col-12">
              <div class="card">
                  <div class="card-header">
                    <div class="row">
                      <div class="col-12 col-lg-4">
                        <h3 class="card-title p-2"><i class="fas fa-file-contract mr-3"></i> Solicitudes</h3>
                      </div>
                      <div class="col-12 col-lg-8">
                        <div class="card-tools" id="card-tools">
                          <div class="gridFilter">
                            <div>
                              <select name="selectEstado" id="selectEstado" class="form-control">
                                <option value="0">-Seleccione una Opción-</option>
                                <option value="tram">En Trámite</option>
                                <option value="end">Finalizado</option>
                                <option value="all">Todos</option>
                              </select>
                            </div>
                            <div class="input-group">
                              <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                              </div>
                              <input type="text" class="form-control float-right" id="reservationtime">
                            </div>
                            <div>
                              <button id="btnFiltro" class="btn btn-outline-primary" style="float: right;" type="button" onclick="getfiltroFechas()">
                                <i class="fas fa-search mr-2"></i> Filtrar
                              </button>
                              <button id="btnCancelFiltro" class="btn btn-outline-danger" style="float: right;display:none;" type="button" onclick="cancelFiltro()">
                                <i class="fas fa-times mr-2"></i> Cancelar
                              </button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body table-responsive p-4" id="divDocAdmin">
                    @include('Administrador.AtencionCiudadana.tabla', ['solicitudes' => $solicitudes])
                  </div>
                  <!-- /.card-body -->
              </div>
          </div>
      </div>
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

<!-- Botón flotante -->
<!--<a href="#" class="btn-flotante" title="Nueva solicitud">
    <i class="fas fa-plus"></i>
</a>-->
@endsection

@section('js')

<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/atciudadana.js')}}"></script>

<!-- DataTables  & Plugins -->
<script src="{{asset('assets/administrador/plugins/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('assets/administrador/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script src="{{asset('assets/administrador/js/validacion.js')}}"></script>

<script>
  const nameInterfaz = "Solicitudes";

  var pickerInstance;
  $(document).ready(function () {
    $('#modalCargando').modal('show');
    setTimeout(() => {
      showInfoAtencionC();
      //Date range picker with time picker
      $('#reservationtime').daterangepicker({
        locale: {
          format: 'MM/DD/YYYY',
          separator: ' - ',
          applyLabel: 'Aplicar',
          cancelLabel: 'Cancelar',
          fromLabel: 'Desde',
          toLabel: 'Hasta',
          customRangeLabel: 'Personalizado',
          weekLabel: 'S',
          daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
          monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                      'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
          firstDay: 1
        },
        ranges: {
          'Hoy': [moment(), moment()],
          'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Últimos 7 días': [moment().subtract(6, 'days'), moment()],
          'Últimos 30 días': [moment().subtract(29, 'days'), moment()],
          'Este mes': [moment().startOf('month'), moment().endOf('month')],
          'Mes pasado': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment().subtract(1, 'days'),
        endDate: moment()
      }, function(start, end, label) {
        // Guardamos la instancia
      });
    }, 1500);

    // Evento cuando se aplica el rango
    /*$('#reservationtime').on('apply.daterangepicker', function(ev, picker) {
      let startDate = picker.startDate.format('YYYY-MM-DD');
      let endDate = picker.endDate.format('YYYY-MM-DD');

      console.log('Fecha de inicio: ' + startDate);
      console.log('Fecha de fin: ' + endDate);

      // Si deseas guardarlos en inputs ocultos, por ejemplo:
      //$('#fecha_inicio').val(startDate);
      //$('#fecha_fin').val(endDate);
    });*/

  });
</script>
@endsection