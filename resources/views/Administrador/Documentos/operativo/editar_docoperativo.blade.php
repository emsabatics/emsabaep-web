@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/doc-operativa.png')}}">
@endsection

@section('title-page')
Admin | Doc Operativa {{getNameInstitucion()}}
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
    <h1>Actualizar Doc. Operativa</h1>
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
                      <h3 class="card-title">Actualizar Documentación Operativa</h3>
                      <div class="card-tools" id="card-tools">
                        <button type="button" class="btn btn-primary btn-block" onclick="urlback()"><i
                            class="fas fa-arrow-left mr-2"></i> Regresar</button>
                    </div>
                  </div>
                  <div class="card-body">
                      <form id="formdocoperativoe" action="" method="POST" enctype="multipart/form-data">
                        @foreach ($filedocoper as $p)
                          <div class="row">
                              <div class="col-6">
                                <div class="form-group noevent">
                                  <input type="hidden" name="iddocoperativo" id="iddocoperativo" value="{{$p->id}}">
                                  <label>Año:</label>
                                  <select class="form-control select2" id="selYearEditDocOper" name="selYearEditDocOper">
                                    <optgroup label="Seleccione una Opción">
                                      <option value="0">-Seleccione una Opción-</option>
                                      @foreach ($anio as $item) 
                                      @if ($p->id_anio == $item->id)
                                      <option value="{{$item->id}}" selected>{{$item->nombre}}</option>
                                      @else
                                      <option value="{{$item->id}}">{{$item->nombre}}</option>
                                      @endif
                                      @endforeach
                                    </optgroup>
                                  </select>
                                </div>
                                <div class="form-group noevent">
                                  <label>Mes:</label>
                                  <select class="form-control select2" id="selEMes" name="selEMes">
                                    <optgroup label="Seleccione una Opción">
                                      <option value="0">-Seleccione una Opción-</option>
                                      @foreach ($mes as $item)
                                      @if ($p->id_mes == $item->id)
                                      <option value="{{$item->id}}" selected>{{$item->mes}}</option>    
                                      @else
                                      <option value="{{$item->id}}">{{$item->mes}}</option>    
                                      @endif
                                      @endforeach
                                    </optgroup>
                                  </select>
                                </div>
                                <div class="form-group">
                                  <label for="inputEDocTitle">Título del Documento: <span class="spanlabel"></span></label>
                                  <textarea class="form-control text-justify" id="inputEDocTitle" name="inputEDocTitle" placeholder="Ingrese un título"
                                    maxlength="255">{{$p->titulo}}</textarea>
                                </div>
                                <div class="form-group">
                                  <label for="inputEAliasFile">Alias del Documento: </label>
                                  <div class="input-group mb-3">
                                    <button class="btn btn-outline-primary" type="button" onclick="generarAliasE()">Generar</button>
                                    <textarea id="inputEAliasFile" name="inputEAliasFile" class="form-control text-justify noevent" placeholder="Ingrese un alias" aria-label="Example" 
                                    aria-describedby="button-addon1">{{substr($p->archivo, 0, -4)}}</textarea>
                                  </div>
                                </div>
                              </div>
                              <div class="col-6">
                                <div class="card-list grid-card-list" id="cardListDocOper">
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
                                <div class="form-group mb-3 noshow" id="divfiledocoper">
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
                          </div>
                          <div class="row">
                            <div class="col-lg-6 col-12 mt-4 d-flex justify-content-start">
                              <button type="button" class="btn btn-danger deletedocoper" style="font-size: 16px;" onclick="eliminarpermdocoper()">
                                <i class="fas fa-trash mr-2"></i>
                                Eliminar Permanentemente
                              </button>
                            </div>
                            <div class="col-lg-6 col-12 mt-4 d-flex justify-content-end">
                              <button type="button" class="btn btn-primary updatedocoper" style="font-size: 16px;" onclick="actualizardocoper()">
                                <i class="far fa-save mr-2"></i>
                                Actualizar
                              </button>
                            </div>
                          </div>
                        @endforeach
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
<script src="{{asset('assets/administrador/js/docoperativo.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>

<script>
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

  var isDocOperativo= true;

  $(document).ready(function () {
    //$('#modalCargando').modal('show');
  });
</script>
@endsection