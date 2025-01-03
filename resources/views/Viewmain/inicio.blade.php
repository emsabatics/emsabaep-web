@extends('Viewmain.Layouts.app')

@section('css')
<link href="{{asset('assets/viewmain/css/cardstyle.css')}}" rel="stylesheet">
<link href="{{asset('assets/administrador/css/no-data-load.css')}}" rel="stylesheet">
<link href="{{asset('assets/viewmain/css/servicestyle.css')}}" rel="stylesheet">
<link href="{{asset('assets/viewmain/css/formatnews.css')}}" rel="stylesheet">
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

@php
 $contadorC=0;
 $contadorR=0;   
@endphp

@section('carousel-home')
<div class="carousel-header">
    <div id="carouselId" class="carousel slide" data-bs-ride="carousel">
        <ol class="carousel-indicators">
            @foreach ($banner as $item)
            @if ($loop->index=='0' || $loop->index==0)
                <li data-bs-target="#carouselId" data-bs-slide-to="{{$loop->index}}" class="active"></li>
            @else
            <li data-bs-target="#carouselId" data-bs-slide-to="{{$loop->index}}"></li>
            @endif
            @endforeach
        </ol>
        <div class="carousel-inner" role="listbox">
            @foreach ($banner as $item)
            @if ($loop->index=='0' || $loop->index==0)
            <div class="carousel-item active">
                <img src="/banner-img/{{$item->imagen}}" class="img-fluid" alt="Image">
                <div class="carousel-caption">
                    <!--<div class="p-3" style="max-width: 900px;">
                        <p class="mb-5 fs-5">Empresa Pública Municipal de Saneamiento Ambiental de Babahoyo 
                        </p>
                    </div>-->
                </div>
            </div>
            @else
            <div class="carousel-item">
                <img src="/banner-img/{{$item->imagen}}" class="img-fluid" alt="Image">
                <div class="carousel-caption"></div>
            </div>
            @endif
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselId" data-bs-slide="prev">
            <span class="carousel-control-prev-icon btn bg-primary" aria-hidden="false"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselId" data-bs-slide="next">
            <span class="carousel-control-next-icon btn bg-primary" aria-hidden="false"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
@endsection

@section('home')
<!-- About Start -->
<div class="container-fluid about py-5">
    <div class="container py-5">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <div class="h-100" style="border: 50px solid; border-color: transparent #13357B transparent #13357B;">
                    <img src="{{asset('assets/viewmain/img/web/interfaz-grafica-principal.png')}}" class="img-fluid w-100 h-100" alt="">
                </div>
            </div>
            <div class="col-lg-7" style="background: linear-gradient(rgba(255, 255, 255, .8), rgba(255, 255, 255, .8)), url(/assets/viewmain/img/web/water-sobre-nosotros.png);">
                <h5 class="section-about-title pe-3">Sobre Nosotros</h5>
                <h1 class="mb-4">Bienvenido a <span class="text-primary">EMSABA EP</span></h1>
                @foreach ($about as $ab)
                <p class="mb-4 text-justify">{{$ab}}</p>
                @endforeach
            </div>
        </div>
    </div>
</div>
<!-- About End -->

<!-- Services Start -->
<div class="container-fluid bg-light blog py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Servicios</h5>
            <h1 class="mb-0">Nuestros Servicios</h1>
        </div>
        <div class="row g-4 tarj-content">
            @if(count($servicios)>0)
            @foreach ($servicios as $nt)
            <div class="tarj justify-content-center">
                <div class="blog-item animate">
                    <div class="blog-img">
                        <div class="blog-img-inner">
                            <img class="img-fluid w-100 rounded-top" src="/servicios-img/{{$nt->imagen}}" alt="Image">
                        </div>
                    </div>
                    <div class="blog-content border border-top-0 rounded-bottom p-4" style="background: #fff;">
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
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
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
        </div>
    </div>
</div>

<!-- Boletines Start -->
<div class="container-fluid destination py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Boletines</h5>
        </div>
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
                        @if(count($boletines)>0)
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
                                    @if($contadorC < 4)
                                    <div class="col-lg-3">
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
                                    @php
                                        $contadorC++;
                                    @endphp
                                    @endif
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
</div>

