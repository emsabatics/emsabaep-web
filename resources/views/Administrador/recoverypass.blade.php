<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="google" content="notranslate">
  <link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/registro.png')}}">
  <title>Admin | Recovery</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/administrador/css/adminlte.min.css')}}">
  <link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
  <link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
  <script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
  <!-- Toastr -->
  <link rel="stylesheet" href="{{asset('assets/administrador/plugins/toastr/toastr.min.css')}}">
  <style>
    body.swal2-height-auto {
      height: auto !important;
      height: 100vh !important;
    }
  </style>
</head>
<body class="hold-transition register-page setBgContent">
  <input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">
  <div class="register-box" id="registro-box" style="display: none;">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="#" class="h1"><b>Admin</b> {{getNameInstitucion()}}</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Restablecer Clave de Acceso</p>

        <form id="formAcceso" class="mb-3">
          <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Usuario" autocomplete="off"
              id="datoUsuario" name="datoUsuario">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Contraseña" autocomplete="off"
              id="passwordUsuario" name="passwordUsuario">
            <div class="input-group-append" onclick="showPassword('passwordUsuario', 'spanLockR1')">
              <div class="input-group-text" id="spanLockR1">
                <span class="fas fa-lock"></span>
              </div>
            </div>
            <span class="spanNotiRegistro">La clave debe tener una longitud mínima de 8 caracteres</span>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Repita la Contraseña" autocomplete="off"
             id="rpasswordUsuario" name="rpasswordUsuario">
            <div class="input-group-append" onclick="showPassword('rpasswordUsuario', 'spanLockR2')">
              <div class="input-group-text" id="spanLockR2">
                <span class="fas fa-lock"></span>
              </div>
            </div>
            <span class="spanNotiRegistro">La clave debe tener una longitud mínima de 8 caracteres</span>
          </div>
        </form>

        <div class="row mb-3">
          <!-- /.col -->
          <div class="col-12">
            <button class="btn btn-primary btn-block" id="btnRecoveryAdmin">Guardar</button>
          </div>
          <!-- /.col -->
        </div>

        <div class="row">
          <div class="col-12">
            <a href="{{url('/login')}}" class="text-center"><i class="fa fa-chevron-left"></i> &nbsp; Regresar</a>
          </div>
        </div>
        </div>
      <!-- /.form-box -->
    </div><!-- /.card -->
  </div>
  <!-- /.register-box -->

  <!-- jQuery -->
  <script src="{{asset('assets/administrador/plugins/jquery/jquery.min.js')}}"></script>
  <!-- Toastr -->
  <script src="{{asset('assets/administrador/plugins/toastr/toastr.min.js')}}"></script>
  <!-- Bootstrap 4 -->
  <!--<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>-->
  <!-- AdminLTE App -->
  <!--<script src="../../dist/js/adminlte.min.js"></script>-->

  <script src="{{asset('assets/administrador/js/recoveryadmin.js')}}"></script>
  <script>

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

    $(document).ready(function(){
      showMessage();
    })
  </script>
</body>
</html>