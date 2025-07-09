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
                $estado_mensaje='';
                $nombre_usuario='';

                $maxId= DB::connection('mysql')->table('tab_seguimiento_mensajes as smsj')
                    ->where('id_mensaje','=', $m->id)->max('id');

                $seguimiento = DB::connection('mysql')
                ->table('tab_seguimiento_mensajes as sm')
                ->join('users as u', 'sm.id_usuariosistema', '=', 'u.id')
                ->select('sm.estado_mensaje', 'u.nombre_usuario')
                ->where('sm.id_mensaje','=', $m->id)
                ->get();

                foreach($seguimiento as $s){
                    $estado_mensaje= $s->estado_mensaje;
                    $nombre_usuario= $s->nombre_usuario;
                }

                $arraydatos[] = array('id'=> $m->id, 'cuenta'=> $m->cuenta, 'nombres'=> $m->nombres, 'email'=> $m->email, 'telefono'=> $m->telefono, 'fecha'=> $m->fecha, 'estado'=> $m->estado,
                    'estado_mensaje'=> $estado_mensaje, 'nombre_usuario'=> $nombre_usuario);
            }
            
            //return $arraydatos;
            return response()->view('Administrador.AtencionCiudadana.atencionciudadana', ['solicitudes'=> $arraydatos]);
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
                ->select('smsj.id as idseguimiento', 'smsj.observaciones', 'smsj.estado_mensaje', 'smsj.id_usuariosistema as iduser', 'users.nombre_usuario')
                ->where('smsj.id_mensaje', '=', $id)
                ->get();

                foreach($observaciones as $obs){
                    $datainside[]= array('idseguimiento'=> $obs->idseguimiento, 'detalleobs'=> $obs->observaciones, 
                        'estado_mensaje'=> $obs->estado_mensaje, 'usuario'=> $obs->nombre_usuario);
                }

                $arraydatos[] = array('id'=> $m->id, 'nombres'=> $m->nombres, 'email'=> $m->email, 'telefono'=> $m->telefono, 'cuenta'=> $m->cuenta, 'detalle'=> $m->detalle, 
                    'fecha'=> $m->fecha, 'estado'=> $m->estado, 'observaciones'=> $datainside);

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
            $estado_sms = "En Trámite";

            $sql_insert_r = DB::connection('mysql')->table('tab_seguimiento_mensajes')
                ->insertGetId(['id_mensaje'=> $idregistro, 'observaciones'=> $observaciones,'fecha'=> $dia,
                    'estado_mensaje'=> $estado_sms,'id_usuariosistema'=> $idusuario,'created_at'=> $date]);
            if($sql_insert_r){

                $contador = DB::connection('mysql')->table('tab_seguimiento_mensajes as smsj')
                    ->where('smsj.id_mensaje', '=', $idregistro)
                    ->count();
                
                //$contador+= 1;

                $observaciones = DB::connection('mysql')
                ->table('tab_seguimiento_mensajes as smsj')
                ->join('users', 'smsj.id_usuariosistema', '=', 'users.id')
                ->select('smsj.observaciones', 'smsj.estado_mensaje', 'smsj.fecha', 'users.nombre_usuario')
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
}
