@extends('Administrador.Layouts.app')

@section('icon-app')
<link rel="shortcut icon" type="image/png" href="{{asset('assets/administrador/img/icons/evento.png')}}">
@endsection

@section('title-page')
Admin | Eventos {{getNameInstitucion()}}
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('assets/administrador/css/personality.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/style-modalfull.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/css/drag-drop.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.css')}}">
<script type="text/javascript" src="{{asset('assets/administrador/plugins/sweetalert/sweetalert2.min.js')}}" ></script>
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2/css/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css')}}">

<!-- fullCalendar -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/fullcalendar/main.css')}}">
<!-- daterange picker -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/daterangepicker/daterangepicker.css')}}">

<!-- Toastr -->
<link rel="stylesheet" href="{{asset('assets/administrador/plugins/toastr/toastr.min.css')}}">
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
    <h1>Eventos</h1>
  </div>
</div>
@endsection

@section('contenido-body')
<input type="hidden" name="csrf-token" value="{{csrf_token()}}" id="token">

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary">
                  <div class="card-body p-3">
                    <!-- THE CALENDAR -->
                    <div id="calendar"></div>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</section>

<!-- MODAL AGG EVENT -->
<div class="modal fade" id="modal-event" tabindex="-1" role="dialog" aria-labelledby="modalArchivosTitle"
aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Agendar Evento</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <form id="formEvento" action="" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group">
                        <label for="drgpickerFecha">Desde:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <div class="input-group-text" id="button-addon-date"><span
                                class="fas fa-calendar fa-16"></span></div>
                            </div>
                            <input type="text" class="form-control noevent" name="R_fechaI" id="R_fechaI">
                        </div>
                        </div>
                        <div class="form-group">
                        <label for="drgpickerFecha">Hasta:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                            <div class="input-group-text" id="button-addon-date"><span
                                class="fas fa-calendar fa-16"></span></div>
                            </div>
                            <input type="text" class="form-control noevent" name="R_fechaHV" id="R_fechaHV">
                            <input type="hidden" class="form-control noevent" name="R_fechaH" id="R_fechaH">
                        </div>
                        </div>
                        <div class="form-group">
                        <label for="inputTituloEvent">Título: <span class="spanlabel">70 caracteres
                            máximo</span></label>
                        <textarea class="form-control text-justify" id="inputTituloEvent" name="inputTituloEvent"
                            placeholder="Ingrese un título" maxlength="70" rows="3" cols="5"></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                          <label for="example-select">Tipo de Evento:</label>
                          <select class="form-control" id="selectEvent" name="selectEvent">
                            <option value="0">-Seleccione una Opción-</option>
                            <option value="comunicado">Comunicado</option>
                            <option value="aviso">Aviso</option>
                            <option value="diacivico">Día Cívico</option>
                          </select>
                        </div>
                        <div class="form-group">
                        <label for="inputDescEvent">Descripción corta (Opcional): <span class="spanlabel">270 caracteres
                            máximo</span></label>
                        <textarea class="form-control text-justify" id="inputDescEvent" name="inputDescEvent"
                            placeholder="Ingrese una breve descripción" rows="10" cols="5" maxlength="270"></textarea>
                        </div>
                    </div>
                    </div>
                    <div class="row mb-3">
                    <div class="col-md-12">
                        <label>Seleccionar Imagen para el Evento <span class="spanlabel">1 imagen máximo</span></label>
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
                <button type="button" class="btn btn-default mr-3" onclick="cerrarModal()">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarEvento()" id="btnAgendar">Guardar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- MODAL EDIT EVENT -->
