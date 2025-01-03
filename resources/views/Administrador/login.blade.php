<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/inicio.png')}}">
    <title>Administrador - {{getNameInstitucion()}}</title> 

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free/css/all.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/administrador/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">

    <!--<link rel="stylesheet" type="text/css" href="assets/plugins/sweetalert/sweetalert2.min.css">
    <script type="text/javascript" src="assets/plugins/sweetalert/sweetalert2.min.js" ></script>-->

    <!--<link rel="stylesheet" href="assets/plugins/sweetalert2/bootstrap-4.min.css">
    <script src="assets/plugins/sweetalert2/sweetalert2.min.js"></script>-->

    <link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
    <script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
    </head>
    <body class="hold-transition login-page setBgContent" id="body-login">
        <input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <!--<div class="card-header text-center">
                    <a href="#" class="h1"><b>Administrador</b></a>
                </div>-->
                <div class="card-body">
                    <div class="logoimg">
                        <img src="{{asset('assets/administrador/img/inside_logo.png')}}" alt="Login">
                    </div>
                    <p class="login-box-msg">- Login -</p>

                    <form id="formDataLogin">
                        <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Usuario" id="txtUsuario" name="txtUsuario" autocomplete="off"
                            value="{{ old('txtUsuario') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        </div>
                        <div class="input-group mb-3">
                        <input type="password" class="form-control" placeholder="Clave" id="txtPassword" name="txtPassword" autocomplete="off">
                        <div class="input-group-append showhideinit" onclick="showPassword('txtPassword')">
                            <div class="input-group-text" id="spanLock">
                            <span class="fas fa-lock"></span>
                            </div>
                        </div>
                        </div>
                        <div class="input-group mb-3">
                            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                        </div>
                    </form>

                    <div class="social-auth-links text-center mt-2 mb-3">
                        <button class="btn btn-block btn-primary" id="btnLoginAdmin">
                            Iniciar Sesión
                        </button>
                    </div>

                </div>
                <div class="card-footer text-muted">
                    <p class="login-box-msg">Desarrollado por: <strong>CTI</strong></p>
                </div>
            </div>
        </div>
    </body>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="{{asset('assets/administrador/plugins/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('assets/administrador/js/loginadmin.js')}}"></script>
</html>