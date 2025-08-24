<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class UsuariosController extends Controller
{
    //FUNCION QUE RETORNA LA VISTA PRINCIPAL DE AJUSTES USUARIOS
    public function index(){
        if(Session::get('usuario') && Session::get('tipo_usuario')=='administrador'){
            //$getusers= DB::connection('mysql')->select('SELECT id, user, nombre_usuario, tipo_usuario, ultimo_acceso, estado FROM users');
            $getusers = DB::table('users')
            ->join('tab_perfil_usuario', 'users.tipo_usuario', '=', 'tab_perfil_usuario.id')
            ->select('users.*', 'tab_perfil_usuario.nombre as tipo_usuario')
            ->get();
            return view('Administrador.Usuarios.usuario', ['getusers'=> $getusers]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function registrar_usuario()
    {
        if(Session::get('usuario') && Session::get('tipo_usuario')=='administrador'){
            $perfiluser= DB::connection('mysql')->table('tab_perfil_usuario')->orderBy('nombre')->get();
            return response()->view('Administrador.Usuarios.registrar_usuario', ['perfiluser'=> $perfiluser]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function store_new_usuario(Request $request){
        $user = $request->usuario;
        $sql_getuser= DB::connection('mysql')->select('select user from users where user= ?', [$user]);

        $usuario='';

        foreach($sql_getuser as $r){
            $usuario= $r->user;
        }

        $nameUsuario = $request->nameUsuario;

        //return response()->json(['nameusuario'=> $nameUsuario, 'usuario'=> $user]);

        if($usuario==''){
            $getusuario= $request->usuario;
            $nameUsuario = $request->nombre;
            $clave = $request->clave;
            $tipousuario= $request->tipou;
            $datetime= now();

            $clave= Hash::make($clave);

            $sql_insert = DB::connection('mysql')->insert('insert into users (
                user,
                password,
                nombre_usuario,
                tipo_usuario,
                created_at
            ) values (?,?,?,?,?)', [$getusuario, $clave, $nameUsuario, $tipousuario, $datetime]);

            if($sql_insert){
                return response()->json(['registro'=> true]);
            }else{
                return response()->json(['registro'=> false]);
            }
        }else{
            return response()->json(['usuario'=> false]);
        }
    }

    //FUNCION QUE ACTUALIZA CLAVE DE USUARIO EN LA BASE DE DATOS
    public function update_password_usuario(Request $r){
        $idusuario= $r->idusuario;
        $clave= $r->clave;

        $clave= Hash::make($clave);

        $sql_update= DB::table('users')
            ->where('id',$idusuario)
            ->update(['password'=> $clave]);
    
        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

     //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA USUARIOS
    public function inactivar_usuario(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('users')
                ->where('id', $id)
                ->update(['estado' => $estado]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR USUARIO
    public function edit_view_usuario($id){
        if(Session::get('usuario') && Session::get('tipo_usuario')=='administrador'){
            $id = desencriptarNumero($id);
            $datos_user= DB::table('users')
                    ->join('tab_perfil_usuario', 'tab_perfil_usuario.id','=','users.tipo_usuario')
                    ->select('users.*', 'tab_perfil_usuario.tipo')
                    ->where('users.id','=', $id)
                    ->get();
            $perfiluser= DB::connection('mysql')->table('tab_perfil_usuario')->orderBy('nombre')->get();
            return response()->view('Administrador.Usuarios.editar_usuario', ['perfiluser'=> $perfiluser, 'datos_user'=>$datos_user]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTUALIZA DATOS DE USUARIO EN LA BASE DE DATOS
    public function update_usuario(Request $r){
        $idusuario= $r->idusuario;
        $nombre= $r->nombre;
        $tipou= $r->tipou;

        $idusuario = desencriptarNumero($idusuario);

        //echo $idusuario.' '.$nombre.' '.$tipou;

        $sql_update= DB::connection('mysql')->table('users')
            ->where('id','=',$idusuario)
            ->update(['nombre_usuario'=> $nombre, 'tipo_usuario'=> $tipou]);
    
        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }


    /**
     * FUNCIONES DE PERFIL DE USUARIO 
    */

    //FUNCION QUE RETORNA LA VISTA PRINCIPAL DE PERFIL DE USUARIO
    public function index_perfil_usuario(){
        if(Session::get('usuario') && Session::get('tipo_usuario')=='administrador'){
            $perfiluser= DB::connection('mysql')->table('tab_perfil_usuario')->orderBy('nombre')->get();
            return view('Administrador.Usuarios.perfilusuario', ['perfiluser'=> $perfiluser]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE REGISTRA EL PERFIL DE USUARIO
    public function registrar_perfil_usuario(Request $r){
        $nombre= $r->nombre;
        $tipo= $r->tipo;
        $descripcion= $r->descripcion;
        $date= now();

        $sql_insert = DB::connection('mysql')->insert('insert into tab_perfil_usuario (
            nombre, descripcion, tipo, created_at
        ) values (?,?,?, ?)', [$nombre, $descripcion, $tipo, $date]);
        
        if($sql_insert){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    //FUNCION QUE OBTIENE INFO DE PERFIL DE USUARIO
    public function get_perfil_usuario($id){
        $sql= DB::connection('mysql')->table('tab_perfil_usuario')->where('id', $id)->get();

        return $sql;
    }

    public function inactivar_perfil_usuario(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();

        $sql_update= DB::table('tab_perfil_usuario')
            ->where('id', $id)
            ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE ACTUALIZA DATOS DE PERFIL DE USUARIO EN LA BASE DE DATOS
    public function update_perfil_usuario(Request $r){
        $idusuario= $r->idperfil;
        $nombre= $r->nombre;
        $tipou= $r->tipo;
        $descripcion= $r->descripcion;
        $date= now();

        $sql_update= DB::table('tab_perfil_usuario')
            ->where('id',$idusuario)
            ->update(['nombre'=> $nombre, 'descripcion'=> $descripcion, 'tipo'=> $tipou, 'updated_at'=> $date]);
    
        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }


    /**
     * FUNCIONES DE AJUSTES DE USUARIO 
    */
    //FUNCION QUE RETORNA LA VISTA PRINCIPAL DE AJUSTES DE PERFIL DE USUARIO
    public function index_settings(){
        if(Session::get('usuario')){
            $usuario= Session::get('usuario');
            $sql_getid= DB::table('users')
                ->where('user','=',$usuario)
                ->get();

            foreach ($sql_getid as $key ) {
                $id= $key->id;
            }

            $datos_user= DB::table('users')
                    ->join('tab_perfil_usuario', 'tab_perfil_usuario.id','=','users.tipo_usuario')
                    ->select('users.*', 'tab_perfil_usuario.tipo')
                    ->where('users.id','=', $id)
                    ->get();
            $perfiluser= DB::connection('mysql')->table('tab_perfil_usuario')->orderBy('nombre')->get();
            return view('Administrador.Usuarios.settings_usuario', ['perfiluser'=> $perfiluser, 'datos_user'=>$datos_user]);
        }else{
            return redirect('/loginadmineep');
        }
    }
}
