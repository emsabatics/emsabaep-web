<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\BuzonAtCiudadana;
use Biscolab\ReCaptcha\Facades\ReCaptcha;

class MailController extends Controller
{
    private function formatDia($day)
    {
        $dia = "";
        switch ($day) {
            case "Sunday":
                $dia = "Domingo";
                break;
            case "Monday":
                $dia = "Lunes";
                break;
            case "Tuesday":
                $dia = "Martes";
                break;
            case "Wednesday":
                $dia = "Miércoles";
                break;
            case "Thursday":
                $dia = "Jueves";
                break;
            case "Friday":
                $dia = "Viernes";
                break;
            case "Saturday":
                $dia = "Sábado";
                break;
        }
        return $dia;
    }

    private function setFecha($date){
        $arraymes= array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre',
        'Noviembre','Diciembre');
        $setfecha= substr($date, 0, 10);
        $anio= substr($setfecha, 0, 4);
        $mes= substr($setfecha,-5,2);
        $dia= substr($setfecha, 8, strlen($setfecha));
        //return $setfecha.' Anio: '.$anio.' Mes: '.$mes.' Día: '.$dia.' //';
    
        $mes= intval($mes);
        $diaN= $this->formatDia(date('l', strtotime($date)));
        //return $dia.' de '.$arraymes[$mes].' del '.$anio;
        return $diaN.', '.$dia.' de '.$arraymes[$mes].' del '.$anio.' - '.substr($date, 11, strlen($date));
    }

    public function index()
    {
        //return response()->view('Mail.mail');
        $usuario= "Jean López";
        $email= "jclopez@emsaba.gob.ec";
        $detalle = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut et malesuada purus. Nunc pulvinar leo nulla, eu cursus nulla sollicitudin at. Ut placerat luctus ante sit amet hendrerit. Integer volutpat condimentum tortor. Nunc non libero non lacus scelerisque faucibus sit amet eget tellus. Donec aliquam elementum metus non dignissim. Phasellus bibendum odio velit, id pretium metus pulvinar id. Suspendisse pellentesque commodo nulla, luctus viverra leo sollicitudin aliquet. Nulla facilisi. Nulla tempor tristique cursus. Vestibulum consectetur leo id mauris euismod laoreet. Nullam vitae lorem vitae dui condimentum laoreet id eu leo. Vivamus lobortis dictum hendrerit. Nunc euismod congue erat quis placerat. Suspendisse sit amet eleifend nisi. Maecenas varius pharetra imperdiet.";
        $fecha= $this->setFecha('2024-10-16 13:57:09');
        return new BuzonAtCiudadana($usuario,$email,$detalle, $fecha);
        /*$response = Mail::mailer("smtp")->to('atencionciudadana@emsaba.gob.ec')
            ->send(new BuzonAtCiudadana($usuario,$email,$detalle));
        dump($response);*/

        //->attachFromStorage();
    }

    public function registro_mensaje_usuario(Request $r){
        $nombres = $r->nombres;
        $email= $r->email;
        $descripcion= $r->descripcion;
        $telefono= $r->telefono;
        $cuenta= $r->cuenta;
        $date= now();
        $dia= $date->format('Y-m-d');

        $r->validate([ 'g-recaptcha-response' => 'required|recaptcha', ]); 
        // Verifica el token de reCAPTCHA 
        $response_captcha = ReCaptcha::validate($r->input('g-recaptcha-response'));

        //VERSION 2 INVISIBLE
        //$response_captcha = ReCaptcha::verify($request->input('g-recaptcha-response'));

        //return response()->json($response);
        if (!$response_captcha){
            $sql_insert= DB::connection('mysql')->table('tab_mensajes')->insertGetId(
                ['nombres'=> $nombres, 'email'=> $email, 'telefono'=> $telefono, 'cuenta'=> $cuenta, 'detalle'=> $descripcion, 'fecha'=> $dia, 'created_at'=> $date]
            );
            $LAST_ID= $sql_insert;
            /*$response = Mail::mailer("smtp")->to('atencionciudadana@emsaba.gob.ec')
                        ->send(new BuzonAtCiudadana($nombres,$email,$descripcion,$telefono,$cuenta,$date));
            
            $sql_insert= true;*/
            if($sql_insert){
                //return response()->json(["resultado"=> true]);
                $sql_insert_noti = DB::connection('mysql')->insert('insert into tab_notificaciones (
                            id_mensaje, fecha, created_at
                        ) values (?,?,?)', [$LAST_ID, $dia, $date]);

                $observaciones = "Ingreso de Solicitud y/o Reclamo";
                $estado_sms = "En Trámite";
                $idusuario = $this->getIdUser();
                $sql_insert_seguimiento = DB::connection('mysql')->insert('insert into tab_seguimiento_mensajes (
                            id_mensaje, observaciones, fecha, estado_mensaje, id_usuariosistema, created_at
                        ) values (?,?,?,?,?,?)', [$LAST_ID, $observaciones, $dia, $estado_sms, $idusuario, $date]);
        
                if($sql_insert_noti && $sql_insert_seguimiento){
                    $response = Mail::mailer("smtp")->to('atencionciudadana@emsaba.gob.ec')
                        ->send(new BuzonAtCiudadana($nombres,$email,$descripcion,$telefono,$cuenta,$date));
                    return response()->json(["resultado"=> true]);
                }else{
                    return response()->json(["resultado"=> false]);
                }
            }else{
                return response()->json(["resultado"=> false]);
            }
        }else { 
            return response()->json(['captcha'=> 'error']);
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
