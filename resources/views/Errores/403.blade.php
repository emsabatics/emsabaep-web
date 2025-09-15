<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/error.png')}}">
    <title>Error 403</title>
    <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/administrador/css/error404.css')}}">
</head>
<body>
    <a href="" class="fa fa-arrow-left"></a>
    <div class="error">
        <h1>403</h1>
        <p>ACCESO DENEGADO</p>
    </div>
<!-- jQuery -->
<script src="{{asset('assets/administrador/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/administrador/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
</body>
</html>