(function ($) {
    "use strict";

    // Spinner
    /*var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 2000);
    };
    spinner(0);*/
    
    var altoW= window.screen.height;
    var anchoW= window.screen.width;
    const logoImg= document.querySelector('#imgNavBar');


    if(anchoW < 600){
        logoImg.src = "/files-img/"+imglogonormal;
    }else{
        logoImg.src = "/files-img/"+imglogoblanco;
    }

    var loadGif= function (){
        var modal = new bootstrap.Modal(document.getElementById('modalCargandoMain'), {
            keyboard: false,
            backdrop: false
        });
        modal.show();
        setTimeout(function () {
            modal.hide();
        }, 2500);
    }

    loadGif();


    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 45) {
            $('.navbar').addClass('sticky-top shadow-sm');
            /*logoImg.src = "/assets/administrador/img/inside_logo.png";*/
            logoImg.src = "/files-img/"+imglogonormal;
        } else {
            $('.navbar').removeClass('sticky-top shadow-sm');
            if(anchoW < 600){
                /*logoImg.src = "/assets/administrador/img/inside_logo.png";*/
                logoImg.src = "/files-img/"+imglogonormal;
            }else{
                /*logoImg.src = "/assets/viewmain/img/web/logo-emsaba-blanco.png";*/
                logoImg.src = "/files-img/"+imglogoblanco;
            }
        }
    });


    // International Tour carousel
    $(".InternationalTour-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        center: false,
        dots: true,
        loop: true,
        margin: 25,
        nav : false,
        navText : [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ],
        responsiveClass: true,
        responsive: {
            0:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:2
            },
            1200:{
                items:3
            }
        }
    });


    // packages carousel
    $(".packages-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        center: false,
        dots: false,
        loop: true,
        margin: 25,
        nav : true,
        navText : [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ],
        responsiveClass: true,
        responsive: {
            0:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:2
            },
            1200:{
                items:3
            }
        }
    });


    // testimonial carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        center: true,
        dots: true,
        loop: true,
        margin: 25,
        nav : true,
        navText : [
            '<i class="bi bi-arrow-left"></i>',
            '<i class="bi bi-arrow-right"></i>'
        ],
        responsiveClass: true,
        responsive: {
            0:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:2
            },
            1200:{
                items:3
            }
        }
    });

    
   // Back to top button
   $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
        $('.back-to-top').fadeIn('slow');
    } else {
        $('.back-to-top').fadeOut('slow');
    }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    }); 

})(jQuery);

