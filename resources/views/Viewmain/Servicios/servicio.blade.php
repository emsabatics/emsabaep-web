@extends('Viewmain.Layouts.app')

@section('css')
<link href="{{asset('assets/viewmain/css/servicestyle.css')}}" rel="stylesheet">
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
<!-- Header Start -->
<div class="container-fluid bg-breadcrumb p-0">
    <div class="container text-center py-5" style="max-width: 300px;">
        <h3 class="text-white display-3 mb-4"></h1>  
    </div>
</div>
<!-- Header End -->

<!-- Blog Start -->
<div class="container-fluid blog py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Servicios</h5>
        </div>
        @if(count($servicios)>0)
        <div class="row g-4 justify-content-center tarj-content">
            @foreach ($servicios as $nt)
            <div class="tarj justify-content-center">
                <div class="blog-item animate">
                    <div class="blog-img">
                        <div class="blog-img-inner">
                            <img class="img-fluid w-100 rounded-top" src="/servicios-img/{{$nt->imagen}}" alt="Image">
                        </div>
                    </div>
                    <div class="blog-content border border-top-0 rounded-bottom p-4">
                        <div class="height-card-body-title">
                            <a href="javascrip:void(0)" class="h4">{{$nt->titulo}}</a>
                        </div>
                        <p class="my-3 text-justify"></p>
                        <div class="height-card-body-detail">
                            {{$nt->descripcion_corta}}
                        </div>
                        <div class="icon-end">
                            <div class="divLinkExterno">
                                @if ($nt->tipo=='externo')
                                    <a href="{{$nt->enlace}}" target="_BLANK" class="btn btn-primary rounded-pill py-2 px-4">Ir</a>
                                @else
                                    <a href="javascript:void(0)" onclick="redirecttosubservice({{$nt->id}})"class="btn btn-primary rounded-pill py-2 px-4">Ver más</a>
                                @endif
                            </div>
                            <div class="div-cont-img">
                                <img class="img-service" src="/servicios-img/{{$nt->icon}}" alt="Image">
                            </div>
                            <!--<svg height="45" width="45" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                    viewBox="0 0 64 64" xml:space="preserve">
                                    <style type="text/css">
                                        .st0{fill:#0054a2;}
                                        .st1{opacity:0.2;}
                                        .st2{fill:#231F20;}
                                        .st3{fill:#FFFFFF;}
                                    </style>
                                    <g id="Layer_1">
                                        <g>
                                            <circle class="st0" cx="32" cy="32" r="32"/>
                                        </g>
                                        <g class="st1">
                                            <path class="st2" d="M48,37.1C48,46.4,40.8,54,32,54s-16-7.6-16-16.9S25.6,20.2,32,10C38.4,20.2,48,27.7,48,37.1z"/>
                                        </g>
                                        <g>
                                            <path class="st3" d="M48,35.1C48,44.4,40.8,52,32,52s-16-7.6-16-16.9S25.6,18.2,32,8C38.4,18.2,48,25.7,48,35.1z"/>
                                        </g>
                                    </g>
                                    <g id="Layer_2">
                                    </g>
                                    <defs>
                                        <linearGradient id="paint0_linear_1366_4565" x1="0" y1="0" x2="120" y2="120" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="white" stop-opacity="0.7"></stop>
                                        <stop offset="0.505208" stop-color="white" stop-opacity="0"></stop>
                                        <stop offset="1" stop-color="white" stop-opacity="0.7"></stop>
                                        </linearGradient>
                                        <radialGradient id="paint1_radial_1366_4565" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(60 60) rotate(96.8574) scale(122.674 149.921)">
                                        <stop stop-color="white"></stop>
                                        <stop offset="1" stop-color="#363437" stop-opacity="0.2"></stop>
                                        </radialGradient>
                                    </defs>
                            </svg>-->
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="row nonews">
            <div class="col-lg-12 no-data">
                <div class="imgadvice">
                    <img src="{{asset('assets/administrador/img/icons/no-content-img.png')}}" alt="Construccion">
                </div>
                <span class="mensaje-noticia mt-4 mb-4">No hay <strong>servicios</strong> disponibles por el momento...</span>
            </div>
        </div> 
        @endif
        <nav aria-label="Page navigation example" class="mt-40 mb-0">
            <ul class="pagination justify-content-center">
            </ul>
        </nav>
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
        <a href="" class="mb-3"><i class="fas fa-info me-2"></i> {{$ct['detalle']}} <br/> {{$ct['hora_a']}} <br/> {{$ct['hora_c']}}</a>
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

@section('js')
<script>
    function redirecttosubservice(id){
        var url= '/sub-services-detail/'+utf8_to_b64(id);
        window.location= url;
    }

    function utf8_to_b64( str ) {
        return window.btoa(unescape(encodeURIComponent( str )));
    }

    /*$(document).ready(function(){
        var element= document.querySelector('.tarj-content');
        setTimeout(() => {
            element.style.display='block';
        }, 500);
    });*/
</script>
@endsection