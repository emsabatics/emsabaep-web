<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class BibliotecaTransparenciaController extends Controller
{
    private function getAllContacts(){
        $contactos= DB::connection('mysql')->table('tab_contactos')->where('tipo_contacto','!=','geolocalizacion')->where('estado','1')->get();
        $arcontac= array();
        foreach($contactos as $ct){
            $id= $ct->id;
            if($ct->tipo_contacto=='telefono'){
                //$telefono= str_replace('&',' - ',$ct->detalle);
                if($ct->telefono_2!='' || $ct->telefono_2!=null){
                    $telefono= $ct->telefono.'-'.$ct->telefono_2;
                }else{
                    $telefono= $ct->telefono;
                }
                $arcontac[]= array('tipo_contacto'=> $ct->tipo_contacto, 'detalle'=> $telefono, 'latitud'=> $ct->latitud, 'longitud'=> $ct->longitud, 
                'hora_a'=> $ct->hora_a, 'hora_c'=> $ct->hora_c, 'detalle2'=> $ct->detalle_2);
            }else{
                $arcontac[]= array('tipo_contacto'=> $ct->tipo_contacto, 'detalle'=> $ct->detalle, 'latitud'=> $ct->latitud, 'longitud'=> $ct->longitud, 
                'hora_a'=> $ct->hora_a, 'hora_c'=> $ct->hora_c, 'detalle2'=> $ct->detalle_2);
            }
        }

        return $arcontac;
    }

    private function getAllSocialMedia(){
        $socialmedia= DB::connection('mysql')->table('tab_social_media')
            ->join('tab_red_social', 'tab_social_media.id_red_social', '=', 'tab_red_social.id')
            ->select('tab_social_media.*', 'tab_red_social.nombre')
            ->where('tab_social_media.estado','1')
            ->get();

        return $socialmedia;
    }

    private function getAllServices(){
        $servicios= DB::connection('mysql')->table('tab_servicios')
        ->join('tab_servicio_descripcion', 'tab_servicios.id', '=', 'tab_servicio_descripcion.id_servicio')
        ->select('tab_servicios.*', 'tab_servicio_descripcion.descripcion_corta', 'tab_servicio_descripcion.descripcion')
        ->where('tab_servicios.estado','1')->get();
        return $servicios;
    }

    public function lotaip_v1(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $getYearLotaip= DB::connection('mysql')->select('SELECT DISTINCT l.id_anio as code, y.nombre as anio FROM tab_lotaip l, tab_anio y WHERE l.id_anio=y.id AND l.estado=1;');

        //return $getYearLotaip;
        return response()->view('Viewmain.Transparencia.lotaip.lotaip_anio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'anio_lotaip'=> $getYearLotaip]);
    }

    public function lotaip_v2(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $getYearLotaip= DB::connection('mysql')->select('SELECT DISTINCT l.id_anio as code, y.nombre as anio FROM tab_lotaip_v2 l, tab_anio y WHERE l.id_anio=y.id AND l.estado=1;');

        //return $getYearLotaip;
        return response()->view('Viewmain.Transparencia.lotaip.lotaip2_anio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'anio_lotaip'=> $getYearLotaip]);
    }

    public function view_desc_lotaip($tipo, $idanio){
        $idanio = desencriptarNumero($idanio);
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();
        
        $resultado= array();

        if($tipo=='v2'){
            $meses= array();
            $articulos= array();
            $nameyear= $this->get_name_year($idanio);

            $getMonthLotaip= DB::connection('mysql')->select('SELECT DISTINCT l.id_mes, m.mes FROM tab_lotaip_v2 l, tab_meses m WHERE l.id_mes=m.id AND l.estado=1 AND l.id_anio=?', [$idanio]);
            foreach($getMonthLotaip as $mes){
                $idmes= $mes->id_mes;
                $getArtOptLotaip= DB::connection('mysql')->select('SELECT DISTINCT l.id_art_lotaip, ar.descripcion FROM tab_lotaip_v2 l, tab_art_lotaip ar WHERE 
                    l.id_art_lotaip=ar.id AND l.id_opt_lotaip IS NULL AND l.id_anio=?', [$idanio]);
                    foreach($getArtOptLotaip as $gao){
                        $archivos= array();
                        $idart= $gao->id_art_lotaip;
                        if($idart=='1'){
                            $getItemLotaip= DB::connection('mysql')->select('SELECT l.*, il.literal, il.descripcion FROM tab_lotaip_v2 l, tab_item_lotaip il WHERE 
                                l.id_item_lotaip=il.id AND l.id_art_lotaip=? AND id_anio=? AND id_mes=? ORDER BY l.id_item_lotaip ASC', [$idart, $idanio, $idmes]);
                            foreach($getItemLotaip as $lot){
                                $archivo= $lot->archivo;
                                $archivocd= $lot->archivo_cdatos;
                                $archivomd= $lot->archivo_mdatos;
                                $archivodd= $lot->archivo_ddatos;
                                $archivos[]= array('id'=> $lot->id, 'id_anio'=> $lot->id_anio, 'id_mes'=> $lot->id_mes, 'id_item_lotaip'=> $lot->id_item_lotaip,
                                    'archivo'=> $archivo, 'archivocd'=> $archivocd, 'archivomd'=> $archivomd, 'archivodd'=> $archivodd, 'literal'=> $lot->literal, 'descripcion'=> $lot->descripcion, 'estado'=> $lot->estado);
                            }
                            $articulos[]= array('id_articulo'=> $idart, 'articulo'=> $gao->descripcion, 'tipo'=>'articulo', 'archivos'=> $archivos);
                            unset($archivos);
                        }else{
                            $getItemLotaip= DB::connection('mysql')->select('SELECT l.* FROM tab_lotaip_v2 l WHERE l.id_art_lotaip=? AND id_anio=? AND id_mes=?', [$idart, $idanio, $idmes]);
                            $namefile='';
                            foreach($getItemLotaip as $lot){
                                $archivo= $lot->archivo;
                                $namefile= substr($archivo, 0, -7); 
                                $newphrase = str_replace('_', ' ', $namefile);
                                $archivocd= $lot->archivo_cdatos;
                                $archivomd= $lot->archivo_mdatos;
                                $archivodd= $lot->archivo_ddatos;
                                $archivos[]= array('id'=> $lot->id, 'id_anio'=> $lot->id_anio, 'id_mes'=> $lot->id_mes, 'id_item_lotaip'=> $lot->id_item_lotaip,
                                    'archivo'=> $archivo, 'archivocd'=> $archivocd, 'archivomd'=> $archivomd, 'archivodd'=> $archivodd, 'estado'=> $lot->estado, 'descripcion'=> $newphrase);
                            }
                            
                            $articulos[]= array('id_articulo'=> $idart, 'articulo'=> $gao->descripcion, 'tipo'=>'articulo', 'archivos'=> $archivos);
                            unset($archivos);
                        }
                    }

                    $getArtOptLotaip2= DB::connection('mysql')->select('SELECT DISTINCT l.id_opt_lotaip, op.descripcion FROM tab_lotaip_v2 l, tab_opciones_lotaip op WHERE 
                            l.id_opt_lotaip=op.id AND l.id_art_lotaip IS NULL AND l.id_anio=?', [$idanio]);
                    $count= count($getArtOptLotaip2);
                    if($count>0){
                        $archivos2= array();
                        foreach($getArtOptLotaip2 as $gao2){
                            $idopt= $gao2->id_opt_lotaip;
                            $getItemLotaip= DB::connection('mysql')->select('SELECT l.*, il.descripcion FROM tab_lotaip_v2 l, tab_opciones_lotaip il WHERE 
                                    l.id_opt_lotaip=il.id AND l.id_opt_lotaip=? AND id_anio=? AND id_mes=? ORDER BY l.id_opt_lotaip ASC', [$idopt, $idanio, $idmes]);
                            if(count($getItemLotaip)>0){
                            foreach($getItemLotaip as $lot){
                                $archivo= $lot->archivo;
                                $archivos2[]= array('id'=> $lot->id, 'id_anio'=> $lot->id_anio, 'id_mes'=> $lot->id_mes, 'archivo'=> $archivo, 
                                    'descripcion'=> $lot->descripcion, 'estado'=> $lot->estado);
                            }
                            $articulos[]= array('id_opcion'=> $idopt, 'opcion'=> $gao2->descripcion, 'tipo'=>'opcion', 'archivos'=> $archivos2);
                            unset($archivos2);
                            }
                        }
                        unset($archivos2);
                    }
                    $meses[]= array('idmes'=> $idmes, 'mes'=> $mes->mes, 'articulos'=> $articulos);
                    unset($articulos);
                }
            $resultado[]= array('anio'=> $nameyear, 'nmes'=> $meses);
            unset($meses);

            json_encode($resultado);
            //return $resultado;
            return response()->view('Viewmain.Transparencia.lotaip.list_lotaipv2', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'lotaip'=> $resultado]);
        }else if($tipo=='v1'){
            $nameyear= $this->get_name_year($idanio);
            $getMonthLotaip= DB::connection('mysql')->select('SELECT DISTINCT l.id_mes, m.mes FROM tab_lotaip l, tab_meses m WHERE l.id_mes=m.id AND l.estado=1 AND l.id_anio=?', [$idanio]);
                foreach($getMonthLotaip as $mes){
                $idmes= $mes->id_mes;
                $getItemLotaip= DB::connection('mysql')->select('SELECT l.*, il.literal, il.descripcion FROM tab_lotaip l, tab_item_lotaip il WHERE l.id_item_lotaip=il.id AND id_anio=? AND id_mes=? ORDER BY l.id_item_lotaip ASC', [$idanio, $idmes]);
                foreach($getItemLotaip as $lot){
                        $archivo= $lot->archivo;
                        $archivos[]= array('id'=> $lot->id, 'id_anio'=> $lot->id_anio, 'id_mes'=> $lot->id_mes, 'id_item_lotaip'=> $lot->id_item_lotaip,
                            'archivo'=> $archivo, 'literal'=> $lot->literal, 'descripcion'=> $lot->descripcion, 'estado'=> $lot->estado);
                }
                $meses[]= array('idmes'=> $idmes, 'mes'=> $mes->mes, 'archivos'=> $archivos);
                unset($archivos);
            }
            $resultado[]= array('anio'=> $nameyear, 'nmes'=> $meses);
            unset($meses);

            json_encode($resultado);
            //return $resultado;
            return response()->view('Viewmain.Transparencia.lotaip.list_lotaipv1', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'lotaip'=> $resultado]);
        }
    }

    private function get_name_year($id){
        $resultado='';

        $sql= DB::connection('mysql')->select('SELECT nombre FROM tab_anio WHERE id=?', [$id]);

        foreach($sql as $s){
            $resultado= $s->nombre;
        }

        return $resultado;
    }

    public function rendicion_cuenta(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $getYearRc= DB::connection('mysql')->select('SELECT rc.id_anio as code, y.nombre as anio FROM tab_rendicion_cuentas rc, tab_anio y WHERE rc.id_anio=y.id AND rc.estado=1;');

        //return $getYearRc;
        return response()->view('Viewmain.Transparencia.rendicion_cuenta.rc_anio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'anio_rc'=> $getYearRc]);
    }

    public function view_desc_rc($tipo, $idanio){
        $idanio = desencriptarNumero($idanio);
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();
        
        $resultado= array();
        $tipovideo= array();
        $tipomedio= array();

        if($tipo=='v1'){
            $nameyear= $this->get_name_year($idanio);
            $getRendicionC= DB::connection('mysql')->select('SELECT * FROM tab_rendicion_cuentas WHERE id_anio=? AND estado=?', [$idanio, '1']);
            foreach($getRendicionC as $grc){
                $id= $grc->id;
                $getMonthRc= DB::connection('mysql')->select('SELECT DISTINCT tipo FROM tab_archivos_rendicion_cuentas WHERE id_rendicion_cuenta=? AND estado=?', [$id, '1']);
                foreach($getMonthRc as $tiporc){
                    $gettipofile= $tiporc->tipo;
                    $getFilesRC= DB::connection('mysql')->select('SELECT * FROM tab_archivos_rendicion_cuentas WHERE id_rendicion_cuenta=? AND tipo=?', [$id, $gettipofile]);
                    foreach($getFilesRC as $frc){
                        $archivo= $frc->archivo;
                        if($gettipofile=="video"){
                            $tipovideo[]= array('id'=> $frc->id, 'id_rendicion_cuenta'=> $frc->id_rendicion_cuenta, 'tipo'=> $frc->tipo,
                                'titulo'=> $frc->titulo, 'archivo'=> $frc->archivo,'estado'=> $frc->estado);
                            if(empty($tipomedio)){
                                $tipomedio = [];
                            }
                        }else if($gettipofile=="medio"){
                            $tipomedio[]= array('id'=> $frc->id, 'id_rendicion_cuenta'=> $frc->id_rendicion_cuenta, 'tipo'=> $frc->tipo,
                                'titulo'=> $frc->titulo, 'archivo'=> $frc->archivo,'estado'=> $frc->estado);
                            if(empty($tipovideo)){
                                $tipovideo = [];
                            }
                        }
                    }
                        
                    //$tipofiles[]= array('idmes'=> $idmes, 'mes'=> $mes->mes, 'archivos'=> $archivos);
                    //unset($archivos);/
                }
                $resultado[]= array('idanio'=> $idanio,'anio'=> $nameyear, 'tipov'=> 'video', 
                        'longitudv'=> sizeof($tipovideo), 'archivos_v'=> $tipovideo, 'tipom'=>'medio', 
                        'longitudm'=> sizeof($tipomedio), 'archivos_m'=> $tipomedio);
                unset($tipomedio);
                unset($tipovideo);
            }
            json_encode($resultado);
            //return $resultado;
            return response()->view('Viewmain.Transparencia.rendicion_cuenta.list_rc', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'rendicionc'=> $resultado]);
        }
    }

    public function play_rc($idanio, $idtovideo, $idvideorc){
        $idanio = desencriptarNumero($idanio);
        $idtovideo = desencriptarNumero($idtovideo);
        $idvideorc = desencriptarNumero($idvideorc);
        
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $dateyear= DB::connection('mysql')->select('SELECT id, nombre as anio FROM tab_anio WHERE id=?', [$idanio]);

        $resultado= DB::connection('mysql')->select('SELECT titulo, archivo FROM tab_archivos_rendicion_cuentas WHERE id=? AND estado=?', [$idtovideo, '1']);
        //return $resultado;
        return response()->view('Viewmain.Transparencia.rendicion_cuenta.playrc', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 
            'archivorc'=> $resultado, 'anio'=> $dateyear]);
    }

    public function doc_financiera(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $getYearDocFin= DB::connection('mysql')->select('SELECT DISTINCT rc.id_anio as code, y.nombre as anio FROM tab_doc_financiero rc, tab_anio y WHERE rc.id_anio=y.id AND rc.estado=1;');

        //return $getYearDocFin;
        return response()->view('Viewmain.Transparencia.doc_financiera.docfinanciera_anio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'anio_docfin'=> $getYearDocFin]);
    }

    public function view_desc_docfin($tipo, $idanio){
        $idanio = desencriptarNumero($idanio);
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();
        
        $resultado= array();

        if($tipo=='v1'){
            $nameyear= $this->get_name_year($idanio);
            $financiero= DB::connection('mysql')->select('SELECT id, titulo, archivo FROM tab_doc_financiero WHERE id_anio=? AND estado=? ORDER BY titulo ASC', [$idanio, '1']);
            $documentos= array();

            foreach($financiero as $f){
                $documentos[]= array('id'=> $f->id, 'titulo'=> $f->titulo, 'archivo'=> $f->archivo); 
            }

            $resultado[]= array('anio'=> $nameyear, 'longitud'=> sizeof($documentos), 'documentos'=> $documentos);
            unset($documentos);
            
            //return $resultado;
            return response()->view('Viewmain.Transparencia.doc_financiera.list_docfin', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'docfin'=> $resultado]);
        }
    }

    //FALTA CHECK
    public function doc_operativa(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $getYearDocOpt= DB::connection('mysql')->select('SELECT DISTINCT rc.id_anio as code, y.nombre as anio FROM tab_doc_operativo rc, tab_anio y WHERE rc.id_anio=y.id AND rc.estado=?', ['1']);

        //return $getYearDocOpt;
        return response()->view('Viewmain.Transparencia.doc_operativa.docoperativa_anio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'anio_docopt'=> $getYearDocOpt]);
    }

    public function view_desc_docopt($tipo, $idanio){
        $idanio = desencriptarNumero($idanio);
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();
        
        $resultado= array();

        if($tipo=='v1'){
            $nameyear= $this->get_name_year($idanio);
            $operativo= DB::connection('mysql')->select('SELECT id, titulo, archivo FROM tab_doc_operativo WHERE id_anio=? ORDER BY titulo ASC', [$idanio]);
            $documentos= array();

            foreach($operativo as $f){
                $documentos[]= array('id'=> $f->id, 'titulo'=> $f->titulo, 'archivo'=> $f->archivo); 
            }

            $resultado[]= array('anio'=> $nameyear, 'longitud'=> sizeof($documentos), 'documentos'=> $documentos);
            unset($documentos);
            
            //return $resultado;
            return response()->view('Viewmain.Transparencia.doc_operativa.list_docopt', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'docopt'=> $resultado]);
        }
    }

    public function doc_laboral(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $getYearDocLab= DB::connection('mysql')->select('SELECT DISTINCT rc.id_anio as code, y.nombre as anio FROM tab_doc_laboral rc, tab_anio y WHERE rc.id_anio=y.id AND rc.estado=?', ['1']);

        //return $getYearDocLab;
        return response()->view('Viewmain.Transparencia.doc_laboral.doclaboral_anio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'anio_doclab'=> $getYearDocLab]);
    }

    public function view_desc_doclab($tipo, $idanio){
        $idanio = desencriptarNumero($idanio);
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();
        
        $resultado= array();

        if($tipo=='v1'){
            $nameyear= $this->get_name_year($idanio);
            $operativo= DB::connection('mysql')->select('SELECT id, titulo, archivo FROM tab_doc_laboral WHERE id_anio=? ORDER BY titulo ASC', [$idanio]);
            $documentos= array();

            foreach($operativo as $f){
                $documentos[]= array('id'=> $f->id, 'titulo'=> $f->titulo, 'archivo'=> $f->archivo); 
            }

            $resultado[]= array('anio'=> $nameyear, 'longitud'=> sizeof($documentos), 'documentos'=> $documentos);
            unset($documentos);
            
            //return $resultado;
            return response()->view('Viewmain.Transparencia.doc_laboral.list_doclab', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'doclab'=> $resultado]);
        }
    }

    public function doc_reglamentos(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $reglamento = DB::table('tab_reglamentos')->where('estado','1')->get();

        //return $getYearDocLab;
        return response()->view('Viewmain.Transparencia.reglamentos.listn_reglamentos', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'reglamento'=> $reglamento]);
    }

    public function doc_auditoria(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $getYearAuditoria= DB::connection('mysql')->select('SELECT DISTINCT rc.id_anio as code, y.nombre as anio FROM tab_auditoria rc, tab_anio y WHERE rc.id_anio=y.id AND rc.estado=?', ['1']);

        //return $getYearAuditoria;
        return response()->view('Viewmain.Transparencia.auditoria.docauditoria_anio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'anio_auditoria'=> $getYearAuditoria]);
    }

    public function view_desc_docaud($tipo, $idanio){
        $idanio = desencriptarNumero($idanio);
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();
        
        $resultado= array();

        if($tipo=='v1'){
            $nameyear= $this->get_name_year($idanio);
            $operativo= DB::connection('mysql')->select('SELECT id, titulo, archivo FROM tab_auditoria WHERE id_anio=? ORDER BY titulo ASC', [$idanio]);
            $documentos= array();

            foreach($operativo as $f){
                $documentos[]= array('id'=> $f->id, 'titulo'=> $f->titulo, 'archivo'=> $f->archivo); 
            }

            $resultado[]= array('anio'=> $nameyear, 'longitud'=> sizeof($documentos), 'documentos'=> $documentos);
            unset($documentos);
            
            //return $resultado;
            return response()->view('Viewmain.Transparencia.auditoria.list_docaud', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'docaud'=> $resultado]);
        }
    }

    public function doc_administrativa(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        return response()->view('Viewmain.Transparencia.doc_administrativa.docadministrativa', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia]);
    }

    public function view_ley_transparencia(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $transparencia = DB::table('tab_ley_transparencia')->where('estado','1')->get();

        //return $getYearDocLab;
        return response()->view('Viewmain.Transparencia.doc_administrativa.list_leytransparencia', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'transparencia'=> $transparencia]);
    }

    public function view_year_pac(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $getYearDocPac= DB::connection('mysql')->select('SELECT DISTINCT rc.id_anio as code, y.nombre as anio FROM tab_pac rc, tab_anio y WHERE rc.id_anio=y.id AND rc.estado=1');

        //return $getYearDocPac;
        return response()->view('Viewmain.Transparencia.doc_administrativa.doc_admin_pac_anio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'anio_docpac'=> $getYearDocPac]);
    }

    public function view_year_poa(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $getYearDocPoa= DB::connection('mysql')->select('SELECT DISTINCT rc.id_anio as code, y.nombre as anio FROM tab_poa rc, tab_anio y WHERE rc.id_anio=y.id AND rc.estado=1');

        //return $getYearDocPoa;
        return response()->view('Viewmain.Transparencia.doc_administrativa.doc_admin_poa_anio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'anio_docpoa'=> $getYearDocPoa]);
    }

    public function view_year_mediosv(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $getYearDocMediosV= DB::connection('mysql')->select('SELECT DISTINCT rc.id_anio as code, y.nombre as anio FROM tab_mediosv rc, tab_anio y WHERE rc.id_anio=y.id AND rc.estado=1;');

        //return $getYearDocMediosV;
        return response()->view('Viewmain.Transparencia.doc_administrativa.doc_admin_mediosv_anio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'anio_docmediosv'=> $getYearDocMediosV]);
    }

    public function view_files_mediosv($tipo, $idanio){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $resultado= array();

        if($tipo=='v1'){
            $nameyear= $this->get_name_year($idanio);
            //$idmv= $this->getIdTabMediosV($idanio);
            $sqlmediosv= DB::connection('mysql')->select('SELECT * FROM tab_mediosv WHERE id_anio=? AND estado=?', [$idanio, '1']);

            foreach($sqlmediosv as $m){
                $idmv= $m->id;
                $titulo= $m->titulo;

                $sql_archivos = DB::connection('mysql')->select('SELECT * FROM tab_archivos_mediosv WHERE id_archivo=? AND estado=?', [$idmv, '1']);
                $documentos= array();

                foreach($sql_archivos as $f){
                    $documentos[]= array('id'=> $f->id, 'id_archivo'=> $f->id_archivo, 'titulofile'=> $f->titulo, 'archivo'=> $f->archivo); 
                }

                $resultado[]= array('anio'=> $nameyear, 'longitud'=> sizeof($documentos), 'titulo'=> $titulo, 'documentos'=> $documentos);
                unset($documentos);
            }

            //return $resultado;
            return response()->view('Viewmain.Transparencia.doc_administrativa.list_docmediosv', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'docmediosv'=> $resultado]);
        }
        
    }

    private function getIdTabMediosV($year){
        $sql= DB::connection('mysql')->select('SELECT id FROM tab_mediosv WHERE id_anio=?', [$year]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->id;
        }

        return $resultado;
    }

    public function view_pliegot(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $pliego = DB::table('tab_pliego_tarifario')->where('estado','1')->get();

        //return $pliego;
        return response()->view('Viewmain.Transparencia.doc_administrativa.list_pliegotarifario', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'pliego'=> $pliego]);
    }

    public function view_procesos(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $procesoc = DB::table('tab_proceso_contratacion')->where('estado','1')->get();

        //return $procesoc;
        return response()->view('Viewmain.Transparencia.doc_administrativa.list_procesos', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'procesoc'=> $procesoc]);
    }

    public function view_year_doc_administrativo(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $getYearDocAdmin= DB::connection('mysql')->select('SELECT DISTINCT rc.id_anio as code, y.nombre as anio FROM tab_doc_administrativo rc, tab_anio y WHERE rc.id_anio=y.id;');

        //return $getYearDocAdmin;
        return response()->view('Viewmain.Transparencia.doc_administrativa.docotheradmin_anio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'anio_docadmin'=> $getYearDocAdmin]);
    }

    public function view_desc_docadmin($tipo, $idanio){
        $idanio = desencriptarNumero($idanio);
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();
        
        $resultado= array();

        if($tipo=='v1'){
            $nameyear= $this->get_name_year($idanio);
            $administrativo= DB::connection('mysql')->select('SELECT id, titulo, archivo FROM tab_doc_administrativo WHERE id_anio=? ORDER BY titulo ASC', [$idanio]);
            $documentos= array();

            foreach($administrativo as $f){
                $documentos[]= array('id'=> $f->id, 'titulo'=> $f->titulo, 'archivo'=> $f->archivo); 
            }

            $resultado[]= array('anio'=> $nameyear, 'longitud'=> sizeof($documentos), 'documentos'=> $documentos);
            unset($documentos);
            
            //return $resultado;
            return response()->view('Viewmain.Transparencia.doc_administrativa.list_docadmin', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'docadmin'=> $resultado]);
        }
    }

    public function view_desc_docpoa($tipo, $idanio){
        $idanio = desencriptarNumero($idanio);
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $resultado= array();
        $idpoa= ''; $item=1; $itemref=1;

        $poa= DB::connection('mysql')->table('tab_poa')->where('id_anio','=',$idanio)->where('estado','=','1')->get();
        foreach($poa as $p){
            $idpoa= $p->id;
            $resultado[] = array('item'=> $item, 'id'=> $p->id, 'titulo'=> $p->titulo, 'archivo'=> $p->archivo, 'tipor'=>'noref');
            $item++;
        }

        //return $resultado;
        return response()->view('Viewmain.Transparencia.doc_administrativa.list_docpoa_anio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'poa'=> $resultado]);
    }

    public function view_desc_docpoa_original($tipo, $idanio){
        $idanio = desencriptarNumero($idanio);
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $resultado= array();
        $idpoa= ''; $item=1; $itemref=1;

        $poa= DB::connection('mysql')->table('tab_poa')->where('id_anio','=',$idanio)->where('estado','=','1')->get();
        foreach($poa as $p){
            $idpoa= $p->id;
            $resultado[] = array('item'=> $item, 'id'=> $p->id, 'titulo'=> $p->titulo, 'archivo'=> $p->archivo, 'tipor'=>'noref');
            $item++;
        }

        $poah= DB::connection ('mysql')->table('tab_poa_history')->where('id_poa','=',$idpoa)->where('estado','=','1')->get();
        foreach($poah as $p){
            $resultado[] = array('item'=> $item, 'itemref'=> $itemref, 'id'=> $p->id, 'titulo'=> $p->titulo, 'archivo'=> $p->archivo, 'tipor'=>'ref');
            $item++;
            $itemref++;
        }

        //return $resultado;
        return response()->view('Viewmain.Transparencia.doc_administrativa.list_docpoa_anio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'poa'=> $resultado]);
    }

    public function view_desc_docpac($tipo, $idanio){
        $idanio = desencriptarNumero($idanio);
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $resultado= array();
        $idpac= ''; $item=1; $itemref=1;

        $pac= DB::connection('mysql')->table('tab_pac')->where('id_anio','=',$idanio)->where('estado','=','1')->get();
        foreach($pac as $p){
            $idpac= $p->id;
            $resultado[] = array('item'=> $item, 'id'=> $p->id, 'titulo'=> $p->titulo, 'archivo'=> $p->archivo, 
                'resol_admin'=> $p->resol_admin, 'archivo_resoladmin'=> $p->archivo_resoladmin, 'tipor'=>'noref');
            $item++;
        }

        //return $resultado;
        return response()->view('Viewmain.Transparencia.doc_administrativa.list_docpac_anio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'pac'=> $resultado]);
    }

    public function view_desc_docpac_original($tipo, $idanio){
        $idanio = desencriptarNumero($idanio);
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $resultado= array();
        $idpac= ''; $item=1; $itemref=1;

        $pac= DB::connection('mysql')->table('tab_pac')->where('id_anio','=',$idanio)->where('estado','=','1')->get();
        foreach($pac as $p){
            $idpac= $p->id;
            $resultado[] = array('item'=> $item, 'id'=> $p->id, 'titulo'=> $p->titulo, 'archivo'=> $p->archivo, 
                'resol_admin'=> $p->resol_admin, 'archivo_resoladmin'=> $p->archivo_resoladmin, 'tipor'=>'noref');
            $item++;
        }

        $pach= DB::connection ('mysql')->table('tab_pac_history')->where('id_pac','=',$idpac)->where('estado','=','1')->get();
        foreach($pach as $p){
            $resultado[] = array('item'=> $item, 'itemref'=> $itemref, 'id'=> $p->id, 'titulo'=> $p->titulo, 'archivo'=> $p->archivo, 
                'resol_admin'=> $p->resol_admin, 'archivo_resoladmin'=> $p->archivo_resoladmin, 'tipor'=>'ref');
            $item++;
            $itemref++;
        }

        //return $resultado;
        return response()->view('Viewmain.Transparencia.doc_administrativa.list_docpac_anio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'pac'=> $resultado]);
    }

    public function view_biblioteca_virtual_original(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $arcat= array();
        $estado='1';
        $getCatBiblioteca= DB::connection('mysql')->table('tab_bv_categoria')->where('estado', $estado)->get();
        foreach($getCatBiblioteca as $c){
            $arsubcat= array();
            $arfile= array();
            $idcat= $c->id;
            
            $getSubcatBiblioteca= DB::connection('mysql')->select('SELECT id, descripcion, estado FROM tab_bv_subcategoria 
                WHERE id_bv_categoria=? AND estado=?', [$idcat, $estado]);
                
            $idsubcat='';
            /* GET SUBCATEGORIA */
            //$wordCount = count($getSubcatBiblioteca);
            foreach($getSubcatBiblioteca as $sc){
                $idsubcat= $sc->id;
                $arfilesubcat= array();

                $getFileBiblioteca= DB::connection('mysql')->select('SELECT id, titulo, archivo, estado FROM tab_bv_archivos 
                WHERE id_bv_categoria=? AND id_bv_subcategoria=? AND estado=?', [$idcat, $idsubcat, $estado]);
                foreach($getFileBiblioteca as $fc){
                    $arfilesubcat[] = array('idfile'=> $fc->id, 'titulo'=> $fc->titulo, 'archivo'=> $fc->archivo);
                }

                $arsubcat[]= array('idsubcat'=> $idsubcat, 'descripcionsubcat'=> $sc->descripcion, 'archivossubcat'=> $arfilesubcat);
                unset($arfilesubcat);
            }
            /* GET SUBCATEGORIA */

            /* GET ARCHIVOS SIN SUBCATEGORIA */
            $getFileBv= DB::connection('mysql')->select('SELECT id, titulo, archivo, estado FROM tab_bv_archivos 
                WHERE id_bv_categoria=? AND estado=? AND id_bv_subcategoria IS NULL', [$idcat, $estado]);
            foreach($getFileBv as $fc){
                $arfile[] = array('idfile'=> $fc->id, 'titulo'=> $fc->titulo, 'archivo'=> $fc->archivo);
            }
            /* GET ARCHIVOS SIN SUBCATEGORIA */

            $arcat[]= array('idcat'=> $idcat, 'descripcioncat'=> $c->descripcion, 'subcategoria'=> $arsubcat, 'archivos'=> $arfile);
            unset($arfile);
            unset($arsubcat);
        }

            
        json_encode($arcat);
        //return $arcat;
        return response()->view('Viewmain.Transparencia.biblioteca_virtual.list_bibliotecav', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 
            'bibliotecav'=> $arcat]);
    }

    public function view_biblioteca_virtual(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $estado='1';
        $getCatBiblioteca= DB::connection('mysql')->table('tab_bv_categoria')->where('estado', $estado)->get();
        
        return response()->view('Viewmain.Transparencia.biblioteca_virtual.list_bibliotecav', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 
            'bibliotecav'=> $getCatBiblioteca]);
    }

    public function get_subcat_gallery_biblioteca_virtual($id){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $idcat = desencriptarNumero($id);

        $estado='1';
        $getSubCatBiblioteca= DB::connection('mysql')->table('tab_bv_subcategoria')
        ->select('id', 'descripcion')
        ->where('id_bv_categoria','=', $idcat)
        ->where('estado', $estado)
        ->get();
        
        return response()->view('Viewmain.Transparencia.biblioteca_virtual.list_bibliotecavsubcatgallery', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 
            'bibliotecav'=> $getSubCatBiblioteca, 'categoria'=> $id]);
    }

    public function show_gallery_biblioteca_virtual($idcat, $idsubcat){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();
        $nidcat = $idcat;
        $idcat = desencriptarNumero($idcat);
        $idsubcat = desencriptarNumero($idsubcat);

        $namesubcat = DB::connection('mysql')->table('tab_bv_subcategoria')->where('id','=', $idsubcat)->value('descripcion');

        $estado='1';
        $getgallerybiblioteca= DB::connection('mysql')->table('tab_bv_galeria')
        ->select('archivo', 'titulo', 'descripcion')
        ->where('id_bv_categoria','=', $idcat)
        ->where('id_bv_subcategoria','=', $idsubcat)
        ->where('estado', $estado)
        ->get();
        
        return response()->view('Viewmain.Transparencia.biblioteca_virtual.viewopengallery', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 
            'bibliotecagallery'=> $getgallerybiblioteca, 'namesubcat' => $namesubcat, 'nidcat'=> $nidcat]);
    }

    public function get_subcat_biblioteca_virtual($id){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $idcat = desencriptarNumero($id);

        $estado='1';
        $getSubCatBiblioteca= DB::connection('mysql')->table('tab_bv_subcategoria')
        ->select('id', 'descripcion')
        ->where('id_bv_categoria','=', $idcat)
        ->where('estado', $estado)
        ->get();
        
        return response()->view('Viewmain.Transparencia.biblioteca_virtual.list_bibliotecavsubcat', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 
            'bibliotecav'=> $getSubCatBiblioteca, 'categoria'=> $id]);
    }

    public function show_archivos_biblioteca_virtual($idcat, $idsubcat){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();
        $nidcat = $idcat;
        $idcat = desencriptarNumero($idcat);
        $idsubcat = desencriptarNumero($idsubcat);

        $namesubcat = DB::connection('mysql')->table('tab_bv_subcategoria')->where('id','=', $idsubcat)->value('descripcion');

        $estado='1';
        $getarchivosbiblioteca= DB::connection('mysql')->table('tab_bv_archivos')
        ->select('id','archivo', 'titulo', 'descripcion')
        ->where('id_bv_categoria','=', $idcat)
        ->where('id_bv_subcategoria','=', $idsubcat)
        ->where('estado', $estado)
        ->get();
        
        return response()->view('Viewmain.Transparencia.biblioteca_virtual.viewopenarchivos', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 
            'bibliotecafiles'=> $getarchivosbiblioteca, 'namesubcat' => $namesubcat, 'nidcat'=> $nidcat]);
    }

    public function get_subcat_gallery_video_virtual($id){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $idcat = desencriptarNumero($id);

        $estado='1';
        $getSubCatBiblioteca= DB::connection('mysql')->table('tab_bv_subcategoria')
        ->select('id', 'descripcion')
        ->where('id_bv_categoria','=', $idcat)
        ->where('estado', $estado)
        ->get();
        
        return response()->view('Viewmain.Transparencia.biblioteca_virtual.list_bibliotecavsubcatvideo', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 
            'bibliotecav'=> $getSubCatBiblioteca, 'categoria'=> $id]);
    }

    public function show_video_biblioteca_virtual($idcat, $idsubcat){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();
        $nidcat = $idcat;
        $idcat = desencriptarNumero($idcat);
        $idsubcat = desencriptarNumero($idsubcat);

        $namesubcat = DB::connection('mysql')->table('tab_bv_subcategoria')->where('id','=', $idsubcat)->value('descripcion');

        $estado='1';
        $getgallerybiblioteca= DB::connection('mysql')->table('tab_bv_videos')
        ->select('archivo', 'titulo', 'descripcion')
        ->where('id_bv_categoria','=', $idcat)
        ->where('id_bv_subcategoria','=', $idsubcat)
        ->where('estado', $estado)
        ->get();

        $getonlyimg = $getgallerybiblioteca
            ->map(function($item){
                return [
                    'video' => $item->archivo
                ];
            });
        
        return response()->view('Viewmain.Transparencia.biblioteca_virtual.viewopenvideo', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 
            'bibliotecagallery'=> $getgallerybiblioteca, 'namesubcat' => $namesubcat, 'nidcat'=> $nidcat, 'getonlyvideo'=> $getonlyimg]);
    }
}
