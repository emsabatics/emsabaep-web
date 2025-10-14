<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AsignacionPermisoController;
use App\Http\Controllers\BannerAlcaldiaController;
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
use App\Http\Controllers\ModulosController;
use App\Http\Controllers\PermisosController;
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
use App\Http\Controllers\ReportesContadorController;
use App\Http\Controllers\ReportesController;
use App\Http\Controllers\ServiciosController;
use App\Http\Controllers\SubmodulosController;
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
    Route::get('/', [HomePageController::class, 'index'])->name('/')->middleware('contador.visitas');

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
    Route::get('/biblioteca-virtual-gallery-subcat/{id}', [BibliotecaTransparenciaController::class, 'get_subcat_gallery_biblioteca_virtual']);
    Route::get('/biblioteca-virtual/gallery/{idcat}/{idsubcat}', [BibliotecaTransparenciaController::class, 'show_gallery_biblioteca_virtual']);

    Route::get('/biblioteca-virtual-subcat/{id}', [BibliotecaTransparenciaController::class, 'get_subcat_biblioteca_virtual']);
    Route::get('/biblioteca-virtual/archivos/{idcat}/{idsubcat}', [BibliotecaTransparenciaController::class, 'show_archivos_biblioteca_virtual']);
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

/*Route::middleware(['throttle:cont_admin_query'])->group(function () {});*/

Route::middleware(['checkruta', 'throttle:limit_admin_view'])->group(function () {
    /*
    *HOME
    */
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    /*
    *NOTICIAS
    */
    Route::get('/registrar_noticia', [NoticiasController::class, 'registrar_noticia'])->name('registrar_noticia');
    Route::get('/listado-noticias', [NoticiasController::class, 'list_noticias'])->name('listado-noticias');

    /*
    *EVENTOS
    */
    Route::get('/eventos', [EventCalendarController::class, 'index'])->name('eventos');

    /*
    *ATENCIÓN CIUDADANA
    */
    Route::get('/atencion-ciudadana', [AtencionCiudadanaController::class, 'index'])->name('atencion-ciudadana');

    /*
    *RED SOCIAL
    */
    Route::get('/red-social', [RedSocialController::class, 'index'])->name('red-social');
    Route::get('/agg-red-social', [RedSocialController::class, 'redsocial'])->name('agg-red-social');

    /*
    *CONTACTOS
    */
    Route::get('/contactos', [ContactController::class, 'index'])->name('contactos');

    /*
    *SERVICIOS
    */
    Route::get('/servicios', [ServiciosController::class, 'index'])->name('servicios');

    /*
    *DOCUMENTOS - AUDITORIA
    */
    Route::get('/auditoria-interna', [AuditoriaController::class, 'index'])->name('auditoria-interna');

    /*
    *DOCUMENTOS - REGLAMENTOS
    */
    Route::get('/leyes', [DocumentosController::class, 'leyes_index'])->name('leyes');

    /*
    *DOCUMENTOS - LEY DE TRANSPARENCIA
    */
    Route::get('/ley-transparencia', [LeyTransparenciaController::class, 'index'])->name('ley-transparencia');

    /*
    *DOCUMENTOS LOTAIP
    */
    Route::get('/lotaip', [LotaipController::class, 'index_lotaip'])->name('lotaip');

    /*
    *DOCUMENTOS LOTAIP V2
    */
    Route::get('/lotaip-v2', [LotaipController::class, 'index_lotaip_v2'])->name('lotaip-v2');

    /*
    *DOCUMENTOS - MEDIOS VERIFICACION
    */
    Route::get('/medios-verificacion', [MediosVerificacionController::class, 'index'])->name('medios-verificacion');

    /*
    *DOCUMENTOS - PAC
    */
    Route::get('/pac', [DocumentosController::class, 'pac_index'])->name('pac');

    /*
    *DOCUMENTOS - POA
    */
    Route::get('/poa', [DocumentosController::class, 'poa_index'])->name('poa');

    /*
    *DOCUMENTOS - PLIEGO TARIFARIO
    */
    Route::get('/pliego-tarifario', [PliegoTarifarioController::class, 'index'])->name('pliego-tarifario');

    /*
    *DOCUMENTOS - PROCESO CONTRATACION
    */
    Route::get('/proceso-contratacion', [DocumentosController::class, 'procesoc_index'])->name('proceso-contratacion');

    /*
    *DOCUMENTOS RENDICION DE CUENTAS
    */
    Route::get('/rendicion-cuentas', [RendicionCuentasController::class, 'index'])->name('rendicion-cuentas');

    /*
    *DOCUMENTACIÓN - DOCUMENTOS ADMINISTRATIVO
    */
    Route::get('/docadministrativo', [DocAdministrativoController::class, 'index'])->name('docadministrativo');

    /*
    *DOCUMENTACIÓN - DOCUMENTOS FINANCIERO
    */
    Route::get('/docfinanciero', [DocFinancieroController::class, 'index'])->name('docfinanciero');

    /*
    *DOCUMENTACIÓN - DOCUMENTOS OPERATIVA
    */
    Route::get('/docoperativo', [DocOperativoController::class, 'index'])->name('docoperativo');

    /*
    *DOCUMENTACIÓN - DOCUMENTOS LABORAL
    */
    Route::get('/doclaboral', [DocLaboralController::class, 'index'])->name('doclaboral');

    /*
    *BIBLIOTECA VIRTUAL
    */
    Route::get('/library-externo', [BibliotecaVirtualController::class, 'index'])->name('library-externo');

    /*
    *AÑOS
    */
    Route::get('/anio', [DateController::class, 'index'])->name('anio');

    /*
    *ARTICULOS LOTAIP
    */
    Route::get('/articles-lotaip', [LotaipController::class, 'index_artlotaip'])->name('articles-lotaip');

    /*
    *LITERALES LOTAIP
    */
    Route::get('/setting-lotaip', [LotaipController::class, 'index'])->name('setting-lotaip');

    /*
    *OPCIONES LOTAIP
    */
    Route::get('/options-lotaip', [LotaipController::class, 'index_optlotaip'])->name('options-lotaip');

    /*
    *BANNER
    */
    Route::get('/banner', [BannerController::class, 'index'])->name('banner');

    /*
    *BANNER ALCALDIA
    */
    Route::get('/banner-alcaldia', [BannerAlcaldiaController::class, 'index'])->name('banner-alcaldia');

    /*
    *IMG INFOR CUENTA
    */
    Route::get('/settings_infor_detaill_cuenta_view', [ContactController::class, 'index_settings_count'])->name('settings_infor_detaill_cuenta_view');

    /*
    *LOGO INSTITUCIONAL
    */
    Route::get('/logo-institucion', [LogoController::class, 'index'])->name('logo-institucion');

    /*
    *ABOUT
    */
    Route::get('/about', [AboutController::class, 'index'])->name('about');

    /*
    *MISION-VISION-VALORES-OBJETIVOS
    */
    Route::get('/mi-vi-va-ob', [MiViVaObController::class, 'index'])->name('mi-vi-va-ob');

    /*
    *ESTRUCTURA
    */
    Route::get('/estructura', [EstructuraController::class, 'index'])->name('estructura');

    /*
    *HISTORIA
    */
    Route::get('/historia', [HistoriaController::class, 'index'])->name('historia');

    /*
    *DEPARTAMENTOS
    */
    Route::get('/departamentos', [DepartamentoController::class, 'index'])->name('departamentos');

    /*
    *USUARIOS
    */
    Route::get('/usuarios', [UsuariosController::class, 'index'])->name('usuarios');

    /*
    *PERFIL DE USUARIOS
    */
    Route::get('/perfil-usuario', [UsuariosController::class, 'index_perfil_usuario'])->name('perfil-usuario');
    
    /*
    *PERMISOS USUARIO
    */
    Route::get('/permisos-usuario', [PermisosController::class, 'index'])->name('permisos-usuario');

    /*
    *PERFIL DE USUARIOS
    */
    Route::get('/perfil', [UsuariosController::class, 'index_settings'])->name('perfil');

    /*
    *NOTIFICACIONES ADMINISTRADOR
    */
    Route::get('/all-notifications', [NotificacionController::class, 'index'])->name('all-notifications');
});

