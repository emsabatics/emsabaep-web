@extends('Viewmain.Layouts.app')

@section('css')
<link href="{{asset('assets/viewmain/css/servicestyle.css')}}" rel="stylesheet">
<link href="{{asset('assets/viewmain/css/stylesubservice.css')}}" rel="stylesheet">
<link href="{{asset('assets/administrador/css/no-data-load.css')}}" rel="stylesheet">
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
<!--<div class="container-fluid blog">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="imgsubservice">
                    <img src="{{asset('assets/viewmain/img/breadcrumb-bg.jpg')}}" alt="" style="width: 100%;height: 100%;">
                </div>
            </div>
        </div>
    </div>
</div>-->

<!-- Blog Start -->
<div class="container-fluid blog pb-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Servicio de {{$servicio}}</h5>
        </div>
        @if(count($subservicio)>0)
        <div class="row g-4 justify-content-center">
            <div class="col-lg-12">
                <div class="cards">
                    <div class="card card--water active" >
                        <div class="card__svg-container">
                            <div class="card__svg-wrapper">
                                <img src="/servicios-img/icono-informacion.png" alt="Información" style="width: 100%;height: 100%;">
                            </div>
                        </div>
                        <div class="card__stuff-container">
                            <div class="card__stuff-text">Información</div>
                        </div>
                    </div>
                    @foreach ($subservicio as $it)
                    <div class="card card--water" onclick="gotosubservice({{$it->id}})">
                        <div class="card__svg-container">
                            <div class="card__svg-wrapper">
                                <img src="/servicios-img/{{$it->imagen}}" alt="{{$it->imagen}}" style="width: 100%;height: 100%;">
                            </div>
                        </div>
                        <div class="card__stuff-container">
                            <div class="card__stuff-text">{{$it->nombre}}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <!--<section class="cards">
                    <div class="card card--oil">
                        <div class="card__svg-container">
                            <div class="card__svg-wrapper">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80">
                                    <filter id="goo">
                                        <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur" />
                                        <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 19 -9" result="goo" />
                                        <feBlend in="SourceGraphic" in2="goo" />
                                    </filter>
                                    <circle cx="40" cy="40" r="39" fill="#6a7a87"/>
                                    <g filter="url(#goo)">
                                        <path id="myTeardrop" fill="#FFFFFF" d="M48.9,43.6c0,4.9-4,8.9-8.9,8.9s-8.9-4-8.9-8.9S40,27.5,40,27.5S48.9,38.7,48.9,43.6z"/>
                                        <path id="TopInit"  fill="#FFFFFF" d="M13,10.8c5-5.3,10.7-8.5,18.3-9.8c11.2-1.8,9.2-1.4,17.6,0C58.3,2.7,66,6,69,13.1V-2.7L13-2.8V10.8z"/>
                                        <path id="TopBulb" fill="#FFFFFF" d="M13,10.8c5-5.3,14.8-4,18.3,2.3c4.3,7.7,13.8,7.6,17.6,0c3.4-7,17.1-7.1,20.1,0V-2.7L13-2.8V10.8z" style="visibility: hidden"/>
                                        <path id="TopBulbSm" fill="#FFFFFF" d="M13,10.8c5-5.3,18.5-14,23.3-8.8c3.6,3.9,3.9,4.5,7.6,0c5-6,22.1,3.9,25.1,11V-2.7L13-2.8V10.8z" style="visibility: hidden"/>
                                        <path id="TopRound" fill="#FFFFFF" d="M13,10.8c5-5.3,10.6-6,18.3-6.8c6.5-0.7,10.5-0.8,17.6,0C58.4,5.1,66,6,69,13.1V-2.7L13-2.8V10.8z" style="visibility: hidden"/>
                                    </g>
                                </svg>
                            </div>
                        </div>
                        <div class="card__count-container">
                            <div class="card__count-text">
                                <span class="card__count-text--big">250</span> Million
                            </div>
                        </div>
                        <div class="card__stuff-container">
                            <div class="card__stuff-icon">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13 13">
                                    <path fill="#6a7a87" d="M9.4,2L9.2,2.3v0.4v7.6v0.4L9.4,11H3.6l0.3-0.3v-0.4V2.7V2.3L3.6,2H9.4 M12,1H1l1.8,1.7v7.6L1,12h11l-1.8-1.7V2.7L12,1L12,1z"/>
                                    <line fill="none" stroke="#6a7a87" class="st0" x1="3" y1="6.5" x2="10" y2="6.5"/>
                                </svg>
                            </div>
                            <div class="card__stuff-text"> Gallons of oil</div>
                        </div>
                    </div>
                    <div class="card card--tree">
                        <div class="card__svg-container">
                            <div class="card__svg-wrapper">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80">
                                    <circle cx="40" cy="40" r="39" fill="#6abf60"/>
                                    <g id="Branches">
                                        <polygon id="topBranches" fill="#FFFFFF" points="40.1,19.8 51.2,43.1 29,43.1"/>
                                        <polygon id="botBranches" fill="#FFFFFF" points="40,28 52,54.3 28,54.3"/>
                                    </g>
                                    <rect id="Trunk" x="37.7" y="53.8" fill="#FFFFFF" width="4.7" height="6"/>
                                    <rect id="Particle" x="37.9" y="54.3" fill="#FFFFFF" width="2" height="2"/>
                                    <polygon id="Axe" fill="#FFFFFF" points="0.7,5.3 7.3,5.3 7.3,10.2 4,20.3 0.7,10.2"/>
                                </svg>
                            </div>
                        </div>
                        <div class="card__count-container">
                            <div class="card__count-text">
                                <span class="card__count-text--big">10</span> Million
                            </div>
                        </div>
                        <div class="card__stuff-container">
                            <div class="card__stuff-icon">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13 13">
                                    <polygon fill="none" stroke="#6a7a87" points="3.5,1.5 5.5,1.5 5.5,5 9.5,1.5 9.5,9 11,11.5 2,11.5 3.5,9 "/>
                                </svg>
                            </div>
                            <div class="card__stuff-text"> Trees cut </div>
                        </div>
                    </div>
                    <div class="card card--water">
                        <div class="card__svg-container">
                            <div class="card__svg-wrapper">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80">
                                    <circle cx="40" cy="40" r="39" fill="#60cbe7"/>
                                    <g id="waveGroup">
                                        <path id="waveTop" fill="#FFFFFF" d="M93,34.1c-3.5,0-5.8-1.1-8.1-4.1h0c-1.6,3-4.9,4.3-8.4,4.3c-3.5,0-6.1-1.3-8.4-4.3h0c-1.6,3-5.1,4.1-8.6,4.1
                                            c-3.5,0-6.6-2-8.6-4.6v0c-2,2.6-4.5,4.3-8,4.3c-3.5,0-6-1.7-8-4.3v0c-2,2.6-5.1,4.5-8.6,4.5c-3.5,0-6.3-1.1-8.5-4.1h0
                                            c-1.6,3-4.9,4.3-8.4,4.3C6,34.3,3.3,33,1.1,30h0c-1.6,3-4.5,4.1-8,4.1c-3.5,0-6-2-8-4.6v0c-2,2.6-5.5,4.3-9,4.3c-3.5,0-6-1.7-9-4.3
                                            v6.6c3,1.5,5.6,2.3,8.6,2.3s6.2-0.9,8.5-2.3c2.2,1.5,5.4,2.3,8.5,2.3s6.1-0.9,8.4-2.3c2.2,1.5,5.4,2.3,8.4,2.3s6.1-0.9,8.4-2.3
                                            c2.2,1.5,5.4,2.3,8.4,2.3s6.1-0.9,8.4-2.3c2.2,1.5,5.3,2.3,8.4,2.3s6.1-0.9,8.4-2.3c2.2,1.5,5.3,2.3,8.4,2.3s6.1-0.9,8.4-2.3
                                            c2.2,1.5,5.3,2.3,8.4,2.3s6.1-0.9,8.4-2.3c2.2,1.5,5,2.3,8,2.3s6-0.9,8-2.3v-6.6C100,32.1,96.5,34.1,93,34.1z"/>
                                                                <path id="waveBot" fill="#FFFFFF" d="M98,46.1c-3.5,0-5.8-1.1-8.1-4.1h0c-1.6,3-4.9,4.3-8.4,4.3c-3.5,0-6.1-1.3-8.4-4.3h0c-1.6,3-5.1,4.1-8.6,4.1
                                            c-3.5,0-6.6-2-8.6-4.6v0c-2,2.6-4.5,4.3-8,4.3c-3.5,0-6-1.7-8-4.3v0c-2,2.6-5.1,4.5-8.6,4.5c-3.5,0-6.3-1.1-8.5-4.1h0
                                            c-1.6,3-4.9,4.3-8.4,4.3C11,46.3,8.3,45,6.1,42h0c-1.6,3-4.5,4.1-8,4.1c-3.5,0-6-2-8-4.6v0c-2,2.6-5.5,4.3-9,4.3c-3.5,0-6-1.7-9-4.3
                                            v6.6c3,1.5,5.6,2.3,8.6,2.3s6.2-0.9,8.5-2.3c2.2,1.5,5.4,2.3,8.5,2.3s6.1-0.9,8.4-2.3c2.2,1.5,5.4,2.3,8.4,2.3s6.1-0.9,8.4-2.3
                                            c2.2,1.5,5.4,2.3,8.4,2.3c3,0,6.1-0.9,8.4-2.3c2.2,1.5,5.3,2.3,8.4,2.3s6.1-0.9,8.4-2.3c2.2,1.5,5.3,2.3,8.4,2.3
                                            c3,0,6.1-0.9,8.4-2.3c2.2,1.5,5.3,2.3,8.4,2.3s6.1-0.9,8.4-2.3c2.2,1.5,5,2.3,8,2.3s6-0.9,8-2.3v-6.6C105,44.1,101.5,46.1,98,46.1z"
                                                                />
                                    </g>
                                </svg>
                            </div>
                        </div>
                        <div class="card__count-container">
                            <div class="card__count-text">
                                <span class="card__count-text--big">One</span> Billion
                            </div>
                        </div>
                        <div class="card__stuff-container">
                            <div class="card__stuff-icon">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 13 13">
                                    <path fill="none" stroke="#6a7a87" d="M1,1.5c0.9,0,0.9,2,1.8,2c0.9,0,0.9-2,1.8-2c0.9,0,0.9,2,1.8,2c0.9,0,0.9-2,1.8-2c0.9,0,0.9,2,1.8,2s0.9-2,1.8-2"/>
                                    <path fill="none" stroke="#6a7a87" d="M1,5.5c0.9,0,0.9,2,1.8,2c0.9,0,0.9-2,1.8-2c0.9,0,0.9,2,1.8,2c0.9,0,0.9-2,1.8-2c0.9,0,0.9,2,1.8,2s0.9-2,1.8-2"/>
                                    <path fill="none" stroke="#6a7a87" d="M1,9.5c0.9,0,0.9,2,1.8,2c0.9,0,0.9-2,1.8-2c0.9,0,0.9,2,1.8,2c0.9,0,0.9-2,1.8-2c0.9,0,0.9,2,1.8,2s0.9-2,1.8-2"/>
                                </svg>
                            </div>
                            <div class="card__stuff-text"> Gallons of water</div>
                        </div>
                    </div>
                </section>-->
            </div>
        </div>
        @else
        <div class="row nonews">
            <div class="col-lg-12 no-data">
                <div class="imgadvice">
                    <img src="{{asset('assets/administrador/img/icons/no-content-img.png')}}" alt="Construccion">
                </div>
                <span class="mensaje-noticia mt-4 mb-4">No hay <strong>información</strong> disponibles por el momento...</span>
            </div>
        </div> 
        @endif
    </div>
</div>

<div class="container-fluid bg-light blog">
    <div class="container py-5">
        <div class="row gy-4">
            <div class="col-lg-3 col-12">
                <div>
                    <img src="/servicios-img/{{$imagen}}" alt="{{$imagen}}" style="width: 100%;height: 100%;">
                </div>
            </div>
            <div class="col-lg-9 col-12">
                {!! $descripcion !!}
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
    function gotosubservice(id){
        var url= '/description-sub-services-detail/'+utf8_to_b64(id);
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