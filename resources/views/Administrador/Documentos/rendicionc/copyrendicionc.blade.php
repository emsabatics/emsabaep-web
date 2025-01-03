@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/solicitud.png')}}">
@endsection

@section('title-page')
Admin | Rendición de Cuentas {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/collapse.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free-5.15.4/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/no-data-load.css')}}">
<style>
  .btntable{
    padding: 3px;
    font-size: 18px;
  }

  .btntable i{
    color: #fff;
  }

  .celdaAsignado{
    word-break: break-word;
    white-space: pre-line;
    overflow-wrap: break-word;
    text-align: justify;
  }

  table {
    table-layout: fixed;
    word-wrap: break-word;
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
    <h1>Rendición de Cuentas</h1>
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
            <h3 class="card-title p-2"><i class="fas fa-file-contract mr-3"></i> Rendición de Cuentas</h3>
            <div class="card-tools" id="card-tools">
              <button type="button" class="btn btn-primary btn-block" onclick="urlregistrarrendicionc()"><i
                class="far fa-plus-square mr-2"></i> Agregar</button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-4" id="divrendicionc">
            <div class="row">
              @if(count($rendicionc) > 0)
              <div class="col-5 col-sm-3">
                <div class="nav flex-column nav-tabs h-100" id="vert-tabs-tab" role="tablist" aria-orientation="vertical">
                  @foreach ($rendicionc as $i)
                    @if ($loop->index=='0')
                      <a class="nav-link active" id="tabs-{{$loop->iteration}}-{{$i['anio']}}-tab" data-toggle="pill" href="#vert-tabs-{{$loop->iteration}}-{{$i['anio']}}" role="tab" aria-controls="vert-tabs-{{$loop->iteration}}-{{$i['anio']}}" aria-selected="true">{{$i['anio']}}</a>
                    @else
                      <a class="nav-link" id="tabs-{{$loop->iteration}}-{{$i['anio']}}-tab" data-toggle="pill" href="#vert-tabs-{{$loop->iteration}}-{{$i['anio']}}" role="tab" aria-controls="vert-tabs-{{$loop->iteration}}-{{$i['anio']}}" aria-selected="false">{{$i['anio']}}</a>
                    @endif
                  @endforeach
                </div>
              </div>
              <div class="col-7 col-sm-9">
                <div class="tab-content" id="vert-tabs-tabContent">
                  @foreach ($rendicionc as $i)
                    @if ($loop->index=='0')
                      <div class="tab-pane text-left fade show active" id="vert-tabs-{{$loop->iteration}}-{{$i['anio']}}" role="tabpanel" aria-labelledby="tabs-{{$loop->iteration}}-{{$i['anio']}}-tab">
                        <div id="accordion" class="myaccordion">
                          <div class="card">
                            <div class="card-header" id="headingOne">
                              <h2 class="mb-0">
                                @if($i['tipov']=='video' && $i['longitudv']>0)
                                <button class="d-flex align-items-center justify-content-between btn btn-link" data-toggle="collapse" data-target="#collapse-{{$i['anio']}}-{{$i['tipov']}}" aria-expanded="true" aria-controls="collapse-{{$i['anio']}}-{{$i['tipov']}}">
                                  VÍDEO
                                  <span class="fa-stack fa-sm">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fas fa-minus fa-stack-1x fa-inverse"></i>
                                  </span>
                                </button>
                                @endif
                                @if($i['tipom']=='medio' && $i['longitudm']>0)
                                <button class="d-flex align-items-center justify-content-between btn btn-link" data-toggle="collapse" data-target="#collapse-{{$i['anio']}}-{{$i['tipom']}}" aria-expanded="true" aria-controls="collapse-{{$i['anio']}}-{{$i['tipom']}}">
                                  MEDIOS DE VERIFICACIÓN
                                  <span class="fa-stack fa-sm">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fas fa-minus fa-stack-1x fa-inverse"></i>
                                  </span>
                                </button>
                                @endif
                              </h2>
                            </div>
                            @if($i['tipov']=='video' && $i['longitudv']>0)
                            <div id="collapse-{{$i['anio']}}-{{$i['tipov']}}" class="collapse" aria-labelledby="heading{{$i['anio']}}-{{$i['tipov']}}" data-parent="#accordion">
                              <div class="card-body">
                                <table class="table table-head-fixed text-nowrap" id="table-{{$i['anio']}}-{{$i['tipov']}}">
                                  <thead>
                                    <tr>
                                      <th style="width: 15%;text-align: center;">Tipo Archivo</th>
                                      <th style="width: 35%;text-align: center;">Archivo</th>
                                      <th style="width: 10%;text-align: center;">Estado</th>
                                      <th style="width: 40%;text-align: center;">Opciones</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach ($i['archivos_v'] as $f)
                                    <tr id="Tr{{$loop->index}}-{{$i['anio']}}-{{$i['tipov']}}">
                                      <td class="celdaAsignado">
                                        @if($f['tipo']=='video')
                                        VÍDEO
                                        @endif
                                      </td>
                                      <td class="celdaAsignado">{{$f['titulo']}}</td>
                                      <td>
                                        @if ($f['estado']=='0')
                                        <span class="badge badge-secondary">No Visible</span>
                                        @else
                                        <span class="badge badge-success">Visible</span> 
                                        @endif
                                      </td>
                                      <td style="display: flex;">
                                        <button type="button" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenrendicionc({{$f['id']}})">
                                          <i class="fas fa-folder"></i>
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdaterendicionc({{$f['id']}})">
                                          <i class="far fa-edit"></i>
                                        </button>
                                        @if ($f['estado']=='1')
                                        <button type="button" class="btn btn-secondary btn-sm mr-3 btntable" title="Inactivar" onclick="inactivarrendicionc({{$f['id']}}, {{$loop->index}}, {{$i['anio']}}, {{$i['tipov']}})">
                                          <i class="fas fa-eye-slash"></i>
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-secondary btn-sm mr-3 btntable" title="Activar" onclick="activarrendicionc({{$f['id']}}, {{$loop->index}}, {{$i['anio']}}, {{$i['tipov']}})">
                                          <i class="fas fa-eye"></i>
                                        </button>
                                        @endif
                                        <button type="button" class="btn btn-success btn-sm mr-3 btntable" title="Descargar Archivo" onclick="downloadrendicionc({{$f['id']}})">
                                          <i class="fas fa-download"></i>
                                        </button>
                                      </td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            @endif
                            @if($i['tipom']=='medio' && $i['longitudm']>0)
                            <div id="collapse-{{$i['anio']}}-{{$i['tipom']}}" class="collapse" aria-labelledby="heading{{$i['anio']}}-{{$i['tipom']}}" data-parent="#accordion">
                              <div class="card-body">
                                <table class="table table-head-fixed text-nowrap" id="table-{{$i['anio']}}-{{$i['tipom']}}">
                                  <thead>
                                    <tr>
                                      <th style="width: 15%;text-align: center;">Tipo Archivo</th>
                                      <th style="width: 35%;text-align: center;">Archivo</th>
                                      <th style="width: 10%;text-align: center;">Estado</th>
                                      <th style="width: 40%;text-align: center;">Opciones</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach ($i['archivos_v'] as $f)
                                    <tr id="Tr{{$loop->index}}-{{$i['anio']}}-{{$i['tipom']}}">
                                      <td class="celdaAsignado">
                                        @if($f['tipo']=='video')
                                        VÍDEO
                                        @endif
                                      </td>
                                      <td class="celdaAsignado">{{$f['titulo']}}</td>
                                      <td>
                                        @if ($f['estado']=='0')
                                        <span class="badge badge-secondary">No Visible</span>
                                        @else
                                        <span class="badge badge-success">Visible</span> 
                                        @endif
                                      </td>
                                      <td style="display: flex;">
                                        <button type="button" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenrendicionc({{$f['id']}})">
                                          <i class="fas fa-folder"></i>
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdaterendicionc({{$f['id']}})">
                                          <i class="far fa-edit"></i>
                                        </button>
                                        @if ($f['estado']=='1')
                                        <button type="button" class="btn btn-secondary btn-sm mr-3 btntable" title="Inactivar" onclick="inactivarrendicionc({{$f['id']}}, {{$loop->index}}, {{$i['anio']}}, {{$i['tipom']}})">
                                          <i class="fas fa-eye-slash"></i>
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-secondary btn-sm mr-3 btntable" title="Activar" onclick="activarrendicionc({{$f['id']}}, {{$loop->index}}, {{$i['anio']}}, {{$i['tipom']}})">
                                          <i class="fas fa-eye"></i>
                                        </button>
                                        @endif
                                        <button type="button" class="btn btn-success btn-sm mr-3 btntable" title="Descargar Archivo" onclick="downloadrendicionc({{$f['id']}})">
                                          <i class="fas fa-download"></i>
                                        </button>
                                      </td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            @endif
                          </div>
                        </div>
                      </div>
                    @else
                      <div class="tab-pane text-left fade" id="vert-tabs-{{$loop->iteration}}-{{$i['anio']}}" role="tabpanel" aria-labelledby="tabs-{{$loop->iteration}}-{{$i['anio']}}-tab">
                        <div id="accordion" class="myaccordion">
                          <div class="card">
                            <div class="card-header" id="headingOne">
                              <h2 class="mb-0">
                                @if($i['tipov']=='video' && $i['longitudv']>0)
                                <button class="d-flex align-items-center justify-content-between btn btn-link" data-toggle="collapse" data-target="#collapse-{{$i['anio']}}-{{$i['tipov']}}" aria-expanded="true" aria-controls="collapse-{{$i['anio']}}-{{$i['tipov']}}">
                                  VÍDEO
                                  <span class="fa-stack fa-sm">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fas fa-minus fa-stack-1x fa-inverse"></i>
                                  </span>
                                </button>
                                @endif
                                @if($i['tipom']=='medio' && $i['longitudm']>0)
                                <button class="d-flex align-items-center justify-content-between btn btn-link" data-toggle="collapse" data-target="#collapse-{{$i['anio']}}-{{$i['tipom']}}" aria-expanded="true" aria-controls="collapse-{{$i['anio']}}-{{$i['tipom']}}">
                                  MEDIOS DE VERIFICACIÓN
                                  <span class="fa-stack fa-sm">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fas fa-minus fa-stack-1x fa-inverse"></i>
                                  </span>
                                </button>
                                @endif
                              </h2>
                            </div>
                            @if($i['tipov']=='video' && $i['longitudv']>0)
                            <div id="collapse-{{$i['anio']}}-{{$i['tipov']}}" class="collapse" aria-labelledby="heading{{$i['anio']}}-{{$i['tipov']}}" data-parent="#accordion">
                              <div class="card-body">
                                <table class="table table-head-fixed text-nowrap" id="table-{{$i['anio']}}-{{$i['tipov']}}">
                                  <thead>
                                    <tr>
                                      <th style="width: 15%;text-align: center;">Tipo Archivo</th>
                                      <th style="width: 35%;text-align: center;">Archivo</th>
                                      <th style="width: 10%;text-align: center;">Estado</th>
                                      <th style="width: 40%;text-align: center;">Opciones</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach ($i['archivos_v'] as $f)
                                    <tr id="Tr{{$loop->index}}-{{$i['anio']}}-{{$i['tipov']}}">
                                      <td class="celdaAsignado">
                                        @if($f['tipo']=='video')
                                        VÍDEO
                                        @endif
                                      </td>
                                      <td class="celdaAsignado">{{$f['titulo']}}</td>
                                      <td>
                                        @if ($f['estado']=='0')
                                        <span class="badge badge-secondary">No Visible</span>
                                        @else
                                        <span class="badge badge-success">Visible</span> 
                                        @endif
                                      </td>
                                      <td style="display: flex;">
                                        <button type="button" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenrendicionc({{$f['id']}})">
                                          <i class="fas fa-folder"></i>
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdaterendicionc({{$f['id']}})">
                                          <i class="far fa-edit"></i>
                                        </button>
                                        @if ($f['estado']=='1')
                                        <button type="button" class="btn btn-secondary btn-sm mr-3 btntable" title="Inactivar" onclick="inactivarrendicionc({{$f['id']}}, {{$loop->index}}, {{$i['anio']}}, {{$i['tipov']}})">
                                          <i class="fas fa-eye-slash"></i>
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-secondary btn-sm mr-3 btntable" title="Activar" onclick="activarrendicionc({{$f['id']}}, {{$loop->index}}, {{$i['anio']}}, {{$i['tipov']}})">
                                          <i class="fas fa-eye"></i>
                                        </button>
                                        @endif
                                        <button type="button" class="btn btn-success btn-sm mr-3 btntable" title="Descargar Archivo" onclick="downloadrendicionc({{$f['id']}})">
                                          <i class="fas fa-download"></i>
                                        </button>
                                      </td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            @endif
                            @if($i['tipom']=='medio' && $i['longitudm']>0)
                            <div id="collapse-{{$i['anio']}}-{{$i['tipom']}}" class="collapse" aria-labelledby="heading{{$i['anio']}}-{{$i['tipom']}}" data-parent="#accordion">
                              <div class="card-body">
                                <table class="table table-head-fixed text-nowrap" id="table-{{$i['anio']}}-{{$i['tipom']}}">
                                  <thead>
                                    <tr>
                                      <th style="width: 15%;text-align: center;">Tipo Archivo</th>
                                      <th style="width: 35%;text-align: center;">Archivo</th>
                                      <th style="width: 10%;text-align: center;">Estado</th>
                                      <th style="width: 40%;text-align: center;">Opciones</th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    @foreach ($i['archivos_v'] as $f)
                                    <tr id="Tr{{$loop->index}}-{{$i['anio']}}-{{$i['tipom']}}">
                                      <td class="celdaAsignado">
                                        @if($f['tipo']=='video')
                                        VÍDEO
                                        @endif
                                      </td>
                                      <td class="celdaAsignado">{{$f['titulo']}}</td>
                                      <td>
                                        @if ($f['estado']=='0')
                                        <span class="badge badge-secondary">No Visible</span>
                                        @else
                                        <span class="badge badge-success">Visible</span> 
                                        @endif
                                      </td>
                                      <td style="display: flex;">
                                        <button type="button" class="btn btn-primary btn-sm mr-3 btntable" title="Ver" onclick="viewopenrendicionc({{$f['id']}})">
                                          <i class="fas fa-folder"></i>
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm mr-3 btntable" title="Actualizar" onclick="interfaceupdaterendicionc({{$f['id']}})">
                                          <i class="far fa-edit"></i>
                                        </button>
                                        @if ($f['estado']=='1')
                                        <button type="button" class="btn btn-secondary btn-sm mr-3 btntable" title="Inactivar" onclick="inactivarrendicionc({{$f['id']}}, {{$loop->index}}, {{$i['anio']}}, {{$i['tipom']}})">
                                          <i class="fas fa-eye-slash"></i>
                                        </button>
                                        @else
                                        <button type="button" class="btn btn-secondary btn-sm mr-3 btntable" title="Activar" onclick="activarrendicionc({{$f['id']}}, {{$loop->index}}, {{$i['anio']}}, {{$i['tipom']}})">
                                          <i class="fas fa-eye"></i>
                                        </button>
                                        @endif
                                        <button type="button" class="btn btn-success btn-sm mr-3 btntable" title="Descargar Archivo" onclick="downloadrendicionc({{$f['id']}})">
                                          <i class="fas fa-download"></i>
                                        </button>
                                      </td>
                                    </tr>
                                    @endforeach
                                  </tbody>
                                </table>
                              </div>
                            </div>
                            @endif
                          </div>
                        </div>
                      </div>
                    @endif
                  @endforeach
                </div>
              </div>
              @else
              <div class="col-12">
                <div class="col-lg-12 no-data">
                  <div class="imgadvice">
                    <img src="assets/administrador/img/icons/no-hay-resultados.png" alt="Construccion">
                  </div>
                  <span class="mensaje-noticia mt-4 mb-4">No hay <strong>datos</strong> disponibles por el momento...</span>
                </div>
              </div>
              @endif
            </div>
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
<script src="{{asset('assets/administrador/js/rendicionc.js')}}"></script>

<script>
  $("#accordion").on("hide.bs.collapse show.bs.collapse", e => {
    $(e.target)
      .prev()
      .find("i:last-child")
      .toggleClass("fa-minus fa-plus");
  });
  $(document).ready(function () {
    $('#modalCargando').modal('show');
    setTimeout(() => {
      //showInfoLotaip();
      $('#modalCargando').modal('hide');
    }, 2500);
  });
</script>
@endsection