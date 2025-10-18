@extends('Viewmain.Layouts.app')

@section('css')
<link href="{{asset('assets/viewmain/css/departamento.css')}}" rel="stylesheet">
<link href="{{asset('assets/viewmain/css/cardatencioncliente.css')}}" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<!--<link href="https://api.mapbox.com/mapbox-gl-js/v3.6.0/mapbox-gl.css" rel="stylesheet">
<script src="https://api.mapbox.com/mapbox-gl-js/v3.6.0/mapbox-gl.js"></script>-->
<script src="https://api.mapbox.com/mapbox-gl-js/v3.14.0/mapbox-gl.js"></script>
<link href="https://api.mapbox.com/mapbox-gl-js/v3.14.0/mapbox-gl.css" rel="stylesheet"/>
<style>
    #map { top: 0; bottom: 0; width: 100%;  height: 450px;}
    /*https://docs.mapbox.com/mapbox-gl-js/assets/washington-monument.jpg*/
    .marker {
        background-image: url('/assets/viewmain/img/web/gota_agua_mapa.svg');
        background-size: cover;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        cursor: pointer;
    }

    .mapboxgl-popup {
        max-width: 400px;
        font:
            12px/20px 'Helvetica Neue',
            Arial,
            Helvetica,
            sans-serif;
    }

    .divfileinfo{
        width: 100%;
        height: 600px;
    }

    .divfileinfo img,
    .divfileinfo video{
        width: 100%;
        height: 100%;
    }
</style>
@endsection


@section('social-media')
    @foreach ($socialmedia as $sm)
        @if ($sm->nombre=='Facebook')
            <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-facebook-f fw-normal"></i></a>
        @elseif ($sm->nombre=='X' || $sm->nombre=='Twitter')
            <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-twitter fw-normal"></i></a>
        @elseif ($sm->nombre=='Instagram')
            <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-instagram fw-normal"></i></a>
        @elseif ($sm->nombre=='Telegram')
            <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-telegram fw-normal"></i></a>
        @elseif ($sm->nombre=='YouTube')
            <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-youtube fw-normal"></i></a>
        @elseif ($sm->nombre=='Linkedin')
            <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-linkedin-in fw-normal"></i></a>
        @elseif ($sm->nombre=='TikTok')
            <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-tiktok fw-normal"></i></a>
        @endif
    @endforeach
@endsection

@section('home')
<input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">

<!-- Header Start -->
<div class="container-fluid bg-breadcrumb p-0">
    <div class="container text-center py-5" style="max-width: 300px;">
        <h3 class="text-white display-3 mb-4"></h1>  
    </div>
</div>
<!-- Header End -->

