@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/solicitud.png')}}">
@endsection

@section('title-page')
Admin | LOTAIP {{getNameInstitucion()}}
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
    <h1>Actualizar LOTAIP</h1>
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
                      <h3 class="card-title">Actualizar LOTAIP</h3>
                      <div class="card-tools" id="card-tools">
                        <button type="button" class="btn btn-primary btn-block" onclick="urlback()"><i
                            class="fas fa-arrow-left mr-2"></i> Regresar</button>
                    </div>
                  </div>
                  <div class="card-body">
                      <form id="formLOTAIPv2" action="" method="POST" enctype="multipart/form-data">
                        @foreach ($filelotaip as $p)
                          <div class="row">
                              <div class="col-6">
                                  <div class="form-group noevent">
                                    <input type="hidden" name="idlotaipv2" id="idlotaipv2" value="{{$p->id}}">
                                    <input type="hidden" name="tipopcion" id="tipopcion" value="{{$typeop}}">
                                    <label>Año:</label>
                                    <select class="form-control select2" id="selYearELotaipv2" name="selYearELotaipv2">
                                      <optgroup label="Seleccione una Opción">
                                        <option value="0">-Seleccione una Opción-</option>
                                        @foreach ($anio as $item) 
                                        @if ($p->id_anio == $item->id)
                                        <option value="{{$item->id}}" selected>{{$item->nombre}}</option>
                                        @else
                                        <option value="{{$item->id}}">{{$item->nombre}}</option>
                                        @endif
                                        @endforeach
                                      </optgroup>
                                    </select>
                                  </div>
                                  <div class="form-group noevent">
                                    <label>Mes:</label>
                                    <select class="form-control select2" id="selEMesv2" name="selEMesv2">
                                      <optgroup label="Seleccione una Opción">
                                        <option value="0">-Seleccione una Opción-</option>
                                        @foreach ($mes as $item)
                                        @if ($p->id_mes == $item->id)
                                        <option value="{{$item->id}}" selected>{{$item->mes}}</option>    
                                        @else
                                        <option value="{{$item->id}}">{{$item->mes}}</option>    
                                        @endif
                                        @endforeach
                                      </optgroup>
                                    </select>
                                  </div>
                                  <div class="form-group noevent">
                                    <label>Artículo:</label>
                                    <select class="form-control select2" id="selOptLotaipv2" name="selOptLotaipv2">
                                      <optgroup label="Seleccione una Opción">
                                        <option value="0">-Seleccione una Opción-</option>
                                        @if ($typeop=='cd' || $typeop=='md' || $typeop=='dd' || $typeop=='art23')
                                          @foreach ($art_lotaip as $item)
                                            @if ($p->id_art_lotaip == $item->id)
                                              <option value="{{$item->id}}" selected>{{$item->descripcion}}</option>
                                            @else
                                              <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                            @endif
                                          @endforeach
                                        @elseif($typeop=='optoth')
                                          @foreach ($opt_lotaip as $item)
                                            @if ($p->id_opt_lotaip == $item->id)
                                              <option value="{{$item->id}}" selected>{{$item->descripcion}}</option>
                                            @else
                                              <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                            @endif
                                          @endforeach
                                        @endif
                                      </optgroup>
                                    </select>
                                  </div>
                                  @if ($typeop=='cd' || $typeop=='md' || $typeop=='dd')
                                  <div class="form-group noevent">
                                    <label>Literal:</label>
                                    <select class="form-control select2" id="selItemELotaipv2" name="selItemELotaipv2">
                                      <optgroup label="Seleccione una Opción">
                                        <option value="0">-Seleccione una Opción-</option>
                                        @foreach ($item_lotaip as $item)
                                        @if ($p->id_item_lotaip == $item->id)
                                        <option value="{{$item->id}}" selected>{{$item->literal}}.- {{$item->descripcion}}</option>    
                                        @else
                                        <option value="{{$item->id}}">{{$item->literal}}.- {{$item->descripcion}}</option>    
                                        @endif
                                        @endforeach
                                      </optgroup>
                                    </select>
                                  </div>
                                  @endif
                                  <div class="form-group">
                                    <label for="inputAliasEFile">Alias del Documento: </label>
                                    <div class="input-group mb-3">
                                      <button class="btn btn-outline-primary" type="button" onclick="generarAliasv2E()">Generar</button>
                                      @if ($typeop=='cd')
                                      <textarea id="inputAliasEFile" name="inputAliasEFile" class="form-control text-justify noevent" 
                                        placeholder="Ingrese un alias" aria-label="Example" aria-describedby="button-addon1">{{substr($p->archivo_cdatos,0,-4)}}</textarea>
                                      @elseif ($typeop=='md')
                                      <textarea id="inputAliasEFile" name="inputAliasEFile" class="form-control text-justify noevent" 
                                        placeholder="Ingrese un alias" aria-label="Example" aria-describedby="button-addon1">{{substr($p->archivo_mdatos,0,-4)}}</textarea>
                                      @elseif ($typeop=='dd')
                                      <textarea id="inputAliasEFile" name="inputAliasEFile" class="form-control text-justify noevent" 
                                        placeholder="Ingrese un alias" aria-label="Example" aria-describedby="button-addon1">{{substr($p->archivo_ddatos,0,-4)}}</textarea>
                                      @else
                                      <textarea id="inputAliasEFile" name="inputAliasEFile" class="form-control text-justify noevent" 
                                        placeholder="Ingrese un alias" aria-label="Example" aria-describedby="button-addon1">{{substr($p->archivo,0,-4)}}</textarea>
                                      @endif
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label>Última modificación: </label> <br/>
                                    <h5><span class="badge badge-info">{{$fechatoshow}} {{$horatoshow}}</span></h5>
                                  </div>
                              </div>
                              <div class="col-6">
                                <div class="card-list grid-card-list" id="cardListLotaipv2">
                                  <div class='item-list grid-item-list' id='divpics'>
                                    <div style='grid-row: 1/2;'>
                                      <div class='avatar' style="text-align: center;height: 78px;">
                                        <img src='/assets/administrador/img/icons/icon-pdf-color.svg' alt='File' class='avatar-img' style="width: 78px;height: 78px;">
                                      </div>
                                    </div>
                                    <div style='grid-row: 1/2;'>
                                      <button class='btn btn-icon btn-danger btn-round btn-xs' onclick='eliminarFile(event)'>
                                        <i class='fas fa-trash'></i>
                                      </button>
                                    </div>
                                    <div style='grid-row: 2/2;'>
                                      <div class="info-user">
                                        @if ($typeop=='cd')
                                        <div class='status'>{{$p->archivo_cdatos}}</div>
                                        @elseif ($typeop=='md')
                                        <div class='status'>{{$p->archivo_mdatos}}</div>
                                        @elseif ($typeop=='dd')
                                        <div class='status'>{{$p->archivo_ddatos}}</div>
                                        @else
                                        <div class='status'>{{$p->archivo}}</div>
                                        @endif
                                      </div>
                                    </div>
                                  </div>
                                </div>
                                <div class="form-group mb-3 noshow" id="divfilelov2">
                                  <span class="spanlabel m-4">Seleccione solo un archivo</span>
                                    <div class="container">
                                      <input type="file" name="fileEdit[]" id="fileEdit" accept="application/pdf" onchange="previewEdit()" multiple>
                                      <label for="fileEdit">
                                        <i class="fas fa-cloud-upload-alt mr-2"></i> Elija un archivo
                                      </label>
                                      <p id="num-of-files-edit">- Ningún archivo seleccionado -</p>
                                      <div id="imagesEdit"></div>
                                    </div>
                                </div>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-12 mt-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary savelotaip" style="font-size: 16px;" onclick="actualizarLotaipv2()">
                                  <i class="far fa-save mr-2"></i>
                                  Actualizar
                                </button>
                              </div>
                          </div>
                        @endforeach
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
                <p style="font-size: 16px;"> Actualizando LOTAIP... </p>
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
<script src="{{asset('assets/administrador/js/lotaipv2.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>

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

  var isLotaipv2= true;

  $(document).ready(function () {
    //$('#modalCargando').modal('show');
  });
</script>
@endsection