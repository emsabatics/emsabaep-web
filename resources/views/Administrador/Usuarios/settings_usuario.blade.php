@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/registro.png')}}">
@endsection

@section('title-page')
Admin | Usuarios {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop.css')}}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/daterangepicker/daterangepicker.css')}}">

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
</style>
@endsection

@section('container-header')
<div class="row mb-2">
  <div class="col-sm-12">
    <h1>Perfil de Usuario</h1>
  </div>
</div>
@endsection

@section('contenido-body')
<input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">

<section class="content">
  <div class="container-fluid">
    <div class="row">
      @foreach ($datos_user as $us)
       <div class="col-lg-3">
          <!-- Profile Image -->
          <div class="card card-primary card-outline">
            <div class="card-body box-profile">
              <div class="text-center">
                <img class="profile-user-img img-fluid img-circle"
                     src="{{asset('assets/administrador/img/user2-160x160.jpg')}}"
                     alt="User profile picture">
              </div>
              <h3 class="profile-username text-center">{{$us->nombre_usuario}}</h3>

              <p class="text-muted text-center">{{$us->user}}</p>
            </div>
          </div>
        </div>  
        <div class="col lg-9">
          <div class="card">
            <div class="card-header p-2">
              <div class="cardsRowTitle">
                <ul class="nav nav-pills">
                  <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab">Ajustes</a></li>
                </ul>
              </div>
            </div>
            <div class="card-body">
              <div class="tab-content">
                <div class="active tab-pane" id="settings">
                  <form class="form-horizontal">
                    <div class="form-group row noevent">
                      <input type="hidden" name="iduser" id="iduser" value="{{$us->id}}">
                      <label for="inputNameUser" class="col-sm-2 col-form-label">Nombres:</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" id="inputNameUser" placeholder="Name" value="{{$us->nombre_usuario}}">
                      </div>
                    </div>
                    <div class="form-group row noevent">
                      <label for="inputUserU" class="col-sm-2 col-form-label">Usuario:</label>
                      <div class="col-sm-10">
                        <input type="email" class="form-control" id="inputUserU" placeholder="text" value="{{$us->user}}">
                      </div>
                    </div>
                    <div class="form-group row noevent">
                      <label for="inputTypeUser" class="col-sm-2 col-form-label">Tipo de Usuario</label>
                      <div class="col-sm-10">
                        <input type="text" class="form-control" id="inputTypeUser" placeholder="Name" value="{{$us->tipo}}">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Clave</label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="password" class="form-control" placeholder="Clave" id="inputPassword" name="inputPassword" autocomplete="off">
                          <div class="input-group-append showhideinit" onclick="showPassword('inputPassword','spanLock')">
                            <div class="input-group-text" id="spanLock">
                              <span class="fas fa-lock"></span>
                            </div>
                          </div>
                        </div>
                        <span class="spanNotiRegistro">La clave debe tener una longitud mínima de 8 caracteres</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputName2" class="col-sm-2 col-form-label">Repita la Clave</label>
                      <div class="col-sm-10">
                        <div class="input-group">
                          <input type="password" class="form-control" placeholder="Clave" id="inputPasswordR" name="inputPasswordR" autocomplete="off">
                          <div class="input-group-append showhideinit" onclick="showPassword('inputPasswordR','spanLock2')">
                            <div class="input-group-text" id="spanLock2">
                              <span class="fas fa-lock"></span>
                            </div>
                          </div>
                        </div>
                        <span class="spanNotiRegistro">La clave debe tener una longitud mínima de 8 caracteres</span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="offset-sm-2 col-sm-10">
                        <button type="button" class="btn btn-primary mb-2 btnsaveprofile" onclick="updatePClavePerfil(event)">Actualizar</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div> 
      @endforeach
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
                <p style="font-size: 16px;"> Actualizando Usuario... </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/usuarios.js')}}"></script>
@endsection