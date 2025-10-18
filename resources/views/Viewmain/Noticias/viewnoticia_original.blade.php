@extends('Viewmain.Layouts.app')
@php
    use App\VideoHelper;
@endphp
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

    .video-container {
        display: flex;
        flex-direction: row;
        justify-content: center;
    }

    .noticia-video {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
        padding: 10px;
        max-width: 500px;
        margin: 0 auto;
        border: 1px solid #ccc;
        border-radius: 10px;
        background: #f2f2f2;
    }

    .noticia-video img.logo-principal {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .btn-ver-video {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background 0.3s ease;
    }

    .btn-ver-video:hover {
        filter: brightness(1.1);
    }

    .logo-plataforma {
        width: 22px;
        height: 22px;
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
                @if(strlen($tx['url']) == 0)
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
                @endif
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
                @if(strlen($tx['url']) > 0)
                    @php
                        $videoData = VideoHelper::getPlatformData($tx['url']);
                    @endphp
                    <div class="noticia-video">
                        <img src="/files-img/logo-emsaba-nuevo.png" alt="Logo Empresa" class="logo-principal">

                        <button class="btn-ver-video" style="background: {{ $videoData['color'] }};"
                            onclick="verVideo('{{ $tx['url'] }}')" title="Ver Vídeo de {{getNameInstitucion()}}">
                        <img src="{{ $videoData['logo'] }}" alt="{{ $videoData['name'] }}" class="logo-plataforma">
                            Ver video en {{ $videoData['name'] }}
                        </button>
                    </div>
                @endif
                @if(strlen($tx['url']) < -1)
                    @if (Str::contains($tx['url'], 'facebook.com'))
                        <div class="video-container">
                            <iframe src="https://www.facebook.com/plugins/video.php?href={{ urlencode($tx['url']) }}&width=300&show_text=false&height=533&appId" 
                                width="300" height="533" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" 
                                allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share" allowFullScreen="true">
                            </iframe>
                        </div>

                    @elseif (Str::contains($tx['url'], 'youtube.com') || Str::contains($tx['url'], 'youtu.be'))
                        @php
                            // Extraer ID de video YouTube
                            preg_match('/(youtu\.be\/|v=)([^&]+)/', $tx['url'], $matches);
                            $youtubeId = $matches[2] ?? null;
                        @endphp
                        @if($youtubeId)
                            <div class="video-container">
                                <iframe
                                    src="https://www.youtube.com/embed/{{ $youtubeId }}"
                                    width="560" height="315"
                                    frameborder="0" allowfullscreen
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share">
                                </iframe>
                            </div>
                        @endif

                    @elseif (Str::contains($tx['url'], 'instagram.com'))
                        <div class="video-container">
                            <iframe
                                src="https://www.instagram.com/p/{{ basename(parse_url($tx['url'], PHP_URL_PATH)) }}/embed"
                                width="400" height="480"
                                frameborder="0" allowfullscreen
                                allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share">
                            </iframe>
                        </div>

                    @elseif (Str::contains($tx['url'], 'tiktok.com'))
                        <div class="video-container">
                            <blockquote class="tiktok-embed" cite="{{ $tx['url'] }}" data-video-id="{{ basename(parse_url($tx['url'], PHP_URL_PATH)) }}" style="max-width: 605px;min-width: 325px;">
                                <section></section>
                            </blockquote>
                            <script async src="https://www.tiktok.com/embed.js"></script>
                        </div>
                    @endif
                @endif
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

    const video = document.getElementById('miVideo');

    video.addEventListener('loadedmetadata', () => {
        const width = video.videoWidth;
        const height = video.videoHeight;

        if (width > height) {
            video.classList.add('horizontal');
        } else {
            video.classList.add('vertical');
        }
    });
</script>
@endsection