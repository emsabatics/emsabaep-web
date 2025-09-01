@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/estructura-jerarquica.png')}}">
@endsection

@section('title-page')
Admin | Departamentos {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">
<style>
  .loadInfo {
    width: 10rem !important;
    height: 10rem !important;
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
    <h1>Actualizar Información de Departamentos de la Institución</h1>
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
                        <h3 class="card-title p-2"><i class="fas fa-pencil-alt mr-3"></i> Actualización de Información</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-secondary btn-block" onclick="backInterfaceDep()"><i
                                class="fas fa-arrow-circle-left mr-2"></i> Regresar</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($resultado as $res)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tipo de Departamento:</label>
                                    <select class="form-control select2" style="width: 100%;" id="selCatDepart" disabled="disabled">
                                        <option value="0">Seleccione una Opción</option>
                                        @foreach ($categoria as $c)
                                            <option value="{{$c['value']}}" {{$c['value']==$res->tipo_dep ? 'selected' : ''}}>{{$c['dato']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Departamento:</label>
                                    <select class="form-control select2" style="width: 100%;" id="selEditDepart" disabled="disabled">
                                        <option value="0">Seleccione una Opción</option>
                                        @foreach ($departamento as $dep)
                                            <option value="{{$dep->id}}" {{$id_dep==$dep->id ? 'selected' : ''}}>{{$dep->nombre}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <input type="hidden" name="inputIdRegistro" id="inputIdRegistro" value="{{$res->id}}">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text">@</span>
                                    </div>
                                    <input type="text" id="inputEditUsuarioEncargado" name="inputEditUsuarioEncargado" class="form-control" placeholder="Personal Encargado" value="{{$res->responsable}}">
                                </div>

                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" id="emailEditUsuarioEncargado" name="emailEditUsuarioEncargado" class="form-control" placeholder="Correo Electrónico" value="{{$res->email}}">
                                </div>

                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            </div>
                                            <input type="text" id="telefonoEditUsuarioEncargado" name="telefonoEditUsuarioEncargado" class="form-control" placeholder="Teléfono" onkeypress="return valideKey(event);" maxlength="12"
                                                value="{{$res->telefono}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text">Ext</span>
                                            </div>
                                            <input type="text" id="extEditUsuarioEncargado" name="extEditUsuarioEncargado" class="form-control" placeholder="Extensión" onkeypress="return solonumeros(event)" maxlength="3"
                                                    value="{{$res->extension}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" style="float: right;" class="btn btn-primary mb-2" onclick="updateinfordep()">
                            <i class="fas fa-save mr-2"></i> Actualizar
                        </button>
                    </div>
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
<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/departamento.js')}}"></script>
<script src="{{asset('assets/administrador/js/validacion.js')}}"></script>
<script>
    $('.select2').select2({
        theme: 'bootstrap4',
    });
    const nameInterfaz = "Departamentos";
    //var elementTypeDep= document.getElementById('selGetDepartReg');
    $('#modalCargando').modal('show');
    setTimeout(() => {
        $('#modalCargando').modal('hide');
    }, 1500);
</script>
@endsection