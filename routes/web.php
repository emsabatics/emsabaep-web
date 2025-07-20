<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BibliotecaVirtualController;
use App\Http\Controllers\DocAdministrativoController;
use App\Http\Controllers\DocFinancieroController;
use App\Http\Controllers\DocLaboralController;
use App\Http\Controllers\DocOperativoController;
use App\Http\Controllers\EstructuraController;
use App\Http\Controllers\EventCalendarController;
use App\Http\Controllers\HistoriaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MiViVaObController;
use App\Http\Controllers\RedSocialController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DateController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\NoticiasController;
use App\Http\Controllers\PliegoTarifarioController;
use App\Http\Controllers\LotaipController;
use App\Http\Controllers\LeyTransparenciaController;
use App\Http\Controllers\RendicionCuentasController;
use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\MediosVerificacionController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\SubserviceController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\HomePageController;
use App\Http\Controllers\BibliotecaTransparenciaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\LogoController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\AtencionCiudadanaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/mail', function(){
    return view('Mail.mail');
});*/

/*Route::middleware('throttle:vistas')->get('', function(Request $request){

});*/

/*===================================================================================================================================/
/=======================================================USUARIOS=====================================================================/
/===================================================================================================================================*/
Route::middleware(['throttle:vista_personalizada'])->group(function () {
    Route::get('/linkstorage', function () {
        Artisan::call('storage:link'); // this will do the command line job
    });
});

/*Route::middleware(['throttle:chat_users'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index']);
    Route::post('/send-message', [ChatController::class, 'sendMessage']);
    Route::get('/get-messages', [ChatController::class, 'getMessages']);
});*/

Route::middleware(['throttle:cont_user_query'])->group(function () {
    /*
    *CONTACTOS VISTA PRINCIPAL
    */
    //Route::post('/registrar-mensaje', [HomePageController::class, 'registro_mensaje_usuario']);
    Route::post('/registrar-mensaje', [MailController::class, 'registro_mensaje_usuario']);

    /*
    *BIBLIOTECA TRANSPARENCIA VISTA PRINCIPAL DOCUMENTACION ADMINISTRATIVA
    */
    Route::get('/download-mediosverificacion/{id}', [MediosVerificacionController::class, 'download_mediosv']);
});

Route::middleware(['throttle:cont_user_vistas'])->group(function () {
    Route::get('/homeCliente', function () {
        return view('Viewmain.home');
    });

    /*
    *HOME PAGE VISTA PRINCIPAL
    */
    Route::get('/', [HomePageController::class, 'index'])->name('/');

    /*
    *MAIL
    */
    Route::get('/mail', [MailController::class, 'index']);

    /*
    *NOTICIAS VISTA PRINCIPAL
    */
    Route::get('/viewnewsemsaba', [HomePageController::class, 'list_news'])->name('viewnewsemsaba');
    Route::get('/vistamain-noticia/{id}/{opcion}', [HomePageController::class, 'ver_noticia']);

    /*
    *ABOUT LA EMPRESA VISTA PRINCIPAL
    */
    Route::get('/aboutus', [HomePageController::class, 'about_us'])->name('aboutus');
    Route::get('/structurus', [HomePageController::class, 'struct_us'])->name('structurus');
    Route::get('/historyus', [HomePageController::class, 'history_us'])->name('historyus');
    Route::get('/departamentous', [HomePageController::class, 'departamento_us'])->name('departamentous');

    /*
    *SERVICIOS VISTA PRINCIPAL
    */
    Route::get('/our-services', [HomePageController::class, 'our_services'])->name('our-services');
    Route::get('/sub-services-detail/{idservice}', [HomePageController::class, 'get_subservices_indi']);
    Route::get('/description-sub-services-detail/{idsubservice}', [HomePageController::class, 'get_description_subservices_indi']);

    /*
    *BOLETINES VISTA PRINCIPAL
    */
    Route::get('/boletines', function(){
        return redirect()->route('anuncios');
    })->name('boletines');

    Route::get('/anuncios', [HomePageController::class, 'get_boletines'])->name('anuncios');


    /*
    *CONTACTOS VISTA PRINCIPAL
    */
    Route::get('/contactus', [HomePageController::class, 'contact_us'])->name('contactus');

    /*
    *BIBLIOTECA TRANSPARENCIA VISTA PRINCIPAL
    */
    Route::get('/biblioteca-transparencia', [HomePageController::class, 'biblioteca_transparencia'])->name('biblioteca-transparencia');

    /*
    *BIBLIOTECA TRANSPARENCIA VISTA PRINCIPAL LOTAIP
    */
    Route::get('/transp-lotaip', [BibliotecaTransparenciaController::class, 'lotaip_v1'])->name('transp-lotaip');
    Route::get('/transp-lotaip2', [BibliotecaTransparenciaController::class, 'lotaip_v2'])->name('transp-lotaip2');
    Route::get('/view-desc-lotaip/{tipo}/{code}', [BibliotecaTransparenciaController::class, 'view_desc_lotaip']);

    /*
    *BIBLIOTECA TRANSPARENCIA VISTA PRINCIPAL RENDICION CUENTA
    */
    Route::get('/transparencia/rendicion-cuenta', [BibliotecaTransparenciaController::class, 'rendicion_cuenta']);
    Route::get('/view-desc-rc/{tipo}/{anio}', [BibliotecaTransparenciaController::class, 'view_desc_rc']);
    Route::get('/view/view-rendicion-cuenta/{idyear}/{idtovideo}/{idtorc}', [BibliotecaTransparenciaController::class, 'play_rc']);

    /*
    *BIBLIOTECA TRANSPARENCIA VISTA PRINCIPAL DOCUMENTACION FINANCIERA
    */
    Route::get('/transparencia/doc-financiera', [BibliotecaTransparenciaController::class, 'doc_financiera']);
    Route::get('/view-desc-docfin/{tipo}/{anio}', [BibliotecaTransparenciaController::class, 'view_desc_docfin']);

    /*
    *BIBLIOTECA TRANSPARENCIA VISTA PRINCIPAL DOCUMENTACION OPERATIVA
    */
    Route::get('/transparencia/doc-operativa', [BibliotecaTransparenciaController::class, 'doc_operativa']);
    Route::get('/view-desc-docopt/{tipo}/{anio}', [BibliotecaTransparenciaController::class, 'view_desc_docopt']);

    /*
    *BIBLIOTECA TRANSPARENCIA VISTA PRINCIPAL DOCUMENTACION LABORAL
    */
    Route::get('/transparencia/doc-laboral', [BibliotecaTransparenciaController::class, 'doc_laboral']);
    Route::get('/view-desc-doclab/{tipo}/{anio}', [BibliotecaTransparenciaController::class, 'view_desc_doclab']);

    /*
    *BIBLIOTECA TRANSPARENCIA VISTA PRINCIPAL DOCUMENTACION ADMINISTRATIVA
    */
    Route::get('/transparencia/doc-administrativa', [BibliotecaTransparenciaController::class, 'doc_administrativa']);
    Route::get('/biblioteca-transparencia/doc-administrativa/view-ley-tr/{tipo}', [BibliotecaTransparenciaController::class, 'view_ley_transparencia']);
    Route::get('/biblioteca-transparencia/doc-administrativa/pac/{tipo}', [BibliotecaTransparenciaController::class, 'view_year_pac']);
    Route::get('/biblioteca-transparencia/doc-administrativa/poa/{tipo}', [BibliotecaTransparenciaController::class, 'view_year_poa']);
    Route::get('/biblioteca-transparencia/doc-administrativa/mediosv/{tipo}', [BibliotecaTransparenciaController::class, 'view_year_mediosv']);
    Route::get('/biblioteca-transparencia/doc-administrativa/pliegot/{tipo}', [BibliotecaTransparenciaController::class, 'view_pliegot']);
    Route::get('/biblioteca-transparencia/doc-administrativa/procesos/{tipo}', [BibliotecaTransparenciaController::class, 'view_procesos']);
    Route::get('/biblioteca-transparencia/doc-administrativa/other_d/{tipo}', [BibliotecaTransparenciaController::class, 'view_year_doc_administrativo']);
    Route::get('/biblioteca-transparencia/doc-administrativa/view-desc-docadmin/{tipo}/{anio}', [BibliotecaTransparenciaController::class, 'view_desc_docadmin']);

    Route::get('/biblioteca-transparencia/doc-administrativa/mediosv/view-desc-docmv/{tipo}/{anio}', [BibliotecaTransparenciaController::class, 'view_files_mediosv']);

    Route::get('/biblioteca-transparencia/doc-administrativa/view-desc-docpoa/{tipo}/{anio}', [BibliotecaTransparenciaController::class, 'view_desc_docpoa']);
    Route::get('/biblioteca-transparencia/doc-administrativa/view-desc-docpac/{tipo}/{anio}', [BibliotecaTransparenciaController::class, 'view_desc_docpac']);

    /*
    *BIBLIOTECA TRANSPARENCIA VISTA PRINCIPAL REGLAMENTOS
    */
    Route::get('/transparencia/reglamentos', [BibliotecaTransparenciaController::class, 'doc_reglamentos']);

    /*
    *BIBLIOTECA TRANSPARENCIA VISTA PRINCIPAL AUDITORIA
    */
    Route::get('/transparencia/auditoria', [BibliotecaTransparenciaController::class, 'doc_auditoria']);
    Route::get('/view-desc-docaud/{tipo}/{anio}', [BibliotecaTransparenciaController::class, 'view_desc_docaud']);


    /*
    *BIBLIOTECA TRANSPARENCIA VISTA PRINCIPAL LOTAIP
    */
    Route::get('/biblioteca-virtual', [BibliotecaTransparenciaController::class, 'view_biblioteca_virtual'])->name('biblioteca-virtual');

});

