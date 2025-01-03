@extends('Viewmain.Layouts.app')

@section('css')
<link href="{{asset('assets/viewmain/css/servicestyle.css')}}" rel="stylesheet">
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

<!-- Blog Start -->
<div class="container-fluid destination py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Boletines</h5>
        </div>
        @if(count($boletines)>0)
        <div class="row g-4 justify-content-center">
            <div class="tab-class text-center">
                <ul class="nav nav-pills d-inline-flex justify-content-center mb-5">
                    <li class="nav-item">
                        <a class="d-flex mx-3 py-2 border border-primary bg-light rounded-pill active" data-bs-toggle="pill" href="#tab-all">
                            <span class="text-dark" style="width: 150px;">Todo</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex py-2 mx-3 border border-primary bg-light rounded-pill" data-bs-toggle="pill" href="#tab-com">
                            <span class="text-dark" style="width: 150px;">Comunicados</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex mx-3 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill" href="#tab-advice">
                            <span class="text-dark" style="width: 150px;">Avisos</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="d-flex mx-3 py-2 border border-primary bg-light rounded-pill" data-bs-toggle="pill" href="#tab-days">
                            <span class="text-dark" style="width: 150px;">Días Cívicos</span>
                        </a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div id="tab-all" class="tab-pane fade show p-0 active">
                        <div class="row g-4">
                            @if($ccuadrado==0 && $crectangulo>=1)
                                <div class="col-xl-12">
                                    <div class="row g-4">
                                    @foreach ($boletines as $bns)
                                    @if ($bns->formaimg == 'rectangular')
                                    <div class="col-lg-6">
                                    <div class="destination-img h-100">
                                        <img class="img-fluid rounded w-100 h-100" src="/eventos-img/{{$bns->imagen}}" style="object-fit: cover; min-height: 300px;" alt="">
                                        <div class="destination-overlay p-4">
                                            <h4 class="text-white mb-2 mt-3">{{$bns->titulo}}</h4>
                                        </div>
                                        <div class="search-icon">
                                            <a href="/eventos-img/{{$bns->imagen}}" data-lightbox="destination-4"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    </div>
                                </div>
                            @elseif($ccuadrado>=1 && $crectangulo==0)
                                <div class="col-lg-12">
                                    <div class="row g-4">
                                        @foreach ($boletines as $bns)
                                        @if ($bns->formaimg == 'cuadrado')
                                        <div class="col-lg-6">
                                            <div class="destination-img">
                                                <img class="img-fluid rounded w-100" src="/eventos-img/{{$bns->imagen}}" alt="">
                                                <div class="destination-overlay p-4">
                                                    <h4 class="text-white mb-2 mt-3">{{$bns->titulo}}</h4>
                                                </div>
                                                <div class="search-icon">
                                                    <a href="/eventos-img/{{$bns->imagen}}" data-lightbox="destination-4"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                            @elseif($ccuadrado>=1 && $crectangulo>=1)
                                <div class="col-lg-6">
                                    <div class="row g-4">
                                        @foreach ($boletines as $bns)
                                        @if ($bns->formaimg == 'cuadrado')
                                        <div class="col-lg-6">
                                            <div class="destination-img">
                                                <img class="img-fluid rounded w-100" src="/eventos-img/{{$bns->imagen}}" alt="">
                                                <div class="destination-overlay p-4">
                                                    <h4 class="text-white mb-2 mt-3">{{$bns->titulo}}</h4>
                                                </div>
                                                <div class="search-icon">
                                                    <a href="/eventos-img/{{$bns->imagen}}" data-lightbox="destination-4"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-xl-6">
                                    <div class="row g-4">
                                    @foreach ($boletines as $bns)
                                    @if ($bns->formaimg == 'rectangular')
                                    <div class="col-lg-6">
                                    <div class="destination-img h-100">
                                        <img class="img-fluid rounded w-100 h-100" src="/eventos-img/{{$bns->imagen}}" style="object-fit: cover; min-height: 300px;" alt="">
                                        <div class="destination-overlay p-4">
                                            <h4 class="text-white mb-2 mt-3">{{$bns->titulo}}</h4>
                                        </div>
                                        <div class="search-icon">
                                            <a href="/eventos-img/{{$bns->imagen}}" data-lightbox="destination-4"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                        </div>
                                    </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div id="tab-com" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            @if(count($comunicado)>0)
                            @foreach ($comunicado as $bns)
                                @if ($bns->tipo == 'comunicado')
                                    <div class="col-lg-3">
                                        <div class="destination-img">
                                            <img class="img-fluid rounded w-100" src="/eventos-img/{{$bns->imagen}}" alt="">
                                            <div class="destination-overlay p-4">
                                                <h4 class="text-white mb-2 mt-3">{{$bns->titulo}}</h4>
                                            </div>
                                            <div class="search-icon">
                                                <a href="/eventos-img/{{$bns->imagen}}" data-lightbox="destination-5"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            @else
                                <div class="row nonews">
                                    <div class="col-lg-12 no-data">
                                        <div class="imgadvice">
                                            <img src="{{asset('assets/administrador/img/icons/no-content-img.png')}}" alt="Construccion">
                                        </div>
                                        <span class="mensaje-noticia mt-4 mb-4">No hay <strong>imágenes</strong> disponibles por el momento...</span>
                                    </div>
                                </div>      
                            @endif
                        </div>
                    </div>
                    <div id="tab-advice" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            @if(count($aviso)>0)
                            @foreach ($aviso as $bns)
                                @if ($bns->tipo == 'aviso')
                                    <div class="col-lg-3">
                                        <div class="destination-img">
                                            <img class="img-fluid rounded w-100" src="/eventos-img/{{$bns->imagen}}" alt="">
                                            <div class="destination-overlay p-4">
                                                <h4 class="text-white mb-2 mt-3">{{$bns->titulo}}</h4>
                                            </div>
                                            <div class="search-icon">
                                                <a href="/eventos-img/{{$bns->imagen}}" data-lightbox="destination-5"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            @else
                                <div class="row nonews">
                                    <div class="col-lg-12 no-data">
                                        <div class="imgadvice">
                                            <img src="{{asset('assets/administrador/img/icons/no-content-img.png')}}" alt="Construccion">
                                        </div>
                                        <span class="mensaje-noticia mt-4 mb-4">No hay <strong>imágenes</strong> disponibles por el momento...</span>
                                    </div>
                                </div>      
                            @endif
                        </div>
                    </div>
                    <div id="tab-days" class="tab-pane fade show p-0">
                        <div class="row g-4">
                            @if(count($diacivico)>0)
                            @foreach ($diacivico as $bns)
                                @if ($bns->tipo == 'diacivico')
                                    <div class="col-lg-3">
                                        <div class="destination-img">
                                            <img class="img-fluid rounded w-100" src="/eventos-img/{{$bns->imagen}}" alt="">
                                            <div class="destination-overlay p-4">
                                                <h4 class="text-white mb-2 mt-3">{{$bns->titulo}}</h4>
                                            </div>
                                            <div class="search-icon">
                                                <a href="/eventos-img/{{$bns->imagen}}" data-lightbox="destination-5"><i class="fa fa-plus-square fa-1x btn btn-light btn-lg-square text-primary"></i></a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            @else
                                <div class="row nonews">
                                    <div class="col-lg-12 no-data">
                                        <div class="imgadvice">
                                            <img src="{{asset('assets/administrador/img/icons/no-content-img.png')}}" alt="Construccion">
                                        </div>
                                        <span class="mensaje-noticia mt-4 mb-4">No hay <strong>imágenes</strong> disponibles por el momento...</span>
                                    </div>
                                </div>      
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>  
        @else
        <div class="row nonews">
            <div class="col-lg-12 no-data">
                <div class="imgadvice">
                    <img src="{{asset('assets/administrador/img/icons/no-content-img.png')}}" alt="Construccion">
                </div>
                <span class="mensaje-noticia mt-4 mb-4">No hay <strong>boletines</strong> disponibles por el momento...</span>
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
<script src="{{asset('assets/viewmain/js/noticias.js')}}"></script>
<script>
    /*$(document).ready(function(){
        var element= document.querySelector('.tarj-content');
        setTimeout(() => {
            element.style.display='block';
        }, 500);
    });*/
</script>
@endsection