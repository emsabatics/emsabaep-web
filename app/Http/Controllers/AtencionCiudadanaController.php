<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AtencionCiudadanaController extends Controller
{
    public function index(){
        if(Session::get('usuario')){

            $arraydatos= array();

            $mensajes = DB::connection('mysql')->table('tab_mensajes')
                ->orderBy('fecha', 'asc')
                ->get();

            foreach($mensajes as $m){
                $nombre_usuario='';
                $fecha_modificacion= '';

                $seguimiento = DB::connection('mysql')
                ->table('tab_seguimiento_mensajes as sm')
                ->join('users as u', 'sm.id_usuariosistema', '=', 'u.id')
                ->select('sm.fecha','u.nombre_usuario')
                ->where('sm.id_mensaje','=', $m->id)
                ->latest('sm.id_mensaje')
                ->get();

                /*$maxId= DB::connection('mysql')->table('tab_seguimiento_mensajes as smsj')
                    ->where('id_mensaje','=', $m->id)->max('id');*/

                foreach($seguimiento as $s){
                    $fecha_modificacion= $s->fecha;
                    $nombre_usuario= $s->nombre_usuario;
                }

                $arraydatos[] = array('id'=> $m->id, 'cuenta'=> $m->cuenta, 'nombres'=> $m->nombres, 'email'=> $m->email, 'telefono'=> $m->telefono, 'fecha'=> $m->fecha, 'estado'=> $m->estado,
                    'observaciones'=> $m->estado_solicitud, 'ultima_modificacion'=> $fecha_modificacion, 'nombre_usuario'=> $nombre_usuario);
            }
            
            //return $arraydatos;
            return response()->view('Administrador.AtencionCiudadana.atencionciudadana', ['solicitudes'=> collect($arraydatos)]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function seguimiento_solicitudes($idseguimientosoli){
        if(Session::get('usuario')){
            $id = desencriptarNumero($idseguimientosoli);

            $arraydatos= array();
            $datainside= array();

            /*$mensajes = DB::connection('mysql')
                ->table('tab_mensajes as sms')
                ->join('tab_seguimiento_mensajes as smsj', 'sms.id', '=', 'smsj.id_mensaje')
                ->join('users', 'smsj.id_usuariosistema', '=', 'users.id')
                ->select('sms.*', 'smsj.id as idseguimiento', 'smsj.observaciones', 'smsj.estado_mensaje',
                    'smsj.id_usuariosistema as iduser', 'users.nombre_usuario')
                ->where('sms.id', '=', $id)
                ->get();*/
            $mensajes= DB::connection('mysql')->table('tab_mensajes')
            ->where('id','=', $id)
            ->get();
            
            foreach($mensajes as $m){
                $observaciones = DB::connection('mysql')
                ->table('tab_seguimiento_mensajes as smsj')
                ->join('users', 'smsj.id_usuariosistema', '=', 'users.id')
                ->select('smsj.id as idseguimiento', 'smsj.observaciones', 'smsj.id_usuariosistema as iduser', 'users.nombre_usuario')
                ->where('smsj.id_mensaje', '=', $id)
                ->get();

                foreach($observaciones as $obs){
                    $datainside[]= array('idseguimiento'=> $obs->idseguimiento, 'detalleobs'=> $obs->observaciones, 
                        'usuario'=> $obs->nombre_usuario);
                }

                $arraydatos[] = array('id'=> $m->id, 'nombres'=> $m->nombres, 'email'=> $m->email, 'telefono'=> $m->telefono, 'cuenta'=> $m->cuenta, 'detalle'=> $m->detalle, 
                    'estado_solicitud'=> $m->estado_solicitud, 'fecha'=> $m->fecha, 'estado'=> $m->estado, 'observaciones'=> $datainside);

                unset($datainside);
            }

            //return json_encode($arraydatos);

            return response()->view('Administrador.AtencionCiudadana.ver_solicitud', ['solicitudes'=> $arraydatos]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function store_observacion(Request $r){
        if(Session::get('usuario')){
            $idusuario = $this->getIdUser();
            $idregistro = $r->id;
            $observaciones = $r->observaciones;
            $date= now();
            $dia= $date->format('Y-m-d');
            //$estado_sms = "En Trámite";

            $sql_insert_r = DB::connection('mysql')->table('tab_seguimiento_mensajes')
                ->insertGetId(['id_mensaje'=> $idregistro, 'observaciones'=> $observaciones,'fecha'=> $dia,
                    'id_usuariosistema'=> $idusuario,'created_at'=> $date]);
            if($sql_insert_r){

                $contador = DB::connection('mysql')->table('tab_seguimiento_mensajes as smsj')
                    ->where('smsj.id_mensaje', '=', $idregistro)
                    ->count();
                
                //$contador+= 1;

                $observaciones = DB::connection('mysql')
                ->table('tab_seguimiento_mensajes as smsj')
                ->join('users', 'smsj.id_usuariosistema', '=', 'users.id')
                ->select('smsj.observaciones', 'smsj.fecha', 'users.nombre_usuario')
                ->where('smsj.id', '=', $sql_insert_r)
                ->get();

                return response()->json(["resultado"=> true, 'observaciones_n'=> $observaciones, 'contador'=> $contador]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function getIdUser(){
        $usuario = Session::get('usuario');
        $sql_getid= DB::connection('mysql')->table('users')->where('user', '=', $usuario)->get();
        
        $gid=0;

        foreach($sql_getid as $ev){
            $gid= $ev->id;
        }

        return $gid;
    }

    public function change_estado(Request $r){
        if(Session::get('usuario')){
            $id = $r->id;
            $estado= $r->estado;
            $newestado="";

            if($estado=='end'){
                $newestado= 'Finalizado';
            }else if($estado=='tram'){
                $newestado= 'En Trámite';
            }

            $sql_update = DB::connection('mysql')->table('tab_mensajes')
            ->where('id', $id)
            ->update(['estado_solicitud'=> $newestado]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function filtrar(Request $request){
        if(Session::get('usuario')){
            $estado = $request->estado;
            $inicio = $request->fecha_inicio;
            $hasta = $request->fecha_fin;

            $nestado='';
            if($estado=='tram'){
                $nestado= 'En Trámite';
            }else if($estado=='end'){
                $nestado= 'Finalizado';
            }

            $arraydatos= array();

            if($estado=='all'){
                $mensajes = DB::connection('mysql')->table('tab_mensajes')
                ->whereBetween('fecha', [$inicio, $hasta])
                ->orderBy('fecha', 'asc')
                ->get();
            }else{
                $mensajes = DB::connection('mysql')->table('tab_mensajes')
                ->where('estado_solicitud','=', $nestado)
                ->whereBetween('fecha', [$inicio, $hasta])
                ->orderBy('fecha', 'asc')
                ->get();
            }
            

            foreach($mensajes as $m){
                $nombre_usuario='';
                $fecha_modificacion= '';

                $seguimiento = DB::connection('mysql')
                ->table('tab_seguimiento_mensajes as sm')
                ->join('users as u', 'sm.id_usuariosistema', '=', 'u.id')
                ->select('sm.fecha','u.nombre_usuario')
                ->where('sm.id_mensaje','=', $m->id)
                ->latest('sm.id_mensaje')
                ->get();

                /*$maxId= DB::connection('mysql')->table('tab_seguimiento_mensajes as smsj')
                    ->where('id_mensaje','=', $m->id)->max('id');*/

                foreach($seguimiento as $s){
                    $fecha_modificacion= $s->fecha;
                    $nombre_usuario= $s->nombre_usuario;
                }

                $arraydatos[] = array('id'=> $m->id, 'cuenta'=> $m->cuenta, 'nombres'=> $m->nombres, 'email'=> $m->email, 'telefono'=> $m->telefono, 'fecha'=> $m->fecha, 'estado'=> $m->estado,
                    'observaciones'=> $m->estado_solicitud, 'ultima_modificacion'=> $fecha_modificacion, 'nombre_usuario'=> $nombre_usuario);
            }
            
            //return $arraydatos;

            $solicitudes = collect($arraydatos); // Convertir a colección

            // Solo devuelve el HTML de la tabla (no la vista entera)
            return view('Administrador.AtencionCiudadana.tabla', compact('solicitudes'));
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function getall(){
        if(Session::get('usuario')){

            $arraydatos= array();

            $mensajes = DB::connection('mysql')->table('tab_mensajes')
                ->orderBy('fecha', 'asc')
                ->get();

            foreach($mensajes as $m){
                $nombre_usuario='';
                $fecha_modificacion= '';

                $seguimiento = DB::connection('mysql')
                ->table('tab_seguimiento_mensajes as sm')
                ->join('users as u', 'sm.id_usuariosistema', '=', 'u.id')
                ->select('sm.fecha','u.nombre_usuario')
                ->where('sm.id_mensaje','=', $m->id)
                ->latest('sm.id_mensaje')
                ->get();

                /*$maxId= DB::connection('mysql')->table('tab_seguimiento_mensajes as smsj')
                    ->where('id_mensaje','=', $m->id)->max('id');*/

                foreach($seguimiento as $s){
                    $fecha_modificacion= $s->fecha;
                    $nombre_usuario= $s->nombre_usuario;
                }

                $arraydatos[] = array('id'=> $m->id, 'cuenta'=> $m->cuenta, 'nombres'=> $m->nombres, 'email'=> $m->email, 'telefono'=> $m->telefono, 'fecha'=> $m->fecha, 'estado'=> $m->estado,
                    'observaciones'=> $m->estado_solicitud, 'ultima_modificacion'=> $fecha_modificacion, 'nombre_usuario'=> $nombre_usuario);
            }
            
            $solicitudes = collect($arraydatos); // Convertir a colección

            // Solo devuelve el HTML de la tabla (no la vista entera)
            return view('Administrador.AtencionCiudadana.tabla', compact('solicitudes'));
        }else{
            return redirect('/loginadmineep');
        }
    }
}
