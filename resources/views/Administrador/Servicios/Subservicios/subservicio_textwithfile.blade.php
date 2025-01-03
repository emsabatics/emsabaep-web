@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/servicio-agua.png')}}">
@endsection

@section('title-page')
Admin | Servicios {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free-5.15.4/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<!-- Toastr -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/toastr/toastr.min.css')}}">
<!-- summernote -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/summernote/summernote-bs4.min.css')}}">

<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop.css')}}">

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

@section('container-header')
<div class="row mb-2">
  <div class="col-sm-12">
    @foreach ($subservicio as $it)
    <h1>Subservicio - {{$it->nombre}}</h1>
    @endforeach
  </div>
</div>
@endsection

@section('contenido-body')
<input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">

<section class="content">
  <div class="container-fluid">
      <div class="row">
          <div class="col-12">
              <div class="card card-primary card-outline">
                  <div class="card-header">
                      <h3 class="card-title">Config. Detalle Informativo</h3>
                      <div class="card-tools" id="card-tools">
                        <button type="button" class="btn btn-primary btn-block" onclick="urlbacktosubservice_filelist()"><i
                            class="fas fa-arrow-left mr-2"></i> Regresar</button>
                    </div>
                  </div>
                  <div class="card-body">
                      <form id="formTextFileSubServicio" action="" method="POST" enctype="multipart/form-data">
                          <h4>Detalle</h4>
                          <div class="row">
                            <div class="col-lg-12">
                              <input type="hidden" name="idsubservice" id="idsubservice" value="{{$idsubservice}}">
                              <div class="form-group">
                                <label style="width: 100%;">Descripción del Subservicio:
                                </label>
                                <textarea id="summernote" name="summernote"></textarea>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-6 mt-4">
                              <div class="form-group mb-3">
                                <span class="spanlabel">Debe elegir solo un archivo</span>
                                <div class="container">
                                  <input type="file" name="file[]" id="file" accept="application/pdf,image/*" onchange="preview()" multiple>
                                  <label for="file">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i> Elija un Archivo
                                  </label>
                                  <p id="num-of-files">- Ningún archivo seleccionado -</p>
                                  <div id="images"></div>
                                </div>
                              </div>
                            </div>
                            <div class="col-lg-6 mt-4">
                              <div class="card card-secondary">
                                <div class="card-header">
                                  <h3 class="card-title">Ubicación del Archivo en la Página</h3>
                                </div>
                                <div class="card-body">
                                  <div class="form-group">
                                    <div class="custom-control custom-radio">
                                      <input class="custom-control-input" type="radio" value="inicio" id="customRadioInicio" 
                                        name="customRadio" onclick="handleClick(this);">
                                      <label for="customRadioInicio" class="custom-control-label">Antes del Texto</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                      <input class="custom-control-input" type="radio" value="end" id="customRadioEnd" 
                                        name="customRadio" onclick="handleClick(this);">
                                      <label for="customRadioEnd" class="custom-control-label">Después del Texto</label>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div><!--COL-LG-6-->
                          </div>
                          <div class="row">
                              <div class="col-lg-12 mt-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary savetextsubservice" style="font-size: 16px;" onclick="guardarTextWithFileSubservicio()">
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
                <p style="font-size: 16px;"> Registrando Información... </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<!-- Summernote -->
<script src="{{asset('assets/administrador/plugins/summernote/summernote-bs4.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/summernote/lang/summernote-es-ES.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/drag-drop-services.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/subservicio_txtfile.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>

<script>
  var idservice= '';
  var subservice= {{Illuminate\Support\Js::from($subservicio)}};
  var typeService='';
  var getidsubservice= {{Illuminate\Support\Js::from($idsubservice)}};
  var interface= 'view';

  $('.select2').select2({
    theme: 'bootstrap4',
  });

  toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": false,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "1800",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
  }

  function getNameSubservice(){
    $(subservice).each(function(i,v){
      idservice= v.id_servicio;
    });
  }

  $(function () {
    // Summernote
    $('#summernote').summernote({
      height: 500,
      focus: true,
      lang: 'es-ES',
      toolbar: [
        // [groupName, [list of button]]
        //['font', ['strikethrough', 'superscript', 'subscript']],
        //['color', ['color']],
        //['para', ['ul', 'ol', 'paragraph']],
        //['height', ['height']]
        //['style', ['bold', 'italic', 'underline', 'clear']],
        //['fontsize', ['fontsize']],
        ['style', ['bold', 'italic', 'underline']],
        //['insert', ['picture']],
        ['para', ['ul', 'paragraph']],
        ['insert', ['link']],
      ],
      fontNames: ['Arial', 'Arial Black'],
      fontNames: 'Arial',
      fontSize: 18,
      lineHeight: 2.0,
      placeholder: 'Ingrese la descripción...',
      popover: {
        image: [
          ['image', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
          ['float', ['floatLeft', 'floatRight', 'floatNone']],
          ['remove', ['removeMedia']]
        ],
        link: [
          ['link', ['linkDialogShow', 'unlink']]
        ],
      }
      /*callbacks: {
        onKeydown: function (e) { 
          var t = e.currentTarget.innerText; 
          if (t.trim().length >= 200) {
            //delete keys, arrow keys, copy, cut, select all
            if (e.keyCode != 8 && !(e.keyCode >=37 && e.keyCode <=40) && e.keyCode != 46 && !(e.keyCode == 88 && e.ctrlKey) && !(e.keyCode == 67 && e.ctrlKey) && !(e.keyCode == 65 && e.ctrlKey))
              e.preventDefault(); 
            } 
          },
          onKeyup: function (e) {
            var t = e.currentTarget.innerText;
            $('#maxContentPost').text(200 - t.trim().length + " caracteres disponibles");
          },
          onPaste: function (e) {
            var t = e.currentTarget.innerText;
            var bufferText = ((e.originalEvent || e).clipboardData || window.clipboardData).getData('Text');
            e.preventDefault();
            var maxPaste = bufferText.length;
            if(t.length + bufferText.length > 200){
              maxPaste = 200 - t.length;
            }
            if(maxPaste > 0){
              document.execCommand('insertText', false, bufferText.substring(0, maxPaste));
            }
          $('#maxContentPost').text(200 - t.length);
        }
      }*/
    });

    getNameSubservice();
  });
</script>

@endsection