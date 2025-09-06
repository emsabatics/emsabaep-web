<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SubmodulosController extends Controller
{
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            $estado='1';
            $submodulos= DB::connection('mysql')->table('tab_submodulo as sm')
            ->join('tab_modulo as m', 'sm.idmodulo','=','m.id')
            ->select('sm.id','sm.submodulo','sm.idmodulo','m.nombre as modulo', 'sm.estado')
            ->get();
            $contarmodulo= DB::connection('mysql')->table('tab_submodulo')->where('estado','=',$estado)->count();
            $modulos= DB::connection('mysql')->table('tab_modulo')->select('id', 'nombre')->where('estado','=',$estado)->get();
            //return $submodulos;
            return view('Administrador.Submodulos.submodulo', ['submodulos'=> $submodulos, 'total'=> $contarmodulo, 'modulos'=> $modulos]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function registro_submodulo(Request $r){
        $nombre= $r->nombre;
        $modulo= $r->modulo;
        $date= now();

        $verificar= $this->getNameSubmodulo($nombre);

        if($verificar==$nombre){
            return response()->json(["resultado"=> "existe"]);
        }else{
            $sql_insert = DB::connection('mysql')->insert('insert into tab_submodulo (
                submodulo, idmodulo, created_at
            ) values (?,?,?)', [$nombre, $modulo, $date]);
            
            if($sql_insert){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
    }

    private function getNameSubmodulo($nombre){
        $sql= DB::connection('mysql')->select('SELECT submodulo FROM tab_submodulo WHERE submodulo=?', [$nombre]);

        $resultado= "";

        foreach($sql as $r){
            $resultado= $r->submodulo;
        }

        return $resultado;
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA DOC ADMINISTRATIVO
    public function inactivar_submodulo(Request $request){
        $id= $request->input('id');
        $id = desencriptarNumero($id);
        $estado= $request->input('estado');
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_submodulo')
                ->where('id', $id)
                ->update(['estado' => $estado]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function get_submodulo($id){
        $id = desencriptarNumero($id);

        $sql = DB::connection('mysql')->select('SELECT submodulo as nombre, idmodulo as modulo, estado_vis_novis FROM tab_submodulo WHERE id=?',[$id]);

        return response()->json($sql);
    }

    public function actualizar_submodulo(Request $r){
        $id= $r->input('id');
        $id= desencriptarNumero($id);
        $nombre= $r->input('nombre');
        $estadosubmodulo= $r->input('estadosubmodulo');
        $date = now();

        $sql_update = DB::connection('mysql')->table('tab_submodulo')
        ->where('id', '=', $id)
        ->update(['submodulo'=> $nombre, 'estado_vis_novis'=> $estadosubmodulo, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=>true]);
        }else{
            return response()->json(['resultado'=>false]);
        }
    }
}