<div class="modal fade bd-example-modal-xl" id="modal-event-edit" tabindex="-1" role="dialog" aria-hidden="true"
data-keyboard="false" data-backdrop="static">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header headerRegister headermodalBg">
        <h5 class="modal-title titletextheader" id="titleModal">Actualizar Evento</h5>
        <button class="btn btn-danger" type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formeditEvento" action="" method="POST" enctype="multipart/form-data">
          <input type="hidden" name="id_agenda" id="id_agenda">
          <div class="row mb-3">
            <div class="col-md-6">
              <div class="form-group">
                <label for="drgpickerFecha">Desde:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text" id="button-addon-date"><span
                        class="fas fa-calendar fa-16"></span></div>
                  </div>
                  <input type="text" class="form-control drgpicker" id="txt_fechaI" name="txt_fechaI">
                </div>
              </div>
              <div class="form-group">
                <label for="drgpickerFecha">Hasta:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <div class="input-group-text" id="button-addon-date"><span
                        class="fas fa-calendar fa-16"></span></div>
                  </div>
                  <input type="text" class="form-control drgpicker" id="txt_fechaH" name="txt_fechaH">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label">Título <span class="spanlabel">70 caracteres máximo</span></label>
                <textarea class="form-control" id="txtTituloEv" name="txtTituloEv" rows="3" cols="5"
                  placeholder="Título del Evento" autocomplete="off"></textarea>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group mb-3">
                <label for="example-select">Tipo de Evento:</label>
                <select class="form-control" id="selectEventEdit" name="selectEventEdit">
                  <option value="0">-Seleccione una Opción-</option>
                  <option value="comunicado">Comunicado</option>
                  <option value="aviso">Aviso</option>
                  <option value="diacivico">Día Cívico</option>
                </select>
              </div>
              <div class="form-group">
                <label class="control-label">Descripción (Opcional): <span class="spanlabel">270 caracteres
                    máximo</span></label>
                <textarea class="form-control" id="txtDescEv" name="txtDescEv" rows="10" cols="5"
                  placeholder="Descripción del Evento" autocomplete="off"></textarea>
              </div>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6" id="divcontainerUp">
              <div class="form-group">
                <div class="container">
                  <input type="file" name="fileedit[]" id="fileedit" accept="image/*"
                    onchange="previewpicsEdit()" multiple>
                  <label for="fileedit">
                    <i class="fe fe-upload-cloud fe-24"></i>&nbsp; Elija una imagen
                  </label>
                  <p id="num-of-files-edit">- Ningún archivo seleccionado -</p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <span style="font-size: 20px;font-weight: bolder;padding: 4px;">Imagen</span>
              <div id="rowPicsInd"></div>
              <div id="images-edit"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button id="btnActionForm" class="btn btn-primary mr-3" type="button" onclick="actualizarEvento()"><i
            class="fa fa-fw fa-lg fa-check-circle"></i><span>Actualizar</span></button>
        <button class="btn btn-danger mr-3" type="button" onclick="eliminarEvento()"><i
            class="fa fa-fw fa-lg fa-trash"></i>Eliminar</span></button>
        <!--<a class="btn btn-secondary" href="#" data-dismiss="modal"><i
            class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</a>-->
          <button class="btn btn-secondary" type="button" onclick="cerrarEvento()"><i
            class="fa fa-fw fa-lg fa-times-circle"></i>Cancelar</span></button>
      </div>
    </div>
  </div>
</div>


<!-- Fullscreen modal -->
<div class="modal fade modal-full" id="modalCargando" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true"
data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body text-center">
        <img src="{{asset('assets/administrador/img/gif/load.gif')}}" alt="">
      </div>
    </div>
  </div>
</div>

@endsection

@section('js')

<!-- Toastr -->
<script src="{{asset('assets/administrador/plugins/toastr/toastr.min.js')}}"></script>

<!-- fullCalendar 2.2.5 -->
<script src="{{asset('assets/administrador/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/fullcalendar/main.js')}}"></script>
<script src="{{asset('assets/administrador/plugins/fullcalendar/locales/es.js')}}"></script>
<!-- date-range-picker -->
<script src="{{asset('assets/administrador/plugins/daterangepicker/daterangepicker.js')}}"></script>

