<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;

class NotificacionController extends Controller
{

    private function timeAgo($time_ago)
    {
        $time_ago = strtotime($time_ago);
        $cur_time   = time();
        $time_elapsed   = $cur_time - $time_ago;
        $seconds    = $time_elapsed ;
        $minutes    = round($time_elapsed / 60 );
        $hours      = round($time_elapsed / 3600);
        $days       = round($time_elapsed / 86400 );
        $weeks      = round($time_elapsed / 604800);
        $months     = round($time_elapsed / 2600640 );
        //$years      = round($time_elapsed / 31207680 );
        // Seconds
        if($seconds <= 60){
            return "Ahora";
        }
        //Minutes
        else if($minutes <=60){
            if($minutes==1){
                return "Hace un minuto";
            }
            else{
                return "Hace ".$minutes." min";
            }
        }
        //Hours
        else if($hours <=24){
            if($hours==1){
                return "Hace una hora";
            }else{
                return "Hace ".$hours." h";
            }
        }
        //Days
        else if($days <= 7){
            if($days==1){
                return "Ayer";
            }else{
                return "Hace ".$days." días";
            }
        }
        //Weeks
        else if($weeks <= 4.3){
            if($weeks==1){
                return "Hace una semana";
            }else{
                return "Hace ".$weeks." semanas";
            }
        }
        //Months
        else if($months <=12){
            if($months==1){
                return "Hace un mes";
            }else{
                return "Hace ".$months." meses";
            }
        }
        //Years
        /*else{
            if($years==1){
                return "Hace un año";
            }else{
                return "Hace ".$years." años";
            }
        }*/
    }

    private function data_first_month_day() {
        $month = date('m');
        $year = date('Y');
        return date('Y-m-d', mktime(0,0,0, $month, 1, $year));
    }

    private function data_last_month_day() { 
        $month = date('m');
        $year = date('Y');
        $day = date("d", mktime(0,0,0, $month+1, 0, $year));

        return date('Y-m-d', mktime(0,0,0, $month, $day, $year));
    }

    private function setFecha($date){
        $arraymes= array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre',
        'Noviembre','Diciembre');
        $anio= substr($date, 0, 4);
        $mes= substr($date,-5,2);
        $dia= substr($date, 8, strlen($date));
    
