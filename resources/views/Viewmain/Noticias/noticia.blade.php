@extends('Viewmain.Layouts.app')

@section('css')
<link href="{{asset('assets/viewmain/css/formatnews.css')}}" rel="stylesheet">
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
<div class="container-fluid blog py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">Noticias</h5>
        </div>
        <div class="row g-4 justify-content-center tarj-content" style="display:none;">
            @if(count($noticias)>0)
            @foreach ($noticias as $nt)
            <div class="col-lg-4 col-md-6 tarj">
                <div class="blog-item">
                    <div class="blog-img">
                        <div class="blog-img-inner" style="height: 225px;">
                            @if (strlen($nt['url']) == 0)
                            <img class="img-fluid w-100 rounded-top" src="/noticias-img/{{$nt['imagen']}}" alt="Image" style="object-fit: cover;height: 100%;">
                            @else
                            <img class="img-fluid w-100 rounded-top" src="/files-img/logo-emsaba-nuevo.png" alt="Image" style="object-fit: cover;height: 100%;">
                            @endif
                        </div>
                    </div>
                    <div class="blog-content border border-top-0 rounded-bottom p-4">
                        <p class="mb-3">{{$nt['lugar']}} | <small class="flex-fill text-center py-2"><i class="fa fa-calendar-alt text-primary me-2"></i>{{$nt['fecha']}}</small></p>
                        <a href="javascrip:void(0)" class="h4">{{$nt['titulo']}}</a>
                        <p class="my-3 text-justify">{{$nt['descripcion']}}</p>
                        <div class="divRowCardNews">
                            <a onclick="viewnew('{{encriptarNumero($nt['id'])}}')" class="btn btn-primary rounded-pill py-2 px-4">Leer más</a>
                            <div class="divIconWater">
                                <img src="assets/viewmain/img/web/gota_agua.svg" alt="EMSABA EP">
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
                    <span class="mensaje-noticia mt-4 mb-4">No hay <strong>noticias</strong> disponibles por el momento...</span>
                </div>
            </div> 
            @endif
            <!--<div class="col-lg-4 col-md-6 tarj">
                <div class="blog-item">
                    <div class="blog-img">
                        <div class="blog-img-inner">
                            <img class="img-fluid w-100 rounded-top" src="'assets/viewmain/img/blog-1.jpg'" alt="Image">
                            <div class="blog-icon">
                                <a href="#" class="my-auto"><i class="fas fa-link fa-2x text-white"></i></a>
                            </div>
                        </div>
                        <div class="blog-info d-flex align-items-center border border-start-0 border-end-0">
                            <small class="flex-fill text-center border-end py-2"><i class="fa fa-calendar-alt text-primary me-2"></i>28 Jan 2050</small>
                            <a href="#" class="btn-hover flex-fill text-center text-white border-end py-2"><i class="fa fa-thumbs-up text-primary me-2"></i>1.7K</a>
                            <a href="#" class="btn-hover flex-fill text-center text-white py-2"><i class="fa fa-comments text-primary me-2"></i>1K</a>
                        </div>
                    </div>
                    <div class="blog-content border border-top-0 rounded-bottom p-4">
                        <p class="mb-3">Posted By: Royal Hamblin </p>
                        <a href="#" class="h4">Adventures Trip</a>
                        <p class="my-3">Tempor erat elitr rebum at clita. Diam dolor diam ipsum sit diam amet diam eos</p>
                        <a href="#" class="btn btn-primary rounded-pill py-2 px-4">Read More</a>
                    </div>
                </div>
            </div>-->
        </div>
        @if(count($noticias)>0)
        <nav aria-label="Page navigation example" class="mt-40 mb-0">
            <ul class="pagination justify-content-center">
            </ul>
        </nav>
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
<script src="{{asset('assets/viewmain/js/noticias.js')}}"></script>
    <script>
        var contarlistado=0;

        function getPageList(totalPages, page, maxLength){
            function range(start, end){
                return Array.from(Array(end - start + 1), (_,i) => i + start);
            }

            var sideWidth= maxLength < 9 ? 1 :2;
            var leftWidth = (maxLength - sideWidth * 2 -3 ) >> 1;
            var rightWidth = (maxLength - sideWidth * 2 -3 ) >> 1;

            if(totalPages <= maxLength){
                return range(1, totalPages)
            }

            if(page <= maxLength - sideWidth - 1 - rightWidth){
                return range(1, maxLength - sideWidth -1).concat(0, range(totalPages - sideWidth + 1, totalPages));
            }

            if(page >= totalPages - sideWidth -1 - rightWidth){
                return range(1, sideWidth).concat(0, range(totalPages - sideWidth - 1 - rightWidth - leftWidth, totalPages));
            }

            return range(1, sideWidth).concat(0, range(page - leftWidth, page + rightWidth), 0, range(totalPages - sideWidth + 1, totalPages));
        }

        $(function(){
            setTimeout(() => {
                var numberOfItems = $(".tarj-content .tarj").length;
                var limitPerPage= 9; // numbers of cards items visible per a page
                var totalPages = Math.ceil(numberOfItems / limitPerPage);
                var paginationSize= 7; //number page elements visible in the pagination
                var currentPage;

                //console.log(numberOfItems);

                function showPage(whichPage){
                    if(whichPage < 1 || whichPage > totalPages) return false;

                    currentPage= whichPage;

                    $(".tarj-content .tarj").hide().slice((currentPage - 1) * limitPerPage, currentPage * limitPerPage).show();

                    $(".pagination li").slice(1, -1).remove();

                    getPageList(totalPages, currentPage, paginationSize).forEach(item => {
                        $("<li>").addClass("page-item").addClass(item ? "current-page" : "dots")
                        .toggleClass("active", item===currentPage).append($("<a>").addClass("page-link")
                        .attr({href: "javascript:void(0)"}).text(item || "...")).insertBefore(".next-page");
                    });

                    $(".previous-page").toggleClass("disable",currentPage === 1);
                    $(".next-page").toggleClass("disable",currentPage === totalPages);
                    return true;
                }

                $(".pagination").append(
                    $("<li>").addClass("page-item").addClass("previous-page").append($("<a>").addClass("page-link").attr({href: "javascript:void(0)"}).text("Ant.")),
                    $("<li>").addClass("page-item").addClass("next-page").append($("<a>").addClass("page-link").attr({href: "javascript:void(0)"}).text("Sig."))
                );
                
                $(".tarj-content").show();
                showPage(1);

                $(document).on("click",".pagination li.current-page:not(.active)", function(){
                    return showPage(+$(this).text());
                });

                $(".next-page").on("click", function(){
                    if(contarlistado>9){
                        return showPage(currentPage + 1);
                    }
                })

                $(".previous-page").on("click", function(){
                    if(contarlistado>9){
                        return showPage(currentPage - 1);
                    }
                })

            },2300);
        });
    </script>
@endsection