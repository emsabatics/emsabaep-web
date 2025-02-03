@extends('Viewmain.Layouts.app')

@section('css')
<link href="{{asset('assets/viewmain/css/servicestyle.css')}}" rel="stylesheet">
<link href="{{asset('assets/viewmain/css/stylesubservice_list.css')}}" rel="stylesheet">
<link href="{{asset('assets/viewmain/css/cardatencioncliente.css')}}" rel="stylesheet">
<link href="{{asset('assets/administrador/css/no-data-load.css')}}" rel="stylesheet">
<link href="{{asset('assets/viewmain/css/stylebutton.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/viewmain/css/collapse.css')}}">
<link rel="stylesheet" href="{{asset('assets/viewmain/css/inner-list.css')}}">
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

<!--SECCION TARJETAS DE SUBSERVICIOS-->
<div class="container-fluid blog pb-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Servicio de {{$servicio}}</h5>
        </div>
        @if(count($subservicio)>0)
        <div class="row g-4 justify-content-center">
            <div class="col-lg-12">
                <div class="cards">
                    <div class="card_list card--water" onclick="gotosubservicemain({{$numservice}})">
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
                    <div class="card_list card--water active" onclick="gotosubservice({{$it->id}})">
                    @else
                    <div class="card_list card--water" onclick="gotosubservice({{$it->id}})">
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
<!--FIN SECCION TARJETAS DE SUBSERVICIOS-->

<!--SECCION TARJETAS DE ATENCIÓN AL CLIENTE-->
<div class="container-fluid contact py-2">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-12">
                <p class="text-justify" style="color: #000;">En esta sección podrás encontrar todos los trámites que puedes realizar de manera presencial en nuestros locales de atención: </p>
            </div>
        </div>
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
                                            <span class="spanTitletext"><i class="far fa-clock mr-3"></i> Horario de Atención: </span>
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
<!--FIN SECCION TARJETAS DE ATENCIÓN AL CLIENTE-->

<!--SECCION LISTADO DE TRAMITES-->
<div class="container-fluid contact bg-light">
    <div class="container py-5">
        <div class="row g-4 align-items-center mb-2" style="padding: 0 50px;">
            <div class="col-12">
                @if(count($detallesubservice)>0)
                <div id="accordion" class="myaccordion">
                    @foreach ($detallesubservice as $item)
                    <div class="card mt-2">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="d-flex text-justify justify-content-between btn btn-link" data-toggle="collapse" data-target="#collapse-{{$loop->index}}" aria-expanded="true" aria-controls="collapse-{{$loop->index}}">
                                  <span class="pr-30">{{$item->titulo}}</span>
                                  <span class="fa-stack fa-sm">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fas fa-plus fa-stack-1x fa-inverse"></i>
                                  </span>
                                </button>
                            </h2>
                        </div>
                        <div id="collapse-{{$loop->index}}" class="collapse" aria-labelledby="heading-{{$loop->index}}" data-parent="#accordion">
                            <div class="card-body">
                                {!! $item->descripcion !!}
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
                        <span class="mensaje-noticia mt-4 mb-4">No hay <strong>información</strong> disponible por el momento...</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!--FIN SECCION LISTADO DE TRAMITES-->
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
<!-- jQuery -->
<script src="{{asset('assets/administrador/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/administrador/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/inner-list.js')}}"></script>
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