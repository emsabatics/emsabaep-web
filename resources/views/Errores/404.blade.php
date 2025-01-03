<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/error-404.png')}}">
    <title>Error 404</title>
    <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/administrador/css/error404.css')}}">
</head>
<body>
    <a href="" class="fa fa-arrow-left"></a>
    <div class="error">
        <h1>404</h1>
        <p>Lo sentimos, no se encuentra el contenido solicitado.</p>
        <button onclick="regresar()" class="button">Regresar</button>
    </div>
<!-- jQuery -->
<script src="{{asset('assets/administrador/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/administrador/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script type="text/javascript">
    function regresar(){
        window.location= '/home';
    }
</script>
</body>
</html>