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
                    <div class="card card--water" onclick="gotosubservicemain({{$numservice}})">
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
                    @if($it->id == $idseleccion)
                    <div class="card card--water active" onclick="gotosubservice({{$it->id}})">
                    @else
                    <div class="card card--water" onclick="gotosubservice({{$it->id}})">
                    @endif
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
        @if(count($detallesubservice)>0)
            @foreach ($detallesubservice as $item)
            <div class="row gy-4">
                <div class="col-lg-3 col-12">
                    <div>
                        <img src="/servicios-img/{{$item->archivo}}" alt="{{$item->archivo}}" style="width: 100%;height: 100%;">
                    </div>
                </div>
                <div class="col-lg-9 col-12">
                    {!! $item->descripcion !!}
                </div>
            </div>
            @endforeach
        @else
            <div class="row nonews">
                <div class="col-lg-12 no-data">
                    <div class="imgadvice">
                        <img src="{{asset('assets/administrador/img/icons/no-content-img.png')}}" alt="Construccion">
                    </div>
                    <span class="mensaje-noticia mt-4 mb-4">No hay <strong>información</strong> disponible por el momento...</span>
                </div>
            </div>
        @endif
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

    function gotosubservicemain(id){
        var url= '/sub-services-detail/'+utf8_to_b64(id);
        window.location= url;
    }
    
    /*$(document).ready(function(){
        var element= document.querySelector('.tarj-content');
        setTimeout(() => {
            element.style.display='block';
        }, 500);
    });*/
</script>
@endsection