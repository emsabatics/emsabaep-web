<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RedSocialController extends Controller
{
    //FUNCION QUE RETORNA LA VISTA PRINCIPAL DE SOCIAL MEDIA
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='operador')){
            //$socialmedia= DB::connection('mysql')->table('tab_social_media')->get();
            /*$socialmedia= DB::connection('mysql')->table('tab_social_media')
            ->join('tab_red_social', function ($join) {
                $join->on('tab_social_media.id_red_social', '=', 'tab_red_social.id');
            })
        ->get();*/
            $redsocial= DB::connection('mysql')->table('tab_red_social')->get();
            $socialmedia= DB::connection('mysql')->table('tab_social_media')
            ->join('tab_red_social', 'tab_social_media.id_red_social', '=', 'tab_red_social.id')
            ->select('tab_social_media.*', 'tab_red_social.nombre')
            ->get();
            return view('Administrador.RedSocial.redsocial', ['socialmedia'=> $socialmedia, 'redsocial'=> $redsocial]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function registro_socialm(Request $r){
        $media= $r->media;
        $usuario= $r->usuario;
        $enlace= $r->enlace;
        $date= now();

        $verificar= $this->getNameMedia($media);

        if($verificar==$media){
            return response()->json(["resultado"=> "existe"]);
        }else{
            $sql_insert = DB::connection('mysql')->insert('insert into tab_social_media (
                id_red_social, usuario, enlace, created_at
            ) values (?,?,?,?)', [$media, $usuario, $enlace, $date]);
            
            if($sql_insert){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
    }

    private function getNameMedia($media){
        $sql= DB::connection('mysql')->select('SELECT id_red_social FROM tab_social_media WHERE id_red_social=?', [$media]);

        $resultado= "";

        foreach($sql as $r){
            $resultado= $r->id_red_social;
        }

        return $resultado;
    }

    public function get_socialm_item($id){
        //$sql= DB::connection('mysql')->table('tab_social_media')->where('id', $id)->get();

        $socialmedia= DB::connection('mysql')->table('tab_social_media')
            ->join('tab_red_social', 'tab_social_media.id_red_social', '=', 'tab_red_social.id')
            ->select('tab_social_media.*', 'tab_red_social.nombre')
            ->where('tab_social_media.id', $id)
            ->get();

        return $socialmedia;
    }

    public function update_socialmedia(Request $r){
        $id= $r->id;
        $usuario= $r->usuario;
        $enlace= $r->enlace;
        $date= now();

        $sql_update= DB::table('tab_social_media')
            ->where('id', $id)
            ->update([ 'usuario'=> $usuario, 'enlace'=> $enlace, 'updated_at'=> $date]);
        
        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    public function inactivar_socialmedia(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();

        if($estado=='1'){
            $socialmedia= DB::connection('mysql')->select('SELECT id_red_social FROM tab_social_media WHERE id=?', [$id]);
            $res_estado='';

            foreach ($socialmedia as $key) {
                $idredsocial= $key->id_red_social;
            }

            $redsocial= DB::connection('mysql')->select('SELECT estado FROM tab_red_social WHERE id=?', [$idredsocial]);

            foreach ($redsocial as $key) {
                $res_estado= $key->estado;
            }

            if($res_estado==0){
                return response()->json(['resultado'=> 'inactivo']);
            }else{
                $sql_update= DB::table('tab_social_media')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

                if($sql_update){
                    return response()->json(['resultado'=> true]);
                }else{
                        return response()->json(['resultado'=> false]);
                }
            }
        }else{
            $sql_update= DB::table('tab_social_media')
            ->where('id', $id)
            ->update(['estado' => $estado, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                    return response()->json(['resultado'=> false]);
            }
        }
        
    }



    //FUNCION QUE RETORNA LA VISTA DE REGISTRO DE RED SOCIAL
    public function redsocial(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='operador')){
            $socialmedia= DB::connection('mysql')->table('tab_red_social')->get();
            return view('Administrador.RedSocial.aggredsocial', ['socialmedia'=> $socialmedia]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE REGISTRA UNA NUEVA RED SOCIAL
    public function registrar_redsocial(Request $r){
        $red= $r->red;
        $date= now();

        $sql_insert = DB::connection('mysql')->insert('insert into tab_red_social (
            nombre, created_at
        ) values (?,?)', [$red, $date]);
        
        if($sql_insert){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    //FUNCION QUE RETORNA INFORMACION DE LA RED SOCIAL SELECCIONADA
    public function get_redsocial($id){
        $res= DB::connection('mysql')->table('tab_red_social')->where('id', $id)->get();
        return $res;
    }

    //FUNCION QUE ACTUALIZA LA RED SOCIAL SELECCIONADA
    public function actualizar_redsocial(Request $r){
        $id= $r->id;
        $red= $r->red;
        $date= now();

        $sql_update= DB::table('tab_red_social')
            ->where('id', $id)
            ->update([ 'nombre'=> $red, 'updated_at'=> $date]);
        
        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    //FUNCION QUE INACTIVA LA RED SOCIAL SELECCIONADA
    public function inactivar_redsocial(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();

        if($estado=='0'){
            $socialmedia= DB::connection('mysql')->select('SELECT estado FROM tab_social_media WHERE id_red_social=?', [$id]);
            $res_estado='';

            foreach ($socialmedia as $key) {
                $res_estado= $key->estado;
            }

            if($res_estado==1){
                return response()->json(['resultado'=> 'activo']);
            }else{
                $sql_update= DB::table('tab_red_social')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

                if($sql_update){
                    return response()->json(['resultado'=> true]);
                }else{
                    return response()->json(['resultado'=> false]);
                }
            }
        }else{
            $sql_update= DB::table('tab_red_social')
            ->where('id', $id)
            ->update(['estado' => $estado, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }

        
    }

    //FUNCION QUE ELIMINAR LA RED SOCIAL SELECCIONADA
    public function delete_socialmedia(Request $request){
        $id= $request->input('id');

        $sql_update= DB::table('tab_social_media')
            ->where('id', $id)
            ->delete();

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }
}
