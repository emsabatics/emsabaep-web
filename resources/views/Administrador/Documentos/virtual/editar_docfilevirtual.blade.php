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
<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop-files.css')}}">
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
      <div class="row">
          <div class="col-12">
              <div class="card card-default">
                  <div class="card-header">
                      <h3 class="card-title">Actualizar Documentación Biblioteca Virtual</h3>
                      <div class="card-tools" id="card-tools">
                        <button type="button" class="btn btn-primary btn-block" onclick="urlbacktosubc()"><i
                            class="fas fa-arrow-left mr-2"></i> Regresar</button>
                    </div>
                  </div>
                  <div class="card-body">
                      <form id="formdocvirtuale" action="" method="POST" enctype="multipart/form-data">
                        <div class="row">
                          <div class="col-6">
                            <div class="form-group noevent">
                              <input type="hidden" name="idfilevirtual" id="idfilevirtual" value="{{$idfile}}">
                              @foreach($categoria as $c)
                                <div class="form-group noevent">
                                  <label>Categoría:</label>
                                  <textarea id="txtnamecatedit" class="form-control text-justify" cols="30" rows="5" placeholder="Categoría" 
                                  autocomplete="off" maxlength="270">{{$c->descripcion}}</textarea>
                                </div>
                              @endforeach
                            </div>
                            @if($idsubcat=='0')
                              <div class="form-group">
                                <label>SubCategoría:</label>
                                <select class="form-control select2" id="selSubCategoriaEdit" name="selSubCategoriaEdit">
                                  <optgroup label="Seleccione una Opción">
                                    <option value="0">-Seleccione una Opción-</option>
                                    @foreach ($subcategoria as $item)
                                    <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                    @endforeach
                                  </optgroup>
                                </select>
                              </div>
                            @elseif($idsubcat!='0')
                            <div class="form-group noevent">
                              <label>SubCategoría:</label>
                              <select class="form-control select2" id="selSubCategoriaEdit" name="selSubCategoriaEdit">
                                <optgroup label="Seleccione una Opción">
                                  <option value="0">-Seleccione una Opción-</option>
                                  @foreach ($subcategoria as $item)
                                    @if($item->id == $idsubcat)
                                      <option value="{{$item->id}}" selected>{{$item->descripcion}}</option>
                                    @else
                                      <option value="{{$item->id}}">{{$item->descripcion}}</option>
                                    @endif
                                  @endforeach
                                </optgroup>
                              </select>
                            </div>
                            @endif
                            @foreach($archivos as $p)
                            <div class="form-group">
                              <label for="inputNameDocBiVirEdit">Título del Documento: <span class="spanlabel"></span></label>
                              <textarea class="form-control text-justify" id="inputNameDocBiVirEdit" name="inputNameDocBiVirEdit" placeholder="Ingrese un título"
                                maxlength="255">{{$p->titulo}}</textarea>
                              <input type="hidden" name="inputoriginalname" id="inputoriginalname" value="{{$p->titulo}}">
                            </div>
                            @endforeach
                          </div>
                          <div class="col-6"> 
                            <div class="form-group">
                              <label for="inputAliasFileDocBiVirEdit">Alias del Documento: </label>
                              <div class="input-group mb-3">
                                <button class="btn btn-outline-primary" type="button" onclick="generarAliasE()">Generar</button>
                                <textarea id="inputAliasFileDocBiVirEdit" name="inputAliasFileDocBiVirEdit" class="form-control text-justify noevent" placeholder="Ingrese un alias" aria-label="Example" 
                                aria-describedby="button-addon1">{{substr($p->archivo, 0, -4)}}</textarea>
                              </div>
                            </div>
                            <div class="form-group mt-3">
                              <label for="inputDescpDocBiViredit">Descipción del Documento: <span class="spanlabel">500 caracteres máximo</span></label>
                              <textarea class="form-control text-justify" id="inputDescpDocBiViredit" name="inputDescpDocBiViredit" placeholder="Ingrese un contenido..."
                                rows="7" cols="30"  maxlength="500"> {{ str_replace('//', "\n", $p->descripcion) }}</textarea>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-3"></div>
                          <div class="col-6">
                            @foreach($archivos as $p)
                            <div class="card-list grid-card-list" id="cardListDocVirtual">
                              <div class='item-list grid-item-list' id='divpics'>
                                <div style='grid-row: 1/2;'>
                                  <div class='avatar' style="text-align: center;height: 78px;">
                                    <img src='/assets/administrador/img/icons/icon-pdf-color.svg' alt='File' class='avatar-img' style="width: 78px;height: 78px;">
                                  </div>
                                </div>
                                <div style='grid-row: 1/2;'>
                                  <button class='btn btn-icon btn-danger btn-round btn-xs' onclick='eliminarFile(event)'>
                                    <i class='fas fa-trash'></i>
                                  </button>
                                </div>
                                <div style='grid-row: 2/2;'>
                                  <div class="info-user">
                                    <div class='status'>{{$p->archivo}}</div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            @endforeach
                            <div class="form-group mb-3 noshow" id="divfiledocvirtual">
                              <span class="spanlabel m-4">Seleccione solo un archivo</span>
                              <div class="container">
                                <input type="file" name="fileEdit[]" id="fileEdit" accept="application/pdf" onchange="previewEdit()" multiple>
                                <label for="fileEdit">
                                  <i class="fas fa-cloud-upload-alt mr-2"></i> Elija un archivo
                                </label>
                                <p id="num-of-files-edit">- Ningún archivo seleccionado -</p>
                                <div id="imagesEdit"></div>
                              </div>
                            </div>
                          </div>
                          <div class="col-3"></div>
                        </div>
                        <div class="row">
                          <div class="col-lg-12 mt-4 d-flex justify-content-end">
                            <button type="button" class="btn btn-primary updatedocvirtual" style="font-size: 16px;" onclick="actualizardocvirtual()">
                              <i class="far fa-save mr-2"></i>
                              Actualizar
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
                <p style="font-size: 16px;"> Actualizando Documento... </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')

<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/toastr/toastr.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/drag-drop-files.js')}}"></script>
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

  var isDocVirtual= true;
  var currIdCat = {{ Illuminate\Support\Js::from($idcat) }};
  var currIdSubc = {{ Illuminate\Support\Js::from($idsubcat) }};
</script>
@endsection