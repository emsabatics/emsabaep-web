@extends('Viewmain.Layouts.app')

@section('css')
<link href="{{asset('assets/viewmain/css/bibliotecat.css')}}" rel="stylesheet">
<link href="{{asset('assets/viewmain/css/stylebutton.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/viewmain/css/collapse.css')}}">
<link rel="stylesheet" href="{{asset('assets/viewmain/css/inner-list.css')}}">
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

<!-- Vision Start -->
<div class="container-fluid contact bg-light py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">
                BIBLIOTECA VIRTUAL
            </h5>
        </div>
        @if(count($bibliotecav)>0)
        <div class="row g-4 align-items-center mb-2">
            <div class="col-2"></div>
            <div class="col-8">
                @foreach ($bibliotecav as $cat)
                <div id="accordion" class="myaccordion">
                    <div class="card mt-2">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                                <button class="d-flex align-items-center justify-content-between btn btn-link" data-toggle="collapse" data-target="#collapse-{{$loop->index}}-{{$cat['idcat']}}" aria-expanded="true" aria-controls="collapse-{{$loop->index}}-{{$cat['idcat']}}">
                                    {{$cat['descripcioncat']}}
                                  <span class="fa-stack fa-sm">
                                    <i class="fas fa-circle fa-stack-2x"></i>
                                    <i class="fas fa-plus fa-stack-1x fa-inverse"></i>
                                  </span>
                                </button>
                            </h2>
                        </div>
                        <div id="collapse-{{$loop->index}}-{{$cat['idcat']}}" class="collapse" aria-labelledby="heading-{{$loop->index}}-{{$cat['idcat']}}" data-parent="#accordion">
                            <div class="card-body">
                                <ul class="accordionul">
                                    @if(count($cat['subcategoria'])>0)
                                        @foreach ($cat['subcategoria'] as $subcat)
                                        <li>
                                            <p class="nest">{{$subcat['descripcionsubcat']}}</p>
                                            @if(count($subcat['archivossubcat'])>0)
                                                <ul class="inner">
                                                    @foreach ($subcat['archivossubcat'] as $f)
                                                        <li>
                                                            <p class="nest">{{$f['titulo']}}</p>
                                                            <ul class="inner">
                                                                <div class="options-list">
                                                                    <button type="button" class="btn btn-primary btn-sm mr-3 btnlistop" title="Descargar" onclick="downloadfilevirtual({{$f['idfile']}}, 1)">
                                                                        <i class="fas fa-download mr-3"></i> Descargar
                                                                    </button>
                                                                </div>
                                                            </ul>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                            <ul class="inner">
                                                <div class="options-list">
                                                    <p class="nest">Sin Datos</p>
                                                </div>
                                            </ul>
                                            @endif
                                        </li>
                                        @endforeach
                                    @endif
                                    @if(count($cat['archivos'])>0)
                                        @foreach ($cat['archivos'] as $fc)
                                            <li>
                                                <p class="nest">{{$fc['titulo']}}</p>
                                                <ul class="inner">
                                                    <div class="options-list">
                                                        <button type="button" class="btn btn-primary btn-sm mr-3 btnlistop" title="Descargar" onclick="downloadfilevirtual({{$fc['idfile']}}, 2)">
                                                            <i class="fas fa-download mr-3"></i> Descargar
                                                        </button>
                                                    </div>
                                                </ul>
                                            </li>
                                        @endforeach
                                    @endif
                                    @if(count($cat['subcategoria'])==0 && count($cat['archivos'])==0)
                                    <li>
                                        <p class="nest">Sin Datos</p> 
                                    </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="col-2"></div>
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
<!-- jQuery -->
<script src="{{asset('assets/administrador/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/administrador/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/viewmain/js/transparencia.js')}}"></script>
<script src="{{asset('assets/administrador/js/inner-list.js')}}"></script>
@endsection