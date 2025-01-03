<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/error.png')}}">
    <title>Error 429</title>
    <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/administrador/css/error404.css')}}">
</head>
<body>
    <a href="" class="fa fa-arrow-left"></a>
    <div class="error">
        <h1>429</h1>
        <p>Ha excedido el número de intentos.</p>
        <p id="contador">
            <span id="minutes">02</span> minutos / <span id="seconds">01</span> segundos restantes
        </p>
        <button id="buttonback" onclick="regresar()" class="button">Regresar</button>
    </div>
<!-- jQuery -->
<script src="{{asset('assets/administrador/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/administrador/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script type="text/javascript">
    function regresar(){
        window.location= '/home';
    }

    var element = document.querySelector('#buttonback');
    element.setAttribute("disabled", "");
    element.style.pointerEvents = "none";

    var date = new Date('2024-12-12 00:01');

    // Función para rellenar con ceros
    var padLeft = n => "00".substring(0, "00".length - n.length) + n;

    // Asignar el intervalo a una variable para poder eliminar el intervale cuando llegue al limite
    var interval = setInterval(() => {

        // Asignar el valor de minutos
        var minutes = padLeft(date.getMinutes() + "");
        // Asignqr el valor de segundos
        var seconds = padLeft(date.getSeconds() + "");
        
        //console.log(minutes, seconds);
        $('#minutes').html(minutes);
        $('#seconds').html(seconds);
        
        // Restarle a la fecha actual 1000 milisegundos
        date = new Date(date.getTime() - 1000);
            
        // Si llega a 2:45, eliminar el intervalo
        if( minutes == '00' && seconds == '00' ) {
            clearInterval(interval); 
            element.removeAttribute("disabled");
            element.style.removeProperty("pointer-events");
            document.getElementById('contador').style.display='none';
        }
    
    }, 1000);
</script>
</body>
</html>