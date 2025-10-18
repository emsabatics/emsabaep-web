@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/registro.png')}}">
@endsection

@section('title-page')
Admin | Noticias {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop.css')}}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/daterangepicker/daterangepicker.css')}}">

<style>
    .bguardar {
      float: right;
      display: flex;
      flex-direction: column;
      margin-top: 13vh;
    }

    .container .dropify-wrapper {
      height: 295px;
    }

    .spanlabel {
      padding-left: 22px;
      font-size: 12.5px;
      font-weight: bold;
    }

    .loadInfo {
      width: 10rem !important;
      height: 10rem !important;
    }

    .formEdit {
      display: block;
      width: 50%;
      height: calc(1.5em + 0.75rem + 2px);
      padding: 0.375rem 0.75rem;
      font-size: 0.875rem;
      font-weight: 400;
      line-height: 1.5;
      color: #495057;
      background-color: #ffffff;
      background-clip: padding-box;
      border: 1px solid #dee2e6;
      border-radius: 0.25rem;
      transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
</style>
@endsection

@section('navbar')
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{url('home')}}" class="nav-link">Inicio</a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Navbar Search -->
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-bell"></i>
        <span class="badge badge-warning navbar-badge" id="num-noti-span"></span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="contain-noti">

      </div>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#" title="Ajustes">
        <i class="fas fa-cogs"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-item dropdown-header">Ajustes</span>
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item">
        <i class="fas fa-user-cog mr-2"></i> Perfil
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{route('logout')}}" class="dropdown-item">
          <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
        </a>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" data-widget="fullscreen" href="javascript:void(0)" role="button">
        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
  </ul>
</nav>
@endsection

@section('container-header')
<div class="row mb-2">
  <div class="col-sm-12">
    <h1>Noticias</h1>
  </div>
</div>
@endsection