<!-- Vision Start -->
<div class="container-fluid contact bg-light py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">
                Contactos
            </h5>
        </div>
        <div class="row g-4 align-items-center">
            <div class="col-lg-4">
                <div class="bg-white rounded p-4">
                    @foreach ($contactos as $ct)
                    @if ($ct['tipo_contacto'] == 'direccion')
                    <div class="text-center mb-4">
                        <i class="fa fa-map-marker-alt fa-3x text-primary"></i>
                        <h4 class="text-primary"><Address></Address></h4>
                        <p class="mb-0">{{$ct['detalle']}}</p>
                    </div>
                    @endif
                    @if ($ct['tipo_contacto'] == 'telefono')
                    <div class="text-center mb-4">
                        <i class="fa fa-phone-alt fa-3x text-primary mb-3"></i>
                        <h4 class="text-primary">Teléfono</h4>
                        <p class="mb-0">{{$ct['detalle']}}</p>
                    </div>
                    @endif
                    @if ($ct['tipo_contacto'] == 'email')
                    <div class="text-center">
                        <i class="fa fa-envelope-open fa-3x text-primary mb-3"></i>
                        <h4 class="text-primary">Email</h4>
                        <p class="mb-0">{{$ct['detalle']}}</p>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            <div class="col-lg-8">
                <h3 class="mb-2">Envíanos un mensaje</h3>
                <p class="mb-4">¡Leeremos cada una de tus sugerencias!</p>
                <form>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control border-0" id="nameinput" placeholder="Nombres y Apellidos" autocomplete="off">
                                <label for="nameinput">Nombres y Apellidos</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control border-0" id="emailinput" placeholder="Email" autocomplete="off">
                                <label for="emailinput">Email</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="text" class="form-control border-0" id="telefonoinput" placeholder="Teléfono" autocomplete="off" 
                                 maxlength="10" onkeypress="return solonumeros(event);">
                                <label for="telefonoinput">Teléfono de Contacto</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <!--<div class="form-floating">
                                <input type="text" class="form-control border-0" id="cuentaservicioinput" placeholder="Teléfono" autocomplete="off" 
                                 maxlength="10" onkeypress="return solonumeros(event);">
                                <label for="cuentaservicioinput">Código del Servicio (Cuenta)</label>
                            </div>-->
                            <div class="input-group mb-3">
                                <input type="text" class="form-control border-0" id="cuentaservicioinput" placeholder="Código del Servicio (Cuenta)" autocomplete="off"
                                maxlength="10" onkeypress="return solonumeros(event);" aria-label="Recipient's username" aria-describedby="button_info_planilla" style="height: calc(3.5rem + 2px);padding: 1rem .75rem;">
                                <button class="btn btn-primary" type="button" id="button_info_planilla">
                                    <i class="fa fa-info-circle" style="font-size: 30px;margin: 0;"></i>
                                </button>
                              </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <textarea class="form-control border-0" placeholder="Escribe tu mensaje aquí" id="messageinput" style="height: 160px;text-align: justify;" autocomplete="off"></textarea>
                                <label for="messageinput">Detalle</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex justify-content-center m-3">
                            <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary w-100 py-3 btnsendmessage" type="button" onclick="sendmessage()">Enviar Mensaje</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid contact py-2">
    <div class="container py-5">
        <div class="row g-4 align-items-center">
            <div class="col-xl-12">
                <div class="card-design-general">
                    <!--<div class="row mt-5 mt-md-4 row-cols-1 row-cols-sm-1 row-cols-md-3 justify-content-center">-->
                        @foreach ($geolocalizacion as $item)
                        @if(str_contains($item->detalle, 'Oficina') || str_contains($item->detalle, 'oficina'))
                        <!--<div class="col">-->
                            <div class="service-card">
                                <div class="row mb-2">
                                    <div class="col-lg-12 setColImgCard">
                                        <div class="imgcard">
                                            @if (str_contains($item->detalle, 'matriz') || str_contains($item->detalle, 'Matriz'))
                                            <img src="{{asset('assets/viewmain/img/web/oficina-matriz.png')}}" alt="img">
                                            @else
                                            <img src="{{asset('assets/viewmain/img/web/oficina-general.png')}}" alt="img">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2 setColImgCard">
                                    <p>{{$item->detalle}}</p>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-lg-12 setTextInfoCard">
                                        <div>
                                            <span class="spanTitletext"><i class="fa fa-map-marker mr-3"></i> Dirección: </span>
                                            <br>
                                            @foreach ($direccionmain as $dir)
                                            @if (str_contains($item->detalle, 'matriz') || str_contains($item->detalle, 'Matriz'))
                                                <span class="spanDescriptiontext">{{$dir->direccion}}</span>
                                            @else
                                                <span class="spanDescriptiontext">{{$item->detalle_2}}</span>
                                            @endif
                                            @endforeach
                                        </div>
                                        <div>
                                            <span class="spanTitletext"><i class="far fa-clock mr-3"></i> Horario de Atención al Usuario: </span>
                                            <br>
                                            @foreach ($horario as $h)
                                                <span class="spanDescriptiontext"> Horario de {{$h->hora_a}} a {{$h->hora_c}} ininterrumpidamente de {{str_replace('-','a',$h->detalle)}}.</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <!--</div>-->
                        @endif
                        @endforeach
                    <!--</div>-->
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid contact py-2">
    <div class="container py-5">
        <div class="row g-4 align-items-center">
            <div class="col-12">
                <div class="rounded">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('home_contact')
@foreach ($contactos as $ct)
    @if ($ct['tipo_contacto'] == 'direccion')
        <a href=""><i class="fas fa-home me-2"></i> {{$ct['detalle']}}</a>
    @elseif ($ct['tipo_contacto'] == 'telefono')
        <a href=""><i class="fas fa-phone me-2"></i> {{$ct['detalle']}}</a>
    @elseif ($ct['tipo_contacto'] == 'email')
        <a href=""><i class="fas fa-envelope me-2"></i> {{$ct['detalle']}}</a>
    @elseif ($ct['tipo_contacto'] == 'houratencion')
        <a href="" class="mb-3"><i class="fas fa-info me-2"></i> {{$ct['detalle']}} <br/> {{$ct['hora_a']}} - {{$ct['hora_c']}}</a>
    @endif
@endforeach
@endsection

@section('home_socialmedia')
<div class="d-flex align-items-center">
    <i class="fas fa-share fa-2x text-white me-2"></i>
    @foreach ($socialmedia as $sm)
        @if ($sm->nombre=='Facebook')
        <a class="btn-square btn btn-primary rounded-circle mx-1" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-facebook-f"></i></a>
        @elseif ($sm->nombre=='X' || $sm->nombre=='Twitter')
        <a class="btn-square btn btn-primary rounded-circle mx-1" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-twitter"></i></a>
        @elseif ($sm->nombre=='Instagram')
        <a class="btn-square btn btn-primary rounded-circle mx-1" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-instagram"></i></a>
        @elseif ($sm->nombre=='Telegram')
        <a class="btn-square btn btn-primary rounded-circle mx-1" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-telegram"></i></a>
        @elseif ($sm->nombre=='YouTube')
        <a class="btn-square btn btn-primary rounded-circle mx-1" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-youtube"></i></a>
        @elseif ($sm->nombre=='Linkedin')
        <a class="btn-square btn btn-primary rounded-circle mx-1" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
        @elseif ($sm->nombre=='TikTok')
        <a class="btn-square btn btn-primary rounded-circle mx-1" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-tiktok"></i></a>
        @endif
    @endforeach
