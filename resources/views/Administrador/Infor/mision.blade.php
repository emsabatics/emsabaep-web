@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/equipo.png')}}">
@endsection

@section('title-page')
Admin | Misión Visión Valores Objetivos {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/no-data-load.css')}}">
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
    <h1>Misión / Visión / Valores / Objetivos</h1>
  </div>
</div>
@endsection

@section('contenido-body')

<input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">

<div class="row">
    <div class="col-12"><!--<i class="fas fa-bullhorn mr-3"></i>-->
      <div class="card card-primary card-outline card-outline-tabs">
        <div class="card-header p-0 border-bottom-0">
          <ul class="nav nav-tabs" id="custom-tabs-four-tab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="custom-tabs-mision-tab" data-toggle="pill" href="#custom-tabs-mision" role="tab" aria-controls="custom-tabs-mision" aria-selected="true">Misión</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-tabs-vision-tab" data-toggle="pill" href="#custom-tabs-vision" role="tab" aria-controls="custom-tabs-vision" aria-selected="false">Visión</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="custom-tabs-valores-tab" data-toggle="pill" href="#custom-tabs-valores" role="tab" aria-controls="custom-tabs-valores" aria-selected="false">Valores</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="custom-tabs-objetivos-tab" data-toggle="pill" href="#custom-tabs-objetivos" role="tab" aria-controls="custom-tabs-objetivos" aria-selected="false">Objetivos</a>
            </li>
          </ul>
        </div>
        <div class="card-body">
          <div class="tab-content" id="custom-tabs-four-tabContent">
            <div class="tab-pane fade show active" id="custom-tabs-mision" role="tabpanel" aria-labelledby="custom-tabs-mision-tab">
              <div class="div-set-button">
                <button type="button" class="btn btn-primary btn-block" onclick="openmodalMision()">
                  <i class="fas fa-edit mr-2"></i>
                  <span class="callout-span-btn">Editar</span>
                </button>
              </div>
              <div id="divMision">
                
              </div>
            </div>
            <div class="tab-pane fade" id="custom-tabs-vision" role="tabpanel" aria-labelledby="custom-tabs-vision-tab">
              <div class="div-set-button">
                <button type="button" class="btn btn-primary btn-block" onclick="openmodalVision()">
                  <i class="fas fa-edit mr-2"></i>
                  <span class="callout-span-btn">Editar</span>
                </button>
              </div>
              <div id="divVision">

              </div>
            </div>
            <div class="tab-pane fade" id="custom-tabs-valores" role="tabpanel" aria-labelledby="custom-tabs-valores-tab">
                <div class="div-set-button">
                  <button type="button" class="btn btn-primary btn-block" onclick="openmodalValores()">
                    <i class="fas fa-edit mr-2"></i>
                    <span class="callout-span-btn">Editar</span>
                  </button>
                </div>
                <div id="divValores" class="div-objetivos">
                    @foreach ($mision as $datos)
                        @if ($datos[0]->tipo == "valores")
                            <p class="p-data-full">{{ $varr= str_replace('//','/n', $datos[0]->descripcion)}}</p>
                        @endif
                    @endforeach
                </div>
              </div>
            <div class="tab-pane fade" id="custom-tabs-objetivos" role="tabpanel" aria-labelledby="custom-tabs-objetivos-tab">
              <div class="div-set-button">
                <button type="button" class="btn btn-primary btn-block" onclick="openmodalObjetivos()">
                  <i class="fas fa-edit mr-2"></i>
                  <span class="callout-span-btn">Editar</span>
                </button>
              </div>
              <div id="divObjetivo" class="div-objetivos">

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<!--MODAL AGG / EDIT MISION-->
<div class="modal fade" id="modal-edit-mision" tabindex="-1" role="dialog"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Misión EMSABA</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12">
            <input type="hidden" id="idmision" name="idmision">
            <div class="card card-outline card-info">
              <div class="card-header">
                <h3 class="card-title">
                  Ingrese la Misión Institucional
                </h3>
              </div>
              <div class="card-body p-0">
                <textarea id="inputMision" name="inputMision" class="form-control text-justify p-3" rows="10" cols="5">{{ old('inputMision') }}</textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary mb-2 btn-save-mision" onclick="guardarRegistroMision()">Guardar</button>
      </div>
    </div>
  <!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>

<!--MODAL AGG / EDIT VISION-->
<div class="modal fade" id="modal-edit-vision" tabindex="-1" role="dialog"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Visión EMSABA</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12">
            <input type="hidden" id="idvision">
            <div class="card card-outline card-info">
              <div class="card-header">
                <h3 class="card-title">
                  Ingrese la Visión Institucional
                </h3>
              </div>
              <div class="card-body p-0">
                <textarea id="inputVision" name="inputVision" class="form-control text-justify p-3" rows="10" cols="5">{{ old('inputVision') }}</textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary mb-2 btn-save-vision" onclick="guardarRegistroVision()">Guardar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
<!-- /.modal-dialog -->
</div>

<!--MODAL AGG / EDIT VALORES-->
<div class="modal fade" id="modal-edit-valores" tabindex="-1" role="dialog"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Valores EMSABA</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-6">
            <!--<div class="form-group">
              <label for="titleValor">Título del Valor: <span class="spanlabel">70 caracteres máximo</span></label>
              <textarea class="form-control text-justify" id="titleValor" name="titleValor" placeholder="Ingrese un Título"
                maxlength="70"></textarea>
            </div>
            <div class="form-group">
              <label for="inputValor">Descripción del Valor: <span class="spanlabel">300 caracteres máximo</span></label>
              <textarea class="form-control text-justify" id="inputValor" name="inputValor" placeholder="Ingrese la Descripción"
                maxlength="300" rows="6" cols="5"></textarea>
            </div>-->
            <div class="card card-outline card-info">
              <div class="card-header">
                <h3 class="card-title">
                  Ingrese el Valor Institucional
                </h3>
              </div>
              <div class="card-body p-0">
                <textarea id="inputValor" class="form-control text-justify p-3" rows="5" cols="5"></textarea>
              </div>
            </div>
            <div class="form-group input-group justify-content-end mt-2 mb-3">
              <button type="button" id="agregarvalore" class="btn btn-primary mt-3 mb-2">
                <i class="fas fa-plus-circle fa-16 mr-2"></i>
                Agregar Valor
              </button>
            </div>
          </div>
          <div class="col-lg-6">
            <div id="almacenarvalor"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary mb-2 btn-save-valor" onclick="guardarRegistroValores()" id="btnGuardarVlr">Guardar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
<!-- /.modal-dialog -->
</div>

<!--MODAL AGG / EDIT VALOR INDIVIDUAL-->
<div class="modal fade" id="modal-edit-valorind" tabindex="-1" role="dialog"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Valor EMSABA</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12">
            <input type="hidden" id="idvalorindi">
            <input type="hidden" id="posvalor">
            <div class="card card-outline card-info">
              <div class="card-header">
                <h3 class="card-title" id="cardTitleValorInd">
                  Valor #
                </h3>
              </div>
              <div class="card-body p-0">
                <textarea id="inputValorIndividual" class="form-control text-justify p-3" rows="6" cols="5"></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary mb-2 btn-updt-valor" onclick="guardarValorIndividual()">Actualizar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
<!-- /.modal-dialog -->
</div>

<!--MODAL AGG / EDIT OBJETIVOS-->
<div class="modal fade" id="modal-edit-objetivos" tabindex="-1" role="dialog"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Objetivos EMSABA</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-6">
            <div class="card card-outline card-info">
              <div class="card-header">
                <h3 class="card-title">
                  Ingrese el Objetivo Institucional
                </h3>
              </div>
              <div class="card-body p-0">
                <textarea id="inputObjetivo" class="form-control text-justify p-3" rows="5" cols="5"></textarea>
              </div>
            </div>
            <div class="form-group input-group justify-content-end mt-2 mb-3">
              <button type="button" id="agregarobjetive" class="btn btn-primary mt-3 mb-2">
                <i class="fas fa-plus-circle fa-16 mr-2"></i>
                Agregar Objetivo
              </button>
            </div>
          </div>
          <div class="col-lg-6">
            <div id="almacenar"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary mb-2 btn-save-obj" onclick="guardarRegistroObjetivos()" id="btnGuardarObj">Guardar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
<!-- /.modal-dialog -->
</div>

<!--MODAL AGG / EDIT OBJETIVO INDIVIDUAL-->
<div class="modal fade" id="modal-edit-objind" tabindex="-1" role="dialog"
aria-hidden="true" data-backdrop="static" data-keyboard="false" data-bs-focus="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Objetivo EMSABA</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-lg-12">
            <input type="hidden" id="idobjetivoindi">
            <input type="hidden" id="posobjetivo">
            <div class="card card-outline card-info">
              <div class="card-header">
                <h3 class="card-title" id="cardTitleObjInd">
                  Objetivo #
                </h3>
              </div>
              <div class="card-body p-0">
                <textarea id="inputObjetivoIndividual" class="form-control text-justify p-3" rows="6" cols="5"></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary mb-2 btn-updt-obj" onclick="guardarObjIndividual()">Actualizar</button>
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
<script src="{{asset('assets/administrador/js/mvvob.js')}}"></script>
<script>
    $('#modalCargando').modal('show');
    setTimeout(() => {
      cargar_mvvob();
    }, 500);
</script>
@endsection