<!-- Noticias Start -->
<div class="container-fluid testimonial blog py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Noticias</h5>
        </div>
        @if(count($noticias) > 0)
            @if(count($noticias) == 1)
            <div class="row">
                <div class="col-lg-4 col-12">
                    @foreach ($noticias as $not)
                    <div class="testimonial-item rounded pb-4">
                        <div class="blog-item">
                            <div class="blog-img">
                                <div class="blog-img-inner" style="height: 225px;">
                                    <img class="img-fluid w-100 rounded-top" src="/noticias-img/{{$not['imagen']}}" alt="Image" style="object-fit: cover;height: 100%;">
                                    <div class="blog-icon">
                                        <a onclick="viewnew({{$not['id']}})" class="my-auto"><i class="fas fa-link fa-2x text-white"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="blog-content border border-top-0 rounded-bottom p-4">
                                <p class="mb-3">{{$not['lugar']}} | <small class="flex-fill text-center py-2"><i class="fa fa-calendar-alt text-primary me-2"></i>{{$not['fecha']}}</small></p>
                                <a onclick="viewnew({{$not['id']}})" class="h4">{{$not['titulo']}}</a>
                                <p class="my-3 text-justify">{{$not['descripcion']}}</p>
                                <div class="divRowCardNews">
                                    <a onclick="viewnew({{$not['id']}})" class="btn btn-primary rounded-pill py-2 px-4">Leer más</a>
                                    <div class="divIconWater">
                                        <img src="assets/viewmain/img/web/gota_agua.svg" alt="EMSABA EP">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @elseif (count($noticias) == 2)
            <div class="row">
                @foreach ($noticias as $not)
                <div class="col-lg-4 col-12">
                    <div class="testimonial-item rounded pb-4">
                        <div class="blog-item">
                            <div class="blog-img">
                                <div class="blog-img-inner" style="height: 225px;">
                                    <img class="img-fluid w-100 rounded-top" src="/noticias-img/{{$not['imagen']}}" alt="Image" style="object-fit: cover;height: 100%;">
                                    <div class="blog-icon">
                                        <a onclick="viewnew({{$not['id']}})" class="my-auto"><i class="fas fa-link fa-2x text-white"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="blog-content border border-top-0 rounded-bottom p-4">
                                <p class="mb-3">{{$not['lugar']}} | <small class="flex-fill text-center py-2"><i class="fa fa-calendar-alt text-primary me-2"></i>{{$not['fecha']}}</small></p>
                                <a onclick="viewnew({{$not['id']}})" class="h4">{{$not['titulo']}}</a>
                                <p class="my-3 text-justify">{{$not['descripcion']}}</p>
                                <div class="divRowCardNews">
                                    <a onclick="viewnew({{$not['id']}})" class="btn btn-primary rounded-pill py-2 px-4">Leer más</a>
                                    <div class="divIconWater">
                                        <img src="assets/viewmain/img/web/gota_agua.svg" alt="EMSABA EP">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
                <div class="testimonial-carousel owl-carousel">
                    @foreach ($noticias as $not)
                    <div class="testimonial-item rounded pb-4">
                        <div class="blog-item">
                            <div class="blog-img">
                                <div class="blog-img-inner" style="height: 225px;">
                                    <img class="img-fluid w-100 rounded-top" src="/noticias-img/{{$not['imagen']}}" alt="Image" style="object-fit: cover;height: 100%;">
                                    <div class="blog-icon">
                                        <a onclick="viewnew({{$not['id']}})" class="my-auto"><i class="fas fa-link fa-2x text-white"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="blog-content border border-top-0 rounded-bottom p-4">
                                <p class="mb-3">{{$not['lugar']}} | <small class="flex-fill text-center py-2"><i class="fa fa-calendar-alt text-primary me-2"></i>{{$not['fecha']}}</small></p>
                                <a onclick="viewnew({{$not['id']}})" class="h4">{{$not['titulo']}}</a>
                                <p class="my-3 text-justify">{{$not['descripcion']}}</p>
                                <div class="divRowCardNews">
                                    <a onclick="viewnew({{$not['id']}})" class="btn btn-primary rounded-pill py-2 px-4">Leer más</a>
                                    <div class="divIconWater">
                                        <img src="assets/viewmain/img/web/gota_agua.svg" alt="EMSABA EP">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="row nonews">
                <div class="col-lg-12 no-data">
                    <div class="imgadvice">
                        <img src="{{asset('assets/administrador/img/icons/no-content-img.png')}}" alt="Construccion">
                    </div>
                    <span class="mensaje-noticia mt-4 mb-4">No hay <strong>noticias</strong> disponibles por el momento...</span>
                </div>
            </div> 
        @endif
    </div>
</div>
<!-- Noticias End -->
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
    function redirecttosubservice(id){
        var url= '/sub-services-detail/'+utf8_to_b64(id);
        window.location= url;
    }

    function utf8_to_b64( str ) {
        return window.btoa(unescape(encodeURIComponent( str )));
    }
</script>
@endsection