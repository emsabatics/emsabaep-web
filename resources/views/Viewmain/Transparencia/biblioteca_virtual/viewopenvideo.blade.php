@extends('Viewmain.Layouts.app')

@section('css')
<link href="{{asset('assets/viewmain/css/bibliotecat.css')}}" rel="stylesheet">
<link href="{{asset('assets/viewmain/css/stylebutton.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/viewmain/css/collapse.css')}}">
<link rel="stylesheet" href="{{asset('assets/viewmain/css/inner-list.css')}}">
<link rel="stylesheet" href="{{asset('assets/viewmain/css/gallerystyle.css')}}">
<link href="{{asset('assets/administrador/css/no-data-load.css')}}" rel="stylesheet">
<style>
    .divVideoModal{
        display: flex;
        flex-direction: row;
        justify-content: center;
    }

    .responsive-video {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover; /* opcional: recorta si se deforma */
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
<div class="container-fluid blog py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">
                BIBLIOTECA VIRTUAL - VIDEO
            </h5>
            <h1 class="mt-2 mb-4">{{ $namesubcat }}</h1>
        </div>
        @if(count($bibliotecagallery)>0)
        <div class="row g-4 justify-content-center mb-2">
            @foreach ($bibliotecagallery as $bg)
                <div class="col-lg-4 col-md-6">
                    <div class="blog-item">
                        <div class="blog-img destination-img">
                            <video id="video{{ $loop->index }}" preload="metadata" style="display: none;">
                              <source src="/videos-bibliotecavirtual/{{ $bg->archivo }}" type="video/mp4">
                            </video>
                            {{-- Aquí se mostrará la miniatura generada --}}
                            <img id="thumbnail{{ $loop->index }}" class="rounded shadow" width="400" alt="Miniatura del video">
                            <div class="search-icon">
                                <a href="javascript:void()" onclick="openmodalvideo({{ $loop->index }})"><i class="fas fa-play-circle fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                            </div>
                        </div>
                        <div class="blog-content border border-top-0 rounded-bottom p-4">
                            <p class="titulop text-justify mb-3">{{ $bg->titulo }}</p>
                            <p class="descripcionp text-justify my-3">{!! str_replace('//', '<br>', $bg->descripcion) !!}</p>
                            <div class="row">
                                <div class="col-12">
                                    <a href="javascript:void(0)" class="btn btn-primary rounded-pill py-2 px-4 btnlistop" onclick="openmodalvideo({{ $loop->index }})">
                                        <i class="fas fa-play-circle mr-2"></i>
                                        Ver Vídeo
                                    </a>
                                </div>
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
                        <img src="{{asset('assets/administrador/img/icons/info-no-encontrada.png')}}" alt="Construccion">
                    </div>
                    <span class="mensaje-noticia mt-4 mb-4">No hay <strong>información</strong> disponible por el momento...</span>
                </div>
            </div> 
        @endif
        <div class="row g-4 mt-4 align-items-center">
            <div class="co-lg-12">
                <div class="btn-group">
                    <button class="btn-p btn-intermediate" onclick="comeback_subcat_video()"><i class="fas fa-arrow-left mr-4"></i> Regresar</button>
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
<div class="modal fade" id="modal_info_video" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
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
                        <div class="divfileinfo divVideoModal" id="div_videoinfo">
                            <!--<img src="/assets/viewmain/img/web/seccion-nuestros-servicios-consulte-su-planilla.png" alt="Información">-->
                            <!--<video width="640" height="360" poster="assets/administrador/img/icons/camara-de-video.png" controls>
                                <source src="/assets/viewmain/video/video_emsaba.mp4" type="video/mp4">
                            </video>-->
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary mr-3" onclick="cerrarModalVideo()">Cerrar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@section('js')
<!-- jQuery -->
<script src="{{asset('assets/administrador/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/administrador/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/viewmain/js/transparencia.js')}}"></script>
<script src="{{asset('assets/administrador/js/inner-list.js')}}"></script>
<script>
    var idcat = @json($nidcat);
    var filevideo = @json($getonlyvideo);
    var longfiles = filevideo.length;
    var arrayVideo= [];

    // Recorremos los objetos
    filevideo.forEach(item => {
        arrayVideo.push(item.video);
    });

    $(document).ready(function(){
        for(var i=0; i< longfiles; i++){
            let video = document.getElementById('video'+i);
            let img = document.getElementById('thumbnail'+i);
            img.classList.add('imgportada', 'mt-2','mb-2');

            // Creamos un canvas temporal
            let canvas = document.createElement('canvas');
            let ctx = canvas.getContext('2d');

            // Espera a que se carguen los metadatos del video
            video.addEventListener('loadedmetadata', () => {
                // Definimos el tamaño de la miniatura
                canvas.width = video.videoWidth / 2;   // puedes ajustar el tamaño
                canvas.height = video.videoHeight / 2;

                // Saltamos al segundo 1 (puedes cambiarlo)
                video.currentTime = Math.min(1, video.duration / 2);
            });

            // Cuando ya está posicionado en ese segundo, dibujamos el frame
            video.addEventListener('seeked', () => {
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                // Convertimos el frame a base64 y lo ponemos en el <img>
                img.src = canvas.toDataURL('image/jpeg');
            });
        }
    });
</script>
@endsection