<script src="{{asset('assets/administrador/plugins/select2/js/select2.full.min.js')}}"></script>
<script src="{{asset('assets/administrador/js/drag-drop.js')}}"></script>
<script src="{{asset('assets/administrador/js/funciones.js')}}"></script>
<script src="{{asset('assets/administrador/js/eventos.js')}}"></script>
<script>
    $('.select2').select2({
        theme: 'bootstrap4',
    });

    var calendarEl = document.getElementById('calendar');

    /** full calendar */
    var initialLocaleCode = 'es';
    var calendar = '';
    var eventDel = null;
    var nagend = null;
    var fSDate = '', fEDate = '';

    var hoy = new Date();
    var currentFecha = [];
    currentFecha = sumarDias(hoy, -7);

    var dia = currentFecha[0];
    var currentMont = currentFecha[1];
    var anio = currentFecha[2];
    var fecha = anio + "/" + currentMont + "/" + dia;
    var lastDay = new Date(hoy.getFullYear(), hoy.getMonth() + 1, 0).getDate();

    $(function () {
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

        /*$.ajaxSetup({
          headers:{
            'X-CSRF-TOKEN': $('#token').val()
          }
        });*/

        calendar = new FullCalendar.Calendar(calendarEl, {
            timeZone: 'America/Guayaquil',
            locale: initialLocaleCode,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,listMonth'
            },
            themeSystem: 'bootstrap',
            editable: false,
            selectable: true,
            droppable: false, // this allows things to be dropped onto the calendar !!!
            navLinks: true, // can click day/week names to navigate views
            dayMaxEvents: true, // allow "more" link when too many events
            select: function (arg) {
                //console.log(format_date(calendar.formatIso(arg.start)));
                if (format_date(calendar.formatIso(arg.start)) == format_date(calendar.formatIso(arg.end))) {
                    //console.log("FECHA INICIO-FIN IGUAL");
                    $('#R_fechaI').val(format_date(calendar.formatIso(arg.start)));
                    $('#R_fechaH').val(format_date(calendar.formatIso(arg.end)));
                    $('#R_fechaHV').val(format_date(calendar.formatIso(arg.end)));
                    $('#R_fechaALL').val(arg.allDay);
                }else if (format_date(calendar.formatIso(arg.start)) < format_date(calendar.formatIso(arg.end))) {
                    //console.log("FECHA INICIO-FIN DESIGUAL");
                    var fechaend = format_date(calendar.formatIso(arg.end));
                    var fechastart = format_date(calendar.formatIso(arg.start));
                    var diff = new Date(fechaend) - new Date(fechastart);
                    var diastranscurridos = Math.floor(diff / (1000 * 60 * 60 * 24));

                    if (diastranscurridos == 1) {
                        var nmesend = fechaend.substring(5, 7); //mes fecha fin
                        var nmes = fechastart.substring(5, 7); //mes fecha desde
                        if (nmes == nmesend) {
                            //console.log('mes iguales');
                            var diaselend = parseInt(fechaend.substring(10, 8)) - 1; //dia fecha fin
                            if (diasel > diaselend) {
                                diaselend = diasel;
                            }
                            if (String(diaselend).length == 1) {
                                diaselend = "0" + diaselend;
                            }
                            var nmes = fechaend.substring(5, 7);
                            var nyear = fechaend.substring(0, 4);
                            //console.log(nyear+'-'+nmes+'-'+diaselend);
                            $('#R_fechaI').val(format_date(calendar.formatIso(arg.start)));
                            $('#R_fechaH').val(format_date(calendar.formatIso(arg.end)));
                            $('#R_fechaHV').val(nyear + '-' + nmes + '-' + diaselend);
                            $('#R_fechaALL').val(arg.allDay);
                        } else {
                            //console.log('mes diferentes');
                            nmesend = nmesend - 1; //igualar al mes actual
                            var diasel = parseInt(fechastart.substring(10, 8)); //dia fecha start
                            var diaselend = parseInt(fechaend.substring(10, 8)); //dia fecha fin
                            if (diasel > diaselend) {
                                diaselend = diasel;
                            }
                            if (String(diaselend).length == 1) {
                                diaselend = "0" + diaselend;
                            }
                            var nmes = fechaend.substring(5, 7);
                            var nyear = fechaend.substring(0, 4);
                            //console.log(nyear+'-'+nmes+'-'+diaselend);
                            $('#R_fechaI').val(format_date(calendar.formatIso(arg.start)));
                            $('#R_fechaH').val(format_date(calendar.formatIso(arg.end)));
                            $('#R_fechaHV').val(nyear + '-' + nmes + '-' + diaselend);
                            $('#R_fechaALL').val(arg.allDay);
                        }
                    } else {
                        var diaselend = parseInt(fechaend.substring(10, 8)) - 1; //dia fecha fin
                        if (String(diaselend).length == 1) {
                            diaselend = "0" + diaselend;
                        }
                        var nmes = fechaend.substring(5, 7);
                        var nyear = fechaend.substring(0, 4);
                        $('#R_fechaI').val(format_date(calendar.formatIso(arg.start)));
                        $('#R_fechaH').val(format_date(calendar.formatIso(arg.end)));
                        $('#R_fechaHV').val(nyear + '-' + nmes + '-' + diaselend);
                        $('#R_fechaALL').val(arg.allDay);
                    }
                }

                setTimeout(() => {
                    $('#modal-event').modal('show');
                }, 500);
            },
            eventSources: [{
              url: '/get-eventos',
              method: 'GET'
            }],
            eventClick: function (info) {
                //alert('ID: '+info.event.groupId);
                eventDel = info;
                nagend = calendar;
                getAgendaEvent(info.event.groupId);
            },
            drop: function (info) {
                // is the "remove after drop" checkbox checked?
                if (checkbox.checked) {
                // if so, remove the element from the "Draggable Events" list
                info.draggedEl.parentNode.removeChild(info.draggedEl);
                }
            }
        });

        calendar.render();
    });

    /*FUNCION FORMATO FECHA*/
    function format_date(fecha) {
        var fechaT = fecha.substring(0, 10);
        return fechaT;
    }
</script>
@endsection