Route::middleware(['throttle:limit_admin_view'])->group(function () {
    /*
    *NOTICIAS
    */
    //Route::get('/noticias', [NoticiasController::class, 'index'])->name('noticias');

    /*
    *RED SOCIAL
    */
    Route::get('/agg-red-social', [RedSocialController::class, 'redsocial'])->name('agg-red-social');


    /*
    *CONTACTOS
    */
    Route::get('/interface-reg-contacto', [ContactController::class, 'open_interface_registro']);
    Route::get('/interface-reg-location', [ContactController::class, 'open_interface_location']);
    Route::get('/interface-update-location/{id}/{tipo}', [ContactController::class, 'open_interface_update_location']);

    /*
    *BANNER
    */
    Route::get('/registro-banner', [BannerController::class, 'registro_banner']);

    /*
    *BANNER ALCALDIA
    */
    Route::get('/registro-banner-alcaldia', [BannerAlcaldiaController::class, 'registro_banner_alcaldia']);

    /*
    *IMG INFOR CUENTA
    */
    Route::get('/registro-files-cuenta', [ContactController::class, 'registro_files_cuenta']);

    /*
    *MISION-VISION-VALORES-OBJETIVOS
    */
    Route::get('/get_mivivaob', [MiViVaObController::class, 'get_data']);

    /*
    *HISTORIA
    */
    Route::get('/add-historia', [HistoriaController::class, 'store_history']);
    Route::get('/update-historia', [HistoriaController::class, 'add_history']);

    /*
    *DEPARTAMENTOS
    */
    Route::get('/registrar-departamento', [DepartamentoController::class, 'add_departamento']);
    Route::get('/registrar-info-departamento', [DepartamentoController::class, 'add_info_departamento']);

    /*
    *SERVICIOS
    */
    Route::get('/registrar_servicio', [ServiciosController::class, 'registrar_servicio']);

    /*
    *DOCUMENTOS - POA
    */
    Route::get('/registrar_poa', [DocumentosController::class, 'poa_register']);

    /*
    *DOCUMENTOS - PAC
    */
    Route::get('/registrar_pac', [DocumentosController::class, 'pac_register']);

    /*
    *DOCUMENTOS LOTAIP
    */
    Route::get('/registrar-lotaip', [LotaipController::class, 'registro_lotaip']);

    /*
    *DOCUMENTOS LOTAIP V2
    */
    Route::get('/register-lotaip-v2', [LotaipController::class, 'register_lotaip_v2']);

    /*
    *DOCUMENTOS - REGLAMENTOS
    */
    Route::get('/registrar_ley', [DocumentosController::class, 'ley_register']);

    /*
    *DOCUMENTOS - LEY DE TRANSPARENCIA
    */
    Route::get('/add-ley-transparencia', [LeyTransparenciaController::class, 'add_ley_transparencia']);

    /*
    *DOCUMENTOS RENDICION DE CUENTAS
    */
    Route::get('/registrar-rendicionc', [RendicionCuentasController::class, 'registro_rendicionc']);

    /*
    *DOCUMENTOS - PLIEGO TARIFARIO
    */
    Route::get('/registrar_pliego', [PliegoTarifarioController::class, 'pliego_register']);

    /*
    *DOCUMENTOS - AUDITORIA
    */
    Route::get('/registrar_auditoria', [AuditoriaController::class, 'auditoria_register']);

    /*
    *DOCUMENTOS - MEDIOS VERIFICACION
    */
    Route::get('/registrar_mediosv', [MediosVerificacionController::class, 'mediosv_register']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS ADMINISTRATIVO
    */
    Route::get('/registrar_doc_administrativo', [DocAdministrativoController::class, 'doc_administrativo_register']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS FINANCIERO
    */
    Route::get('/registrar_doc_financiero', [DocFinancieroController::class, 'doc_financiero_register']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS OPERATIVA
    */
    Route::get('/registrar_doc_operativo', [DocOperativoController::class, 'doc_operativo_register']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS LABORAL
    */
    Route::get('/registrar_doc_laboral', [DocLaboralController::class, 'doc_laboral_register']);

    /*
    *USUARIOS
    */
    Route::get('/registrar-new-usuario', [UsuariosController::class, 'registrar_usuario']);
    
    /*
    *NOTIFICACIONES ADMINISTRADOR
    */
    Route::get('/get-notificacion', [NotificacionController::class, 'get_notificacion']);
    Route::get('/get-all-notificacion', [NotificacionController::class, 'get_all_notificacion']);
    Route::get('/get-today-notificacion', [NotificacionController::class, 'get_today_notificacion']);
    Route::get('/get-read-notificacion', [NotificacionController::class, 'get_read_notificacion']);
    Route::get('/get-contador-notificacion', [NotificacionController::class, 'get_contador_notificacion']);

    /*
    *LOGO INSTITUCIONAL
    */
    Route::get('/registrar-logo', [LogoController::class, 'registrar_logo']);

    Route::get('/logo/get-logos', [LogoController::class, 'get_logos']);


    /*
    *MODULOS
    */
    Route::get('/modulos', [ModulosController::class, 'index'])->name('modulos');

    /*
    *SUBMODULOS
    */
    Route::get('/submodulos', [SubmodulosController::class, 'index'])->name('submodulos');

    /*
    *REPORTES CONTADOR
    */
    Route::get('/reportes-contador', [ReportesContadorController::class, 'index'])->name('reportes-contador');

    /*
    *REPORTES CONTADOR DESCARGAS
    */
    Route::get('/reportes-contador-descargas-admin', [ReportesContadorController::class, 'index_descargas_admin'])->name('reportes-contador-descargas-admin');
    Route::get('/reportes-contador-descargas-fin', [ReportesContadorController::class, 'index_descargas_fin'])->name('reportes-contador-descargas-fin');
    Route::get('/reportes-contador-descargas-opt', [ReportesContadorController::class, 'index_descargas_opt'])->name('reportes-contador-descargas-opt');
    Route::get('/reportes-contador-descargas-lab', [ReportesContadorController::class, 'index_descargas_lab'])->name('reportes-contador-descargas-lab');
    Route::get('/reportes-contador-descargas-ley', [ReportesContadorController::class, 'index_descargas_ley'])->name('reportes-contador-descargas-ley');
    Route::get('/reportes-contador-descargas-auditoria', [ReportesContadorController::class, 'index_descargas_auditoria'])->name('reportes-contador-descargas-auditoria');
    Route::get('/reportes-contador-descargas-rendicionc', [ReportesContadorController::class, 'index_descargas_rendicionc'])->name('reportes-contador-descargas-rendicionc');
    Route::get('/reportes-contador-descargas-lotaipv1', [ReportesContadorController::class, 'index_descargas_lotaipv1'])->name('reportes-contador-descargas-lotaipv1');
    Route::get('/reportes-contador-descargas-lotaipv2', [ReportesContadorController::class, 'index_descargas_lotaipv2'])->name('reportes-contador-descargas-lotaipv2');
});

Route::middleware(['throttle:limit_admin_select'])->group(function () {
    /*
    *NOTICIAS
    */
    Route::get('/actualizar-noticia/{id}', [NoticiasController::class, 'actualizar_noticia']);

    /*
    *ATENCIÓN CIUDADANA
    */
    Route::get('/atciudadana/seguimiento-solicitud/{idseguimientosoli}', [AtencionCiudadanaController::class, 'seguimiento_solicitudes']);

    /*
    *EVENTOS
    */
    Route::get('/get-eventos', [EventCalendarController::class, 'get_eventos']);
    Route::post('/eventos/get-item-select', [EventCalendarController::class, 'get_evento_select']);

    /*
    *RED SOCIAL
    */
    Route::get('/get-socialm-item/{id}', [RedSocialController::class, 'get_socialm_item']);
    Route::get('/get-red-social/{id}', [RedSocialController::class, 'get_redsocial']);

    /*
    *CONTACTOS
    */
    Route::get('/get-contact-item/{id}', [ContactController::class, 'get_contact_item']);

    /*
    *AÑOS
    */
    Route::get('/get-year/{id}', [DateController::class, 'get_year']);
    
    /*
    *LITERALES LOTAIP
    */
    Route::get('/get-item-lotaip/{id}', [LotaipController::class, 'get_item_lotaip']);
    
    /*
    *ARTICULOS LOTAIP
    */
    Route::get('/get-articulo-lotaip/{id}', [LotaipController::class, 'get_articulo_lotaip']);

    /*
    *OPCIONES LOTAIP
    */
    Route::get('/get-opcion-lotaip/{id}', [LotaipController::class, 'get_opcion_lotaip']);

    /*
    *BANNER
    */
    Route::get('/download-banner/{id}', [BannerController::class, 'download_banner']);

    /*
    *BANNER ALCALDIA
    */
    Route::get('/download-banner-alcaldia/{id}', [BannerAlcaldiaController::class, 'download_banner_alcaldia']);

    /*
    *IMG INFOR CUENTA
    */
    Route::get('/download-cuenta-file/{id}', [ContactController::class, 'download_cuenta_file']);

    /*
    *ABOUT
    */

    /*
    *MISION-VISION-VALORES-OBJETIVOS
    */

    /*
    *ESTRUCTURA
    */

    /*
    *HISTORIA
    */
    Route::get('/storage/{archivo}', [HistoriaController::class, 'download_img']);

    /*
    *DEPARTAMENTOS
    */
    Route::get('/get-departamento/{tipo}', [DepartamentoController::class, 'get_departamento']);
    Route::get('/get-departamento-indi/{tipo}/{id}', [DepartamentoController::class, 'get_depar_indi']);
    Route::get('/actualizar-info-departamento/{id}', [DepartamentoController::class, 'get_up_info_departamento']);
    Route::get('/get-info-departamento/{tipo}', [DepartamentoController::class, 'get_info_departamento']);

    /*
    *SERVICIOS
    */
    Route::get('/edit-service/{id}', [ServiciosController::class, 'edit_service']);
    Route::get('/download-archivo-service/{id}/{option}', [ServiciosController::class, 'download_archivo_service']);

    /*
    *SERVICIOS - SUBSERVICIOS
    */
    Route::get('/listsubservice-services/{ids}', [SubserviceController::class, 'index']);
    Route::get('/get-name-subservice/{id}', [SubserviceController::class, 'get_namesubservice']);
    Route::get('/registrar_subservicio/{idser}', [SubserviceController::class, 'registrar_subservicio']);

    /*
    *SERVICIOS - SUBSERVICIOS INFORMACION
    */
    Route::get('/subservice-detail-infor/{id}/{version}/{interface}', [SubserviceController::class, 'register_detail_info']);
    Route::get('/subservice-view-detail-infor/{id}/{version}', [SubserviceController::class, 'view_detail_info']);
    Route::get('/subservice-updatedetail-infor/{id}/{version}', [SubserviceController::class, 'update_detail_info']);

    /*
    *SERVICIOS - SUBSERVICIOS LISTA
    */
    Route::get('/subservice-view-detaillist/{id}/{version}', [SubserviceController::class, 'view_detail_lista']);
    Route::get('/subservice-register-list/{id}/{version}/{interface}', [SubserviceController::class, 'view_list_large']);
    Route::get('/subservice-updatedetail-list/{id}/{version}', [SubserviceController::class, 'update_detail_list']);

    /*
    *SERVICIOS - SUBSERVICIOS TEXTO Y ARCHIVO
    */
    Route::get('/subservice-view-filelist/{id}/{version}', [SubserviceController::class, 'view_detail_filelist']);
    Route::get('/subservice-file-list/{id}/{version}/{interface}', [SubserviceController::class, 'file_list_subservice']);
    Route::get('/subservice-updatedetail-filelist/{id}/{version}', [SubserviceController::class, 'update_detail_filelist']);
    Route::get('/download-archivo-subservice/{id}/{table}', [SubserviceController::class, 'download_archivo_subservice']);

    /*
    *DOCUMENTOS - POA
    */
    Route::get('/view-poa/{id}', [DocumentosController::class, 'view_poa']);
    Route::get('/edit-poa/{id}', [DocumentosController::class, 'edit_poa']);
    Route::get('/download-poa/{id}/{tipo}', [DocumentosController::class, 'download_poa']);
    Route::get('/view-reforma-poa/{id}', [DocumentosController::class, 'view_reforma_poa']);

    /*
    *DOCUMENTOS - POA REFORMADOS
    */
    Route::get('/view-ref-poa/{id}', [DocumentosController::class, 'view_poa_reformado']);
    Route::get('/edit-ref-poa/{id}', [DocumentosController::class, 'edit_ref_poa']);

    /*
    *DOCUMENTOS - PAC
    */
    Route::get('/view-pac/{id}', [DocumentosController::class, 'view_pac']);
    Route::get('/edit-pac/{id}', [DocumentosController::class, 'edit_pac']);
    Route::get('/download-pac/{id}/{tipo}', [DocumentosController::class, 'download_pac']);
    Route::get('/download-ra/{id}/{tipo}', [DocumentosController::class, 'download_ra']);
    Route::get('/view-reforma-pac/{id}', [DocumentosController::class, 'view_reforma_pac']);
    
    /*
    *DOCUMENTOS - PAC REFORMADOS
    */
    Route::get('/view-ref-pac/{id}', [DocumentosController::class, 'view_pac_reformado']);
    Route::get('/edit-ref-pac/{id}', [DocumentosController::class, 'edit_ref_pac']);

    /*
    *DOCUMENTOS LOTAIP
    */
    Route::get('/view-lotaip/{id}', [LotaipController::class, 'view_lotaip']);
    Route::get('/edit-lotaip/{id}', [LotaipController::class, 'edit_lotaip']);
    Route::get('/download-lotaip/{id}', [LotaipController::class, 'download_lotaip']);

    /*
    *DOCUMENTOS LOTAIP V2
    */
    Route::get('/get-literal-lotaip/{id}', [LotaipController::class, 'get_literal_lotaip']);
    Route::get('/view-lotaipv2/{id}/{tipo}', [LotaipController::class, 'view_lotaip_v2']);
    Route::get('/download-lotaipv2/{id}/{tipo}', [LotaipController::class, 'download_lotaip_v2']);
    Route::get('/edit-lotaipv2/{id}/{tipo}', [LotaipController::class, 'edit_lotaip_v2']);

    /*
    *DOCUMENTOS - PROCESO CONTRATACION
    */
    Route::get('/get-inforproceso/{id}', [DocumentosController::class, 'get_infor_proceso']);

    /*
    *DOCUMENTOS - REGLAMENTOS
    */
    Route::get('/view-ley/{id}', [DocumentosController::class, 'view_ley']);
    Route::get('/edit-ley/{id}', [DocumentosController::class, 'edit_ley']);
    Route::get('/download-ley/{id}', [DocumentosController::class, 'download_ley']);

    /*
    *DOCUMENTOS - LEY DE TRANSPARENCIA
    */
    Route::get('/view-transparencia/{id}', [LeyTransparenciaController::class, 'view_ley']);
    Route::get('/update-ley-transparencia', [LeyTransparenciaController::class, 'update_ley_transparencia']);
    Route::get('/edit-leytransparencia/{id}', [LeyTransparenciaController::class, 'edit_ley']);
    Route::get('/download-leytransparencia/{id}', [LeyTransparenciaController::class, 'download_ley']);

    /*
    *DOCUMENTOS RENDICION DE CUENTAS
    */
    Route::get('/view-rendicionc/{id}', [RendicionCuentasController::class, 'view_rendicionc']);
    Route::get('/edit-rendicionc/{id}', [RendicionCuentasController::class, 'edit_rendicionc']);
    Route::get('/download-rendicionc/{id}', [RendicionCuentasController::class, 'download_rendicionc']);

    /*
    *DOCUMENTOS - PLIEGO TARIFARIO
    */
    Route::get('/view-pliego/{id}', [PliegoTarifarioController::class, 'view_pliego']);
    Route::get('/edit-pliego/{id}', [PliegoTarifarioController::class, 'edit_pliego']);
    Route::get('/download-pliego/{id}', [PliegoTarifarioController::class, 'download_pliego']);

    /*
    *DOCUMENTOS - AUDITORIA
    */
    Route::get('/view-auditoria/{id}', [AuditoriaController::class, 'view_auditoria']);
    Route::get('/edit-auditoria/{id}', [AuditoriaController::class, 'edit_auditoria']);
    Route::get('/download-auditoria/{id}', [AuditoriaController::class, 'download_auditoria']);

    /*
    *DOCUMENTOS - MEDIOS VERIFICACION
    */
    Route::get('/edit-mediosv/{id}', [MediosVerificacionController::class, 'edit_mediosv']);
    Route::get('/view-mediosv/{id}', [MediosVerificacionController::class, 'view_mediosv']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS ADMINISTRATIVO
    */
    Route::get('/view-docadministrativo/{id}', [DocAdministrativoController::class, 'view_doc_administrativo']);
    Route::get('/edit-docadministrativo/{id}', [DocAdministrativoController::class, 'edit_doc_administrativo']);
    Route::get('/download-docadministrativo/{id}', [DocAdministrativoController::class, 'download_doc_administrativo']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS FINANCIERO
    */
    Route::get('/view-docfinanciero/{id}', [DocFinancieroController::class, 'view_doc_financiero']);
    Route::get('/edit-docfinanciero/{id}', [DocFinancieroController::class, 'edit_doc_financiero']);
    Route::get('/download-docfinanciero/{id}', [DocFinancieroController::class, 'download_doc_financiero']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS OPERATIVA
    */
    Route::get('/view-docoperativo/{id}', [DocOperativoController::class, 'view_doc_operativo']);
    Route::get('/edit-docoperativo/{id}', [DocOperativoController::class, 'edit_doc_operativo']);
    Route::get('/download-docoperativo/{id}', [DocOperativoController::class, 'download_doc_operativo']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS LABORAL
    */
    Route::get('/view-doclaboral/{id}', [DocLaboralController::class, 'view_doc_laboral']);
    Route::get('/edit-doclaboral/{id}', [DocLaboralController::class, 'edit_doc_laboral']);
    Route::get('/download-doclaboral/{id}', [DocLaboralController::class, 'download_doc_laboral']);

    /*
    *BIBLIOTECA VIRTUAL
    */
    Route::get('/get-name-categoria/{id}', [BibliotecaVirtualController::class, 'get_namecat']);
    Route::get('/registrar_docs_virtual/{idcat}/{idsubcat}/{tipo}', [BibliotecaVirtualController::class, 'docs_virtual_register']);
    Route::get('/registrar_gallery_virtual/{idcat}/{idsubcat}/{tipo}', [BibliotecaVirtualController::class, 'galeria_virtual_register']);
    Route::get('/view_listdocs_subcatvirtual/{idcat}/{idsubcat}/{tipo}', [BibliotecaVirtualController::class, 'listdoc_virtual_subcat']);
    Route::get('/view_galleryfiles_subcatvirtual/{idcat}/{idsubcat}/{tipo}', [BibliotecaVirtualController::class, 'galleryfiles_virtual_subcat']);
    Route::get('/get-name-subcategoria/{id}', [BibliotecaVirtualController::class, 'get_namesubcat']);
    Route::get('/edit_doc_subcatvirtual/{idf}/{opcion}/{tipo}', [BibliotecaVirtualController::class, 'edit_virtual_filesubcat']);
    Route::get('/download-docvirtual/{idf}/{opcion}', [BibliotecaVirtualController::class, 'download_doc_virtual']);
    Route::get('/view-docfilevirtual/{idf}/{opcion}', [BibliotecaVirtualController::class, 'view_doc_filevirtual']);
    Route::get('/get-txt-img/{id}', [BibliotecaVirtualController::class, 'get_txt_img']);
    Route::get('/download-filebibliovirtual/{idf}', [BibliotecaVirtualController::class, 'download_file_bibliovirtual']);

    /*
    *LOGO INSTITUCIONAL
    */
    Route::get('/download-logo/{id}', [LogoController::class, 'download_logo']);

    /*
    *USUARIOS
    */
    Route::get('/edit-view-usuario/{id}', [UsuariosController::class, 'edit_view_usuario']);

    /*
    *PERFIL DE USUARIOS
    */
    Route::get('/get-profile-user/{id}', [UsuariosController::class, 'get_perfil_usuario']);

    /*
    *PERMISOS DE USUARIO
    */
    Route::get('/get-all-permisos-usuario', [PermisosController::class, 'get_all_permisos_usuario']);
    Route::post('/get-permisos-usuario', [PermisosController::class, 'get_permisos_usuario']);
    Route::post('/get-permiso-by-usuario', [PermisosController::class, 'obtenerModulosPorRol']);

    /*
    *ASIGNAR PERMISOS ROL CON MODULOS Y SUBMODULOS
    */
    Route::get('/set-permisos-modulo/{id}', [AsignacionPermisoController::class, 'mostrarAsignacion']);

    /*
    *MODULOS
    */
    Route::get('/get-modulo/{id}', [ModulosController::class, 'get_modulo']);

     /*
    *SUBMODULOS
    */
    Route::get('/get-submodulo/{id}', [SubmodulosController::class, 'get_submodulo']);

    /*
    *NOTIFICACIONES ADMINISTRADOR
    */
    Route::get('/read-view-noti/{id}', [NotificacionController::class, 'index_view_notificacion']);

    /*
    *ATENCION CIUDADANA
    */
    Route::get('/atencion-ciudadana/getall', [AtencionCiudadanaController::class, 'getall'])->name('atencion.getall');
    Route::get('/exportar-solicitudes-all-excel', [ReportesController::class, 'exportarSolicitudesExcel']);
    Route::get('/exportar-solicitudes-all-pdf', [ReportesController::class, 'exportarSolicitudesPDF']);
    Route::post('/atencion-ciudadana/filtrar', [AtencionCiudadanaController::class, 'filtrar'])->name('atencion.filtrar');
    Route::post('/exportar-solicitudes-filter-excel', [ReportesController::class, 'exportarFilterSolicitudesExcel']);
    Route::post('/exportar-solicitudes-filter-pdf', [ReportesController::class, 'exportarFilterSolicitudesPDF']);

    /*
    *CONTADOR VISITAS
    */
    Route::post('/repcon/filtrar', [ReportesContadorController::class, 'filtrar']);
});

Route::middleware(['throttle:limit_admin_insert'])->group(function () {
    /*
    *NOTICIAS
    */
    Route::post('/registrar-noticia', [NoticiasController::class, 'store_noticia']);
    
    /*
    *EVENTOS
    */
    Route::post('/registro-eventos', [EventCalendarController::class, 'registro_eventos']);
    
    /*
    *RED SOCIAL
    */
    Route::post('/registro-socialm', [RedSocialController::class, 'registro_socialm']);
    Route::post('/registrar-red-social', [RedSocialController::class, 'registrar_redsocial']);
    
    /*
    *CONTACTOS
    */
    Route::post('/registro-contacto', [ContactController::class, 'store_registro']);
    Route::post('/registro-location-contacto', [ContactController::class, 'store_location_registro']);

    /*
    *AÑOS
    */
    Route::post('/registro-year', [DateController::class, 'registro_year']);

    /*
    *LITERALES LOTAIP
    */
    Route::post('/registro-item-lotaip', [LotaipController::class, 'registro_item_lotaip']);

    /*
    *ARTICULOS LOTAIP
    */
    Route::post('/registro-articulo-lotaip', [LotaipController::class, 'registro_articulo_lotaip']);

    /*
    *OPCIONES LOTAIP
    */
    Route::post('/registro-opcion-lotaip', [LotaipController::class, 'registro_opcion_lotaip']);

    /*
    *BANNER
    */
    Route::post('/banner/registro-banner', [BannerController::class, 'store_banner']);
    Route::post('/banner/registro-orden-banner', [BannerController::class, 'registro_orden_banner']);

    /*
    *BANNER ALCALDIA
    */
    Route::post('/banner/registro-banner-alcaldia', [BannerAlcaldiaController::class, 'store_banner_alcaldia']);
    Route::post('/banner/registro-orden-banner-alcaldia', [BannerAlcaldiaController::class, 'registro_orden_banner_alcaldia']);

    /*
    *IMG INFOR CUENTA
    */
    Route::post('/cuentas/registro-file-cuenta', [ContactController::class, 'store_file_cuenta']);

    /*
    *ABOUT
    */
    Route::post('/registrar-about', [AboutController::class, 'registrar_about']);

    /*
    *MISION-VISION-VALORES-OBJETIVOS
    */
    Route::post('/registrar-mivivaob', [MiViVaObController::class, 'registrar_mivivaob']);
    Route::post('/registro-objetivo', [MiViVaObController::class, 'registrar_objetivo']);
    Route::post('/registro-valor', [MiViVaObController::class, 'registrar_valor']);

    /*
    *ESTRUCTURA
    */
    Route::post('/registrar-estructura', [EstructuraController::class, 'registrar_estructura']);
    Route::post('/save-structure', [EstructuraController::class, 'save_estructura']);

    /*
    *HISTORIA
    */
    Route::post('/registrar-historia', [HistoriaController::class, 'registrar_historia']);

    /*
    *DEPARTAMENTOS
    */
    Route::post('/registrar-dept', [DepartamentoController::class, 'store_departamento']);
    Route::post('/registro-info-depart', [DepartamentoController::class, 'insert_info_departamento']);

    /*
    *SERVICIOS
    */
    Route::post('/store-service', [ServiciosController::class, 'store_service']);

    /*
    *SERVICIOS - SUBSERVICIOS
    */
    Route::post('/store-subservice', [SubserviceController::class, 'store_subservice']);

    /*
    *SERVICIOS - SUBSERVICIOS INFORMACION
    */
    Route::post('/store_detail_infor_subservice', [SubserviceController::class, 'store_detailinfor_subservice']);

    /*
    *SERVICIOS - SUBSERVICIOS LISTA
    */
    Route::post('/store_list_show_subservice', [SubserviceController::class, 'store_showlist_subservice']);

    /*
    *SERVICIOS - SUBSERVICIOS TEXTO Y ARCHIVO
    */
    Route::post('/store_text_file_subservice', [SubserviceController::class, 'store_textfile_subservice']);
    Route::post('/store-doc-subservice', [SubserviceController::class, 'store_doc_subservicio']);

    /*
    *DOCUMENTOS - POA
    */
    Route::post('/store-poa', [DocumentosController::class, 'store_poa']);
    Route::post('/poa-increment', [DocumentosController::class, 'poa_increment']);

    /*
    *DOCUMENTOS - POA REFORMADOS
    */

    /*
    *DOCUMENTOS - PAC
    */
    Route::post('/store-pac', [DocumentosController::class, 'store_pac']);
    Route::post('/pac-increment', [DocumentosController::class, 'pac_increment']);
    Route::post('/pac-increment-resol', [DocumentosController::class, 'pac_increment_resol']);

    /*
    *DOCUMENTOS - PAC REFORMADOS
    */

    /*
    *DOCUMENTOS LOTAIP
    */
    Route::post('/store-lotaip', [LotaipController::class, 'store_lotaip']);
    Route::post('/lotaipv1-increment', [LotaipController::class, 'lotaipv1_increment']);

    /*
    *DOCUMENTOS LOTAIP V2
    */
    Route::post('/store-lotaipv2', [LotaipController::class, 'store_lotaip_v2']);
    Route::post('/lotaipv2-increment-cd', [LotaipController::class, 'lotaip_v2_increment_cd']);
    Route::post('/lotaipv2-increment-md', [LotaipController::class, 'lotaip_v2_increment_md']);
    Route::post('/lotaipv2-increment-dd', [LotaipController::class, 'lotaip_v2_increment_dd']);
    Route::post('/lotaipv2-increment', [LotaipController::class, 'lotaip_v2_increment']);

    /*
    *DOCUMENTOS - PROCESO CONTRATACION
    */
    Route::post('/registro-proceso', [DocumentosController::class, 'store_proceso']);
    /*
    *DOCUMENTOS - REGLAMENTOS
    */
    Route::post('/store-ley', [DocumentosController::class, 'store_ley']);
    Route::post('/reglamento-increment', [DocumentosController::class, 'reglamento_increment']);

    /*
    *DOCUMENTOS - LEY DE TRANSPARENCIA
    */
    Route::post('/registrar-ley-transparencia', [LeyTransparenciaController::class, 'registrar_ley_transparencia']);
    Route::post('/leyt-increment', [LeyTransparenciaController::class, 'ley_increment']);

    /*
    *DOCUMENTOS RENDICION DE CUENTAS
    */
    Route::post('/store-rendicionc', [RendicionCuentasController::class, 'store_rendicionc']);
    Route::post('/rendicionc-increment', [RendicionCuentasController::class, 'rendicionc_increment']);

    /*
    *DOCUMENTOS - PLIEGO TARIFARIO
    */
    Route::post('/store-pliego', [PliegoTarifarioController::class, 'store_pliego']);
    Route::post('/pliegot-increment', [PliegoTarifarioController::class, 'pliego_increment']);

    /*
    *DOCUMENTOS - AUDITORIA
    */
    Route::post('/store-auditoria', [AuditoriaController::class, 'store_auditoria']);
    Route::post('/auditoria-increment', [AuditoriaController::class, 'auditoria_increment']);

    /*
    *DOCUMENTOS - MEDIOS VERIFICACION
    */
    Route::post('/store-mediosv', [MediosVerificacionController::class, 'registro_mediosv']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS ADMINISTRATIVO
    */
    Route::post('/store-doc-administrativo', [DocAdministrativoController::class, 'store_doc_administrativo']);
    Route::post('/docadmin-increment', [DocAdministrativoController::class, 'docadmin_increment']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS FINANCIERO
    */
    Route::post('/store-doc-financiero', [DocFinancieroController::class, 'store_doc_financiero']);
    Route::post('/docfin-increment', [DocFinancieroController::class, 'docfin_increment']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS OPERATIVA
    */
    Route::get('/view-docoperativo/{id}', [DocOperativoController::class, 'view_doc_operativo']);
    Route::get('/edit-docoperativo/{id}', [DocOperativoController::class, 'edit_doc_operativo']);
    Route::get('/download-docoperativo/{id}', [DocOperativoController::class, 'download_doc_operativo']);
    Route::post('/store-doc-operativo', [DocOperativoController::class, 'store_doc_operativo']);
    Route::post('/docoperativo-increment', [DocOperativoController::class, 'docoperativo_increment']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS LABORAL
    */
    Route::post('/store-doc-laboral', [DocLaboralController::class, 'store_doc_laboral']);
    Route::post('/doclaboral-increment', [DocLaboralController::class, 'doclaboral_increment']);

    /*
    *BIBLIOTECA VIRTUAL
    */
    Route::post('/registro-categoria', [BibliotecaVirtualController::class, 'registro_categoria']);
    Route::post('/registro-subcategoria', [BibliotecaVirtualController::class, 'registro_subcategoria']);
    Route::post('/store-doc-bibliovirtual', [BibliotecaVirtualController::class, 'store_doc_bibliovirtual']);
    Route::post('/store-files-bibliovirtual', [BibliotecaVirtualController::class, 'store_files_bibliovirtual']);
    Route::post('/filebibliovir-increment', [BibliotecaVirtualController::class, 'filebibliovir_increment']);

    /*
    *LOGO INSTITUCIONAL
    */
    Route::post('/logo/registro-logo', [LogoController::class, 'storage_logo']);

    /*
    *USUARIOS
    */
    Route::post('/store-new-usuario', [UsuariosController::class, 'store_new_usuario']);

    /*
    *PERFIL DE USUARIOS
    */
    Route::post('/registro-perfiluser', [UsuariosController::class, 'registrar_perfil_usuario']);

    /*
    *PERMISOS DE USUARIO
    */
    Route::post('/permisos/registro_p_modulo', [PermisosController::class, 'registro_permisos_modulo']);
    Route::post('/permisos/registro_ps_modulo', [PermisosController::class, 'registro_permisos_modulo_sinsub']);
    Route::post('/permisos/registro_ps_submodulo', [PermisosController::class, 'registro_permisos_modulo_withsub']);

    /*
    *ASIGNAR PERMISOS ROL CON MODULOS Y SUBMODULOS
    */

    /*
    *MODULOS
    */
    Route::post('/registro-modulo', [ModulosController::class, 'registro_modulo']);
    Route::post('/registro-orden-modulo', [ModulosController::class, 'registro_orden_modulo']);

     /*
    *SUBMODULOS
    */
    Route::post('/registro-submodulo', [SubmodulosController::class, 'registro_submodulo']);

    /*
    *NOTIFICACIONES ADMINISTRADOR
    */

    /*
    *ATENCION CIUDADANA
    */
    Route::post('/registrar-observacion-solicitud', [AtencionCiudadanaController::class, 'store_observacion']);
});

Route::middleware(['throttle:limit_admin_update'])->group(function () {
    /*
    *NOTICIAS
    */
    Route::post('/actualizar-noticia-texto', [NoticiasController::class, 'actualizar_noticia_texto']);
    Route::post('/actualizar-noticia-img', [NoticiasController::class, 'actualizar_noticia_img']);

    /*
    *EVENTOS
    */    
    Route::post('/actualizar-evento', [EventCalendarController::class, 'actualizar_eventos']);

    /*
    *RED SOCIAL
    */
    Route::post('/actualizar-socialmedia', [RedSocialController::class, 'update_socialmedia']);
    Route::post('/actualizar-red-social', [RedSocialController::class, 'actualizar_redsocial']);

    /*
    *CONTACTOS
    */
    Route::post('/actualizar-contacto-geo', [ContactController::class, 'actualizar_contacto_geo']);
    Route::post('/actualizar-contacto-diteem', [ContactController::class, 'actualizar_contacto']);
    Route::post('/actualizar-contacto-hour', [ContactController::class, 'actualizar_contacto_hour']);

    /*
    *AÑOS
    */
    Route::post('/actualizar-year', [DateController::class, 'update_year']);

    /*
    *LITERALES LOTAIP
    */
    Route::post('/actualizar-item-lotaip', [LotaipController::class, 'update_item_lotaip']);

    /*
    *ARTICULOS LOTAIP
    */
    Route::post('/actualizar-articulo-lotaip', [LotaipController::class, 'update_articulo_lotaip']);

    /*
    *OPCIONES LOTAIP
    */
    Route::post('/actualizar-opcion-lotaip', [LotaipController::class, 'update_opcion_lotaip']);

    /*
    *BANNER
    */

    /*
    *IMG INFOR CUENTA
    */

    /*
    *ABOUT
    */
    Route::post('/actualizar-img-about', [AboutController::class, 'actualizar_img_about']);

    /*
    *MISION-VISION-VALORES-OBJETIVOS
    */

    /*
    *ESTRUCTURA
    */
    Route::post('/actualizar-estructura-img', [EstructuraController::class, 'actualizar_estructura_img']);

    /*
    *HISTORIA
    */
    Route::post('/actualizar-historia', [HistoriaController::class, 'actualizar_historia']);

    /*
    *DEPARTAMENTOS
    */
    Route::post('/actualizar-dept', [DepartamentoController::class, 'update_departamento']);
    Route::post('/update-info-depart', [DepartamentoController::class, 'update_info_departamento']);

    /*
    *SERVICIOS
    */
    Route::post('/update-service', [ServiciosController::class, 'update_service']);
    Route::post('/actualizar-service-img', [ServiciosController::class, 'actualizar_servicio_img']);
    Route::post('/actualizar-service-icono', [ServiciosController::class, 'actualizar_servicio_icono']);

    /*
    *SERVICIOS - SUBSERVICIOS
    */
    Route::post('/actualizar-subservicio', [SubserviceController::class, 'actualizar_subservicio']);

    /*
    *SERVICIOS - SUBSERVICIOS INFORMACION
    */
    Route::post('/update_subservice_infodetail', [SubserviceController::class, 'update_subservice_infodetail']);
    Route::post('/actualizar-subservice-img-infodet', [SubserviceController::class, 'actualizar_subservicio_img_infodetail']);

    /*
    *SERVICIOS - SUBSERVICIOS LISTA
    */
    Route::post('/update_list_show_subservice', [SubserviceController::class, 'update_showlist_subservice']);

    /*
    *SERVICIOS - SUBSERVICIOS TEXTO Y ARCHIVO
    */
    Route::post('/actualizar-subservice-positionfile-filelist', [SubserviceController::class, 'actualizar_subservicio_positionfile_filelist']);
    Route::post('/actualizar-subservice-file-filelist', [SubserviceController::class, 'actualizar_subservicio_file_filelist']);
    Route::post('/actualizar-subservice-textfilelist', [SubserviceController::class, 'actualizar_subservicio_textfilelist']);

    /*
    *DOCUMENTOS - POA
    */
    Route::post('/update-poa', [DocumentosController::class, 'update_poa']);

    /*
    *DOCUMENTOS - POA REFORMADOS
    */
    Route::post('/update-ref-poa', [DocumentosController::class, 'update_ref_poa']);

    /*
    *DOCUMENTOS - PAC
    */
    Route::post('/update-pac', [DocumentosController::class, 'update_pac']);

    /*
    *DOCUMENTOS - PAC REFORMADOS
    */
    Route::post('/update-ref-pac', [DocumentosController::class, 'update_ref_pac']);

    /*
    *DOCUMENTOS LOTAIP
    */
    Route::post('/update-lotaip', [LotaipController::class, 'update_lotaip']);

    /*
    *DOCUMENTOS LOTAIP V2
    */
    Route::post('/update-lotaipv2', [LotaipController::class, 'update_lotaip_v2']);

    /*
    *DOCUMENTOS - PROCESO CONTRATACION
    */
    Route::post('/editar-proceso', [DocumentosController::class, 'update_proceso']);

    /*
    *DOCUMENTOS - REGLAMENTOS
    */
    Route::post('/update-ley', [DocumentosController::class, 'update_ley']);

    /*
    *DOCUMENTOS - LEY DE TRANSPARENCIA
    */
    Route::post('/actualizar-ley-transparencia', [LeyTransparenciaController::class, 'store_up_ley_transparencia']);
    Route::post('/update-leytransparencia', [LeyTransparenciaController::class, 'update_ley']);

    /*
    *DOCUMENTOS RENDICION DE CUENTAS
    */
    Route::post('/update-rendicionc', [RendicionCuentasController::class, 'update_rendicionc']);

    /*
    *DOCUMENTOS - PLIEGO TARIFARIO
    */
    Route::post('/update-pliego', [PliegoTarifarioController::class, 'update_pliego']);

    /*
    *DOCUMENTOS - AUDITORIA
    */
    Route::post('/update-auditoria', [AuditoriaController::class, 'update_auditoria']);

    /*
    *DOCUMENTOS - MEDIOS VERIFICACION
    */
    Route::post('/update-mediosv', [MediosVerificacionController::class, 'update_mediosv']);
    Route::post('/update-texto-mediosv', [MediosVerificacionController::class, 'update_texto_mediosv']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS ADMINISTRATIVO
    */
    Route::post('/update-docadministrativo', [DocAdministrativoController::class, 'update_doc_administrativo']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS FINANCIERO
    */
    Route::post('/update-docfinanciero', [DocFinancieroController::class, 'update_doc_financiero']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS OPERATIVA
    */
    Route::post('/update-docoperativo', [DocOperativoController::class, 'update_doc_operativo']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS LABORAL
    */
    Route::post('/update-doclaboral', [DocLaboralController::class, 'update_doc_laboral']);

    /*
    *BIBLIOTECA VIRTUAL
    */
    Route::post('/actualizar-categoria', [BibliotecaVirtualController::class, 'actualizar_categoria']);
    Route::post('/update-docvirtual', [BibliotecaVirtualController::class, 'update_doc_virtual']);
    Route::post('/actualizar-subcategoria', [BibliotecaVirtualController::class, 'actualizar_subcategoria']);
    Route::post('/update-files-bibliovirtual', [BibliotecaVirtualController::class, 'update_files_bibliovirtual']);
    Route::post('/update-txtfiles-bibliovirtual', [BibliotecaVirtualController::class, 'update_txtfiles_bibliovirtual']);

    /*
    *LOGO INSTITUCIONAL
    */

    /*
    *USUARIOS
    */
    Route::post('/update-usuario', [UsuariosController::class, 'update_usuario']);
    Route::post('/update-password-usuario', [UsuariosController::class, 'update_password_usuario']);

    /*
    *PERFIL DE USUARIOS
    */
    Route::post('/actualizar-perfil-usuario', [UsuariosController::class, 'update_perfil_usuario']);

    /*
    *PERMISOS DE USUARIO
    */

    /*
    *ASIGNAR PERMISOS ROL CON MODULOS Y SUBMODULOS
    */
    Route::post('/permisos/actualizar', [AsignacionPermisoController::class, 'actualizarPermiso'])->name('permisos.actualizar');

    /*
    *MODULOS
    */
    Route::post('/actualizar-modulo', [ModulosController::class, 'actualizar_modulo']);

     /*
    *SUBMODULOS
    */
    Route::post('/actualizar-submodulo', [SubmodulosController::class, 'actualizar_submodulo']);

    /*
    *NOTIFICACIONES ADMINISTRADOR
    */
    Route::post('/actualizar_items_notificaciones', [NotificacionController::class, 'update_item_notificacion']);

    /*
    *ATENCION CIUDADANA
    */
    Route::post('/change-estado-solicitud', [AtencionCiudadanaController::class, 'change_estado']);
});

Route::middleware(['throttle:limit_admin_delete'])->group(function () {
    /*
    *NOTICIAS
    */
    Route::post('/in-activar-img-noticia', [NoticiasController::class, 'inactivar_img_noticia']);
    Route::post('/in-activar-noticia', [NoticiasController::class, 'inactivar_noticia']);

    /*
    *EVENTOS
    */
    Route::post('/inactivar-evento', [EventCalendarController::class, 'inactivar_eventos']);

    /*
    *RED SOCIAL
    */
    Route::post('/in-activar-socialm', [RedSocialController::class, 'inactivar_socialmedia']);
    Route::post('/delete-socialm', [RedSocialController::class, 'delete_socialmedia']);
    Route::post('/in-activar-reds', [RedSocialController::class, 'inactivar_redsocial']);

    /*
    *CONTACTOS
    */

    /*
    *AÑOS
    */
    Route::post('/in-activar-year', [DateController::class, 'inactivar_year']);

    /*
    *LITERALES LOTAIP
    */
    Route::post('/in-activar-item-lotaip', [LotaipController::class, 'inactivar_item_lotaip']);

    /*
    *ARTICULOS LOTAIP
    */
    Route::post('/in-activar-articulo-lotaip', [LotaipController::class, 'inactivar_articulo_lotaip']);

    /*
    *OPCIONES LOTAIP
    */
    Route::post('/in-activar-opciones-lotaip', [LotaipController::class, 'inactivar_opciones_lotaip']);

    /*
    *BANNER
    */
    Route::post('/in-activar-banner', [BannerController::class, 'inactivar_banner']);
    Route::post('/delete-banner', [BannerController::class, 'delete_banner']);

    /*
    *BANNER ALCALDIA
    */
    Route::post('/in-activar-banner-alcaldia', [BannerAlcaldiaController::class, 'inactivar_banner_alcaldia']);
    Route::post('/delete-banner-alcaldia', [BannerAlcaldiaController::class, 'delete_banner_alcaldia']);

    /*
    *IMG INFOR CUENTA
    */
    Route::post('/in-activar-cuentafile', [ContactController::class, 'inactivar_cuentafile']);
    Route::post('/delete-cuentafile', [ContactController::class, 'delete_cuentafile']);

    /*
    *ABOUT
    */
    Route::post('/in-activar-img-about', [AboutController::class, 'inactivar_img_about']);

    /*
    *MISION-VISION-VALORES-OBJETIVOS
    */
    Route::post('/in-activar-objindi', [MiViVaObController::class, 'inactivar_objetivo']);
    Route::post('/in-activar-valindi', [MiViVaObController::class, 'inactivar_valor']);
    Route::post('/eliminar-objindi', [MiViVaObController::class, 'eliminar_objetivo']);
    Route::post('/eliminar-valorindi', [MiViVaObController::class, 'eliminar_valor']);

    /*
    *ESTRUCTURA
    */
    Route::post('/in-activar-img-estructura', [EstructuraController::class, 'inactivar_img_estructura']);

    /*
    *HISTORIA
    */
    Route::post('/activar-imghistoria-delete', [HistoriaController::class, 'activar_imghistoria_delete']);

    /*
    *DEPARTAMENTOS
    */
    Route::post('/in-activar-dept', [DepartamentoController::class, 'inactivar_departamento']);
    Route::post('/in-activar-info-dept', [DepartamentoController::class, 'inactivar_info_departamento']);

    /*
    *SERVICIOS
    */
    Route::post('/in-activar-servicio', [ServiciosController::class, 'inactivar_servicio']);
    Route::post('/delete-oneservice', [ServiciosController::class, 'eliminar_servicio']);

    /*
    *SERVICIOS - SUBSERVICIOS
    */
    Route::post('/eliminar-subservicio', [SubserviceController::class, 'eliminar_subservicio']);

    /*
    *SERVICIOS - SUBSERVICIOS INFORMACION
    */
    Route::post('/in-activar-subservicioinfodetail', [SubserviceController::class, 'inactivar_subservice_detailinfo']);
    Route::post('/delete-subservicioinfodetail', [SubserviceController::class, 'delete_subservice_detailinfo']);

    /*
    *SERVICIOS - SUBSERVICIOS LISTA
    */
    Route::post('/in-activar-subserviciodetaillist', [SubserviceController::class, 'inactivar_subservice_detaillist']);
    Route::post('/delete-subserviciodetaillist', [SubserviceController::class, 'delete_subservice_detaillist']);

    /*
    *SERVICIOS - SUBSERVICIOS TEXTO Y ARCHIVO
    */
    Route::post('/in-activar-subserviciofilelist', [SubserviceController::class, 'inactivar_subservice_filelist']);
    Route::post('/delete-subserviciofilelist', [SubserviceController::class, 'delete_subservice_filelist']);

    /*
    *DOCUMENTOS - POA
    */
    Route::post('/in-activar-poa', [DocumentosController::class, 'inactivar_poa']);

    /*
    *DOCUMENTOS - POA REFORMADOS
    */

    /*
    *DOCUMENTOS - PAC
    */
    Route::post('/in-activar-pac', [DocumentosController::class, 'inactivar_pac']);

    /*
    *DOCUMENTOS - PAC REFORMADOS
    */
    
    /*
    *DOCUMENTOS LOTAIP
    */
    Route::post('/in-activar-lotaip', [LotaipController::class, 'inactivar_lotaip']);

    /*
    *DOCUMENTOS LOTAIP V2
    */

    /*
    *DOCUMENTOS - PROCESO CONTRATACION
    */

    /*
    *DOCUMENTOS - REGLAMENTOS
    */
    Route::post('/in-activar-ley', [DocumentosController::class, 'inactivar_ley']);

    /*
    *DOCUMENTOS - LEY DE TRANSPARENCIA
    */
    Route::post('/in-activar-leytransparencia', [LeyTransparenciaController::class, 'inactivar_ley']);

    /*
    *DOCUMENTOS RENDICION DE CUENTAS
    */
    Route::post('/in-activar-rendicionc', [RendicionCuentasController::class, 'inactivar_rendicionc']);

    /*
    *DOCUMENTOS - PLIEGO TARIFARIO
    */
    Route::post('/in-activar-pliego', [PliegoTarifarioController::class, 'inactivar_pliego']);

    /*
    *DOCUMENTOS - AUDITORIA
    */
    Route::post('/in-activar-auditoria', [AuditoriaController::class, 'inactivar_auditoria']);

    /*
    *DOCUMENTOS - MEDIOS VERIFICACION
    */
    Route::post('/in-activar-mediosv', [MediosVerificacionController::class, 'inactivar_mediosv']);
    Route::post('/in-activar-file-mediosv', [MediosVerificacionController::class, 'inactivar_file_mediosv']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS ADMINISTRATIVO
    */
    Route::post('/in-activar-docadministrativo', [DocAdministrativoController::class, 'inactivar_doc_administrativo']);
    Route::post('/delete-docadministrativo', [DocAdministrativoController::class, 'delete_doc_administrativo']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS FINANCIERO
    */
    Route::post('/in-activar-docfinanciero', [DocFinancieroController::class, 'inactivar_doc_financiero']);
    Route::post('/delete-docfinanciero', [DocFinancieroController::class, 'delete_doc_financiero']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS OPERATIVA
    */
    Route::post('/in-activar-docoperativo', [DocOperativoController::class, 'inactivar_doc_operativo']);
    Route::post('/delete-docoperativo', [DocOperativoController::class, 'delete_doc_operativo']);

    /*
    *DOCUMENTACIÓN - DOCUMENTOS LABORAL
    */
    Route::post('/in-activar-doclaboral', [DocLaboralController::class, 'inactivar_doc_laboral']);
    Route::post('/delete-doclaboral', [DocLaboralController::class, 'delete_doc_laboral']);

    /*
    *BIBLIOTECA VIRTUAL
    */
    Route::post('/in-activar-subcategoria', [BibliotecaVirtualController::class, 'inactivar_doc_subcategoria']);
    Route::post('/in-activar-filesubcategoria', [BibliotecaVirtualController::class, 'inactivar_doc_filesubcategoria']);
    Route::post('/delete-file-oncat', [BibliotecaVirtualController::class, 'delete_file_oncat']);
    Route::post('/in-activar-filegaleria', [BibliotecaVirtualController::class, 'inactivar_filegaleria']);
    Route::post('/delete-file-galeria', [BibliotecaVirtualController::class, 'delete_file_galeria']);

    /*
    *LOGO INSTITUCIONAL
    */
    Route::post('/in-activar-logo', [LogoController::class, 'inactivar_logo']);
    Route::post('/delete-logo', [LogoController::class, 'delete_logo']);

    /*
    *USUARIOS
    */
    Route::post('/in-activar-usuario', [UsuariosController::class, 'inactivar_usuario']);

    /*
    *PERFIL DE USUARIOS
    */
    Route::post('/in-activar-profileuser', [UsuariosController::class, 'inactivar_perfil_usuario']);

    /*
    *PERMISOS DE USUARIO
    */

    /*
    *ASIGNAR PERMISOS ROL CON MODULOS Y SUBMODULOS
    */

    /*
    *MODULOS
    */
    Route::post('/in-activar-modulo', [ModulosController::class, 'inactivar_modulo']);

     /*
    *SUBMODULOS
    */
    Route::post('/in-activar-submodulo', [SubmodulosController::class, 'inactivar_submodulo']);

    /*
    *NOTIFICACIONES ADMINISTRADOR
    */

    /*
    *ATENCION CIUDADANA
    */
});