@extends('Viewmain.Layouts.app')

@section('css')
<link href="{{asset('assets/viewmain/css/bibliotecat.css')}}" rel="stylesheet">
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
            <h5 class="section-title px-3">
                BIBLIOTECA DE TRANSPARENCIA
            </h5>
        </div>
        <div class="row g-4 align-items-center">
            <div class="col-lg-12 main-container">
                <div class="cards">
                    <div class="card card-emsaba">
                        <div class="card__icon"><i class="fas fa-tint"></i></div>
                        <p class="card__exit"><i class="fas fa-times"></i></p>
                        <h2 class="card__title">Biblioteca Administrativa</h2>
                        <p class="card__apply">
                          <a class="card__link" href="{{url('transparencia/doc-administrativa')}}">Ver más <i class="fas fa-arrow-right ml-4"></i></a>
                        </p>
                    </div>
                    <div class="card card-emsaba">
                        <div class="card__icon"><i class="fas fa-tint"></i></div>
                        <p class="card__exit"><i class="fas fa-times"></i></p>
                        <h2 class="card__title">Biblioteca Financiera</h2>
                        <p class="card__apply">
                          <a class="card__link" href="{{url('transparencia/doc-financiera')}}">Ver más <i class="fas fa-arrow-right ml-4"></i></a>
                        </p>
                    </div>
                    <div class="card card-emsaba">
                        <div class="card__icon"><i class="fas fa-tint"></i></div>
                        <p class="card__exit"><i class="fas fa-times"></i></p>
                        <h2 class="card__title">Biblioteca Operativa</h2>
                        <p class="card__apply">
                          <a class="card__link" href="{{url('transparencia/doc-operativa')}}">Ver más <i class="fas fa-arrow-right ml-4"></i></a>
                        </p>
                    </div>
                    <div class="card card-emsaba">
                        <div class="card__icon"><i class="fas fa-tint"></i></div>
                        <p class="card__exit"><i class="fas fa-times"></i></p>
                        <h2 class="card__title">Biblioteca Legal</h2>
                        <p class="card__apply">
                          <a class="card__link" href="{{url('transparencia/reglamentos')}}">Ver más <i class="fas fa-arrow-right ml-4"></i></a>
                        </p>
                    </div>
                    <div class="card card-emsaba">
                        <div class="card__icon"><i class="fas fa-tint"></i></div>
                        <p class="card__exit"><i class="fas fa-times"></i></p>
                        <h2 class="card__title">Biblioteca Laboral</h2>
                        <p class="card__apply">
                          <a class="card__link" href="{{url('transparencia/doc-laboral')}}">Ver más <i class="fas fa-arrow-right ml-4"></i></a>
                        </p>
                    </div>
                    <!--<div class="card card-emsaba">
                        <div class="card__icon"><i class="fas fa-tint"></i></div>
                        <p class="card__exit"><i class="fas fa-times"></i></p>
                        <h2 class="card__title">Biblioteca de Trámite</h2>
                        <p class="card__apply">
                          <a class="card__link" href="#">Ver más... <i class="fas fa-arrow-right"></i></a>
                        </p>
                    </div>-->
                    <div class="card card-emsaba">
                        <div class="card__icon"><i class="fas fa-tint"></i></div>
                        <p class="card__exit"><i class="fas fa-times"></i></p>
                        <h2 class="card__title">Biblioteca de Rendición de Cuentas</h2>
                        <p class="card__apply">
                          <a class="card__link" href="{{url('transparencia/rendicion-cuenta')}}">Ver más <i class="fas fa-arrow-right ml-4"></i></a>
                        </p>
                    </div>
                    <div class="card card-emsaba">
                        <div class="card__icon"><i class="fas fa-tint"></i></div>
                        <p class="card__exit"><i class="fas fa-times"></i></p>
                        <h2 class="card__title">Biblioteca de LOTAIP</h2>
                        <p class="card__apply">
                          <a class="card__link" href="{{url('transp-lotaip')}}">Ver más <i class="fas fa-arrow-right ml-4"></i></a>
                        </p>
                    </div>
                    <div class="card card-emsaba">
                        <div class="card__icon"><i class="fas fa-tint"></i></div>
                        <p class="card__exit"><i class="fas fa-times"></i></p>
                        <h2 class="card__title">Biblioteca de LOTAIP 2.0</h2>
                        <p class="card__apply">
                          <a class="card__link" href="{{url('transp-lotaip2')}}">Ver más <i class="fas fa-arrow-right ml-4"></i></a>
                        </p>
                    </div>
                    <div class="card card-emsaba">
                        <div class="card__icon"><i class="fas fa-tint"></i></div>
                        <p class="card__exit"><i class="fas fa-times"></i></p>
                        <h2 class="card__title">Auditoría Interna</h2>
                        <p class="card__apply">
                          <a class="card__link" href="{{url('transparencia/auditoria')}}">Ver más <i class="fas fa-arrow-right ml-4"></i></a>
                        </p>
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