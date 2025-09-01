@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/doc-laboral.png')}}">
@endsection

@section('title-page')
Admin | Doc Laboral {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/fontawesome-free-5.15.4/css/all.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop-files.css')}}">
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
</style>
@endsection

@section('container-header')
<div class="row mb-2">
  <div class="col-sm-12">
    <h1>Doc. Laboral</h1>
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
                      <h3 class="card-title">Registrar Documentación Laboral</h3>
                      <div class="card-tools" id="card-tools">
                        <button type="button" class="btn btn-primary btn-block" onclick="urlback()"><i
                            class="fas fa-arrow-left mr-2"></i> Regresar</button>
                    </div>
                  </div>
                  <div class="card-body">
                      <form id="formDocLab" action="" method="POST" enctype="multipart/form-data">
                          <div class="row">
                            <div class="col-md-6">
                              <div class="form-group">
                                <label>Año:</label>
                                <select class="form-control select2" id="selYearDocLab" name="selYearDocLab">
                                  <optgroup label="Seleccione una Opción">
                                    <option value="0">-Seleccione una Opción-</option>
                                    @foreach ($anio as $item)
                                    <option value="{{$item->id}}">{{$item->nombre}}</option>
                                    @endforeach
                                  </optgroup>
                                </select>
                              </div>
                              <div class="form-group">
                                <label>Mes: <span class="spanlabel">(Opcional)</span></label>
                                <select class="form-control select2" id="selMes" name="selMes">
                                  <optgroup label="Seleccione una Opción">
                                    <option value="0">-Seleccione una Opción-</option>
                                    @foreach ($mes as $item)
                                    <option value="{{$item->id}}">{{$item->mes}}</option>
                                    @endforeach
                                  </optgroup>
                                </select>
                              </div>
                              <div class="form-group">
                                <label for="inputNameDocLab">Nombre del Documento: <span class="spanlabel">250 caracteres máximo</span></label>
                                <textarea class="form-control text-justify" id="inputNameDocLab" name="inputNameDocLab" placeholder="Ingrese un nombre"
                                  maxlength="250"></textarea>
                              </div>
                              <div class="form-group mr-3 mt-3">
                                <label for="inputAliasFileDocLab">Alias del Documento: <span class="spanlabel">250 caracteres máximo</span></label>
                                <div class="input-group mb-3">
                                  <button class="btn btn-outline-primary" type="button" onclick="generarAlias()">Generar</button>
                                  <textarea id="inputAliasFileDocLab" name="inputAliasFileDocLab" class="form-control text-justify noevent" placeholder="Ingrese un alias" aria-label="Example" aria-describedby="button-addon1" maxlength="250"></textarea>
                                </div>
                              </div>
                            </div>
                            <div class="col-md-6">
                              <div class="form-group mb-3">
                                <span class="spanlabel m-4">Seleccione solo un archivo</span>
                                <div class="container">
                                  <input type="file" name="file[]" id="file" accept="application/pdf" onchange="preview()" multiple>
                                  <label for="file">
                                    <i class="fas fa-cloud-upload-alt mr-2"></i> Elija un archivo
                                  </label>
                                  <p id="num-of-files">- Ningún archivo seleccionado -</p>
                                  <div id="images"></div>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                              <div class="col-lg-12 mt-4 d-flex justify-content-end">
                                <button type="button" class="btn btn-primary savedoclaboral" style="font-size: 16px;" onclick="guardarDocLaboral()">
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
                <p style="font-size: 16px;"> Registrando Documento... </p>
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
<script src="{{asset('assets/administrador/js/doclaboral.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/validacion.js')}}"></script>
<script>
  $('.select2').select2({
    theme: 'bootstrap4',
  });
  const nameInterfaz = "Doc. Laboral";
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
</script>

@endsection