/*===================================================================================================================================/
/=====================================================ADMINISTRADOR==================================================================/
/===================================================================================================================================*/
Route::middleware(['throttle:cont_admin_vistas'])->group(function () {
    /*
    *LOGIN
    */
    Route::get('/loginadmineep', [LoginController::class, 'index']);
    /*Route::get('/login', function () {
        return redirect('/loginadmineep');
    });*/
    Route::get('/logout', [LoginController::class, 'cerrar_sesion'])->name('logout');

    /*
    *REGISTRO
    */
    Route::get('/registro-aep', [RegisterController::class, 'index']);

    /*
    *RECUPERACION ACCESO USUARIO ADMIN
    */
    Route::get('/recovery-aep', [LoginController::class, 'recovery_pass']);

    /*
    *HOME
    */
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    /*
    *NOTICIAS
    */
    Route::get('/registrar_noticia', [NoticiasController::class, 'registrar_noticia'])->name('registrar_noticia');
    //Route::get('/noticias', [NoticiasController::class, 'index'])->name('noticias');
    Route::get('/listado-noticias', [NoticiasController::class, 'list_noticias'])->name('listado-noticias');
    Route::get('/actualizar-noticia/{id}', [NoticiasController::class, 'actualizar_noticia']);


    /*
    *ATENCIÓN CIUDADANA
    */
    Route::get('/atencion-ciudadana', [AtencionCiudadanaController::class, 'index'])->name('atencion-ciudadana');
    Route::get('/atciudadana/seguimiento-solicitud/{idseguimientosoli}', [AtencionCiudadanaController::class, 'seguimiento_solicitudes']);
    Route::post('/registrar-observacion-solicitud', [AtencionCiudadanaController::class, 'store_observacion']);
    Route::post('/change-estado-solicitud', [AtencionCiudadanaController::class, 'change_estado']);
    Route::post('/atencion-ciudadana/filtrar', [AtencionCiudadanaController::class, 'filtrar'])->name('atencion.filtrar');
    Route::get('/atencion-ciudadana/getall', [AtencionCiudadanaController::class, 'getall'])->name('atencion.getall');
    Route::get('/exportar-solicitudes-all-excel', [ReportesController::class, 'exportarSolicitudesExcel']);
    Route::get('/exportar-solicitudes-all-pdf', [ReportesController::class, 'exportarSolicitudesPDF']);
    Route::post('/exportar-solicitudes-filter-excel', [ReportesController::class, 'exportarFilterSolicitudesExcel']);
    Route::post('/exportar-solicitudes-filter-pdf', [ReportesController::class, 'exportarFilterSolicitudesPDF']);

    /*
    *EVENTOS
    */
    Route::get('/eventos', [EventCalendarController::class, 'index'])->name('eventos');

    /*
    *RED SOCIAL
    */
    Route::get('/red-social', [RedSocialController::class, 'index'])->name('red-social');
    Route::get('/agg-red-social', [RedSocialController::class, 'redsocial'])->name('agg-red-social');

    /*
    *CONTACTOS
    */
    Route::get('/contactos', [ContactController::class, 'index'])->name('contactos');
    Route::get('/interface-reg-contacto', [ContactController::class, 'open_interface_registro']);
    Route::get('/interface-reg-location', [ContactController::class, 'open_interface_location']);
    Route::get('/interface-update-location/{id}/{tipo}', [ContactController::class, 'open_interface_update_location']);

    /*
    *AÑOS
    */
    Route::get('/anio', [DateController::class, 'index'])->name('anio');

    /*
    *LITERALES LOTAIP
    */
    Route::get('/setting-lotaip', [LotaipController::class, 'index'])->name('setting-lotaip');

    /*
    *ARTICULOS LOTAIP
    */
    Route::get('/articles-lotaip', [LotaipController::class, 'index_artlotaip'])->name('articles-lotaip');

    /*
    *OPCIONES LOTAIP
    */
    Route::get('/options-lotaip', [LotaipController::class, 'index_optlotaip'])->name('options-lotaip');

    /*
    *BANNER
    */
    Route::get('/banner', [BannerController::class, 'index'])->name('banner');
    Route::get('/registro-banner', [BannerController::class, 'registro_banner']);

    /*
    *IMG INFOR CUENTA
    */
    Route::get('/settings_infor_detaill_cuenta_view', [ContactController::class, 'index_settings_count'])->name('settings_infor_detaill_cuenta_view');
    Route::get('/registro-files-cuenta', [ContactController::class, 'registro_files_cuenta']);

    /*
    *ABOUT
    */
    Route::get('/about', [AboutController::class, 'index'])->name('about');

    /*
    *MISION-VISION-VALORES-OBJETIVOS
    */
    Route::get('/mi-vi-va-ob', [MiViVaObController::class, 'index'])->name('mi-vi-va-ob');
    Route::get('/get_mivivaob', [MiViVaObController::class, 'get_data']);

    /*
    *ESTRUCTURA
    */
    Route::get('/estructura', [EstructuraController::class, 'index'])->name('estructura');

    /*
    *HISTORIA
    */
    Route::get('/historia', [HistoriaController::class, 'index'])->name('historia');
    Route::get('/add-historia', [HistoriaController::class, 'store_history']);
    Route::get('/update-historia', [HistoriaController::class, 'add_history']);

    /*
    *DEPARTAMENTOS
    */
    Route::get('/departamentos', [DepartamentoController::class, 'index'])->name('departamentos');
    Route::get('/registrar-departamento', [DepartamentoController::class, 'add_departamento']);
    Route::get('/registrar-info-departamento', [DepartamentoController::class, 'add_info_departamento']);

    /*
    *SERVICIOS
    */
    Route::get('/servicios', [ServiciosController::class, 'index'])->name('servicios');
    Route::get('/registrar_servicio', [ServiciosController::class, 'registrar_servicio']);

    /*
    *DOCUMENTOS - POA
    */
    Route::get('/poa', [DocumentosController::class, 'poa_index'])->name('poa');
    Route::get('/registrar_poa', [DocumentosController::class, 'poa_register']);

    /*
    *DOCUMENTOS - PAC
    */
    Route::get('/pac', [DocumentosController::class, 'pac_index'])->name('pac');
    Route::get('/registrar_pac', [DocumentosController::class, 'pac_register']);

    /*
    *DOCUMENTOS LOTAIP
    */
    Route::get('/lotaip', [LotaipController::class, 'index_lotaip'])->name('lotaip');
    Route::get('/registrar-lotaip', [LotaipController::class, 'registro_lotaip']);

    /*
    *DOCUMENTOS LOTAIP V2
    */
    Route::get('/lotaip-v2', [LotaipController::class, 'index_lotaip_v2'])->name('lotaip-v2');
    Route::get('/register-lotaip-v2', [LotaipController::class, 'register_lotaip_v2']);

    /*
    *DOCUMENTOS - PROCESO CONTRATACION
    */
    Route::get('/proceso-contratacion', [DocumentosController::class, 'procesoc_index'])->name('proceso-contratacion');

    /*
    *DOCUMENTOS - REGLAMENTOS
    */
    Route::get('/leyes', [DocumentosController::class, 'leyes_index'])->name('leyes');
    Route::get('/registrar_ley', [DocumentosController::class, 'ley_register']);

    /*
    *DOCUMENTOS - LEY DE TRANSPARENCIA
    */
    Route::get('/ley-transparencia', [LeyTransparenciaController::class, 'index'])->name('ley-transparencia');
    Route::get('/add-ley-transparencia', [LeyTransparenciaController::class, 'add_ley_transparencia']);

    /*
    *DOCUMENTOS RENDICION DE CUENTAS
    */
    Route::get('/rendicion-cuentas', [RendicionCuentasController::class, 'index'])->name('rendicion-cuentas');
    Route::get('/registrar-rendicionc', [RendicionCuentasController::class, 'registro_rendicionc']);

    /*
    *DOCUMENTOS - PLIEGO TARIFARIO
    */
    Route::get('/pliego-tarifario', [PliegoTarifarioController::class, 'index'])->name('pliego-tarifario');
    Route::get('/registrar_pliego', [PliegoTarifarioController::class, 'pliego_register']);

    /*
    *DOCUMENTOS - AUDITORIA
    */
    Route::get('/auditoria-interna', [AuditoriaController::class, 'index'])->name('auditoria-interna');
    Route::get('/registrar_auditoria', [AuditoriaController::class, 'auditoria_register']);

    /*
    *DOCUMENTOS - MEDIOS VERIFICACION
    */
    Route::get('/medios-verificacion', [MediosVerificacionController::class, 'index'])->name('medios-verificacion');
    Route::get('/registrar_mediosv', [MediosVerificacionController::class, 'mediosv_register']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS ADMINISTRATIVO
    */
    Route::get('/docadministrativo', [DocAdministrativoController::class, 'index'])->name('docadministrativo');
    Route::get('/registrar_doc_administrativo', [DocAdministrativoController::class, 'doc_administrativo_register']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS FINANCIERO
    */
    Route::get('/docfinanciero', [DocFinancieroController::class, 'index'])->name('docfinanciero');
    Route::get('/registrar_doc_financiero', [DocFinancieroController::class, 'doc_financiero_register']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS OPERATIVA
    */
    Route::get('/docoperativo', [DocOperativoController::class, 'index'])->name('docoperativo');
    Route::get('/registrar_doc_operativo', [DocOperativoController::class, 'doc_operativo_register']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS LABORAL
    */
    Route::get('/doclaboral', [DocLaboralController::class, 'index'])->name('doclaboral');
    Route::get('/registrar_doc_laboral', [DocLaboralController::class, 'doc_laboral_register']);

    /*
    *PERFIL DE USUARIOS
    */
    Route::get('/perfil-usuario', [UsuariosController::class, 'index_perfil_usuario'])->name('perfil-usuario');

    /*
    *USUARIOS
    */
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios');
    Route::get('/registrar-new-usuario', [UsuariosController::class, 'registrar_usuario']);

    /*
    *PERFIL DE USUARIOS
    */
    Route::get('/perfil', [UsuariosController::class, 'index_settings'])->name('perfil');

    /*
    *NOTIFICACIONES ADMINISTRADOR
    */
    Route::get('/all-notifications', [NotificacionController::class, 'index'])->name('all-notifications');
    Route::get('/get-notificacion', [NotificacionController::class, 'get_notificacion']);
    Route::get('/get-all-notificacion', [NotificacionController::class, 'get_all_notificacion']);
    Route::get('/get-today-notificacion', [NotificacionController::class, 'get_today_notificacion']);
    Route::get('/get-read-notificacion', [NotificacionController::class, 'get_read_notificacion']);
    Route::get('/get-contador-notificacion', [NotificacionController::class, 'get_contador_notificacion']);

    /*
    *BIBLIOTECA VIRTUAL
    */
    Route::get('/library-externo', [BibliotecaVirtualController::class, 'index'])->name('library-externo');

    /*
    *LOGO INSTITUCIONAL
    */
    Route::get('/logo-institucion', [LogoController::class, 'index'])->name('logo-institucion');
    Route::get('/registrar-logo', [LogoController::class, 'registrar_logo']);

    Route::get('/logo/get-logos', [LogoController::class, 'get_logos']);
});

Route::middleware(['throttle:cont_admin_query_login'])->group(function () {
    /*
    *LOGIN
    */
    Route::post('/iniciar-sesion', [LoginController::class, 'iniciar_sesion']);

    /*
    *REGISTRO
    */
    Route::post('/registrar-usuario', [RegisterController::class, 'registrar_usuario']);

    /*
    *RECUPERACION ACCESO USUARIO ADMIN
    */
    Route::post('/get-password-admin', [LoginController::class, 'get_code_access']);
    Route::post('/cambiar-password-usuario', [LoginController::class, 'registrar_clave_usuario']);
});

Route::middleware(['throttle:cont_admin_query'])->group(function () {
    /*
    *NOTICIAS
    */
    Route::post('/registrar-noticia', [NoticiasController::class, 'store_noticia']);
    Route::post('/actualizar-noticia-texto', [NoticiasController::class, 'actualizar_noticia_texto']);
    Route::post('/actualizar-noticia-img', [NoticiasController::class, 'actualizar_noticia_img']);
    Route::post('/in-activar-img-noticia', [NoticiasController::class, 'inactivar_img_noticia']);
    Route::post('/in-activar-noticia', [NoticiasController::class, 'inactivar_noticia']);

    /*
    *EVENTOS
    */
    Route::get('/get-eventos', [EventCalendarController::class, 'get_eventos']);
    Route::post('/registro-eventos', [EventCalendarController::class, 'registro_eventos']);
    Route::post('/eventos/get-item-select', [EventCalendarController::class, 'get_evento_select']);
    Route::post('/actualizar-evento', [EventCalendarController::class, 'actualizar_eventos']);
    Route::post('/inactivar-evento', [EventCalendarController::class, 'inactivar_eventos']);

    /*
    *RED SOCIAL
    */
    Route::post('/registro-socialm', [RedSocialController::class, 'registro_socialm']);
    Route::get('/get-socialm-item/{id}', [RedSocialController::class, 'get_socialm_item']);
    Route::post('/actualizar-socialmedia', [RedSocialController::class, 'update_socialmedia']);
    Route::post('/in-activar-socialm', [RedSocialController::class, 'inactivar_socialmedia']);
    Route::post('/delete-socialm', [RedSocialController::class, 'delete_socialmedia']);

    Route::post('/registrar-red-social', [RedSocialController::class, 'registrar_redsocial']);
    Route::get('/get-red-social/{id}', [RedSocialController::class, 'get_redsocial']);
    Route::post('/actualizar-red-social', [RedSocialController::class, 'actualizar_redsocial']);
    Route::post('/in-activar-reds', [RedSocialController::class, 'inactivar_redsocial']);

    /*
    *CONTACTOS
    */
    Route::post('/registro-contacto', [ContactController::class, 'store_registro']);
    Route::post('/registro-location-contacto', [ContactController::class, 'store_location_registro']);
    Route::post('/actualizar-contacto-geo', [ContactController::class, 'actualizar_contacto_geo']);
    Route::get('/get-contact-item/{id}', [ContactController::class, 'get_contact_item']);
    Route::post('/actualizar-contacto-diteem', [ContactController::class, 'actualizar_contacto']);
    Route::post('/actualizar-contacto-hour', [ContactController::class, 'actualizar_contacto_hour']);

    /*
    *AÑOS
    */
    Route::post('/registro-year', [DateController::class, 'registro_year']);
    Route::get('/get-year/{id}', [DateController::class, 'get_year']);
    Route::post('/actualizar-year', [DateController::class, 'update_year']);
    Route::post('/in-activar-year', [DateController::class, 'inactivar_year']);

    /*
    *LITERALES LOTAIP
    */
    Route::post('/registro-item-lotaip', [LotaipController::class, 'registro_item_lotaip']);
    Route::get('/get-item-lotaip/{id}', [LotaipController::class, 'get_item_lotaip']);
    Route::post('/actualizar-item-lotaip', [LotaipController::class, 'update_item_lotaip']);
    Route::post('/in-activar-item-lotaip', [LotaipController::class, 'inactivar_item_lotaip']);

    /*
    *ARTICULOS LOTAIP
    */
    Route::post('/registro-articulo-lotaip', [LotaipController::class, 'registro_articulo_lotaip']);
    Route::get('/get-articulo-lotaip/{id}', [LotaipController::class, 'get_articulo_lotaip']);
    Route::post('/actualizar-articulo-lotaip', [LotaipController::class, 'update_articulo_lotaip']);
    Route::post('/in-activar-articulo-lotaip', [LotaipController::class, 'inactivar_articulo_lotaip']);

    /*
    *OPCIONES LOTAIP
    */
    Route::post('/registro-opcion-lotaip', [LotaipController::class, 'registro_opcion_lotaip']);
    Route::get('/get-opcion-lotaip/{id}', [LotaipController::class, 'get_opcion_lotaip']);
    Route::post('/actualizar-opcion-lotaip', [LotaipController::class, 'update_opcion_lotaip']);
    Route::post('/in-activar-opciones-lotaip', [LotaipController::class, 'inactivar_opciones_lotaip']);

    /*
    *BANNER
    */
    Route::post('/banner/registro-banner', [BannerController::class, 'store_banner']);
    Route::post('/banner/registro-orden-banner', [BannerController::class, 'registro_orden_banner']);
    Route::post('/in-activar-banner', [BannerController::class, 'inactivar_banner']);
    Route::post('/delete-banner', [BannerController::class, 'delete_banner']);
    Route::get('/download-banner/{id}', [BannerController::class, 'download_banner']);

    /*
    *IMG INFOR CUENTA
    */
    Route::post('/cuentas/registro-file-cuenta', [ContactController::class, 'store_file_cuenta']);
    Route::post('/in-activar-cuentafile', [ContactController::class, 'inactivar_cuentafile']);
    Route::get('/download-cuenta-file/{id}', [ContactController::class, 'download_cuenta_file']);
    Route::post('/delete-cuentafile', [ContactController::class, 'delete_cuentafile']);

    /*
    *ABOUT
    */
    Route::post('/registrar-about', [AboutController::class, 'registrar_about']);
    Route::post('/actualizar-img-about', [AboutController::class, 'actualizar_img_about']);
    Route::post('/in-activar-img-about', [AboutController::class, 'inactivar_img_about']);

    /*
    *MISION-VISION-VALORES-OBJETIVOS
    */
    Route::post('/registrar-mivivaob', [MiViVaObController::class, 'registrar_mivivaob']);
    Route::post('/eliminar-objindi', [MiViVaObController::class, 'eliminar_objetivo']);
    Route::post('/in-activar-objindi', [MiViVaObController::class, 'inactivar_objetivo']);
    Route::post('/registro-objetivo', [MiViVaObController::class, 'registrar_objetivo']);
    Route::post('/registro-valor', [MiViVaObController::class, 'registrar_valor']);
    Route::post('/in-activar-valindi', [MiViVaObController::class, 'inactivar_valor']);
    Route::post('/eliminar-valorindi', [MiViVaObController::class, 'eliminar_valor']);

    /*
    *ESTRUCTURA
    */
    Route::post('/registrar-estructura', [EstructuraController::class, 'registrar_estructura']);
    Route::post('/save-structure', [EstructuraController::class, 'save_estructura']);
    Route::post('/in-activar-img-estructura', [EstructuraController::class, 'inactivar_img_estructura']);
    Route::post('/actualizar-estructura-img', [EstructuraController::class, 'actualizar_estructura_img']);

    /*
    *HISTORIA
    */
    Route::post('/registrar-historia', [HistoriaController::class, 'registrar_historia']);
    Route::get('/storage/{archivo}', [HistoriaController::class, 'download_img']);
    Route::post('/activar-imghistoria-delete', [HistoriaController::class, 'activar_imghistoria_delete']);
    Route::post('/actualizar-historia', [HistoriaController::class, 'actualizar_historia']);

    /*
    *DEPARTAMENTOS
    */
    Route::get('/get-departamento/{tipo}', [DepartamentoController::class, 'get_departamento']);
    Route::post('/registrar-dept', [DepartamentoController::class, 'store_departamento']);
    Route::post('/in-activar-dept', [DepartamentoController::class, 'inactivar_departamento']);
    Route::get('/get-departamento-indi/{tipo}/{id}', [DepartamentoController::class, 'get_depar_indi']);
    Route::post('/actualizar-dept', [DepartamentoController::class, 'update_departamento']);
    Route::get('/actualizar-info-departamento/{id}', [DepartamentoController::class, 'get_up_info_departamento']);
    Route::get('/get-info-departamento/{tipo}', [DepartamentoController::class, 'get_info_departamento']);
    Route::post('/registro-info-depart', [DepartamentoController::class, 'insert_info_departamento']);
    Route::post('/in-activar-info-dept', [DepartamentoController::class, 'inactivar_info_departamento']);
    Route::post('/update-info-depart', [DepartamentoController::class, 'update_info_departamento']);

    /*
    *SERVICIOS
    */
    Route::post('/store-service', [ServiciosController::class, 'store_service']);
    Route::get('/edit-service/{id}', [ServiciosController::class, 'edit_service']);
    Route::post('/update-service', [ServiciosController::class, 'update_service']);
    Route::post('/in-activar-servicio', [ServiciosController::class, 'inactivar_servicio']);
    Route::post('/actualizar-service-img', [ServiciosController::class, 'actualizar_servicio_img']);
    Route::post('/actualizar-service-icono', [ServiciosController::class, 'actualizar_servicio_icono']);
    Route::get('/download-archivo-service/{id}/{option}', [ServiciosController::class, 'download_archivo_service']);
    Route::post('/delete-oneservice', [ServiciosController::class, 'eliminar_servicio']);

    /*
    *SERVICIOS - SUBSERVICIOS
    */
    Route::get('/listsubservice-services/{ids}', [SubserviceController::class, 'index']);
    Route::get('/registrar_subservicio/{idser}', [SubserviceController::class, 'registrar_subservicio']);
    Route::post('/store-subservice', [SubserviceController::class, 'store_subservice']);
    Route::get('/get-name-subservice/{id}', [SubserviceController::class, 'get_namesubservice']);
    Route::post('/actualizar-subservicio', [SubserviceController::class, 'actualizar_subservicio']);
    Route::post('/eliminar-subservicio', [SubserviceController::class, 'eliminar_subservicio']);

    /*
    *SERVICIOS - SUBSERVICIOS INFORMACION
    */
    Route::get('/subservice-detail-infor/{id}/{version}/{interface}', [SubserviceController::class, 'register_detail_info']);
    Route::get('/subservice-view-detail-infor/{id}/{version}', [SubserviceController::class, 'view_detail_info']);
    Route::post('/store_detail_infor_subservice', [SubserviceController::class, 'store_detailinfor_subservice']);
    Route::post('/in-activar-subservicioinfodetail', [SubserviceController::class, 'inactivar_subservice_detailinfo']);
    Route::post('/delete-subservicioinfodetail', [SubserviceController::class, 'delete_subservice_detailinfo']);
    Route::get('/subservice-updatedetail-infor/{id}/{version}', [SubserviceController::class, 'update_detail_info']);
    Route::post('/update_subservice_infodetail', [SubserviceController::class, 'update_subservice_infodetail']);
    Route::post('/actualizar-subservice-img-infodet', [SubserviceController::class, 'actualizar_subservicio_img_infodetail']);

    /*
    *SERVICIOS - SUBSERVICIOS LISTA
    */
    Route::get('/subservice-view-detaillist/{id}/{version}', [SubserviceController::class, 'view_detail_lista']);
    Route::get('/subservice-register-list/{id}/{version}/{interface}', [SubserviceController::class, 'view_list_large']);
    Route::post('/store_list_show_subservice', [SubserviceController::class, 'store_showlist_subservice']);
    Route::get('/subservice-updatedetail-list/{id}/{version}', [SubserviceController::class, 'update_detail_list']);
    Route::post('/update_list_show_subservice', [SubserviceController::class, 'update_showlist_subservice']);
    Route::post('/in-activar-subserviciodetaillist', [SubserviceController::class, 'inactivar_subservice_detaillist']);
    Route::post('/delete-subserviciodetaillist', [SubserviceController::class, 'delete_subservice_detaillist']);

    /*
    *SERVICIOS - SUBSERVICIOS TEXTO Y ARCHIVO
    */
    Route::get('/subservice-view-filelist/{id}/{version}', [SubserviceController::class, 'view_detail_filelist']);
    Route::get('/subservice-file-list/{id}/{version}/{interface}', [SubserviceController::class, 'file_list_subservice']);
    Route::post('/store_text_file_subservice', [SubserviceController::class, 'store_textfile_subservice']);
    Route::post('/store-doc-subservice', [SubserviceController::class, 'store_doc_subservicio']);
    Route::post('/in-activar-subserviciofilelist', [SubserviceController::class, 'inactivar_subservice_filelist']);
    Route::post('/delete-subserviciofilelist', [SubserviceController::class, 'delete_subservice_filelist']);
    Route::get('/subservice-updatedetail-filelist/{id}/{version}', [SubserviceController::class, 'update_detail_filelist']);
    Route::post('/actualizar-subservice-positionfile-filelist', [SubserviceController::class, 'actualizar_subservicio_positionfile_filelist']);
    Route::post('/actualizar-subservice-file-filelist', [SubserviceController::class, 'actualizar_subservicio_file_filelist']);
    Route::post('/actualizar-subservice-textfilelist', [SubserviceController::class, 'actualizar_subservicio_textfilelist']);

    Route::get('/download-archivo-subservice/{id}/{table}', [SubserviceController::class, 'download_archivo_subservice']);

    /*
    *DOCUMENTOS - POA
    */
    Route::post('/store-poa', [DocumentosController::class, 'store_poa']);
    Route::get('/view-poa/{id}', [DocumentosController::class, 'view_poa']);
    Route::get('/edit-poa/{id}', [DocumentosController::class, 'edit_poa']);
    Route::post('/in-activar-poa', [DocumentosController::class, 'inactivar_poa']);
    Route::get('/download-poa/{id}/{tipo}', [DocumentosController::class, 'download_poa']);
    Route::post('/update-poa', [DocumentosController::class, 'update_poa']);
    Route::get('/view-reforma-poa/{id}', [DocumentosController::class, 'view_reforma_poa']);

    /*
    *DOCUMENTOS - POA REFORMADOS
    */
    Route::get('/view-ref-poa/{id}', [DocumentosController::class, 'view_poa_reformado']);
    Route::get('/edit-ref-poa/{id}', [DocumentosController::class, 'edit_ref_poa']);
    Route::post('/update-ref-poa', [DocumentosController::class, 'update_ref_poa']);

    /*
    *DOCUMENTOS - PAC
    */
    Route::post('/store-pac', [DocumentosController::class, 'store_pac']);
    Route::get('/view-pac/{id}', [DocumentosController::class, 'view_pac']);
    Route::get('/edit-pac/{id}', [DocumentosController::class, 'edit_pac']);
    Route::post('/in-activar-pac', [DocumentosController::class, 'inactivar_pac']);
    Route::get('/download-pac/{id}/{tipo}', [DocumentosController::class, 'download_pac']);
    Route::get('/download-ra/{id}/{tipo}', [DocumentosController::class, 'download_ra']);
    Route::post('/update-pac', [DocumentosController::class, 'update_pac']);
    Route::get('/view-reforma-pac/{id}', [DocumentosController::class, 'view_reforma_pac']);

    /*
    *DOCUMENTOS - PAC REFORMADOS
    */
    Route::get('/view-ref-pac/{id}', [DocumentosController::class, 'view_pac_reformado']);
    Route::get('/edit-ref-pac/{id}', [DocumentosController::class, 'edit_ref_pac']);
    Route::post('/update-ref-pac', [DocumentosController::class, 'update_ref_pac']);

    /*
    *DOCUMENTOS LOTAIP
    */
    Route::post('/store-lotaip', [LotaipController::class, 'store_lotaip']);
    Route::get('/view-lotaip/{id}', [LotaipController::class, 'view_lotaip']);
    Route::get('/edit-lotaip/{id}', [LotaipController::class, 'edit_lotaip']);
    Route::post('/update-lotaip', [LotaipController::class, 'update_lotaip']);
    Route::post('/in-activar-lotaip', [LotaipController::class, 'inactivar_lotaip']);
    Route::get('/download-lotaip/{id}', [LotaipController::class, 'download_lotaip']);

    /*
    *DOCUMENTOS LOTAIP V2
    */
    Route::get('/get-literal-lotaip/{id}', [LotaipController::class, 'get_literal_lotaip']);
    Route::post('/store-lotaipv2', [LotaipController::class, 'store_lotaip_v2']);
    Route::get('/view-lotaipv2/{id}/{tipo}', [LotaipController::class, 'view_lotaip_v2']);
    Route::get('/download-lotaipv2/{id}/{tipo}', [LotaipController::class, 'download_lotaip_v2']);
    Route::get('/edit-lotaipv2/{id}/{tipo}', [LotaipController::class, 'edit_lotaip_v2']);
    Route::post('/update-lotaipv2', [LotaipController::class, 'update_lotaip_v2']);

    /*
    *DOCUMENTOS - PROCESO CONTRATACION
    */
    Route::post('/registro-proceso', [DocumentosController::class, 'store_proceso']);
    Route::get('/get-inforproceso/{id}', [DocumentosController::class, 'get_infor_proceso']);
    Route::post('/editar-proceso', [DocumentosController::class, 'update_proceso']);

    /*
    *DOCUMENTOS - REGLAMENTOS
    */
    Route::post('/store-ley', [DocumentosController::class, 'store_ley']);
    Route::get('/view-ley/{id}', [DocumentosController::class, 'view_ley']);
    Route::get('/edit-ley/{id}', [DocumentosController::class, 'edit_ley']);
    Route::post('/in-activar-ley', [DocumentosController::class, 'inactivar_ley']);
    Route::get('/download-ley/{id}', [DocumentosController::class, 'download_ley']);
    Route::post('/update-ley', [DocumentosController::class, 'update_ley']);

    /*
    *DOCUMENTOS - LEY DE TRANSPARENCIA
    */
    Route::post('/registrar-ley-transparencia', [LeyTransparenciaController::class, 'registrar_ley_transparencia']);
    Route::get('/view-transparencia/{id}', [LeyTransparenciaController::class, 'view_ley']);
    Route::get('/update-ley-transparencia', [LeyTransparenciaController::class, 'update_ley_transparencia']);
    Route::post('/actualizar-ley-transparencia', [LeyTransparenciaController::class, 'store_up_ley_transparencia']);
     Route::get('/edit-leytransparencia/{id}', [LeyTransparenciaController::class, 'edit_ley']);
    Route::post('/update-leytransparencia', [LeyTransparenciaController::class, 'update_ley']);
    Route::post('/in-activar-leytransparencia', [LeyTransparenciaController::class, 'inactivar_ley']);
    Route::get('/download-leytransparencia/{id}', [LeyTransparenciaController::class, 'download_ley']);

    /*
    *DOCUMENTOS RENDICION DE CUENTAS
    */
    Route::post('/store-rendicionc', [RendicionCuentasController::class, 'store_rendicionc']);
    Route::get('/view-rendicionc/{id}', [RendicionCuentasController::class, 'view_rendicionc']);
    Route::get('/edit-rendicionc/{id}', [RendicionCuentasController::class, 'edit_rendicionc']);
    Route::post('/update-rendicionc', [RendicionCuentasController::class, 'update_rendicionc']);
    Route::post('/in-activar-rendicionc', [RendicionCuentasController::class, 'inactivar_rendicionc']);
    Route::get('/download-rendicionc/{id}', [RendicionCuentasController::class, 'download_rendicionc']);

    /*
    *DOCUMENTOS - PLIEGO TARIFARIO
    */
    Route::post('/store-pliego', [PliegoTarifarioController::class, 'store_pliego']);
    Route::get('/view-pliego/{id}', [PliegoTarifarioController::class, 'view_pliego']);
    Route::get('/edit-pliego/{id}', [PliegoTarifarioController::class, 'edit_pliego']);
    Route::post('/in-activar-pliego', [PliegoTarifarioController::class, 'inactivar_pliego']);
    Route::get('/download-pliego/{id}', [PliegoTarifarioController::class, 'download_pliego']);
    Route::post('/update-pliego', [PliegoTarifarioController::class, 'update_pliego']);

    /*
    *DOCUMENTOS - AUDITORIA
    */
    Route::post('/store-auditoria', [AuditoriaController::class, 'store_auditoria']);
    Route::get('/view-auditoria/{id}', [AuditoriaController::class, 'view_auditoria']);
    Route::get('/edit-auditoria/{id}', [AuditoriaController::class, 'edit_auditoria']);
    Route::post('/in-activar-auditoria', [AuditoriaController::class, 'inactivar_auditoria']);
    Route::get('/download-auditoria/{id}', [AuditoriaController::class, 'download_auditoria']);
    Route::post('/update-auditoria', [AuditoriaController::class, 'update_auditoria']);

    /*
    *DOCUMENTOS - MEDIOS VERIFICACION
    */
    Route::post('/store-mediosv', [MediosVerificacionController::class, 'registro_mediosv']);
    Route::get('/edit-mediosv/{id}', [MediosVerificacionController::class, 'edit_mediosv']);
    Route::post('/update-mediosv', [MediosVerificacionController::class, 'update_mediosv']);
    Route::get('/view-mediosv/{id}', [MediosVerificacionController::class, 'view_mediosv']);
    Route::post('/in-activar-mediosv', [MediosVerificacionController::class, 'inactivar_mediosv']);
    Route::post('/in-activar-file-mediosv', [MediosVerificacionController::class, 'inactivar_file_mediosv']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS ADMINISTRATIVO
    */
    Route::post('/store-doc-administrativo', [DocAdministrativoController::class, 'store_doc_administrativo']);
    Route::get('/view-docadministrativo/{id}', [DocAdministrativoController::class, 'view_doc_administrativo']);
    Route::get('/edit-docadministrativo/{id}', [DocAdministrativoController::class, 'edit_doc_administrativo']);
    Route::post('/in-activar-docadministrativo', [DocAdministrativoController::class, 'inactivar_doc_administrativo']);
    Route::get('/download-docadministrativo/{id}', [DocAdministrativoController::class, 'download_doc_administrativo']);
    Route::post('/update-docadministrativo', [DocAdministrativoController::class, 'update_doc_administrativo']);
    Route::post('/delete-docadministrativo', [DocAdministrativoController::class, 'delete_doc_administrativo']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS FINANCIERO
    */
    Route::post('/store-doc-financiero', [DocFinancieroController::class, 'store_doc_financiero']);
    Route::get('/view-docfinanciero/{id}', [DocFinancieroController::class, 'view_doc_financiero']);
    Route::get('/edit-docfinanciero/{id}', [DocFinancieroController::class, 'edit_doc_financiero']);
    Route::post('/in-activar-docfinanciero', [DocFinancieroController::class, 'inactivar_doc_financiero']);
    Route::get('/download-docfinanciero/{id}', [DocFinancieroController::class, 'download_doc_financiero']);
    Route::post('/update-docfinanciero', [DocFinancieroController::class, 'update_doc_financiero']);
    Route::post('/delete-docfinanciero', [DocFinancieroController::class, 'delete_doc_financiero']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS OPERATIVA
    */
    Route::post('/store-doc-operativo', [DocOperativoController::class, 'store_doc_operativo']);
    Route::get('/view-docoperativo/{id}', [DocOperativoController::class, 'view_doc_operativo']);
    Route::get('/edit-docoperativo/{id}', [DocOperativoController::class, 'edit_doc_operativo']);
    Route::post('/in-activar-docoperativo', [DocOperativoController::class, 'inactivar_doc_operativo']);
    Route::get('/download-docoperativo/{id}', [DocOperativoController::class, 'download_doc_operativo']);
    Route::post('/update-docoperativo', [DocOperativoController::class, 'update_doc_operativo']);
    Route::post('/delete-docoperativo', [DocOperativoController::class, 'delete_doc_operativo']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS LABORAL
    */
    Route::post('/store-doc-laboral', [DocLaboralController::class, 'store_doc_laboral']);
    Route::get('/view-doclaboral/{id}', [DocLaboralController::class, 'view_doc_laboral']);
    Route::get('/edit-doclaboral/{id}', [DocLaboralController::class, 'edit_doc_laboral']);
    Route::post('/in-activar-doclaboral', [DocLaboralController::class, 'inactivar_doc_laboral']);
    Route::get('/download-doclaboral/{id}', [DocLaboralController::class, 'download_doc_laboral']);
    Route::post('/update-doclaboral', [DocLaboralController::class, 'update_doc_laboral']);
    Route::post('/delete-doclaboral', [DocLaboralController::class, 'delete_doc_laboral']);

    /*
    *PERFIL DE USUARIOS
    */
    Route::post('/registro-perfiluser', [UsuariosController::class, 'registrar_perfil_usuario']);
    Route::get('/get-profile-user/{id}', [UsuariosController::class, 'get_perfil_usuario']);
    Route::post('/in-activar-profileuser', [UsuariosController::class, 'inactivar_perfil_usuario']);
    Route::post('/actualizar-perfil-usuario', [UsuariosController::class, 'update_perfil_usuario']);

    /*
    *USUARIOS
    */
    Route::post('/store-new-usuario', [UsuariosController::class, 'store_new_usuario']);
    Route::post('/update-password-usuario', [UsuariosController::class, 'update_password_usuario']);
    Route::post('/in-activar-usuario', [UsuariosController::class, 'inactivar_usuario']);
    Route::get('/edit-view-usuario/{id}', [UsuariosController::class, 'edit_view_usuario']);
    Route::post('/update-usuario', [UsuariosController::class, 'update_usuario']);

    /*
    *NOTIFICACIONES ADMINISTRADOR
    */
    Route::get('/read-view-noti/{id}', [NotificacionController::class, 'index_view_notificacion']);
    Route::post('/actualizar_items_notificaciones', [NotificacionController::class, 'update_item_notificacion']);

    /*
    *BIBLIOTECA VIRTUAL
    */
    Route::post('/registro-categoria', [BibliotecaVirtualController::class, 'registro_categoria']);
    Route::post('/actualizar-categoria', [BibliotecaVirtualController::class, 'actualizar_categoria']);
    Route::get('/get-name-categoria/{id}', [BibliotecaVirtualController::class, 'get_namecat']);
    Route::post('/registro-subcategoria', [BibliotecaVirtualController::class, 'registro_subcategoria']);
    Route::get('/registrar_doc_virtual/{idcat}/{idsubcat}/{tipo}', [BibliotecaVirtualController::class, 'doc_virtual_register']);
    Route::get('/view_listdocs_subcatvirtual/{idcat}/{idsubcat}/{tipo}', [BibliotecaVirtualController::class, 'listdoc_virtual_subcat']);
    Route::post('/store-doc-bibliovirtual', [BibliotecaVirtualController::class, 'store_doc_bibliovirtual']);
    Route::post('/in-activar-subcategoria', [BibliotecaVirtualController::class, 'inactivar_doc_subcategoria']);
    Route::get('/get-name-subcategoria/{id}', [BibliotecaVirtualController::class, 'get_namesubcat']);
    Route::post('/actualizar-subcategoria', [BibliotecaVirtualController::class, 'actualizar_subcategoria']);
    Route::post('/in-activar-filesubcategoria', [BibliotecaVirtualController::class, 'inactivar_doc_filesubcategoria']);
    Route::get('/edit_doc_subcatvirtual/{idf}/{opcion}/{tipo}', [BibliotecaVirtualController::class, 'edit_virtual_filesubcat']);
    Route::post('/update-docvirtual', [BibliotecaVirtualController::class, 'update_doc_virtual']);
    Route::post('/delete-file-oncat', [BibliotecaVirtualController::class, 'delete_file_oncat']);
    Route::get('/download-docvirtual/{idf}/{opcion}', [BibliotecaVirtualController::class, 'download_doc_virtual']);
    Route::get('/view-docfilevirtual/{idf}/{opcion}', [BibliotecaVirtualController::class, 'view_doc_filevirtual']);

    
    /*
    *LOGO INSTITUCIONAL
    */
    Route::post('/logo/registro-logo', [LogoController::class, 'storage_logo']);
    Route::get('/download-logo/{id}', [LogoController::class, 'download_logo']);
    Route::post('/in-activar-logo', [LogoController::class, 'inactivar_logo']);
    Route::post('/delete-logo', [LogoController::class, 'delete_logo']);
});