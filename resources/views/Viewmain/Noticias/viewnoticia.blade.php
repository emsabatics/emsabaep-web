@extends('Viewmain.Layouts.app')

@section('css')
<!--<link
  rel="stylesheet"
  href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
/>-->
<link rel="stylesheet" href="{{asset('assets/viewmain/lib/swipper/swiper-bundle.min.css')}}">

<style>
    .swiper {
      width: 100%;
      height: 100%;
    }

    .swiper-slide {
      text-align: center;
      font-size: 18px;
      background: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .swiper-slide img {
      display: block;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .swiper-button-next,
    .swiper-button-prev{
        /*opacity: 0.7;
        color: var(--celeste);*/
        opacity: 1;
        color: white;
        transition: all 0.3s ease;
    }

    .swiper-button-next:hover,
    .swiper-button-prev:hover {
        opacity: 1;
        color: white;
    }

    .swiper-pagination {
        bottom: -4px;
    }

    .swiper-pagination .swiper-pagination-bullet {
        height: 7px;
        width: 26px;
        border-radius: 25px;
        background: white;
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
        <div class="row gy-4">
            <div class="col-lg-8">
                @foreach ($texto as $tx)
                    <div class="section-title-new">
                        <h4>{{$tx['lugar']}} | {{$tx['titulo']}}</h4>
                        <span class="fecha"><i class="fa fa-calendar"></i> {{$tx['fecha']}}</span>
                    </div>
                @endforeach
                
                    <div class="noti-slide-img mt-4">
                        <div class="swiper mySwiper">
                            <div class="swiper-wrapper">
                                @foreach ($imagen as $r)
                                    <div class="swiper-slide">
                                        <img src="/noticias-img/{{$r->imagen}}" />
                                    </div>
                                @endforeach
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                @foreach ($texto as $tx)
                    <div class="noti-content mt-4">
                        @foreach($tx['descripcion'] as $key => $dato)
                            <p class="text-justify noti-texto">{{$dato}}</p>
                        @endforeach
                        @foreach($tx['hashtag'] as $key => $value)
                            <p style="color: #00b6e8;margin-bottom: 0.2rem;font-weight: bold;">{{$value}}</p>
                        @endforeach
                    </div>
                @endforeach
            </div>
            <div class="col-lg-4">
                <div class="row cabecera-logo-row">
                    <div class="image-logo">
                        <img src="{{asset('assets/viewmain/img/web/icono-de-sitio-web.png')}}" alt="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12" style="text-align: center;">
                        <h4 class="text-upercase bottom40 top40">ÚLTIMAS <span class="color_celeste">NOTICIAS</span></h4>
                        <div id="contenedor-noticias">
                            @foreach ($listnew as $ln)
                            <div class="media">
                                <div class="media-left media-middle">
                                    <a href="javascript:void(0)" onclick="viewnew({{$ln->id}})">
                                        <img src="/noticias-img/{{$ln->imagen}}" alt="Noticia" width="100" height="70"
                                            class="media-object" style="width: 100px;height: 68px;">
                                    </a>
                                </div>
                                <div class="media-body">
                                    <h4 class="media-heading">
                                        <a href="javascript:void(0)" onclick="viewnew({{$ln->id}})">
                                            {{$ln->lugar}} | {{$ln->titulo}}
                                        </a>
                                    </h4>
                                    <span><i class="fa fa-calendar"></i> {{$ln->fecha}}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="container" style="display: flex;justify-content: center;">
                            <video src="{{asset('assets/viewmain/video/video_emsaba.mp4')}}" autoplay muted loop class="video-load"></video>
                        </div>
                    </div>
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
<!--<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>-->
<!-- Swiper JS -->
<script src="{{asset('assets/viewmain/lib/swipper/swiper-bundle.min.js')}}"></script>
<script src="{{asset('assets/viewmain/js/noticias.js')}}"></script>
<script>
    var swiper = new Swiper(".mySwiper", {
        effect: "fade",
        loop: false,
        loopFillGroupWithBlank: true,
        navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
        },pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        autoplay: {
            delay: 4500,
        }
    });
</script>
@endsection