@extends('Viewmain.Layouts.app')

@section('css')
<link href="{{asset('assets/viewmain/css/bibliotecat.css')}}" rel="stylesheet">
<link href="{{asset('assets/viewmain/css/stylebutton.css')}}" rel="stylesheet">
<link href="https://vjs.zencdn.net/8.16.1/video-js.css" rel="stylesheet" />
<style>
    .cardvideo{
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        justify-content: center;
    }

    .cardinvideo{
        margin: 10px;
        padding: 20px;
        width: 785px;
        min-height: 310px;
        border-radius: 10px;
        box-shadow: 0px 6px 10px rgba(0, 0, 0, 0.25);
        transition: all 0.2s;
    }

    .divTitle{
        display: flex;
        flex-direction: row;
        justify-content: center;
    }

    .titulorc{
        position: relative;
        display: inline-block;
        text-transform: uppercase;
        color: var(--bs-primary);
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

<!-- Vision Start -->
<div class="container-fluid contact bg-light py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            @foreach ($anio as $y)
            <h5 class="section-title px-3">
                BIBLIOTECA DE TRANSPARENCIA - RENDICIÓN DE CUENTAS {{$y->anio}}
            </h5>
            @endforeach
        </div>
        <div class="row g-4 bg-light align-items-center">
            <div class="col-lg-12 divTitle">
                @foreach ($archivorc as $item)
                <h5 class="titulorc px-3">{{$item->titulo}}</h5>
                @endforeach
            </div>
        </div>
        <div class="row g-4 align-items-center">
            <div class="col-lg-12 main-container">
                <div class="cardvideo">
                    @foreach ($archivorc as $item)
                    @php
                        $titulo= $item->titulo;
                    @endphp
                    <div class="cardinvideo">
                        <video
                            id="my-video"
                            class="video-js"
                            controls
                            preload="auto"
                            width="740"
                            height="364"
                            data-setup="{}"
                        >
                            <source src="/doc-rendicion-cuentas/{{$item->archivo}}" type="video/mp4" />
                            <p class="vjs-no-js">
                                Para ver este video, habilite JavaScript y considere actualizar a un
                                navegador web que admita vídeo HTML5
                            </p>
                        </video>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="row g-4 bg-light align-items-center">
            <div class="co-lg-12">
                @foreach ($anio as $y)
                <div class="btn-group">
                    <button class="btn-p btn-intermediate" onclick="comeback_playrc({{$y->id}})"><i class="fas fa-arrow-left mr-4"></i> Regresar</button>
                </div>
                @endforeach
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
<script src="https://vjs.zencdn.net/8.16.1/video.min.js"></script>
<script src="{{asset('assets/viewmain/js/transparencia.js')}}"></script>
<script>
    /*videojs.addLanguage('es', {
       "Play": "Reproducir",
       "Play Video": "Reproducir Vídeo",
       "Pause": "Pausa",
       "Current Time": "Tiempo reproducido",
       "Duration": "Duración total",
       "Remaining Time": "Tiempo restante",
       "Stream Type": "Tipo de secuencia",
       "LIVE": "DIRECTO",
       "Loaded": "Cargado",
       "Progress": "Progreso",
       "Fullscreen": "Pantalla completa",
       "Non-Fullscreen": "Pantalla no completa",
       "Mute": "Silenciar",
       "Unmute": "No silenciado",
       "Playback Rate": "Velocidad de reproducción",
       "Subtitles": "Subtítulos",
       "subtitles off": "Subtítulos desactivados",
       "Captions": "Subtítulos especiales",
       "captions off": "Subtítulos especiales desactivados",
       "Chapters": "Capítulos"
    })*/
 </script>
<script>
    var titulo= '<?php  echo $titulo; ?>';
    var player = videojs('my-video');

    player.on('pause', function() {

    // Modals are temporary by default. They dispose themselves when they are
    // closed; so, we can create a new one each time the player is paused and
    // not worry about leaving extra nodes hanging around.
    var modal = player.createModal(titulo);

    // When the modal closes, resume playback.
    modal.on('modalclose', function() {
        player.play();
    });
    });
</script>
@endsection