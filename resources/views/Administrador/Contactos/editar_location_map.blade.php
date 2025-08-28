@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/contact-list.png')}}">
@endsection

@section('title-page')
Admin | Contactos {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<script src='https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.js'></script>
<link href='https://api.mapbox.com/mapbox-gl-js/v2.14.1/mapbox-gl.css' rel='stylesheet' />

<style>
    .mapboxgl-popup {
      max-width: 300px;
      font: 12px/20px 'Helvetica Neue', Arial, Helvetica, sans-serif;
    }

    .coordinates {
      background: rgba(0, 0, 0, 0.5);
      color: #fff;
      position: absolute;
      bottom: 40px;
      left: 20px;
      padding: 5px 10px;
      margin: 0;
      font-size: 11px;
      line-height: 18px;
      border-radius: 3px;
      display: none;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu.top{
      padding-left: 10px;
    }

    .bootstrap-datetimepicker-widget.dropdown-menu.bottom{
      padding-left: 10px;
    }

    .btnbackloc{
      width: 14%;
      float: right;
      margin: 12px;
      padding: 5px;
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
    <h1>Contactos</h1>
  </div>
</div>
@endsection

@section('contenido-body')
<input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">

<section class="content">
    <div class="container-fluid">
        <div class="row mb-3">
          <div class="col-lg-12">
            <div class="btnbackloc">
              <button type="button" class="btn btn-primary btn-block" onclick="urlback()"><i
                class="fas fa-arrow-left mr-2"></i> Regresar</button>
            </div>
          </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
              <div id='map' style='width: 100%; height: 300px;'></div>
              <pre id="coordinates" class="coordinates"></pre>
            </div>
        </div>
        <!-- /.row -->
        <div class="row mt-4">
            <div class="col-lg-12">
              <div class="card card-default">
                <div class="card-header">
                  <h3 class="card-title">Información de Contactos</h3>
                </div>
                <div class="card-body">
                  @foreach($resultado as $loc)
                  <div class="row">
                    <input type="hidden" id="idcontactogeo" value="{{$loc->id}}">
                    <div class="col-lg-6">
                      <div class="form-group mb-3">
                        <label for="inputEditNameLocation">Nombre</label>
                        <textarea class="form-control text-justify" id="inputEditNameLocation" placeholder="Ingrese un Nombre" rows="2" cols="5" 
                          maxlength="270">{{$loc->detalle}}</textarea>
                      </div>
                      <div class="form-group mb-3">
                        <label for="inputELatitud">Latitud:</label>
                        <input type="text" id="inputELatitud" class="form-control" value="{{$loc->latitud}}" placeholder="Latitud" autocomplete="off" readonly>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group mb-3">
                        <label for="inputEditDireccionLocation">Dirección</label>
                        <textarea class="form-control text-justify" id="inputEditDireccionLocation" placeholder="Ingrese una dirección" rows="2" 
                          cols="5" maxlength="270">{{$loc->detalle_2}}</textarea>
                      </div>
                      <div class="form-group mb-3">
                        <label for="inputELongitud">Longitud:</label>
                        <input type="text" id="inputELongitud" class="form-control" value="{{$loc->longitud}}" placeholder="Longitud" autocomplete="off" readonly>
                      </div>
                    </div>
                  </div>
                  @endforeach
                  <div class="row">
                    <div class="col-lg-12 d-flex justify-content-end">
                      <button type="button" class="btn btn-primary btn-block" onclick="actualizarRegistroGeo()" style="width: 12%;" id="btnUpdateNewLocation"><i
                            class="fas fa-save mr-2"></i> Actualizar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')

<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/contacto_location.js')}}"></script>
<script src="{{asset('assets/administrador/js/validacion.js')}}"></script>
<script>
    $('.select2').select2({
        theme: 'bootstrap4',
    });

    const nameInterfaz = "Contactos";

    /*mapboxgl.accessToken = 'pk.eyJ1IjoiamNsb3BlejE0IiwiYSI6ImNqemE2cjI4ZzAwbmEzamxveXU1OG8za3UifQ.BadDhjV5YpOq3cG4c7sTbw';*/
    mapboxgl.accessToken = 'pk.eyJ1IjoiamVhbmNsIiwiYSI6ImNtMGZpbmpjazA0YzAybHBucWhzOWluNnkifQ.DPKfnyD9t1DbHroX-OJ1Fg';
    var map=null;
    var marker;
    var firstOpen = true;
    var time;
    var lat=0;
    var long=0;
    var sendlat=0, sendlong=0;
    var coordenadas = {{ Illuminate\Support\Js::from($coordenadas) }};
    
    const coordinates = document.getElementById('coordinates');

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            console.log("Geolocation is not supported by this browser.");
        }
    }

    function showPosition() {
        /*lat= position.coords.latitude;
        long=position.coords.longitude;
        coordenadas= [long, lat];*/

        map = new mapboxgl.Map({
            container: 'map', // container ID
            style: 'mapbox://styles/mapbox/streets-v12', // style URL
            center: coordenadas, // starting position [lng, lat]
            zoom: 15, // starting zoom
        });

        marker = new mapboxgl.Marker({draggable: true})
            .setLngLat(coordenadas)
            .addTo(map);

        function onDragEnd() {
            const lngLat = marker.getLngLat();
            coordinates.style.display = 'block';
            coordinates.innerHTML = `Longitude: ${lngLat.lng}<br />Latitude: ${lngLat.lat}`;
            sendlat=lngLat.lat; sendlong=lngLat.lng;
            $('#inputELatitud').val(lngLat.lat);
            $('#inputELongitud').val(lngLat.lng);
        }

        marker.on('dragend', onDragEnd);
    }

    $(document).ready(function(){
      //getLocation();
      showPosition();
    });

</script>
@endsection