        $mes= intval($mes);
        //$diaN= formatDia(date('l', strtotime($date)));
        //return $dia.' de '.$arraymes[$mes].' del '.$anio;
        //return $diaN.', '.$dia.' de '.$arraymes[$mes].' del '.$anio;
        //return $arraymes[$mes].' '.$dia.', '.$anio;
        return $dia.' '.$arraymes[$mes].' '.$anio;
    }

    //FUNCION CARGA INFORMACION SOBRE LA INTERFAZ DE NOTIFICACIONES
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            //$array_resultado = DB::connection('mysql')->select('SELECT * FROM tab_notificaciones WHERE estado="1" ORDER BY fecha DESC');
            //return response()->view('Administrador.Notificaciones.notificacion', ['notificacion' => $array_resultado]);
            return response()->view('Administrador.Notificaciones.notificacion');
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function get_notificacion(){
        $date= now();
        $dia= $date->format('Y-m-d');

        $notificacion= DB::connection('mysql')->table('tab_notificaciones')
            ->join('tab_mensajes', 'tab_notificaciones.id_mensaje', '=', 'tab_mensajes.id')
            ->select('tab_mensajes.*')
            ->where('tab_notificaciones.fecha', $dia)
            ->where('tab_notificaciones.estado','1')
            ->get();
        
        $data= array();

        foreach($notificacion as $k){
            $tiempo= $this->timeAgo($k->created_at);
            $data[]= array('id'=> $k->id, 'tiempo'=> $tiempo, 'estado'=> $k->estado);
        }
        
        $count= DB::connection('mysql')->select('SELECT COUNT(*) as total FROM tab_notificaciones WHERE fecha=? AND estado=?', [$dia, '1']);

        $arraynoti= array('notificacion'=> $data, 'contador'=> $count);

        return response()->json($arraynoti);
    }

    public function get_all_notificacion(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            //$array_resultado = DB::connection('mysql')->select('SELECT * FROM tab_notificaciones WHERE estado="1" ORDER BY fecha DESC');
            $date= now();
            $hoy= $date->format('Y-m-d');
            $primerdia= $this->data_first_month_day();
            $ultimodia= $this->data_last_month_day();
            $notificacion= DB::connection('mysql')->table('tab_notificaciones')
            ->join('tab_mensajes', 'tab_notificaciones.id_mensaje', '=', 'tab_mensajes.id')
            ->select('tab_mensajes.*')
            ->whereBetween('tab_notificaciones.fecha', [$primerdia, $ultimodia])
            ->where('tab_notificaciones.estado','1')
            ->get();

            $data= array();

            foreach($notificacion as $k){
                $tiempo= $this->timeAgo($k->created_at);
                $data[]= array('id'=> $k->id, 'nombres'=> $k->nombres, 'descripcion'=> $k->detalle, 'tiempo'=> $tiempo, 'fecha'=> $k->fecha, 
                    'estado'=> $k->estado, 'email'=> $k->email);
            }

            return $data;
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function get_today_notificacion(){
        $date= now();
        $dia= $date->format('Y-m-d');

        $notificacion= DB::connection('mysql')->table('tab_notificaciones')
            ->join('tab_mensajes', 'tab_notificaciones.id_mensaje', '=', 'tab_mensajes.id')
            ->select('tab_mensajes.*')
            ->where('tab_notificaciones.fecha', $dia)
            ->where('tab_notificaciones.estado','1')
            ->get();

        $data= array();

        foreach($notificacion as $k){
            $tiempo= $this->timeAgo($k->created_at);
            $data[]= array('id'=> $k->id, 'nombres'=> $k->nombres, 'descripcion'=> $k->detalle, 'tiempo'=> $tiempo, 'fecha'=> $k->fecha, 
                'estado'=> $k->estado, 'email'=> $k->email);
        }

        return response()->json($data);
    }

    public function get_read_notificacion(){
        $primerdia= $this->data_first_month_day();
        $ultimodia= $this->data_last_month_day();
        $notificacion= DB::connection('mysql')->table('tab_notificaciones')
            ->join('tab_mensajes', 'tab_notificaciones.id_mensaje', '=', 'tab_mensajes.id')
            ->select('tab_mensajes.*')
            ->whereBetween('tab_notificaciones.fecha', [$primerdia, $ultimodia])
            ->where('tab_notificaciones.estado','0')
            ->where('tab_notificaciones.tipo_estado','leido')
            ->orderBy('tab_notificaciones.fecha','desc')
            ->get();

        $data= array();

        foreach($notificacion as $k){
            $tiempo= $this->timeAgo($k->created_at);
            $data[]= array('id'=> $k->id, 'nombres'=> $k->nombres, 'descripcion'=> $k->detalle, 'tiempo'=> $tiempo, 'fecha'=> $k->fecha, 
                'estado'=> $k->estado, 'email'=> $k->email);
        }

        return response()->json($data);
    }

    public function get_contador_notificacion(){
        $dato= array();
   
        $hoy= now();
        $fecha= $hoy->format('Y-m-d');
        $fecha= strval($fecha);
        $estado= "1";

        $totalhoy= DB::connection('mysql')->table('tab_notificaciones')
            ->join('tab_mensajes', 'tab_notificaciones.id_mensaje', '=', 'tab_mensajes.id')
            ->select('tab_mensajes.id')
            ->where('tab_notificaciones.fecha',$fecha)
            ->where('tab_notificaciones.estado','1')
            ->count();
            
        $totalall= DB::connection('mysql')->table('tab_notificaciones')
        ->join('tab_mensajes', 'tab_notificaciones.id_mensaje', '=', 'tab_mensajes.id')
        ->select('tab_mensajes.id')
        ->where('tab_notificaciones.estado','1')
        ->count();

        $dato[]= array('thoy'=> $totalhoy, 'tall'=> $totalall);

        return response()->json($dato);
    }

    //FUNCION CARGA INFORMACION SOBRE LA INTERFAZ DE LECTURA DE NOTIFICACIONES
    public function index_view_notificacion($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            //$array_resultado = DB::connection('mysql')->select('SELECT * FROM tab_notificaciones WHERE estado="1" ORDER BY fecha DESC');
            //return response()->view('Administrador.Notificaciones.notificacion', ['notificacion' => $array_resultado]);
            $notificacion= DB::connection('mysql')->table('tab_notificaciones')
            ->join('tab_mensajes', 'tab_notificaciones.id_mensaje', '=', 'tab_mensajes.id')
            ->select('tab_mensajes.*')
            ->where('tab_notificaciones.id', $id)
            ->get();

            $data= array();

            foreach($notificacion as $k){
                $fecha = substr($k->created_at, 0, -8); // devuelve "2024-00-00"
                $hora = substr($k->created_at, -8);    // devuelve "00:00:00"
                $shora= new DateTime($hora);
                $shora= $shora->format('h:i A');
                $sfecha= $this->setFecha($fecha);
                $tiempo= $sfecha.' '.$shora;
                $detalle = explode("//", $k->detalle);

                $data[]= array('id'=> $k->id, 'nombres'=> $k->nombres, 'descripcion'=> $detalle, 'tiempo'=> $tiempo, 'fecha'=> $k->fecha, 
                    'estado'=> $k->estado, 'email'=> $k->email, 'telefono'=> $k->telefono, 'cuenta'=> $k->cuenta);
            }

            json_encode($data);

            return response()->view('Administrador.Notificaciones.viewnotificacion', ['notificacion' => $data]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTUALIZA DOCUMENTACION LABORAL EN LA BASE DE DATOS
    public function update_item_notificacion(Request $r){
        $date= now();
        $notis= $r->idnotis;
        //$arraynotis= explode(",", $notis);
        //$arraynotis = json_decode($r->idnotis, true);
        $arraynotis = array_map('intval', explode(',', $r->idnotis));
        $estado="0";
        $contar=0;
        $longitud= sizeof($arraynotis);

        foreach($arraynotis as $v){
            $sql_update= DB::table('tab_mensajes')
                ->where('id',$v)
                ->update(['estado'=> $estado]);
    
            if($sql_update){
                $sql_update_m= DB::table('tab_notificaciones')
                    ->where('id_mensaje',$v)
                    ->update(['tipo_estado'=> 'leido', 'estado'=> $estado, 'updated_at'=> $date]);
    
                if($sql_update_m){
                    $contar++;
                }
            }
        }

        if($contar== $longitud){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }
}
