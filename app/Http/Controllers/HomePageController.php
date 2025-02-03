<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class HomePageController extends Controller
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

    private function getAllSubservicesFromService($idservicio){
        $subservicio= DB::connection('mysql')->table('tab_servicios_subservicio')
        ->select('id', 'nombre', 'imagen', 'icon')
        ->where('id_servicio', $idservicio)
        ->where('estado','=','1')
        ->get();

        return $subservicio;
    }

    private function getAllServices(){
        $servicios= DB::connection('mysql')->table('tab_servicios')
        ->join('tab_servicio_descripcion', 'tab_servicios.id', '=', 'tab_servicio_descripcion.id_servicio')
        ->select('tab_servicios.*', 'tab_servicio_descripcion.descripcion_corta', 'tab_servicio_descripcion.descripcion')
        ->where('tab_servicios.estado','1')->get();
        return $servicios;
    }

    public function index()
    {
        $sql_banner= DB::connection('mysql')->table('tab_img_banner')->where('estado','1')->orderBy('orden')->get();
        
        $about = DB::table('tab_about_institucion')->where('estado','1')->get();
        $arab= array();
        foreach ($about as $ab){
            $arab = explode("//", $ab->descripcion);
        }

        $mariaDbec= DB::connection('mysql')->table('tab_noticias')->where('estado','1')->orderBy('fecha', 'desc')->offset(0)->limit(10)->get();
        $arnew= array();
        foreach($mariaDbec as $not){
            $imagen= $this->getImagen($not->id);
            $arnew[]= array('id'=> $not->id, 'lugar'=> $not->lugar, 'titulo'=> $not->titulo, 'descripcion'=> substr($not->descripcion_corta,0, 100)."...", 'fecha'=> $not->fecha, 'imagen'=> $imagen);
        }
        
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();
        $servicios= $this->getAllServices();
        
        /*$arrayCom= array();
        $arrayAv= array();
        $arrayDay= array();*/
        $hoy = new DateTime();
        $ano= $hoy->format('Y');
        $mes= $hoy->format('m');
        $month = $ano."-".$mes;
        $aux = date('Y-m-d', strtotime("{$month} + 1 month"));
        $last_day = date('Y-m-d', strtotime("{$aux} - 1 day"));
        //$first_day= $hoy->modify('first day of this month')->format('d');
        $first_day= $ano.'-'.$mes.'-01';

        $diacivico= DB::connection('mysql')->table('tab_eventos')->where('estado', '1')
        ->where('tipo', 'diacivico')
        ->whereBetween('desde', [$first_day, $last_day])->offset(0)->limit(10)
        ->get()->toArray();

        $comunicado= DB::connection('mysql')->table('tab_eventos')->where('estado', '1')
        ->where('tipo', 'comunicado')
        ->whereBetween('desde', [$first_day, $last_day])->offset(0)->limit(10)
        ->get()->toArray();

        $aviso= DB::connection('mysql')->table('tab_eventos')->where('estado', '1')
        ->where('tipo', 'aviso')
        ->whereBetween('desde', [$first_day, $last_day])->offset(0)->limit(10)
        ->get()->toArray();

        $userId = 'cuadrado';
        $sql_eventos= DB::connection('mysql')->table('tab_eventos')->where('estado', '1')
            ->whereBetween('desde', [$first_day, $last_day])->offset(0)->limit(10)
            ->get()->toArray();
        
        $coun_squeare= 0;
        $coun_rectangle= 0;

        foreach ($sql_eventos as $p) {
            if($p->formaimg=='cuadrado'){
                $coun_squeare++;
            }

            if($p->formaimg=='rectangular'){
                $coun_rectangle++;
            }
        }

        //return $coun_squeare;

        //return $sql_banner;
        //'socialmedia'=> $socialmedia, 
        return response()->view('Viewmain.inicio', ['banner'=> $sql_banner, 'about'=> $arab, 'noticias'=> $arnew, 
            'contactos'=> $contactos, 'boletines'=> $sql_eventos, 
            'diacivico'=> $diacivico, 'comunicado'=> $comunicado, 'aviso'=> $aviso, 'ccuadrado'=> $coun_squeare, 'crectangulo'=> $coun_rectangle,
            'servicios'=> $servicios]);
    }

    public function our_services(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();
        $servicios= $this->getAllServices();

        return response()->view('Viewmain.Servicios.servicio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia,'servicios'=> $servicios]);
    }

    public function get_subservices_indi($idservice){
        $idservice= base64_decode($idservice);

        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $subservicio= DB::connection('mysql')->table('tab_servicios_subservicio')
        ->select('id', 'nombre', 'imagen', 'icon')
        ->where('id_servicio', $idservice)
        ->where('estado','1')
        ->get();

        $getnameservice= $this->getNameService($idservice);
        $getimagenservice= $this->getImagenService($idservice);
        $getdescpservice= $this->getDescripcionService($idservice);

        return response()->view('Viewmain.Servicios.subservicio', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 
            'subservicio'=> $subservicio, 'servicio'=> $getnameservice, 'imagen'=> $getimagenservice, 'descripcion'=> $getdescpservice]);
    }

    public function get_description_subservices_indi($idsubservice){
        $idsubservice= base64_decode($idsubservice);

        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $idservice= $this->getIdServiceFromSubService($idsubservice);
        $getnameservice= $this->getNameService($idservice);
        $subservicio= $this->getAllSubservicesFromService($idservice);

        $tiposubservicio= $this->getTypeSubservice($idsubservice);

        if($tiposubservicio=='informativo'){
            $infosubservicio= DB::connection('mysql')->table('tab_subservicio_informativo')
            ->select('descripcion', 'archivo')
            ->where('id_subservicio', $idsubservice)
            ->where('estado','1')
            ->get();

            //return $subservicio;

            return response()->view('Viewmain.Servicios.subservicio_informativo', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 
                'subservicio'=> $subservicio, 'servicio'=> $getnameservice, 'idseleccion'=> $idsubservice, 'detallesubservice'=> $infosubservicio,
                'numservice'=> $idservice]);
        
        } else if($tiposubservicio=='lista_tramite'){
            $infosubservicio= DB::connection('mysql')->table('tab_subservicio_listdesplegable')
            ->select('titulo','descripcion')
            ->where('id_subservicio', $idsubservice)
            ->where('estado','1')
            ->get();

            $contactgeolocalizacion= DB::connection('mysql')->table('tab_contactos')
            ->select('tipo_contacto', 'detalle', 'latitud', 'longitud' , 'detalle_2')
            ->where('tipo_contacto','=','geolocalizacion')
            ->where('estado','=','1')->get();

            $contacthorario= DB::connection('mysql')->table('tab_contactos')
            ->select('detalle', 'hora_a', 'hora_c')
            ->where('tipo_contacto','=','houratencion')
            ->where('estado','=','1')->get();

            $contactdireccion= DB::connection('mysql')->table('tab_contactos')
            ->select('detalle as direccion')
            ->where('tipo_contacto','=','direccion')
            ->where('estado','=','1')->get();

            //return $infosubservicio;
            return response()->view('Viewmain.Servicios.subservicio_lista_tramite', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 
                'subservicio'=> $subservicio, 'servicio'=> $getnameservice, 'idseleccion'=> $idsubservice, 'detallesubservice'=> $infosubservicio,
                'geolocalizacion' => $contactgeolocalizacion, 'horario'=>$contacthorario, 'direccionmain'=> $contactdireccion, 'numservice'=> $idservice]);
        } else if($tiposubservicio=='lista'){
            $infosubservicio= DB::connection('mysql')->table('tab_subservicio_listdesplegable')
            ->select('titulo','descripcion')
            ->where('id_subservicio', $idsubservice)
            ->where('estado','1')
            ->get();

            //return $infosubservicio;
            return response()->view('Viewmain.Servicios.subservicio_lista', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 
                'subservicio'=> $subservicio, 'servicio'=> $getnameservice, 'idseleccion'=> $idsubservice, 'detallesubservice'=> $infosubservicio,
                'numservice'=> $idservice]);
        } else if($tiposubservicio=='archivo'){
            $infosubservicio= DB::connection('mysql')->table('tab_subservicio_text_file')
            ->select('archivo','descripcion', 'posicion','tipo_file')
            ->where('id_subservicio', $idsubservice)
            ->where('estado','1')
            ->get();

            $arhistory= array();
            $cadena='';
            $archivo=''; $posicion=''; $tipofile='';
            foreach($infosubservicio as $st){
                if($st->descripcion == null || $st->descripcion == ''){
                    $archivo= $st->archivo;
                    $posicion= $st->posicion;
                    $tipofile= $st->tipo_file;
                }else{
                    $cadena.= $st->descripcion;
                    
                }
            }

            $arhistory[]= array('descripcion'=> $cadena, 'archivo'=> $archivo, 'posicion'=> $posicion, 'tipo_file'=> $tipofile);
            json_encode($arhistory);

            //return $arhistory;
            return response()->view('Viewmain.Servicios.subservicio_archivo', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 
                'subservicio'=> $subservicio, 'servicio'=> $getnameservice, 'idseleccion'=> $idsubservice, 'detallesubservice'=> $arhistory,
                'numservice'=> $idservice]);
        }
    }
    private function getNameService($id){
        $resultado= '';

        $sql= DB::connection('mysql')->select('SELECT titulo FROM tab_servicios WHERE id=?', [$id]);

        foreach($sql as $s){
            $resultado= $s->titulo;
        }

        return $resultado;
    }

    private function getImagenService($id){
        $resultado= '';

        $sql= DB::connection('mysql')->select('SELECT imagen FROM tab_servicios WHERE id=?', [$id]);

        foreach($sql as $s){
            $resultado= $s->imagen;
        }

        return $resultado;
    }

    private function getDescripcionService($id){
        $resultado= '';

        $sql= DB::connection('mysql')->select('SELECT descripcion FROM tab_servicio_descripcion WHERE id_servicio=?', [$id]);

        foreach($sql as $s){
            $resultado= $s->descripcion;
        }

        return $resultado;
    }

    private function getIdServiceFromSubService($id){
        $resultado= '';

        $sql= DB::connection('mysql')->select('SELECT id_servicio FROM tab_servicios_subservicio WHERE id=?', [$id]);

        foreach($sql as $s){
            $resultado= $s->id_servicio;
        }

        return $resultado;
    }

    private function getTypeSubservice($id){
        $resultado= '';

        $sql= DB::connection('mysql')->select('SELECT tipo FROM tab_servicios_subservicio WHERE id=?', [$id]);

        foreach($sql as $s){
            $resultado= $s->tipo;
        }

        return $resultado;
    }

    public function get_boletines(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $hoy = new DateTime();
        $ano= $hoy->format('Y');
        $mes= $hoy->format('m');
        $month = $ano."-".$mes;
        $aux = date('Y-m-d', strtotime("{$month} + 1 month"));
        $last_day = date('Y-m-d', strtotime("{$aux} - 1 day"));
        //$first_day= $hoy->modify('first day of this month')->format('d');
        $first_day= $ano.'-'.$mes.'-01';

        $diacivico= DB::connection('mysql')->table('tab_eventos')->where('estado', '1')
        ->where('tipo', 'diacivico')
        ->whereBetween('desde', [$first_day, $last_day])->offset(0)->limit(10)
        ->get()->toArray();

        $comunicado= DB::connection('mysql')->table('tab_eventos')->where('estado', '1')
        ->where('tipo', 'comunicado')
        ->whereBetween('desde', [$first_day, $last_day])->offset(0)->limit(10)
        ->get()->toArray();

        $aviso= DB::connection('mysql')->table('tab_eventos')->where('estado', '1')
        ->where('tipo', 'aviso')
        ->whereBetween('desde', [$first_day, $last_day])->offset(0)->limit(10)
        ->get()->toArray();

        $userId = 'cuadrado';
        $sql_eventos= DB::connection('mysql')->table('tab_eventos')->where('estado', '1')
            ->whereBetween('desde', [$first_day, $last_day])->offset(0)->limit(10)
            ->get()->toArray();
        
        $coun_squeare= 0;
        $coun_rectangle= 0;

        foreach ($sql_eventos as $p) {
            if($p->formaimg=='cuadrado'){
                $coun_squeare++;
            }

            if($p->formaimg=='rectangular'){
                $coun_rectangle++;
            }
        }

        return response()->view('Viewmain.Boletines.boletin', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'boletines'=> $sql_eventos, 
            'diacivico'=> $diacivico, 'comunicado'=> $comunicado, 'aviso'=> $aviso, 'ccuadrado'=> $coun_squeare, 'crectangulo'=> $coun_rectangle]);
    }

    private function getImagen($id){
        $sql= DB::connection('mysql')->select('SELECT imagen FROM tab_img_noticias WHERE estado="1" AND id_noticia=?', [$id]);
        $resultado= 0;
        foreach($sql as $r){
            $resultado= $r->imagen;
        }
        return $resultado;
    }

    public function list_news(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $mariaDbec= DB::connection('mysql')->table('tab_noticias')->where('estado','1')->orderBy('fecha', 'desc')->get();
        $arnew= array();
        foreach($mariaDbec as $not){
            $id= $not->id;
            $imagen= $this->getImagen($id);
            $arnew[]= array('id'=>$id, 'lugar'=> $not->lugar, 'titulo'=> $not->titulo, 'descripcion'=> substr($not->descripcion_corta,0, 100)."...", 'fecha'=> $not->fecha, 'imagen'=> $imagen);
        }

        return response()->view('Viewmain.Noticias.noticia', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia,'noticias'=> $arnew]);
    }

    public function ver_noticia($idn, $opcion){
        $estado='1';
        $idn= base64_decode($idn);

        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $countlist = DB::connection('mysql')->table('tab_noticias')->where('estado', $estado)->get()->count();
        if($countlist <= 3 ){
            $listnewf = DB::connection('mysql')->table('tab_noticias')
            ->join('tab_img_noticias', 'tab_noticias.id', '=', 'tab_img_noticias.id_noticia')
            ->select('tab_noticias.*', 'tab_img_noticias.imagen')
            ->where('tab_noticias.estado', $estado)
            ->where('tab_img_noticias.estado', $estado)
            ->offset(0)->limit($countlist)
            ->get();
        }else{
            $listnewf = DB::connection('mysql')->table('tab_noticias')
            ->join('tab_img_noticias', 'tab_noticias.id', '=', 'tab_img_noticias.id_noticia')
            ->select('tab_noticias.*', 'tab_img_noticias.imagen')
            ->where('tab_noticias.estado', $estado)
            ->where('tab_img_noticias.estado', $estado)
            ->offset(0)->limit(4)
            ->get();
        }
        
        $sqltexto = DB::connection('mysql')->table('tab_noticias')->where('id', $idn)->get();
        $sqlimg= DB::connection('mysql')->select('SELECT imagen FROM tab_img_noticias WHERE id_noticia=? AND estado=?', [$idn, $estado]);

        $arnoticia= array();
        foreach($sqltexto as $nt){
            $fecha= $this->setFecha($nt->fecha);
            $arrhashtag = explode(",", $nt->hashtag);
            $arrdescrip= explode("//", $nt->descripcion);
            $arnoticia[]= array('lugar'=> $nt->lugar, 'titulo'=> $nt->titulo, 'descripcion'=> $arrdescrip, 'hashtag'=> $arrhashtag, 
                'fecha'=> $fecha, 'hora'=> $nt->hora);
            unset($arrdescrip);
            unset($arrhashtag);
        }

        //return $listnewf;
        return view('Viewmain.Noticias.viewnoticia', ['texto'=> $arnoticia, 'imagen'=> $sqlimg, 'contactos'=> $contactos, 
            'socialmedia'=> $socialmedia, 'listnew'=> $listnewf]);
    }

    private function formatDia($day){
        $dia="";
        switch ($day) {
            case "Sunday":
                $dia="Domingo";
                break;
            case "Monday":
                $dia="Lunes";
                break;
            case "Tuesday":
                $dia="Martes";
                break;
            case "Wednesday":
                $dia="Miércoles";
                break;
            case "Thursday":
                $dia="Jueves";
                break;
            case "Friday":
                $dia="Viernes";
                break;
            case "Saturday":
                $dia="Sábado";
                break;
        }
        return $dia;
    }

    private function setFecha($date){
        $arraymes= array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre',
        'Noviembre','Diciembre');
        $anio= substr($date, 0, 4);
        $mes= substr($date,-5,2);
        $dia= substr($date, 8, strlen($date));
    
        $mes= intval($mes);
        $diaN= $this->formatDia(date('l', strtotime($date)));
        //return $dia.' de '.$arraymes[$mes].' del '.$anio;
        return $diaN.', '.$dia.' de '.$arraymes[$mes].' del '.$anio;
    }

    public function about_us(){
        $about = DB::table('tab_about_institucion')->where('estado','1')->get();
        $arab= array();
        foreach ($about as $ab){
            $arab = explode("//", $ab->descripcion);
        }

        $mision = DB::table('mvvob')->where('estado','1')->get();
        //$groupmision= $mision->groupBy('tipo');
        $arvalor= array();
        foreach($mision as $gm){
            if($gm->tipo=='valores'){
                $minar= explode("//", $gm->descripcion);
                $arvalor[]= array('titulo'=> $minar[0], 'descripcion'=> $minar[1], 'tipo'=> 'valores');
            }else{
                $arvalor[]= array('descripcion'=> $gm->descripcion, 'tipo'=> $gm->tipo);
            }
            
        }

        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();
        //return $arvalor;
        return response()->view('Viewmain.About.about', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'about' => $arab, 'mision' => $arvalor]);
    }

    public function struct_us(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $estructura = DB::table('tab_estructura_institucion')->where('estado','1')->get();
        /*$arestructura= Array();
        foreach($estructura as $st){
            $a= html_entity_decode($st->descripcion);
            $arestructura[]= array('descripcion'=> $a);
        }*/

        //return $estructura;
        return response()->view('Viewmain.About.struct', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'estructura'=> $estructura]);
    }

    public function history_us(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $historia = DB::table('tab_historia_institucion')->where('estado','1')->get();
        $arhistory= Array();
        $cadena='';
        $imagen=''; $posicion='';
        foreach($historia as $st){
            if($st->descripcion == null || $st->descripcion == ''){
                $imagen= $st->imagen;
                $posicion= $st->posicion;
            }else{
                $cadena.= $st->descripcion;
                
            }
        }

        $arhistory[]= array('descripcion'=> $cadena, 'imagen'=> $imagen, 'posicion'=> $posicion);

        //return $arhistory;
        return response()->view('Viewmain.About.history', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'historia'=> $arhistory]);
    }

    public function departamento_us(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $resultado= array();
        $array_resultado = DB::connection('mysql')->select('SELECT * FROM tab_info_each_departamento WHERE estado=? ORDER BY tipo_dep DESC',['1']);
        foreach($array_resultado as $data){
            $tipo= $data->tipo_dep;
            $res='';
            $img= '';
            if($tipo=='gerencia'){
                $id_dep= $data->id_gerencia;
                $res_table_in= DB::connection('mysql')->select('SELECT nombre, imagen FROM tab_gerencia_dep WHERE id=?', [$id_dep]);
                foreach($res_table_in as $d){
                    $res= $d->nombre;
                    $img= $d->imagen;
                }
            }else if($tipo=='direccion'){
                $id_dep= $data->id_direccion;
                $res_table_in= DB::connection('mysql')->select('SELECT nombre, imagen FROM tab_direccion_dep WHERE id=?', [$id_dep]);
                foreach($res_table_in as $d){
                        $res= $d->nombre;
                        $img= $d->imagen;
                }
            }else if($tipo=='coordinacion'){
                $id_dep= $data->id_coordinacion;
                $res_table_in= DB::connection('mysql')->select('SELECT id_gerencia, id_direccion, nombre FROM tab_coordinacion_dep WHERE id=?', [$id_dep]);
                foreach($res_table_in as $d){
                    $res= $d->nombre;
                    $idgerencia= $d->id_gerencia;
                    $iddireccion= $d->id_direccion;
                    if(($idgerencia!='' || $idgerencia!=null) && ($iddireccion=='' || $iddireccion==null)){
                        $sqlimgger= DB::connection('mysql')->table('tab_gerencia_dep')
                        ->select('imagen')
                        ->where('id', $idgerencia)
                        ->get();

                        foreach($sqlimgger as $item){
                            $img= $item->imagen;
                        }
                    }

                    if(($idgerencia=='' || $idgerencia==null) && ($iddireccion!='' || $iddireccion!=null)){
                        $sqlimgdir= DB::connection('mysql')->table('tab_direccion_dep')
                        ->select('imagen')
                        ->where('id', $iddireccion)
                        ->get();

                        foreach($sqlimgdir as $item){
                            $img= $item->imagen;
                        }
                    }
                }
            }

            $resultado[] = array('responsable'=> $data->responsable, 'email'=> $data->email, 'telefono'=> $data->telefono,
                'extension'=> $data->extension, 'nombre_dep'=> $res, 'imagen'=> $img);
        }

        //return $resultado;
        return response()->view('Viewmain.About.departamento', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 'departamentos'=> $resultado]);
    }

    public function contact_us(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        $contactgeolocalizacion= DB::connection('mysql')->table('tab_contactos')
        ->select('tipo_contacto', 'detalle', 'latitud', 'longitud' , 'detalle_2')
        ->where('tipo_contacto','=','geolocalizacion')
        ->where('estado','=','1')->get();

        $contacthorario= DB::connection('mysql')->table('tab_contactos')
        ->select('detalle', 'hora_a', 'hora_c')
        ->where('tipo_contacto','=','houratencion')
        ->where('estado','=','1')->get();

        $contactdireccion= DB::connection('mysql')->table('tab_contactos')
        ->select('detalle as direccion')
        ->where('tipo_contacto','=','direccion')
        ->where('estado','=','1')->get();

        $getfilecuenta= DB::connection('mysql')->table('tab_img_infor_cuenta')
        ->select('archivo', 'tipo')
        ->where('estado','=','1')->get();

        //return $contactgeolocalizacion;
        return response()->view('Viewmain.Contactos.contacto', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia, 
            'geolocalizacion' => $contactgeolocalizacion, 'horario'=>$contacthorario, 'direccionmain'=> $contactdireccion,
            'getfilecuenta' => $getfilecuenta]);
    }

    public function registro_mensaje_usuario(Request $r){
        $nombres = $r->nombres;
        $email= $r->email;
        $descripcion= $r->descripcion;
        $date= now();
        $dia= $date->format('Y-m-d');

        $sql_insert= DB::connection('mysql')->table('tab_mensajes')->insertGetId(
            ['nombres'=> $nombres, 'email'=> $email, 'detalle'=> $descripcion, 'fecha'=> $dia, 'created_at'=> $date]
        );
        $LAST_ID= $sql_insert;
        if($sql_insert){
            $sql_insert_noti = DB::connection('mysql')->insert('insert into tab_notificaciones (
                        id_mensaje, fecha, created_at
                    ) values (?,?,?)', [$LAST_ID, $dia, $date]);
    
            if($sql_insert_noti){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    public function biblioteca_transparencia(){
        $contactos= $this->getAllContacts();
        $socialmedia= $this->getAllSocialMedia();

        //return $resultado;
        return response()->view('Viewmain.Transparencia.homet', ['contactos'=> $contactos, 'socialmedia'=> $socialmedia]);
    }
}
