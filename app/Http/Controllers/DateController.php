<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DateController extends Controller
{
    //FUNCION QUE RETORNA LA VISTA PRINCIPAL DE AÑOS
    public function index(){
        if(Session::get('usuario') && Session::get('tipo_usuario')=='administrador'){
            $dateyear= DB::connection('mysql')->table('tab_anio')->orderByDesc('nombre')->get();
            return view('Administrador.Date.date', ['dateyear'=> $dateyear]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE REGISTRA EL AÑO
    public function registro_year(Request $r){
        $year= $r->year;
        $date= now();

        $sql_insert = DB::connection('mysql')->insert('insert into tab_anio (
            nombre, created_at
        ) values (?,?)', [$year, $date]);
        
        if($sql_insert){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    //FUNCION QUE OBTIENE EL AÑO
    public function get_year($id){
        $sql= DB::connection('mysql')->table('tab_anio')->where('id', $id)->get();

        return $sql;
    }

    //FUNCION QUE ACTUALIZA EL AÑO
    public function update_year(Request $r){
        $id= $r->idyear;
        $year= $r->year;
        $date= now();

        $sql_update= DB::table('tab_anio')
            ->where('id', $id)
            ->update(['nombre'=> $year, 'updated_at'=> $date]);
        
        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    public function inactivar_year(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();

        if($estado=='1'){
            $sql_update= DB::table('tab_anio')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }else{
            $sql_update= DB::table('tab_anio')
            ->where('id', $id)
            ->update(['estado' => $estado, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }
        
    }
}
