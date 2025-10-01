<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ModulosController extends Controller
{
    //FUNCION QUE RETORNA LA VISTA PRINCIPAL DE SOCIAL MEDIA
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            $estado='1';
            $modulos= DB::connection('mysql')->table('tab_modulo')->get();
            $contarmodulo= DB::connection('mysql')->table('tab_modulo')->where('estado','=',$estado)->count();
            return view('Administrador.Modulos.modulo', ['modulos'=> $modulos, 'total'=> $contarmodulo]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function registro_modulo(Request $r){
        $nombre= $r->nombre;
        $icono= $r->icono;
        $prioridad= $r->prioridad;
        $date= now();

        $verificar= $this->getNameModulo($nombre);

        if($verificar==$nombre){
            return response()->json(["resultado"=> "existe"]);
        }else{
            $sql_insert = DB::connection('mysql')->insert('insert into tab_modulo (
                nombre, icono, nivel_prioridad, created_at
            ) values (?,?,?,?)', [$nombre, $icono, $prioridad, $date]);
            
            if($sql_insert){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
    }

    private function getNameModulo($nombre){
        $sql= DB::connection('mysql')->select('SELECT nombre FROM tab_modulo WHERE nombre=?', [$nombre]);

        $resultado= "";

        foreach($sql as $r){
            $resultado= $r->nombre;
        }

        return $resultado;
    }

    public function get_modulo($id){
        $id = desencriptarNumero($id);

        $sql = DB::connection('mysql')->select('SELECT nombre, icono, nivel_prioridad, estado_vis_novis FROM tab_modulo WHERE id=?',[$id]);

        return response()->json($sql);
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA DOC ADMINISTRATIVO
    public function inactivar_modulo(Request $request){
        $id= $request->input('id');
        $id = desencriptarNumero($id);
        $estado= $request->input('estado');
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_modulo')
                ->where('id', $id)
                ->update(['estado' => $estado]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function actualizar_modulo(Request $r){
        $id= $r->input('id');
        $id= desencriptarNumero($id);
        $nombre= $r->input('nombre');
        $icono= $r->input('icono');
        $prioridad= $r->input('prioridad');
        $estadomodulo= $r->input('estadomodulo');
        $date = now();

        $sql_update = DB::connection('mysql')->table('tab_modulo')
        ->where('id', '=', $id)
        ->update(['nombre'=> $nombre, 'icono'=> $icono, 'nivel_prioridad'=> $prioridad, 'estado_vis_novis'=> $estadomodulo, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=>true]);
        }else{
            return response()->json(['resultado'=>false]);
        }
    }

    //FUNCION QUE ACTUALIZA EL ORDEN DE PRIORIDAD
    public function registro_orden_modulo(Request $r){
        $res= $r->getContent();
        $array = json_decode($res, true);
        $longcadena= sizeof($array);
        $date= now();
        $i=0;

        foreach ($array as $value) {
            if($this->updateOrderModulo($value['id'],  $value['orden'], $date)){
                $i++;
            }
        }

        if($longcadena==$i){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    private function updateOrderModulo($id, $orden, $date){
        $sql_update= DB::table('tab_modulo')
            ->where('id', $id)
            ->update(['nivel_prioridad'=> $orden, 'updated_at'=> $date]);
        
        if($sql_update){
            return true;
        }else{
            return false;
        }
    }
}
