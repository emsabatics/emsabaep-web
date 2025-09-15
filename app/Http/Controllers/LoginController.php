<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Biscolab\ReCaptcha\Facades\ReCaptcha;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Services\PermisosService;


class LoginController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if(Session::get('usuario')){
            //return redirect()->route('home');
        }else{
            return response()->view('Administrador.login');
        }
    }

    public function iniciar_sesion(Request $request){
        $ip = request()->ip();
        $user = $request->input('txtUsuario');
        $estadouser='1';

        $request->validate([ 'g-recaptcha-response' => 'required|recaptcha', ]); 
        // Verifica el token de reCAPTCHA 
        $response = ReCaptcha::validate($request->input('g-recaptcha-response'));

        //VERSION 2 INVISIBLE
        //$response = ReCaptcha::verify($request->input('g-recaptcha-response'));

        //return response()->json($response);
        if (!$response){
            $sql= DB::connection('mysql')->select('select password, estado from users where user= ?', [$user]);

            $clave='0';
            $estadou='1';

            foreach($sql as $r){
                $clave= $r->password;
                $estadou= $r->estado;
            }
            if($estadou=='1'){
                if($clave!='0'){
                    if(Hash::check($request->input('txtPassword'), $clave)){
                        $datos_user= DB::table('users')
                            ->join('tab_perfil_usuario', 'tab_perfil_usuario.id','=','users.tipo_usuario')
                            ->select('users.*', 'tab_perfil_usuario.tipo')
                            ->where('users.user','=', $user)
                            ->get();

                        $date= now();
                        $sql_update= DB::table('users')
                                    ->where('user',$user)
                                    ->update(['ultimo_acceso'=> $date]);

                        foreach($datos_user as $usuario){
                            $request->session()->put('usuario', $usuario->user);
                            $request->session()->put('nombre_usuario', $usuario->nombre_usuario);
                            $request->session()->put('tipo_usuario', $usuario->tipo);
                        }

                        // Esto fuerza a que se carguen los permisos en cache
                        app(PermisosService::class)->generarPermisos($this->getIdUser());
                        return response()->json(['respuesta'=> true, 'usuario' => Session::get('usuario')]);
                    }else{
                        return response()->json(['clave'=> false]);
                    }
                }else{
                    return response()->json(['usuario'=> false]);
                }
            }else if($estadou=='0'){
                return response()->json(['usuario'=> 'inactivouser']);
            }
        }else { 
            return response()->json(['captcha'=> 'error']);
        }
    }

    public function recovery_pass(){
        return response()->view('Administrador.recoverypass');
    }

    public function get_code_access(Request $r){
        $clave= $r->red;
        $getcodigo='';
        $sql_get= DB::connection('mysql')->select('SELECT * FROM tab_recovery_pass_admin WHERE codigo=?',[$clave]);
        foreach($sql_get as $sg){
            $getcodigo= $sg->codigo;
        }

        if($clave==$getcodigo){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function registrar_clave_usuario(Request $request){
        $user = $request->input('datoUsuario');
        $sql_getuser= DB::connection('mysql')->select('select id, user from users where user= ?', [$user]);

        $usuario='';
        $idusuario='';

        foreach($sql_getuser as $r){
            $idusuario= $r->id;
            $usuario= $r->user;
        }

        $nameUsuario = $request->nameUsuario;

        //return response()->json(['nameusuario'=> $nameUsuario, 'usuario'=> $user]);

        if($usuario==$user){
            $clave = $request->input('passwordUsuario');

            $clave= Hash::make($clave);

            $sql_update= DB::table('users')
                ->where('id', $idusuario)
                ->update(['password' => $clave]);

            if($sql_update){
                return response()->json(['registro'=> true]);
            }else{
                return response()->json(['registro'=> false]);
            }
        }else{
            return response()->json(['usuario'=> false]);
        }
    }
    

    public function cerrar_sesion(){
        
        $userId = $this->getIdUser();
        
        Session::flush();

        // Eliminar cache de permisos
        //Cache::forget("permisos_user_{$userId}");
        app(PermisosService::class)->clearPermisos($userId);

        return redirect()->to('/loginadmineep');
    }

    private function getIdUser(){
        $user = Session::get('usuario');

        $sql = DB::connection('mysql')->table('users')->select('id')->where('user','=', $user)->get();

        $iduser = 0;

        foreach($sql as $s){
            $iduser = $s->id;
        }

        return $iduser;
    }

    private function completeCache(PermisosService $permiso){
        $userId = $this->getIdUser();


        /*Cache::forget("permisos_user_{$userId}"); // limpiar por si acaso

        $permisos = app(PermisosService::class)->generarPermisos($userId);ot
        Cache::put("permisos_user_{$userId}", $permisos, now()->addMinutes(30));

        Cache::remember("permisos_user_{$userId}", now()->addMinutes(30), function() use ($userId){
           return app(PermisosService::class)->generarPermisos($userId);
        });*/
    }    
}
