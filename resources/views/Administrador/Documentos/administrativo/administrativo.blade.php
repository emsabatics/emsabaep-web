@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/doc-administrativa.png')}}">
@endsection

@section('title-page')
Admin | Doc Administrativa {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free-5.15.4/css/all.min.css')}}">

<!-- DataTables -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/data-tables/dataTables.bootstrap4.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/datatables/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
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
    <h1>Doc. Administrativa</h1>
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
                      <h3 class="card-title p-2"><i class="fas fa-file-contract mr-3"></i> Documentación Administrativa</h3>
                      <div class="card-tools" id="card-tools">
                          <button type="button" class="btn btn-primary btn-block" onclick="urlregistrardocadministrativo()"><i
                              class="far fa-plus-square mr-2"></i> Agregar</button>
                      </div>
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body table-responsive p-4" id="divDocAdmin">
                    <table class="table datatables" id="tablaDocAdmin">
                      <thead class="thead-dark">
                        <tr style="pointer-events:none;">
                          <th>N°</th>
                          <th>Año</th>
                          <th>Descripción</th>
                          <th>Estado</th>
                          <th>Opciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($administrativo as $item)
                        <tr id="Tr{{$loop->index}}">
                          <td>{{$loop->iteration}}</td>
                          <td>{{$item->anio}}</td>
                          <td>
                            @if($item->id_mes!=null || $item->id_mes!='')
                            @foreach ($mes as $m)
                              @if($m->id==$item->id_mes)
                                <strong>{{$m->mes}}</strong><br/>
                                {{$item->titulo}}
                              @endif
                            @endforeach
                            @else
                            {{$item->titulo}}
                            @endif
                          </td>
                          <td>
                            @if ($item->estado=='0')
                            <span class="badge badge-secondary">No Visible</span>
                            @else
                            <span class="badge badge-success">Visible</span> 
                            @endif
                          </td>
                          <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm mt-2 mr-3" href="javascript:void(0)" onclick="viewopenDocAdmin({{$item->id}})">
                              <i class="fas fa-folder mr-2"></i>
                              Ver
                            </a>
                            <a class="btn btn-info btn-sm mt-2 mr-3" href="javascript:void(0)" onclick="interfaceupdateDocAdmin({{$item->id}})">
                              <i class="far fa-edit mr-2"></i>
                              Actualizar
                            </a>
                            @if ($item->estado=='1')
                            <a class="btn btn-secondary btn-sm mt-2 mr-3" href="javascript:void(0)" onclick="inactivarDocAdmin({{$item->id}}, {{$loop->index}})">
                              <i class="fas fa-eye-slash mr-2"></i>
                              Inactivar
                            </a>
                            @else
                            <a class="btn btn-secondary btn-sm mt-2 mr-3" href="javascript:void(0)" onclick="activarDocAdmin({{$item->id}}, {{$loop->index}})">
                              <i class="fas fa-eye mr-2"></i>
                              Activar
                            </a>
                            @endif
                            <a class="btn btn-success btn-sm mt-2 mr-3" onclick="downloadDocAdmin('{{encriptarNumero($item->id)}}')">
                              <i class="fas fa-download mr-2"></i>
                              Descargar Documento
                            </a>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
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
@endsection

@section('js')

<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/docadministrativo.js')}}"></script>

<!-- DataTables  & Plugins -->
<script src="{{asset('assets/administrador/plugins/datatables/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/datatables/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>

<script>
  
  $(document).ready(function () {
    $('#modalCargando').modal('show');
    setTimeout(() => {
      showInfoAdministrativo();
    }, 1500);
  });
</script>
@endsection