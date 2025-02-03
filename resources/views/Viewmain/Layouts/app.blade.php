<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <link rel="shortcut icon" type="image/png" href="{{asset('assets/viewmain/img/web/icono-de-sitio-web.png')}}">
        <title>{{getNameInstitucion()}}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="google" content="notranslate">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="" name="keywords">
        <meta content="" name="description">
        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500;600&family=Roboto&display=swap" rel="stylesheet"> 

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

        <!-- Libraries Stylesheet -->
        <link href="{{asset('assets/viewmain/lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/viewmain/lib/lightbox/css/lightbox.min.css')}}" rel="stylesheet">

        <link href="{{asset('assets/viewmain/css/personality.css')}}" rel="stylesheet">

        <!-- Customized Bootstrap Stylesheet -->
        <link href="{{asset('assets/viewmain/css/bootstrap.min.css')}}" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="{{asset('assets/viewmain/css/style.css')}}" rel="stylesheet">
        <link href="{{asset('assets/viewmain/css/loadGif.css')}}" rel="stylesheet">
        <link href="{{asset('assets/viewmain/css/botonflotante.css')}}" rel="stylesheet">
        @yield('css')
    </head>

    <body>
        <!-- Spinner Start -->
        <!--<div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>-->
        <!-- Spinner End -->

        <div class="modal fade modal-full" id="modalCargandoMain" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"
            data-backdrop="static" data-keyboard="false">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-body text-center">
                <div class="spinner">
                    <div class="circle one"></div>
                    <div class="circle two"></div>
                    <div class="circle three"></div>
                    <div class="c-toro">
                        <img src="{{asset('assets/viewmain/img/web/icono-de-sitio-web.png')}}" alt="">
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Topbar Start -->
        <div class="container-fluid bg-primary px-5 d-none d-lg-block">
            <div class="row gx-0">
                <div class="col-lg-8 text-center text-lg-start mb-2 mb-lg-0">
                    <div class="d-inline-flex align-items-center" style="height: 45px;">
                        @php
                            $socialmedia = getAllSocialMediaGeneral(); // Llamamos a la función del helper
                        @endphp
                        @foreach ($socialmedia as $sm)
                            @if ($sm->nombre=='Facebook')
                                <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-facebook-f fw-normal"></i></a>
                            @elseif ($sm->nombre=='X' || $sm->nombre=='Twitter')
                                <a class="btn btn-sm btn-outline-light btn-sm-square rounded-circle me-2" href="{{$sm->enlace}}" target="_blank"><i class="fa-brands fa-x-twitter fw-normal"></i></a>
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
                    </div>
                </div>
            </div>
        </div>
        <!-- Topbar End -->
        <!-- Navbar & Hero Start -->
        <div class="container-fluid position-relative p-0">
            <nav class="navbar navbar-expand-lg navbar-light px-4 px-lg-5 py-3 py-lg-0">
                <a href="{{url('/')}}" class="navbar-brand p-0">
                    <!--<h1 class="m-0"><i class="fas fa-tint me-3"></i>EMSABA EP</h1>-->
                    <!-- <img src="img/logo.png" alt="Logo"> -->
                    <img id="imgNavBar" src="{{asset('assets/viewmain/img/web/logo-emsaba-blanco.png')}}" alt="Login">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto py-0">
                        <a href="{{url('/')}}" class="nav-item nav-link {{setActive('/')}}">Inicio</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Sobre Nosotros</a>
                            <div class="dropdown-menu m-0">
                                <a href="{{url('aboutus')}}" class="dropdown-item">Sobre Nosotros</a>
                                <a href="{{url('structurus')}}" class="dropdown-item">Estructura</a>
                                <a href="{{url('historyus')}}" class="dropdown-item">Historia</a>
                                <a href="{{url('departamentous')}}" class="dropdown-item">Departamentos</a>
                            </div>
                        </div>
                        <a href="{{url('our-services')}}" class="nav-item nav-link">Servicios</a>
                        <a href="{{url('boletines')}}" class="nav-item nav-link">Boletines</a>
                        <a href="{{url('viewnewsemsaba')}}" class="nav-item nav-link {{setActive('viewnewsemsaba')}}">Noticias</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Transparencia</a>
                            <div class="dropdown-menu m-0">
                                <a href="{{url('transp-lotaip')}}" class="dropdown-item">LOTAIP</a>
                                <a href="{{url('transp-lotaip2')}}" class="dropdown-item">LOTAIP 2.0</a>
                                <a href="{{url('/transparencia/rendicion-cuenta')}}" class="dropdown-item">Rendición de Cuentas</a>
                                <a href="{{url('biblioteca-virtual')}}" class="dropdown-item">Biblioteca Virtual</a>
                                <a href="{{url('biblioteca-transparencia')}}" class="dropdown-item">Biblioteca de Transparencia</a>
                            </div>
                        </div>
                        <a href="{{url('contactus')}}" class="nav-item nav-link">Contactos</a>
                    </div>
                </div>
            </nav>

            @yield('carousel-home')
            <!-- Carousel Start -->
            
            <!-- Carousel End -->
        </div>


        @yield('home')

        <!-- BannerMain Start -->
        <div class="container-fluid subscribe py-5">
            <div class="container text-center py-5">
                <div class="mx-auto text-center" style="max-width: 900px;">
                    <h5 class="subscribe-title px-3">{{getNameInstitucion()}}</h5>
                    <p class="text-white mb-5">{{getFullNameInstitucion()}}
                    </p>
                </div>
            </div>
        </div>
        <!-- BannerMain End -->

        <!-- Footer Start -->
        <div class="container-fluid footer py-5">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-md-6 col-lg-6 col-xl-4">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="mb-4 text-white">Contactos</h4>
                            @yield('home_contact')
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-4">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="mb-4 text-white">{{getNameInstitucion()}}</h4>
                            <a href="{{url('aboutus')}}"><i class="fas fa-angle-right me-2"></i> Sobre Nosotros</a>
                            <a href="{{url('our-services')}}"><i class="fas fa-angle-right me-2"></i> Servicios</a>
                            <a href="{{url('boletines')}}"><i class="fas fa-angle-right me-2"></i> Boletines</a>
                            <a href="{{url('viewnewsemsaba')}}"><i class="fas fa-angle-right me-2"></i> Noticias</a>
                            <a href="{{url('biblioteca-transparencia')}}"><i class="fas fa-angle-right me-2"></i> Transparencia</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 col-xl-4">
                        <div class="footer-item d-flex flex-column">
                            <h4 class="mb-4 text-white">Redes Sociales</h4>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-share fa-2x text-white me-2"></i>
                                @foreach ($socialmedia as $sm)
                                    @if ($sm->nombre=='Facebook')
                                    <a class="btn-square btn btn-primary rounded-circle mx-1" href="{{$sm->enlace}}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                    @elseif ($sm->nombre=='X' || $sm->nombre=='Twitter')
                                    <a class="btn-square btn btn-primary rounded-circle mx-1" href="{{$sm->enlace}}" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->

        <!-- Back to Top -->
        <a href="#" class="btn btn-primary btn-primary-outline-0 btn-md-square back-to-top"><i class="fa fa-arrow-up"></i></a>   

        <!-- Botón flotante -->
        <button class="floating-btn" onclick="getSupportOnline()">
            <i class="fas fa-headset"></i>
        </button>
        
        <!-- JavaScript Libraries -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
        <!--<script src="assets/administrador/plugins/jquery/jquery.min.js')}}"></script>
        <script src="assets/administrador/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>-->
        <script src="{{asset('assets/viewmain/lib/easing/easing.min.js')}}"></script>
        <script src="{{asset('assets/viewmain/lib/waypoints/waypoints.min.js')}}"></script>
        <script src="{{asset('assets/viewmain/lib/owlcarousel/owl.carousel.min.js')}}"></script>
        <script src="{{asset('assets/viewmain/lib/lightbox/js/lightbox.min.js')}}"></script>
        <script>
            var imglogonormal='';
            var imglogoblanco='';

            var resultadoArray = {{Illuminate\Support\Js::from(getLogos())}};
            var resultadoNumber = {{Illuminate\Support\Js::from(getPhoneNumber())}};
            
            $(resultadoArray).each(function(i,v){
               if(v.archivo.includes('blanco')){
                imglogoblanco= v.archivo;
               }else{
                imglogonormal= v.archivo;
               }
            });

            function getSupportOnline(){
                //alert(resultadoNumber);
                var url= "https://api.whatsapp.com/send/?phone=593"+resultadoNumber+"&text&type=phone_number&app_absent=0";
                window.open(url, "_blank");
            }
        </script>
        

        <!-- Template Javascript -->
        <script src="{{asset('assets/viewmain/js/main.js')}}"></script>

        @yield('js')
        <script>
            /* if(anchoW < 600){
                logoImg.src = "/assets/administrador/img/inside_logo.png";
                }else{
                    logoImg.src = "/assets/viewmain/img/web/logo-emsaba-blanco.png";
                }*/
            //$('#modalCargandoMain').modal('show');
            /*var altoW= window.screen.height;
            var anchoW= window.screen.width
            var item = document.querySelector(".navbar");
            var repetir= 0;
            console.log(altoW, anchoW);
            if(anchoW < 600){
                var logo= document.querySelector('#imgNavBar');
                logo.src = "/assets/administrador/img/inside_logo.png";
            }else{
            
            }
            window.addEventListener("load",() => {
                window.addEventListener("scroll", () => {
                    let windowBottom = window.pageYOffset + window.innerHeight;
                    //console.log("WB: "+windowBottom);
                    if(altoW< 950){
                        if(windowBottom < 740){
                            var hasClase2 = item.classList.contains( 'shadow-sm' );
                            var logo= document.querySelector('#imgNavBar');
                            logo.src = "/assets/viewmain/img/web/logo-emsaba-blanco.png";
                            repetir= 0;
                        }else{
                            if(repetir==0){
                                var hasClase2 = item.classList.contains( 'shadow-sm' );
                                var logo= document.querySelector('#imgNavBar');
                                logo.src = "/assets/administrador/img/inside_logo.png";
                            }
                            repetir = 1;
                        }
                    }else{
                        if(windowBottom < 1023){
                            var hasClase2 = item.classList.contains( 'shadow-sm' );
                            var logo= document.querySelector('#imgNavBar');
                            logo.src = "/assets/viewmain/img/web/logo-emsaba-blanco.png";
                            repetir= 0;
                        }else{
                            if(repetir==0){
                                var hasClase2 = item.classList.contains( 'shadow-sm' );
                                var logo= document.querySelector('#imgNavBar');
                                logo.src = "/assets/administrador/img/inside_logo.png";
                            }
                            repetir = 1;
                        }
                    }
                })
            });*/
        </script>
    </body>
</html>