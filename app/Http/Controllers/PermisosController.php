<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermisosController extends Controller
{
    public function indexOriginal(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            $estado='1';

            $datosarray= array();

            $modulos= DB::connection('mysql')->table('tab_modulo')
            ->select('id', 'nombre')
            ->where('estado','=', $estado)->get();

            $getusers = DB::table('users')
            ->join('tab_perfil_usuario', 'users.tipo_usuario', '=', 'tab_perfil_usuario.id')
            ->select('users.id', 'users.nombre_usuario', 'users.estado', 'tab_perfil_usuario.nombre as tipo_usuario')
            ->get();

            foreach($getusers as $us){
                $contarmodulo= $this->getContadorModulo($us->id, $estado);

                $datosarray[] = array('id'=> $us->id, 'nombres'=> $us->nombre_usuario, 'rol'=> $us->tipo_usuario, 
                    'estado'=> $us->estado, 'total_modulo'=> $contarmodulo);
            }

            $permisos = collect($datosarray); // Convertir a colección
            
            return view('Administrador.Permisos.permisos', ['permisos'=> $permisos, 'modulos'=> $modulos]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            $estado='1';

            $datosarray= array();

            $getusers = DB::table('users')
            ->join('tab_perfil_usuario', 'users.tipo_usuario', '=', 'tab_perfil_usuario.id')
            ->select('users.id', 'users.nombre_usuario', 'users.estado', 'tab_perfil_usuario.nombre as tipo_usuario')
            ->get();

            foreach($getusers as $us){
                $contarmodulo= $this->getContadorModulo($us->id, $estado);

                $datosarray[] = array('id'=> $us->id, 'nombres'=> $us->nombre_usuario, 'rol'=> $us->tipo_usuario, 
                    'estado'=> $us->estado, 'total_modulo'=> $contarmodulo);
            }

            $permisos = collect($datosarray); // Convertir a colección
            $pmodulos = collect([]); // así mantienes siempre una colección
            
            return view('Administrador.Permisos.permisos', ['permisos'=> $permisos, 'pmodulos'=> $pmodulos]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function obtenerModulosPorRol(Request $r)
    {
        $idusuario = $r->input('idusuario');
        $idusuario = desencriptarNumero($idusuario);

        $idperfil = $this->getIdRolByUser($idusuario);

        $pmodulos = DB::table('tab_asig_rol_mod as ap')
            ->join('tab_modulo as m', 'm.id', '=', 'ap.idmodulo')
            ->where('ap.idperfil', $idperfil)
            ->select(
                'm.id',
                'm.nombre',
            )
            ->distinct()
            ->orderBy('m.id')
            ->get();
        
        return view('Administrador.Permisos.select', compact('pmodulos'));
    }

    public function obtenerModulosPorRolOriginal($idRol)
    {
        $permisos = DB::table('tab_asig_rol_mod as ap')
            ->leftJoin('tab_modulo as m', 'm.id', '=', 'ap.idmodulo')
            ->leftJoin('tab_submodulo as s', 's.id', '=', 'ap.idsubmodulo')
            ->where('ap.idperfil', $idRol)
            ->select(
                'm.id as modulo_id',
                'm.nombre as modulo_nombre',
                's.id as submodulo_id',
                's.submodulo as submodulo_nombre'
            )
            ->orderBy('m.id')
            ->orderBy('s.id')
            ->get();
        
        return $permisos;
        //return view('formulario.select_modulos', compact('permisos'));
    }

    private function getIdRolByUser($idu){
        $usuario = Session::get('usuario');
        $sql_getid= DB::connection('mysql')->table('users')->select('tipo_usuario')->where('id', '=', $idu)->get();
        
        $gid=0;

        foreach($sql_getid as $ev){
            $gid= $ev->tipo_usuario;
        }

        return $gid;
    }

    private function getIdUser(){
        $usuario = Session::get('usuario');
        $sql_getid= DB::connection('mysql')->table('users')->where('user', '=', $usuario)->get();
        
        $gid=0;

        foreach($sql_getid as $ev){
            $gid= $ev->id;
        }

        return $gid;
    }

    private function getContadorModuloOriginal($idusuario, $estado){
        $total=0;
        $contarmodulo= DB::connection('mysql')->table('tab_permisos')
            ->select('idmodulo', DB::raw('count(idmodulo) as total'))
            ->where('idusuario','=',$idusuario)
            ->where('estado', '=', $estado)
            ->groupBy('idusuario')
            ->get();


        foreach ($contarmodulo as $k) {
            $total = $k->total;
        }

        return $total;
    }

    private function getContadorModulo($idusuario, $estado){
        $total=0;
        $contarmodulo= DB::connection('mysql')
            ->select('SELECT COUNT(DISTINCT(idmodulo)) as total FROM tab_permisos WHERE idusuario=? AND estado=? ', [$idusuario, $estado]);

        foreach ($contarmodulo as $k) {
            $total = $k->total;
        }

        return $total;
    }

    public function get_permisos_usuarioOriginal(Request $r){
        $idm = $r->input('idm'); //idmodulo
        $idu= $r->input('idu'); //idusuario
        $estado="1";
        $idu = desencriptarNumero($idu);

        $dato= array();
        $arraymodulo= array();
        $arraysubm= array();
        $opcionesGUD= array();

        $sqlmod = DB::connection('mysql')->table('tab_permisos')
        ->select('idmodulo')
        ->where('idusuario', '=', $idu)
        ->where('estado','=', $estado)
        ->get();

        foreach ($sqlmod as $k) {
            array_push($arraymodulo, $k->idmodulo);
        }

        if(sizeof($arraymodulo)>0){
            //echo $idm.'  ';
            if (in_array($idm, $arraymodulo)) {
               //echo "IDMODULO SELECCION SE ENCUENTRA <br/>";
                $contarmoduloinsubmodulo= DB::connection('mysql')->table('tab_submodulo')
                ->select('idmodulo')
                ->where('idmodulo','=', $idm)
                ->where('estado','=',$estado)
                ->count();

                if($contarmoduloinsubmodulo==0){
                    //echo 'NO TIENE SUBMODULOS ESTE MODULO <br/>';
                    $sqlopciones = DB::connection('mysql')->table('tab_permisos')
                    ->select('guardar','actualizar','eliminar','descargar','configurar')
                    ->where('idmodulo','=', $idm)
                    ->where('idusuario','=', $idu)
                    ->get();

                    foreach($sqlopciones as $op){
                        $opcionesGUD[] = array('guardar'=> $op->guardar, 'actualizar'=> $op->actualizar, 'eliminar'=> $op->eliminar, 'descargar'=> $op->decargar,
                                'configurar'=> $op->configurar);
                    }
                    
                    $dato[]=array('datomod'=> "lleno", 'numsubm'=>"0",'modulo'=> $this->getNameModulo($idm), 'seleccionado'=>"si", 'opciones'=>$opcionesGUD);
                    unset($opcionesGUD);
                }else if($contarmoduloinsubmodulo>=1){
                    //echo 'TIENE SUBMODULOS ESTE MODULO SELECCIONADO <br/>';
                    $sqlsmodulo = DB::connection('mysql')->table('tab_submodulo')
                    ->select('id','submodulo')
                    ->where('idmodulo','=', $idm)
                    ->get();
                    //LLENO EL ARRAY CON LOS SUBMODULOS CREADOS EN EL SISTEMA
                    foreach ($sqlsmodulo as $sm) {
                        $contarsm = DB::connection('mysql')->table('tab_permisos')
                        ->select('guardar','actualizar','eliminar','descargar','configurar')
                        ->where('idmodulo','=', $idm)
                        ->where('idsubmodulo','=', $sm->id)
                        ->where('idusuario','=', $idu)
                        ->count();
                        //echo '  ContarSM: '.$contarsm.' ';
                        if($contarsm > 0){
                            $sqlopciones_sm = DB::connection('mysql')->table('tab_permisos')
                            ->select('guardar','actualizar','eliminar')
                            ->where('idmodulo','=', $idm)
                            ->where('idsubmodulo','=', $sm->id)
                            ->where('idusuario','=', $idu)
                            ->get();

                            foreach($sqlopciones_sm as $op){
                                $arraysubm[] = array('idsm'=> $sm->id, 'submodulo'=> $sm->submodulo, 'seleccionSM'=>"si", 'guardar'=> $op->guardar, 'actualizar'=> $op->actualizar, 'eliminar'=> $op->eliminar,
                                        'descargar'=> $op->descargar, 'configurar'=> $op->configurar);
                            }
                        }else{
                            $arraysubm[] = array('idsm'=> $sm->id, 'submodulo'=> $sm->submodulo,'seleccionSM'=>"no", 'guardar'=> "no", 'actualizar'=> "no", 'eliminar'=> "no", 'descargar'=> "no",'configurar'=> "no");
                        }
                    }
                    $dato[]=array('datomod'=> "lleno", 'numsubm'=>"2",'modulo'=> $this->getNameModulo($idm), 'seleccionado'=>"si", 'opciones'=>$arraysubm);
                    unset($opcionesGUD);
                }
            }else{
                //echo "IDMODULO SELECCION NO SE ENCUENTRA <br/>";
                $contarmoduloinsubmodulo= DB::connection('mysql')->table('tab_submodulo')
                ->select('idmodulo')
                ->where('idmodulo','=', $idm)
                ->where('estado','=',$estado)
                ->count();

                if($contarmoduloinsubmodulo==0){
                    //echo 'NO TIENE SUBMODULOS ESTE MODULO <br/>';
                    $sqlopciones = DB::connection('mysql')->table('tab_permisos')
                    ->select('guardar','actualizar','eliminar','descargar','configurar')
                    ->where('idmodulo','=', $idm)
                    ->where('idusuario','=', $idu)
                    ->get();

                    foreach($sqlopciones as $op){
                        $opcionesGUD[] = array('guardar'=> $op->guardar, 'actualizar'=> $op->actualizar, 'eliminar'=> $op->eliminar, 'descargar'=> $op->descargar, 'configurar'=> $op->configurar);
                    }

                    if(sizeof($opcionesGUD)==0){
                        $opcionesGUD[] = array('guardar'=> 'no', 'actualizar'=> 'no', 'eliminar'=> 'no','descargar'=> 'no', 'configurar'=> 'no');
                    }

                    $dato[]=array('datomod'=> "lleno", 'numsubm'=>"0",'modulo'=> $this->getNameModulo($idm), 'seleccionado'=>"no", 'opciones'=>$opcionesGUD);
                    unset($opcionesGUD);
                }else if($contarmoduloinsubmodulo>=1){
                    //echo 'TIENE SUBMODULOS ESTE MODULO <br/>';
                    $sqlsmodulo = DB::connection('mysql')->table('tab_submodulo')
                    ->select('id','submodulo')
                    ->where('idmodulo','=', $idm)
                    ->get();
                    //LLENO EL ARRAY CON LOS SUBMODULOS CREADOS EN EL SISTEMA
                    foreach ($sqlsmodulo as $sm) {
                        $arraysubm[] = array('idsm'=> $sm->id, 'submodulo'=> $sm->submodulo);
                    }

                    $opcionesGUD[] = array('guardar'=> 'no', 'actualizar'=> 'no', 'eliminar'=> 'no', 'descargar'=> 'no','configurar'=> 'no');

                    $dato[]=array('datomod'=> "lleno", 'numsubm'=>"1",'seleccionado'=>"no",'submodulos'=> $arraysubm, 'opciones'=>$opcionesGUD);
                    unset($arraysubm);
                    unset($opcionesGUD);
                }
            }

            return response()->json($dato);
        }else{
            //echo "ARRAY VACIO <br/>";
            $contarmodulo= DB::connection('mysql')->table('tab_submodulo')
            ->select('submodulo')
            ->where('idmodulo','=', $idm)
            ->where('estado','=',$estado)
            ->count();

            if($contarmodulo > 0){
                $submodulos= DB::connection('mysql')->table('tab_submodulo')
                ->select('id', 'submodulo')
                ->where('idmodulo','=', $idm)
                ->where('estado','=', $estado)
                ->get();

                foreach($submodulos as $sm){
                    $arraysubm[] = array('idsm'=> $sm->id, 'submodulo'=> $sm->submodulo);
                }

                if(sizeof($opcionesGUD)==0){
                    $opcionesGUD[] = array('guardar'=> 'no', 'actualizar'=> 'no', 'eliminar'=> 'no', 'descargar'=> 'no', 'configurar'=> 'no');
                }

                $dato[]=array('datomod'=> "vacio", 'numsubm'=>"1",'seleccionado'=>"no",'submodulos'=> $arraysubm, 'opciones'=>$opcionesGUD);
                unset($arraysubm);
                unset($opcionesGUD);

            }else if($contarmodulo == 0){
                $dato[]=array('datomod'=> "vacio", 'numsubm'=>"0", 'modulo'=> $this->getNameModulo($idm), 'seleccionado'=>"no");
            }

            //$respuesta = collect($dato);

            return response()->json($dato);
        }
    }

    public function get_permisos_usuario(Request $r){
        $idm = $r->input('idm'); //idmodulo
        $idu= $r->input('idu'); //idusuario
        $estado="1";
        $idu = desencriptarNumero($idu);
        //$idm = base64_decode($idm);

        $dato= array();
        $arraymodulo= array();
        $arraysubm= array();
        $opcionesGUD= array();

        $sqlmod = DB::connection('mysql')->table('tab_permisos')
        ->select('idmodulo')
        ->where('idusuario', '=', $idu)
        ->where('estado','=', $estado)
        ->get();

        foreach ($sqlmod as $k) {
            array_push($arraymodulo, $k->idmodulo);
        }

        if(sizeof($arraymodulo)>0){
            //echo $idm.'  ';
            if (in_array($idm, $arraymodulo)) {
               //echo "IDMODULO SELECCION SE ENCUENTRA <br/>";
                /*$contarmoduloinsubmodulo= DB::connection('mysql')->table('tab_submodulo')
                ->select('idmodulo')
                ->where('idmodulo','=', $idm)
                ->where('estado','=',$estado)
                ->count();*/

                $contarmoduloinsubmodulo= $submodulos = DB::table('tab_asig_rol_mod as ap')
                    ->join('tab_submodulo as s', 's.id', '=', 'ap.idsubmodulo')
                    ->select('s.submodulo')
                    ->where('ap.idmodulo','=', $idm)
                    ->where('ap.estado','=',$estado)
                    ->distinct()
                    ->count();

                if($contarmoduloinsubmodulo==0){
                    //echo 'NO TIENE SUBMODULOS ESTE MODULO <br/>';
                    $sqlopciones = DB::connection('mysql')->table('tab_permisos')
                    ->select('guardar','actualizar','eliminar')
                    ->where('idmodulo','=', $idm)
                    ->where('idusuario','=', $idu)
                    ->get();

                    foreach($sqlopciones as $op){
                        $opcionesGUD[] = array('guardar'=> $op->guardar, 'actualizar'=> $op->actualizar, 'eliminar'=> $op->eliminar);
                    }
                    
                    $dato[]=array('datomod'=> "lleno", 'numsubm'=>"0",'modulo'=> $this->getNameModulo($idm), 'seleccionado'=>"si", 'opciones'=>$opcionesGUD);
                    unset($opcionesGUD);
                }else if($contarmoduloinsubmodulo>=1){
                    //echo 'TIENE SUBMODULOS ESTE MODULO SELECCIONADO <br/>';
                    /*$sqlsmodulo = DB::connection('mysql')->table('tab_submodulo')
                    ->select('id','submodulo')
                    ->where('idmodulo','=', $idm)
                    ->get();*/

                    $sqlsmodulo = DB::table('tab_asig_rol_mod as ap')
                        ->join('tab_submodulo as s', 's.id', '=', 'ap.idsubmodulo')
                        ->where('ap.idmodulo', $idm)
                        ->select('s.id', 's.submodulo')
                        ->distinct()
                        ->orderBy('s.id')
                        ->get();

                    
                    //LLENO EL ARRAY CON LOS SUBMODULOS CREADOS EN EL SISTEMA
                    foreach ($sqlsmodulo as $sm) {
                        $contarsm = DB::connection('mysql')->table('tab_permisos')
                        ->select('guardar','actualizar','eliminar')
                        ->where('idmodulo','=', $idm)
                        ->where('idsubmodulo','=', $sm->id)
                        ->where('idusuario','=', $idu)
                        ->count();
                        //echo '  ContarSM: '.$contarsm.' ';
                        if($contarsm > 0){
                            $sqlopciones_sm = DB::connection('mysql')->table('tab_permisos')
                            ->select('guardar','actualizar','eliminar')
                            ->where('idmodulo','=', $idm)
                            ->where('idsubmodulo','=', $sm->id)
                            ->where('idusuario','=', $idu)
                            ->get();

                            foreach($sqlopciones_sm as $op){
                                $arraysubm[] = array('idsm'=> $sm->id, 'submodulo'=> $sm->submodulo, 'seleccionSM'=>"si", 'guardar'=> $op->guardar, 'actualizar'=> $op->actualizar, 'eliminar'=> $op->eliminar);
                            }
                        }else{
                            $arraysubm[] = array('idsm'=> $sm->id, 'submodulo'=> $sm->submodulo,'seleccionSM'=>"no", 'guardar'=> "no", 'actualizar'=> "no", 'eliminar'=> "no");
                        }
                    }
                    $dato[]=array('datomod'=> "lleno", 'numsubm'=>"2",'modulo'=> $this->getNameModulo($idm), 'seleccionado'=>"si", 'opciones'=>$arraysubm);
                    unset($opcionesGUD);
                }
            }else{
                //echo "IDMODULO SELECCION NO SE ENCUENTRA <br/>";
                /*$contarmoduloinsubmodulo= DB::connection('mysql')->table('tab_submodulo')
                ->select('idmodulo')
                ->where('idmodulo','=', $idm)
                ->where('estado','=',$estado)
                ->count();*/

                $contarmoduloinsubmodulo= $submodulos = DB::table('tab_asig_rol_mod as ap')
                    ->join('tab_submodulo as s', 's.id', '=', 'ap.idsubmodulo')
                    ->select('s.submodulo')
                    ->where('ap.idmodulo','=', $idm)
                    ->where('ap.estado','=',$estado)
                    ->distinct()
                    ->count();

                if($contarmoduloinsubmodulo==0){
                    //echo 'NO TIENE SUBMODULOS ESTE MODULO <br/>';
                    $sqlopciones = DB::connection('mysql')->table('tab_permisos')
                    ->select('guardar','actualizar','eliminar')
                    ->where('idmodulo','=', $idm)
                    ->where('idusuario','=', $idu)
                    ->get();

                    foreach($sqlopciones as $op){
                        $opcionesGUD[] = array('guardar'=> $op->guardar, 'actualizar'=> $op->actualizar, 'eliminar'=> $op->eliminar);
                    }

                    if(sizeof($opcionesGUD)==0){
                        $opcionesGUD[] = array('guardar'=> 'no', 'actualizar'=> 'no', 'eliminar'=> 'no');
                    }

                    $dato[]=array('datomod'=> "lleno", 'numsubm'=>"0",'modulo'=> $this->getNameModulo($idm), 'seleccionado'=>"no", 'opciones'=>$opcionesGUD);
                    unset($opcionesGUD);
                }else if($contarmoduloinsubmodulo>=1){
                    //echo 'TIENE SUBMODULOS ESTE MODULO <br/>';
                    /*$sqlsmodulo = DB::connection('mysql')->table('tab_submodulo')
                    ->select('id','submodulo')
                    ->where('idmodulo','=', $idm)
                    ->get();*/

                    $sqlsmodulo = DB::table('tab_asig_rol_mod as ap')
                        ->join('tab_submodulo as s', 's.id', '=', 'ap.idsubmodulo')
                        ->where('ap.idmodulo', $idm)
                        ->select('s.id', 's.submodulo')
                        ->distinct()
                        ->orderBy('s.id')
                        ->get();

                    //LLENO EL ARRAY CON LOS SUBMODULOS CREADOS EN EL SISTEMA
                    foreach ($sqlsmodulo as $sm) {
                        $arraysubm[] = array('idsm'=> $sm->id, 'submodulo'=> $sm->submodulo);
                    }

                    $opcionesGUD[] = array('guardar'=> 'no', 'actualizar'=> 'no', 'eliminar'=> 'no');

                    $dato[]=array('datomod'=> "lleno", 'numsubm'=>"1",'seleccionado'=>"no",'submodulos'=> $arraysubm, 'opciones'=>$opcionesGUD);
                    unset($arraysubm);
                    unset($opcionesGUD);
                }
            }

            return response()->json($dato);
        }else{
            //echo "ARRAY VACIO <br/>  ";
            /*$contarmodulo= DB::connection('mysql')->table('tab_submodulo')
            ->select('submodulo')
            ->where('idmodulo','=', $idm)
            ->where('estado','=',$estado)
            ->count();*/

            $contarmodulo= $submodulos = DB::table('tab_asig_rol_mod as ap')
                    ->join('tab_submodulo as s', 's.id', '=', 'ap.idsubmodulo')
                    ->select('s.submodulo')
                    ->where('ap.idmodulo','=', $idm)
                    ->where('ap.estado','=',$estado)
                    ->distinct()
                    ->count();
            
            //echo $contarmodulo.' <br/> ';

            if($contarmodulo > 0){
                /*$submodulos= DB::connection('mysql')->table('tab_submodulo')
                ->select('id', 'submodulo')
                ->where('idmodulo','=', $idm)
                ->where('estado','=', $estado)
                ->get();*/

                $submodulos = DB::table('tab_asig_rol_mod as ap')
                        ->join('tab_submodulo as s', 's.id', '=', 'ap.idsubmodulo')
                        ->where('ap.idmodulo', $idm)
                        ->select('s.id', 's.submodulo')
                        ->distinct()
                        ->orderBy('s.id')
                        ->get();

                foreach($submodulos as $sm){
                    $arraysubm[] = array('idsm'=> $sm->id, 'submodulo'=> $sm->submodulo);
                }

                if(sizeof($opcionesGUD)==0){
                    $opcionesGUD[] = array('guardar'=> 'no', 'actualizar'=> 'no', 'eliminar'=> 'no');
                }

                $dato[]=array('datomod'=> "vacio", 'numsubm'=>"1",'seleccionado'=>"no",'submodulos'=> $arraysubm, 'opciones'=>$opcionesGUD);
                unset($arraysubm);
                unset($opcionesGUD);

            }else if($contarmodulo == 0){
                //echo $idm. ' <br/> ';
                $dato[]=array('datomod'=> "vacio", 'numsubm'=>"0", 'modulo'=> $this->getNameModulo($idm), 'seleccionado'=>"no");
            }

            return $dato;

            //$respuesta = collect($dato);

            return response()->json($dato);
        }
    }
    private function getNameModulo($id){
        $sql= DB::connection('mysql')->select('SELECT nombre FROM tab_modulo WHERE id=?', [$id]);

        $resultado= "";

        foreach($sql as $r){
            $resultado= $r->nombre;
        }

        return $resultado;
    }

    private function getIdModulo($idm, $idu){
        $sql_getid= DB::connection('mysql')->table('tab_permisos')
        ->select('idmodulo')
        ->where('idmodulo', '=', $idm)
        ->where('idusuario', '=', $idu)
        ->get();
        
        $gid=0;

        foreach($sql_getid as $ev){
            $gid= $ev->idmodulo;
        }

        return $gid;
    }

    private function getIdSubmodulo($idm, $idsm, $idu){
        $sql_getid= DB::connection('mysql')->table('tab_permisos')
        ->select('idsubmodulo')
        ->where('idmodulo', '=', $idm)
        ->where('idsubmodulo', '=', $idsm)
        ->where('idusuario', '=', $idu)
        ->get();
        
        $gid=0;

        foreach($sql_getid as $ev){
            $gid= $ev->idsubmodulo;
        }

        return $gid;
    }

    private function getIdfromPermisos($idm, $idsm, $idu){
        $sql_getid= DB::connection('mysql')->table('tab_permisos')
        ->select('id')
        ->where('idmodulo', '=', $idm)
        ->where('idsubmodulo', '=', $idsm)
        ->where('idusuario', '=', $idu)
        ->get();
        
        $gid=0;

        foreach($sql_getid as $ev){
            $gid= $ev->id;
        }

        return $gid;
    }

    public function registro_permisos_modulo(Request $r){
        $idu= $r->input('id');
        $idmodulo= $r->input('idmodulo');
        $estado= $r->input('estado');
        $date = now();

        $idu = desencriptarNumero($idu);
        $idmodulo = base64_decode($idmodulo);

        $getidmodulo= $this->getIdModulo($idmodulo, $idu);

        //echo 'idu: '.$idu.' idmodulo: '.$idmodulo;
        //echo '  <br/>  ';
        //echo $getidmodulo;
        
        if($getidmodulo > 0){
            // OBTENGO TOTAL DE SUBMODULOS
            $totalSM = DB::connection('mysql')->table('tab_submodulo')
                ->select('id')
                ->where('idmodulo','=', $idmodulo)
                ->count();
            //echo '  <br/>  totalSM:  ';
            //echo $totalSM;
            if($totalSM==0){
                $sql_update = DB::connection('mysql')->table('tab_permisos')
                ->where('idusuario', '=', $idu)
                ->where('idmodulo', '=', $idmodulo)
                ->update(['estado'=> $estado, 'updated_at'=> $date]);

                if($sql_update){
                    return response()->json(['resultado'=>true]);
                }else{
                    return response()->json(['resultado'=>false]);
                }
            }else{
                $sql_update = DB::connection('mysql')->table('tab_permisos')
                ->where('idusuario', '=', $idu)
                ->where('idmodulo', '=', $idmodulo)
                ->update(['estado'=> $estado, 'updated_at'=> $date]);

                if($sql_update){
                    $guardar="no";
                    $actualizar="no";
                    $eliminar="no";
                    $descargar= "no";
                    $configurar= "no";

                    $sql_pmod = DB::connection('mysql')->table('tab_permisos')
                    ->select('idsubmodulo')
                    ->where('idusuario', '=', $idu)
                    ->where('idmodulo', '=', $idmodulo)
                    ->get();

                    foreach ($sql_pmod as $k) {
                        if($k->idsubmodulo!=NULL || $k->idsubmodulo!=''){
                            $sql_update = DB::connection('mysql')->table('tab_permisos')
                            ->where('idusuario', '=', $idu)
                            ->where('idmodulo', '=', $idmodulo)
                            ->where('idsubmodulo', '=', $k->idsubmodulo)
                            ->update(['guardar'=> $guardar, 'actualizar'=> $actualizar, 'eliminar'=> $eliminar, 
                                'descargar'=> $descargar, 'configurar'=> $configurar, 'estado'=> $estado, 'updated_at'=> $date]);
                        }
                    }
                    return response()->json(['resultado'=>true]);
                }else{
                    return response()->json(['resultado'=>false]);
                }
            }
        }else {
            $sql_insert = DB::connection('mysql')->insert('insert into tab_permisos (
                idusuario, idmodulo, estado
            ) values (?,?,?)', [$idu, $idmodulo, $estado]);
            
            if($sql_insert){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
    }

    public function registro_permisos_modulo_sinsub(Request $r){
        $idu= $r->input('id');
        $idmodulo= $r->input('idmodulo');
        $opcion= $r->input('opcion');
        $estado= $r->input('estado');
        $date = now();

        $idu = desencriptarNumero($idu);
        $idmodulo = base64_decode($idmodulo);

        $getidmodulo= $this->getIdModulo($idmodulo, $idu);
        //echo 'IDMODULO: '.$idmodulo.' IDUSUARIO: '.$idu.' GETIDMODULO: '.$getidmodulo;
        if($getidmodulo > 0){
            $selsi="si";
            $selno="no";
            if($estado=="1"){
                switch ($opcion){
                    case "guardar":
                        $sql_update = DB::connection('mysql')->table('tab_permisos')
                        ->where('idusuario', '=', $idu)
                        ->where('idmodulo', '=', $idmodulo)
                        ->update(['guardar'=> $selsi, 'updated_at'=> $date]);
                        break;
                    case "actualizar":
                        $sql_update = DB::connection('mysql')->table('tab_permisos')
                        ->where('idusuario', '=', $idu)
                        ->where('idmodulo', '=', $idmodulo)
                        ->update(['actualizar'=> $selsi, 'updated_at'=> $date]);
                        break;
                    case "eliminar":
                        $sql_update = DB::connection('mysql')->table('tab_permisos')
                        ->where('idusuario', '=', $idu)
                        ->where('idmodulo', '=', $idmodulo)
                        ->update(['eliminar'=> $selsi, 'updated_at'=> $date]);
                        break;
                    case "descargar":
                        $sql_update = DB::connection('mysql')->table('tab_permisos')
                        ->where('idusuario', '=', $idu)
                        ->where('idmodulo', '=', $idmodulo)
                        ->update(['descargar'=> $selsi, 'updated_at'=> $date]);
                        break;
                    case "configurar":
                        $sql_update = DB::connection('mysql')->table('tab_permisos')
                        ->where('idusuario', '=', $idu)
                        ->where('idmodulo', '=', $idmodulo)
                        ->update(['configurar'=> $selsi, 'updated_at'=> $date]);
                        break;
                    default:
                        // code...
                        break;
                }

                if($sql_update){
                    return response()->json(['resultado'=>true]);
                }else{
                    return response()->json(['resultado'=>false]);
                }
            }else if($estado=="0"){
                switch ($opcion){
                    case "guardar":
                        $sql_update = DB::connection('mysql')->table('tab_permisos')
                        ->where('idusuario', '=', $idu)
                        ->where('idmodulo', '=', $idmodulo)
                        ->update(['guardar'=> $selno, 'updated_at'=> $date]);
                        break;
                    case "actualizar":
                        $sql_update = DB::connection('mysql')->table('tab_permisos')
                        ->where('idusuario', '=', $idu)
                        ->where('idmodulo', '=', $idmodulo)
                        ->update(['actualizar'=> $selno, 'updated_at'=> $date]);
                        break;
                    case "eliminar":
                        $sql_update = DB::connection('mysql')->table('tab_permisos')
                        ->where('idusuario', '=', $idu)
                        ->where('idmodulo', '=', $idmodulo)
                        ->update(['eliminar'=> $selno, 'updated_at'=> $date]);
                        break;
                    case "descargar":
                        $sql_update = DB::connection('mysql')->table('tab_permisos')
                        ->where('idusuario', '=', $idu)
                        ->where('idmodulo', '=', $idmodulo)
                        ->update(['descargar'=> $selno, 'updated_at'=> $date]);
                        break;
                    case "configurar":
                        $sql_update = DB::connection('mysql')->table('tab_permisos')
                        ->where('idusuario', '=', $idu)
                        ->where('idmodulo', '=', $idmodulo)
                        ->update(['configurar'=> $selno, 'updated_at'=> $date]);
                        break;
                    default:
                        // code...
                        break;
                }

                if($sql_update){
                    return response()->json(['resultado'=>true]);
                }else{
                    return response()->json(['resultado'=>false]);
                }
            }
        }else{
            return response()->json(["resultado"=> 'no_exist']);
        }
    }

    public function registro_permisos_modulo_withsub(Request $r){
        $idu= $r->input('id');
        $idmodulo= $r->input('idmodulo');
        $idsmodulo= $r->input('idsubmodulo');
        $opcion= $r->input('opcion');
        $estado= $r->input('estado');
        $date = now();

        $idu = desencriptarNumero($idu);
        $idmodulo = base64_decode($idmodulo);
        $idsmodulo = base64_decode($idsmodulo);

        $getidmodulo= $this->getIdModulo($idmodulo, $idu);
        if($getidmodulo > 0){
            $selsi="si";
            $selno="no";
            $getidsubmodulo= $this->getIdSubmodulo($idmodulo, $idsmodulo, $idu);
            if($getidsubmodulo > 0){
                $idpermiso = $this->getIdfromPermisos($idmodulo, $idsmodulo, $idu);
                if($estado=="1"){
                    switch ($opcion){
                        case "guardar":
                            $sql_update = DB::connection('mysql')->table('tab_permisos')
                            ->where('id', '=', $idpermiso)
                            ->where('idusuario', '=', $idu)
                            ->where('idmodulo', '=', $idmodulo)
                            ->update(['idsubmodulo'=> $idsmodulo, 'guardar'=> $selsi, 'updated_at'=> $date]);
                            break;
                        case "actualizar":
                            $sql_update = DB::connection('mysql')->table('tab_permisos')
                            ->where('id', '=', $idpermiso)
                            ->where('idusuario', '=', $idu)
                            ->where('idmodulo', '=', $idmodulo)
                            ->update(['idsubmodulo'=> $idsmodulo, 'actualizar'=> $selsi, 'updated_at'=> $date]);
                            break;
                        case "eliminar":
                            $sql_update = DB::connection('mysql')->table('tab_permisos')
                            ->where('id', '=', $idpermiso)
                            ->where('idusuario', '=', $idu)
                            ->where('idmodulo', '=', $idmodulo)
                            ->update(['idsubmodulo'=> $idsmodulo, 'eliminar'=> $selsi, 'updated_at'=> $date]);
                            break;
                        case "descargar":
                            $sql_update = DB::connection('mysql')->table('tab_permisos')
                            ->where('id', '=', $idpermiso)
                            ->where('idusuario', '=', $idu)
                            ->where('idmodulo', '=', $idmodulo)
                            ->update(['idsubmodulo'=> $idsmodulo, 'descargar'=> $selsi, 'updated_at'=> $date]);
                            break;
                        case "configurar":
                            $sql_update = DB::connection('mysql')->table('tab_permisos')
                            ->where('id', '=', $idpermiso)
                            ->where('idusuario', '=', $idu)
                            ->where('idmodulo', '=', $idmodulo)
                            ->update(['idsubmodulo'=> $idsmodulo, 'configurar'=> $selsi, 'updated_at'=> $date]);
                            break;
                        default:
                            // code...
                            break;
                    }

                    if($sql_update){
                        return response()->json(['resultado'=>true]);
                    }else{
                        return response()->json(['resultado'=>false]);
                    }
                }else if($estado=="0"){
                    switch ($opcion){
                        case "guardar":
                            $sql_update = DB::connection('mysql')->table('tab_permisos')
                            ->where('id', '=', $idpermiso)
                            ->where('idusuario', '=', $idu)
                            ->where('idmodulo', '=', $idmodulo)
                            ->update(['idsubmodulo'=> $idsmodulo, 'guardar'=> $selno, 'updated_at'=> $date]);
                            break;
                        case "actualizar":
                            $sql_update = DB::connection('mysql')->table('tab_permisos')
                            ->where('id', '=', $idpermiso)
                            ->where('idusuario', '=', $idu)
                            ->where('idmodulo', '=', $idmodulo)
                            ->update(['idsubmodulo'=> $idsmodulo, 'actualizar'=> $selno, 'updated_at'=> $date]);
                            break;
                        case "eliminar":
                            $sql_update = DB::connection('mysql')->table('tab_permisos')
                            ->where('id', '=', $idpermiso)
                            ->where('idusuario', '=', $idu)
                            ->where('idmodulo', '=', $idmodulo)
                            ->update(['idsubmodulo'=> $idsmodulo, 'eliminar'=> $selno, 'updated_at'=> $date]);
                            break;
                        case "descargar":
                            $sql_update = DB::connection('mysql')->table('tab_permisos')
                            ->where('id', '=', $idpermiso)
                            ->where('idusuario', '=', $idu)
                            ->where('idmodulo', '=', $idmodulo)
                            ->update(['idsubmodulo'=> $idsmodulo, 'descargar'=> $selsi, 'updated_at'=> $date]);
                            break;
                        case "configurar":
                            $sql_update = DB::connection('mysql')->table('tab_permisos')
                            ->where('id', '=', $idpermiso)
                            ->where('idusuario', '=', $idu)
                            ->where('idmodulo', '=', $idmodulo)
                            ->update(['idsubmodulo'=> $idsmodulo, 'configurar'=> $selsi, 'updated_at'=> $date]);
                            break;
                        default:
                            // code...
                            break;
                    }

                    if($sql_update){
                        return response()->json(['resultado'=>true]);
                    }else{
                        return response()->json(['resultado'=>false]);
                    }
                }
            }else{
                if($estado=="1"){
                    switch ($opcion){
                        case "guardar":
                            $sql_insert = DB::connection('mysql')->insert('insert into tab_permisos (
                                idusuario, idmodulo, idsubmodulo, guardar
                            ) values (?,?,?,?)', [$idu, $idmodulo, $idsmodulo, $selsi]);
                            break;
                        case "actualizar":
                            $sql_insert = DB::connection('mysql')->insert('insert into tab_permisos (
                                idusuario, idmodulo, idsubmodulo, actualizar
                            ) values (?,?,?,?)', [$idu, $idmodulo, $idsmodulo, $selsi]);
                            break;
                        case "eliminar":
                            $sql_insert = DB::connection('mysql')->insert('insert into tab_permisos (
                                idusuario, idmodulo, idsubmodulo, eliminar
                            ) values (?,?,?,?)', [$idu, $idmodulo, $idsmodulo, $selsi]);
                            break;
                        case "descargar":
                            $sql_insert = DB::connection('mysql')->insert('insert into tab_permisos (
                                idusuario, idmodulo, idsubmodulo, descargar
                            ) values (?,?,?,?)', [$idu, $idmodulo, $idsmodulo, $selsi]);
                            break;
                        case "configurar":
                            $sql_insert = DB::connection('mysql')->insert('insert into tab_permisos (
                                idusuario, idmodulo, idsubmodulo, configurar
                            ) values (?,?,?,?)', [$idu, $idmodulo, $idsmodulo, $selsi]);
                            break;
                        default:
                            // code...
                            break;
                    }

                    if($sql_insert){
                        return response()->json(['resultado'=>true]);
                    }else{
                        return response()->json(['resultado'=>false]);
                    }
                }else if($estado=="0"){
                    switch ($opcion){
                        case "guardar":
                            $sql_insert = DB::connection('mysql')->insert('insert into tab_permisos (
                                idusuario, idmodulo, idsubmodulo, guardar
                            ) values (?,?,?,?)', [$idu, $idmodulo, $idsmodulo, $selno]);
                            break;
                        case "actualizar":
                            $sql_insert = DB::connection('mysql')->insert('insert into tab_permisos (
                                idusuario, idmodulo, idsubmodulo, actualizar
                            ) values (?,?,?,?)', [$idu, $idmodulo, $idsmodulo, $selno]);
                            break;
                        case "eliminar":
                            $sql_insert = DB::connection('mysql')->insert('insert into tab_permisos (
                                idusuario, idmodulo, idsubmodulo, eliminar
                            ) values (?,?,?,?)', [$idu, $idmodulo, $idsmodulo, $selno]);
                            break;
                        case "descargar":
                            $sql_insert = DB::connection('mysql')->insert('insert into tab_permisos (
                                idusuario, idmodulo, idsubmodulo, descargar
                            ) values (?,?,?,?)', [$idu, $idmodulo, $idsmodulo, $selno]);
                            break;
                        case "configurar":
                            $sql_insert = DB::connection('mysql')->insert('insert into tab_permisos (
                                idusuario, idmodulo, idsubmodulo, configurar
                            ) values (?,?,?,?)', [$idu, $idmodulo, $idsmodulo, $selno]);
                            break;
                        default:
                            // code...
                            break;
                    }

                    if($sql_insert){
                        return response()->json(['resultado'=>true]);
                    }else{
                        return response()->json(['resultado'=>false]);
                    }
                }
            }
        }else{
            return response()->json(["resultado"=> 'no_exist']);
        }
    }

    public function get_all_permisos_usuario(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            $estado='1';

            $datosarray= array();

            $modulos= DB::connection('mysql')->table('tab_modulo')
            ->select('id', 'nombre')
            ->where('estado','=', $estado)->get();

            $getusers = DB::table('users')
            ->join('tab_perfil_usuario', 'users.tipo_usuario', '=', 'tab_perfil_usuario.id')
            ->select('users.id', 'users.nombre_usuario', 'users.estado', 'tab_perfil_usuario.nombre as tipo_usuario')
            ->get();

            foreach($getusers as $us){
                $contarmodulo= $this->getContadorModulo($us->id, $estado);

                $datosarray[] = array('id'=> $us->id, 'nombres'=> $us->nombre_usuario, 'rol'=> $us->tipo_usuario, 
                    'estado'=> $us->estado, 'total_modulo'=> $contarmodulo);
            }

            $permisos = collect($datosarray); // Convertir a colección

            // Solo devuelve el HTML de la tabla (no la vista entera)
            return view('Administrador.Permisos.tabla', compact('permisos'));
        }else{
            return redirect('/loginadmineep');
        }
    }
}