</div>
@endsection

<!-- MODAL INFORMATIVO -->
<div class="modal fade" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false" style="display: none;">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: #0056a4;">
                <h4 class="modal-title" style="color: #fff;">Información</h4>
                <!--<button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>-->
            </div>
            <div class="modal-body p-4">
                <div class="row">
                    <div class="col-12">
                        <div class="divfileinfo" id="div_fileinfo">
                            <!--<img src="/assets/viewmain/img/web/seccion-nuestros-servicios-consulte-su-planilla.png" alt="Información">-->
                            <!--<video width="640" height="360" poster="assets/administrador/img/icons/camara-de-video.png" controls>
                                <source src="/assets/viewmain/video/video_emsaba.mp4" type="video/mp4">
                            </video>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary mr-3" onclick="cerrarModal()">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@section('js')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<!-- jQuery -->
<script src="{{asset('assets/administrador/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/administrador/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/viewmain/js/contactos.js')}}"></script>
<script>
    $('#nameinput').attr("autocomplete", "off");
    $('#emailinput').attr("autocomplete", "off");
    $('#messageinput').attr("autocomplete", "off");
    mapboxgl.accessToken = 'pk.eyJ1IjoiamVhbmNsIiwiYSI6ImNtMGZpbmpjazA0YzAybHBucWhzOWluNnkifQ.DPKfnyD9t1DbHroX-OJ1Fg';

    var coordenadas = {{Illuminate\Support\Js::from($geolocalizacion)}};
    var getfilecuenta = {{Illuminate\Support\Js::from($getfilecuenta)}};
    var data= [];

    function getCoordenadas(){
        $(coordenadas).each(function(i,v){
            if(v.tipo_contacto=='geolocalizacion'){
                var descp='';
                if(v.detalle2=='' || v.detalle2==null){
                    descp= 'EMSABA EP';
                }else{
                    descp= v.detalle2;
                }
                data.push({
                    'type': 'Feature',
                    'properties': {
                        'title': '<strong>'+v.detalle+'</strong>',
                        'description':
                            '<p>'+descp+'</p>',
                        },
                    'geometry': {
                        'type': 'Point',
                        'coordinates': [v.longitud, v.latitud]
                    }
                });
            }
        });
    }

    const latitud = -1.7980285621412122;
    const longitud = -79.5310171772688;
    
    $(document).ready(function(){
        getCoordenadas();
        setTimeout(() => {
            const geojson= {
                'type': 'FeatureCollection',
                'features': data
            };

            const map = new mapboxgl.Map({
                container: 'map', // container ID
                style: 'mapbox://styles/mapbox/standard', // style URL
                center: [longitud, latitud], // starting position [lng, lat]. Note that lat must be set between -90 and 90
                zoom: 13 // starting zoom
            });

            // add markers to map
            for (const feature of geojson.features) {
                //console.log(feature.geometry.coordinates);
                // create a HTML element for each feature
                const el = document.createElement('div');
                el.className = 'marker';

                // make a marker for each feature and add it to the map
                new mapboxgl.Marker(el)
                .setLngLat(feature.geometry.coordinates)
                .setPopup(
                    new mapboxgl.Popup({ offset: 25 }) // add popups
                    .setHTML(
                        `<h5>${feature.properties.title}</h5><p>${feature.properties.description}</p>`
                    )
                )
                .addTo(map);
            }

        }, 500);
    });

    /*const map = new mapboxgl.Map({
        container: 'map', // container ID
        center: [longitud, latitud], // starting position [lng, lat]. Note that lat must be set between -90 and 90
        zoom: 17 // starting zoom
    });

    // create the popup
    const popup = new mapboxgl.Popup({ offset: 30, closeOnClick: false }).setHTML(
        '<h2>EMSABA EP</h2>'
    );

    // create DOM element for the marker
    const el = document.createElement('div');
    el.id = 'marker';

    // Create a default Marker and add it to the map.
    const marker = new mapboxgl.Marker(el)
        .setLngLat([longitud, latitud])
        .setPopup(popup)
        .addTo(map);

    // Add zoom and rotation controls to the map.
    map.addControl(new mapboxgl.NavigationControl());

    // Example of a MapMouseEvent of type "click"
    el.addEventListener('click', () => { 
        //alert("Marker Clicked.");
    });*/
    
</script>
@endsection