@section('contenido-body')
<input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Registrar Noticia</h3>
                    </div>
                    <div class="card-body">
                        <form id="formNoticia" action="" method="POST" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="inputLugar">Lugar</label>
                                        <input type="text" class="form-control" id="inputLugar" placeholder="Lugar"
                                          autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label>Fecha:</label>
                                        <div class="input-group date" id="inputFecha" data-target-input="nearest">
                                          <input type="text" class="form-control datetimepicker-input drgpicker"
                                            data-target="#inputFecha" id="drgpickerFecha"/>
                                          <div class="input-group-append" data-target="#inputFecha" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                          </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputTitulo">Título: <span class="spanlabel">70 caracteres máximo</span></label>
                                        <textarea class="form-control text-justify" id="inputTitulo" placeholder="Ingrese un título"
                                          maxlength="70"></textarea>
                                    </div>
            
                                    <div class="form-group">
                                        <label for="inputDescShort">Descripción corta: <span class="spanlabel">270 caracteres
                                            máximo</span></label>
                                        <textarea class="form-control text-justify" id="inputDescShort"
                                          placeholder="Ingrese una breve descripción" rows="5" cols="5" maxlength="270"></textarea>
                                    </div>

                                    <div class="form-group mb-3">
                                        <span class="spanlabel m-4">Las imágenes deben tener un alto mayor a 1000px y un ancho mayor a 1900px</span>
                                        <div class="container">
                                          <input type="file" name="file[]" id="file" accept="image/*" onchange="preview()" multiple>
                                          <label for="file">
                                            <i class="fas fa-cloud-upload-alt mr-2"></i> Elija una imagen
                                          </label>
                                          <p id="num-of-files">- Ningún archivo seleccionado -</p>
                                          <div id="images"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-3">
                                      <label for="inputUrlVideo">Url: <span class="spanlabel">Opcional</span></label>
                                      <textarea class="form-control text-justify" id="inputUrlVideo"
                                          placeholder="https://..." rows="2" cols="5"></textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="inputDesc">Descripción:</label>
                                        <textarea class="form-control text-justify" id="inputDesc"
                                          placeholder="Ingrese el contenido de la noticia" rows="15" cols="5"></textarea>
                                    </div>
                                    <div class="form-group input-group mb-3">
                                        <label for="txt_hashtag">Hashtag (#):</label>
                                        <input type="text" id="txt_hashtag" class="form-control" placeholder="Hashtag"
                                          aria-describedby="basic-addon1" autocomplete="off" style="width: 100%;">
                                        <button type="button" id="agregarhash" class="btn btn-primary mt-3 mb-2">
                                          <i class="fas fa-plus-circle fa-16 mr-2"></i>
                                          Agregar Hashtag
                                        </button>
                                    </div>
                                    <div id="almacenar"></div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mt-4 d-flex justify-content-end">
                                  <button type="button" class="btn btn-primary" style="font-size: 16px;" onclick="guardarNoticia()">
                                    <i class="far fa-save mr-2"></i>
                                    Guardar
                                  </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="modalFullSend" class="modal fade modal-full" tabindex="-1" role="dialog"
    aria-labelledby="mySmallModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <button aria-label="" type="button" class="close px-2" data-dismiss="modal" aria-hidden="true">
        <!--<span aria-hidden="true">×</span>-->
    </button>
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="spinner-border loadInfo mr-3 text-primary" role="status">
                    <span class="sr-only">Cargando...</span>
                </div>
                <br><br>
                <p style="font-size: 16px;"> Registrando Noticia... </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/drag-drop.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/noticias.js')}}"></script>
<script src="{{asset('assets/administrador/js/validacion.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('assets/administrador/plugins/daterangepicker/daterangepicker.js')}}"></script>
<script>

    const nameInterfaz = "Registrar Noticia";

    /* Función que suma o resta días a una fecha, si el parámetro
            días es negativo restará los días*/
    function sumarDias(fecha, dias) {
        let arrayFecha = [];
        fecha.setDate(fecha.getDate() + dias);
        arrayFecha[0] = fecha.getDate();
        arrayFecha[1] = fecha.getMonth();
        arrayFecha[2] = fecha.getFullYear();
        return arrayFecha;
    }

    var hoy = new Date();
    var currentFecha = [];
    currentFecha = sumarDias(hoy, -7);
    var dia = currentFecha[0];
    var currentMont = currentFecha[1];
    var anio = currentFecha[2];
    var fecha = anio + "/" + currentMont + "/" + dia;

    //$(function () {
    $('.drgpicker').daterangepicker({
        singleDatePicker: true,
        timePicker: false,
        showDropdowns: true,
        "locale": {
          "format": "YYYY-MM-DD",
          "separator": " - ",
          "applyLabel": "Aplicar",
          "cancelLabel": "Cancelar",
          "fromLabel": "De",
          "toLabel": "Hasta",
          "customRangeLabel": "Custom",
          "daysOfWeek": [
            "Dom",
            "Lun",
            "Mar",
            "Mie",
            "Jue",
            "Vie",
            "Sáb"
          ],
          "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
          ],
          "firstDay": 1
        },
        showButtonPanel: true,
        minDate: new Date(anio, currentMont, dia),
        isInvalidDate: function (ele) {
          var currDate = moment(ele._d).format('YY-MM-DD');
          return ["17-09-09"].indexOf(currDate) != -1;
        }
    });
    
    var btn = document.getElementById('agregarhash');
    var txthash = document.getElementById('txt_hashtag');
    var contenedor = document.getElementById('almacenar');

    var contadorHash = 0;
    var arrayHash= [];

    btn.addEventListener('click',function(){
        if(txthash.value == ""){ //si no ingresa nada en el input le manda mensaje de que ingrese un nombre
          swal('Ingresa un valor','','warning');
          return false;
        }else{
          let replacechar= txthash.value.replace(/\s+/g, '');
          contadorHash++;
          //console.log(contadorHash);
          var input = document.createElement('input');//creo elemento input y le creo un salto de línea
          var salto = document.createElement('br');
          var btn_eliminar = document.createElement('button');
          var divgroup= document.createElement('div');
          divgroup.id="divGroup"+contadorHash;
          divgroup.className="d-flex flex-row";
          //btn_eliminar.innerText= "Eliminar";
          btn_eliminar.innerHTML='<i class="fas fa-trash mr-2"></i> Eliminar';
          btn_eliminar.type = 'button';
          btn_eliminar.className="btn btn-danger ml-2";
          btn_eliminar.id = "btn"+contadorHash;

          input.type = 'text';
          input.className="formEdit";
          input.id = "inputHash"+contadorHash;
          input.name = 'btn'+contadorHash;
          input.value = replacechar;
          //input.style.width = '150px';
          input.style.cssText= 'width: 60%;';
          input.setAttribute('disabled',''); // propiedad disabled

          divgroup.append(input);
          divgroup.append(btn_eliminar);
          contenedor.append(salto);//todo lo agrego al div de almacenar
          contenedor.append(divgroup);

          //let replacechar= txthash.value.replace(" ","");
          arrayHash.push(replacechar);

          txthash.value="";
          //console.log(arrayHash);
          //console.log(input);
          //console.log(btn_eliminar);
    
          var botones = document.getElementById('btn'+contadorHash);
    
          botones.addEventListener('click', function(){
            //console.log("El texto que tiene es: ", this.id);
            var posi= this.id.substr(3, this.id.length);
            var divactual= document.getElementById("divGroup"+posi);
            var input_name = divactual.querySelector('input[name='+this.id+']');
            /*var btn_id = divactual.querySelector(this.id);
            var input_name = divactual.querySelector('input[name='+this.id+']');
            divactual.removeChild(btn_id);
            divactual.removeChild(input_name);*/
            while (divactual.firstChild) {
              divactual.removeChild(divactual.firstChild);
            }
            var index1= arrayHash.indexOf(input_name.value);
            if(index1 > -1){
              arrayHash.splice(index1,1);
            }
            contenedor.removeChild(salto);
            contenedor.removeChild(divactual);
            /*var btn_id = document.getElementById(this.id);
            var input_name = document.querySelector('input[name='+this.id+']');
            //contenedor.removeChild(btn_id);
            //contenedor.removeChild(input_name);
            contenedor.removeChild(divgroup);
            contenedor.removeChild(salto);
            //contadorHash--;
            */
          });
        }
    });
</script>
@endsection