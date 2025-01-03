<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MiViVaObController extends Controller
{
    public function index()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $mision = DB::table('mvvob')->where('estado','1')->get();
            $groupmision= $mision->groupBy('tipo');
            return response()->view('Administrador.Infor.mision', ['mision' => $groupmision]);
        }else{
            return redirect('/login');
            //return redirect()->to('/login');
        }
    }

    public function get_data(){
        //$mision = DB::table('mvvob')->where('estado','1')->get();
        $mision = DB::table('mvvob')->get();
        $groupmision= $mision->groupBy('tipo');
        return $groupmision;
    }

    /* FUNCION QUE REGISTRA MISION, VISION, VALORES & OBJETIVOS */
    public function registrar_mivivaob(Request $request){
        $id= $request->input('id');
        $descripcion= $request->input('descripcion');
        $tipo= $request->input('tipo');
        $date= now();

        if($id==''){
            $sql_insert = DB::connection('mysql')->insert('insert into mvvob (
                descripcion,
                tipo,
                created_at
            ) values (?,?,?)', [$descripcion, $tipo, $date]);

            if($sql_insert){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }else{
            //$sql_update = DB::connection('mysql')->update('update mvvob set descripcion= ?, tipo= ?, updated_at= ? WHERE id= ?', [$descripcion, $tipo, $date, $id]);
            $sql_update= DB::table('mvvob')
                ->where('id', $id)
                ->update(['descripcion' => $descripcion, 'tipo'=> $tipo, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }
    }

    public function eliminar_objetivo(Request $request){
        $id= $request->input('id');
        $deleted = DB::table('mvvob')->where('id', '=', $id)->delete();

        if($deleted){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function inactivar_objetivo(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);
        $sql_update= DB::table('mvvob')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function registrar_objetivo(Request $request){
        $descripcion= $request->input('descripcion');
        $tipo= $request->input('tipo');
        $date= now();
        //return response()->json(['descripcion'=> $descripcion,'tipo'=> $tipo]);
        $arraydescp = explode("<>,", $descripcion);
        $longiarray= sizeof($arraydescp);
        $contar=0;
        $dato= array();

        foreach($arraydescp as $k){
            $findme   = '<>';
            $pos = strpos($k, $findme);
            if ($pos !== false) {
                $ncadena= substr($k, 0, $pos);
                $arrayget= DB::table('mvvob')->insertGetId([
                    'descripcion' => $ncadena,
                    'tipo' => $tipo,
                    'created_at' => $date
                ]);
                if($arrayget){
                    $dato[]=array('id'=> $arrayget, 'descripcion'=> $ncadena, 'estado'=>"1");
                    $contar++;
                }
            }else{
                $arrayget= DB::table('mvvob')->insertGetId([
                    'descripcion' => $k,
                    'tipo' => $tipo,
                    'created_at' => $date
                ]);
                if($arrayget){
                    $dato[]=array('id'=> $arrayget, 'descripcion'=> $k, 'estado'=>"1");
                    $contar++;
                }
            }
        }
    
        if($contar==$longiarray){
            return response()->json(["resultado"=> true, 'objetivos'=> $dato]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    public function registrar_valor(Request $request){
        $descripcion= $request->input('descripcion');
        $tipo= $request->input('tipo');
        $date= now();
        //return response()->json(['descripcion'=> $descripcion,'tipo'=> $tipo]);
        $arraydescp = explode("<>,", $descripcion);
        $longiarray= sizeof($arraydescp);
        $contar=0;
        $dato= array();

        foreach($arraydescp as $k){
            $findme   = '<>';
            $pos = strpos($k, $findme);
            if ($pos !== false) {
                $ncadena= substr($k, 0, $pos);
                $arrayget= DB::table('mvvob')->insertGetId([
                    'descripcion' => $ncadena,
                    'tipo' => $tipo,
                    'created_at' => $date
                ]);
                if($arrayget){
                    $dato[]=array('id'=> $arrayget, 'descripcion'=> $ncadena, 'estado'=>"1");
                    $contar++;
                }
            }else{
                $arrayget= DB::table('mvvob')->insertGetId([
                    'descripcion' => $k,
                    'tipo' => $tipo,
                    'created_at' => $date
                ]);
                if($arrayget){
                    $dato[]=array('id'=> $arrayget, 'descripcion'=> $k, 'estado'=>"1");
                    $contar++;
                }
            }
        }
    
        if($contar==$longiarray){
            return response()->json(["resultado"=> true, 'valores'=> $dato]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    public function inactivar_valor(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);
        $sql_update= DB::table('mvvob')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function eliminar_valor(Request $request){
        $id= $request->input('id');
        $deleted = DB::table('mvvob')->where('id', '=', $id)->delete();

        if($deleted){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

}
