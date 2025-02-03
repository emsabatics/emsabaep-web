@extends('Viewmain.Layouts.app')

@section('css')
<link href="{{asset('assets/viewmain/css/departamento.css')}}" rel="stylesheet">
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
<div class="container-fluid service py-5">
    <div class="container py-5">
        <div class="mx-auto text-center mb-5" style="max-width: 900px;">
            <h5 class="section-title px-3">
                Departamentos
            </h5>
        </div>
        <div class="row g-4">
            <div class="col-12">
                <div class="section-container">
                    <div uk-filter="target: .card-container; animation: fade">
                      <!-- Search Form -->
                      <div class="search-form" style="margin: 1.2em 0 1.2em 0">
                        <input onsearch="mySearch()" type="search" id="cardSearch" class="card-search" placeholder="Buscar Departamento..." onkeyup="mySearch()" style="background-color: #fff;">
                        <div class="display-flex flexmargin">
                          <div class="flex-wrap-break">
                  
                          </div>
                        </div>
                      </div>
                      <!-- No Results Message -->
                      <div class="no-results-message display-none" id="no-results" style="padding: 1em;margin: 1em;">
                        <div id="resultsSearchTerm">No hay resultados</div>
                  
                        <ul>
                          <li>Verifique el término ingresado y vuelva a intentar</li>
                          <li>Ingrese un término similar</li>
                        </ul>
                  
                      </div>
                  
                      <!-- CARDS -->
                      <ol class="card-container" id="cards">
                        @foreach ($departamentos as $dp)
                        <li class="card">
                          <!-- Project Image assets/viewmain/img/web/img_departamentos_g.png-->
                          <div>
                            <img class="card-image-header" src="/files-img/{{$dp['imagen']}}">
                          </div>
                          <!-- Main Content -->
                          <div class="card-content">
                            <!-- Project Title -->
                            <div class="icon-title-wrapper">
                              <h3 class="icon-title">{{$dp['nombre_dep']}}</h3>
                            </div>
                            <hr class="border-light">
                            <!-- Read More -->
                            <!--<button aria-expanded="false" type="button" class="read-more-project" onclick="readMoreProject(this)">Read more</button>
                            <hr class="border-light">-->
                  
                            <!-- Details -->
                            <ul class="card-details">
                              <li class="detail"><i class="fa fa-user"></i>Responsable: <br/><strong>{{$dp['responsable']}}</strong></li>
                              <li class="detail"><i class="fa fa-envelope"></i>Email: <br/><strong>{{$dp['email']}}</strong></li>
                              <li class="detail"><i class="fa fa-phone"></i>Teléfono: <br/><strong>{{$dp['telefono']}}</strong> 
                                &nbsp;&nbsp;Ext: <strong>{{$dp['extension']}}</strong></li>
                            </ul>
                          </div>
                        </li>
                        @endforeach
                      </ol>
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
<script src="https://cdn.jsdelivr.net/npm/uikit@3.16.3/dist/js/uikit.min.js"></script>
<script src="{{asset('assets/viewmain/js/noticias.js')}}"></script>
<script>
const accordionBtns = document.querySelectorAll(".item-header");
const cards = document.querySelectorAll(".card");
const resultsSearchTerm = document.getElementById("resultsSearchTerm");

accordionBtns.forEach((accordion) => {
  accordion.onclick = function () {
    this.classList.toggle("active");
    let content = this.nextElementSibling;
    //  console.log(content);

    if (content.style.maxHeight) {
      //this is if the accordion is open
      content.style.maxHeight = null;
      content.style.visibility = "hidden";
      this.setAttribute("aria-expanded", "false");
    } else {
      //if the accordion is currently closed
      content.style.maxHeight = content.scrollHeight + "px";
      content.style.visibility = "visible";
      this.setAttribute("aria-expanded", "true");
    }
  };
});

function mySearch() {
  const input = document.getElementById("cardSearch").value.toUpperCase();
  const cardContainer = document.getElementById("cards");
  const cards = document.querySelectorAll(".card");

  [...cards].map((card) => {
    const noResults = document.getElementById("no-results");
    const title = card
      .querySelector("div.card-content")
      .innerText.toUpperCase();
    title.includes(input)
      ? card.classList.remove("display-none")
      : card.classList.add("display-none");
    checkDisplay(input);
  });

  function checkDisplay(input) {
    const noResults = document.getElementById("no-results");
    const result = [...cards].filter((card) =>
      card.classList.contains("display-none")
    );
    if (cards.length === result.length) {
      resultsSearchTerm.innerHTML = `<p style="font-size: 1.5rem; margin-bottom: .5rem; font-weight: 600">Hmmm...</p>
      <p style="font-size: 1.2rem">No pudimos encontrar coincidencias para "<span class="highlight">${input.toLowerCase()}</span>".</p>`;
      noResults.classList.remove("display-none");
    } else {
      noResults.classList.add("display-none");
    }
  }
}

// Get all the "Read more" buttons
const readMoreButtons = document.querySelectorAll(".read-more-project");

// Iterate through each button and attach a click event listener
readMoreButtons.forEach((button) => {
  button.addEventListener("click", () => {
    const description = button.previousElementSibling; // Get the description element just before the clicked button
    description.classList.toggle("truncated");

    if (description.classList.contains("truncated")) {
      button.innerText = "Read more";
    } else {
      button.innerText = "Read less";
    }
  });
});

// // Get all <li> elements with class "card"
// const creekCards = document.querySelectorAll('li.card');

// // Convert NodeList to array for easier manipulation
// const cardsArray = Array.prototype.slice.call(creekCards);

// // Define sorting function
// function compareTitles(a, b) {
//     var titleA = a.querySelector('h3.icon-title').textContent.trim().toLowerCase();
//     var titleB = b.querySelector('h3.icon-title').textContent.trim().toLowerCase();

//     if (titleA < titleB) {
//         return -1;
//     }
//     if (titleA > titleB) {
//         return 1;
//     }
//     return 0;
// }

// // Sort cards array using the sorting function
// cardsArray.sort(compareTitles);

// // Clear the existing list
// var parent = cards[0].parentNode;
// parent.innerHTML = '';

// // Append sorted items to the parent
// cardsArray.forEach(function(card) {
//     parent.appendChild(card);
// });

</script>
@endsection