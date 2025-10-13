@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/doc-administrativa.png')}}">
@endsection

@section('title-page')
Admin | Bib. Virtual {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free-5.15.4/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/setcards.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<!-- Toastr -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/toastr/toastr.min.css')}}">

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

  .noshow{
    display: none;
  }

  .fila {
    display: flex;       /* activa Flexbox */
    justify-content: space-between; /* distribuye el espacio */
  }

  .fila > * {
      flex: 1;             /* cada elemento ocupa la misma "columna" */
      text-align: center;  /* opcional: centrar contenido */
      padding: 5px;
  }
</style>
@endsection

@section('container-header')
<div class="row mb-2">
  <div class="col-sm-12">
    <h1>Biblioteca Virtual</h1>
  </div>
</div>
@endsection

@section('contenido-body')
<input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">

<section class="content">
  <div class="container-fluid">
    <h5 class="mb-2">{{ $categoria }} \ {{ $subcategoria }}</h5>
    <div class="row mt-2">
      <div class="col-12">
        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-title">Actualizar {{ $categoria }}</h3>
            <div class="card-tools" id="card-tools">
              <button type="button" class="btn btn-primary btn-block" onclick="urlbacktosubcgallery()"><i
                class="fas fa-arrow-left mr-2"></i> Regresar</button>
            </div>
          </div>
          <div class="card-body pb-0">
            <div class="row">
              <!--FOREACH-->
              @foreach ($archivos as $fl)
                <div class="col-12 col-sm-6 col-md-4 d-flex align-items-stretch flex-column" id="cardGallery{{ $loop->index }}">
                  <div class="card bg-light d-flex flex-fill">
                    <div class="card-header text-muted border-bottom-0">
                      Fotografía # {{ $loop ->iteration }}
                    </div>
                    <div class="card-body pt-0">
                      <div class="row">
                        <div class="col-7">
                          <p class="text-muted text-sm"><b>Imagen: </b> {{ $fl->archivo }} </p>
                        </div>
                        <div class="col-5 text-center">
                          <img src="/galeria-bibliotecavirtual/{{ $fl->archivo }}" alt="user-avatar" class="img-fluid" id="imggaleria{{ $loop->index }}">
                        </div>
                      </div>
                      <div class="row mt-2">
                        <div class="col-12">
                          <ul class="ml-4 mb-0 fa-ul text-muted">
                            <li class="small">
                              <span class="fa-li"><i class="fas fa-lg fa-info"></i></span> 
                              <b>Título: </b><br><p id="ptitulo{{ $loop->index }}">{{ $fl->titulo }}</p>
                            </li>
                            <li class="small">
                              <span class="fa-li"><i class="fas fa-lg fa-envelope-open"></i></span>
                              <b>Descripción:</b>
                              <p class="text-justify" id="pdescp{{ $loop->index }}">{!! nl2br(e(str_replace('//', "\n", $fl->descripcion))) !!}</p>
                            </li>
                            <li class="small">
                              <span class="fa-li"><i class="fas fa-lg fa-info"></i></span> 
                              <b>Estado: </b><br>
                              @if ($fl->estado=='1')
                              <p id="pestado{{ $loop->index }}">Activo</p>
                              @else
                              <p id="pestado{{ $loop->index }}">Inactivo</p>
                              @endif
                            </li>
                          </ul>
                        </div>
                      </div>
                    </div>
                    <div class="card-footer">
                      <div class="text-right" id="footerCard{{ $loop->index }}">
                        <!--<div id="divoptionstatus">-->
                        @if ($fl->estado=='1')
                          <a class="btn btn-secondary btn-sm" href="javascript:void(0)" onclick="inactivarfilegaleria('{{ encriptarNumero($fl->id) }}', {{$loop->index}})">
                            <i class="fas fa-eye-slash"></i>
                          </a>
                        @else
                          <a class="btn btn-secondary btn-sm" href="javascript:void(0)" onclick="activarfilegaleria('{{ encriptarNumero($fl->id) }}', {{$loop->index}})">
                            <i class="fas fa-eye"></i>
                          </a>
                        @endif
                        <!--</div>-->
                        <a href="javascript:void(0)" class="btn btn-sm btn-success" title="Ver" onclick="viewopenimg({{ $loop ->index }})">
                          <i class="fas fa-search-plus"></i>
                        </a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-primary" title="Actualizar Contenido" onclick="updatetxtgallery({{ $loop ->index }}, '{{ encriptarNumero($fl->id) }}')">
                          <i class="fas fa-edit"></i>
                        </a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-success" title="Actualizar Imagen" onclick="updateimggallery({{ $loop ->index }}, '{{ encriptarNumero($fl->id) }}')">
                          <i class="fas fa-image"></i>
                        </a>
                        <a href="javascript:void(0)" class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarfileongaleria('{{ encriptarNumero($fl->id) }}', {{$loop->index}})">
                          <i class="fas fa-trash"></i>
                        </a>
                      </div>
                    </div>
                  </div>
                </div>
              @endforeach
              <!--ENDFOREACH-->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- MODAL VIEW IMAGENES -->
<div class="modal fade bd-example-modal-xl" id="modal-view-imagen" tabindex="-1" role="dialog" aria-hidden="true"
data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header headerRegister headermodalBg">
        <h5 class="modal-title titletextheader" id="titleModal">Imágenes</h5>
        <button class="btn btn-danger" type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row mb-3">
          <div class="col-lg-12 d-flex flex-column justify-content-center align-items-center">
            <div id="divShowImgBanner" class="divShowImgBanner"></div>
            <div id="divShowSpanBanner" class="mt-3"></div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a class="btn btn-secondary" href="#" data-dismiss="modal"><i class="fa fa-fw fa-lg fa-times-circle"></i>Cerrar</a>
      </div>
    </div>
  </div>
</div>

<!-- MODAL UPDATE IMAGENES -->
<div class="modal fade" id="modal-update-imagen" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Actualizar Imagen</h4>
            </div>
            <div class="modal-body p-4">
                <form id="formImgGaleria" action="" method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="idindex" id="idindex">
                  <input type="hidden" name="idfile" id="idfile">
                  <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Seleccionar Imagen <span class="spanlabel">1 imagen máximo</span></label>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                        <div class="container">
                            <input type="file" name="file[]" id="file" accept="image/*" onchange="preview()" multiple>
                            <label for="file">
                            <i class="fas fa-cloud-upload-alt mr-2"></i>&nbsp; Elija una imagen
                            </label>
                            <p id="num-of-files">- Ningún archivo seleccionado -</p>
                        </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div id="images"></div>
                    </div>
                  </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default mr-3" onclick="cerrarModalImg()">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarImgGaleria()" id="btnAgendar">Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!--MODAL EDIT OBSERVACION IMG-->
<div class="modal fade" id="modal-update-txtimagen" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Editar Contenido</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="idindex" id="idindex">
        <input type="hidden" name="idfile" id="idfile">
        <div class="row">
          <div class="col-lg-12">
            <div class="form-group mb-3">
              <label for="inputtituloimg">Título:</label>
              <textarea class="form-control text-justify" id="inputtituloimg" name="inputtituloimg" placeholder="Ingrese un título"
                maxlength="250"></textarea>
            </div>
             <div class="form-group mb-3">
              <label for="inputdescripcionimg">Descripción:</label>
              <textarea class="form-control text-justify" id="inputdescripcionimg" name="inputdescripcionimg" placeholder="Ingrese una descripción"
                maxlength="500" rows="7" cols="30"></textarea>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-primary mb-2" onclick="actualizarRegistroImg()" id="btnuptxtimg">Actualizar</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

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
                <p style="font-size: 16px;"> Cargando... </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/drag-drop.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/biblioteca_virtual.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/validacion.js')}}"></script>
<script>
  $('.select2').select2({
    theme: 'bootstrap4',
  });
  const nameInterfaz = "Biblioteca Virtual";
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

  var arrayImg= [];
  var arrayIdImg= [];
  var isDocVirtual= true;
  var currIdCat = {{ Illuminate\Support\Js::from($idcat) }};
  var currIdSubc = {{ Illuminate\Support\Js::from($idsubcat) }};
  var onlyimg = @json($getonlyimg);

  // Recorremos los objetos
  onlyimg.forEach(item => {
    arrayIdImg.push(item.id_imagen);
    arrayImg.push(item.imagen);
  });

  $(document).ready(function(){
    $('#modalFullSend').modal('show');
    setTimeout(() => {
       $('#modalFullSend').modal('hide');
    }, 1500);
  });
</script>
@endsection