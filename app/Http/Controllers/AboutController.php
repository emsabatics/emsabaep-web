<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AboutController extends Controller
{
    public function index()
    {
        if(Session::get('usuario') && Session::get('tipo_usuario')=='administrador'){
            $about = DB::table('tab_about_institucion')->where('estado','1')->get();
            //$groupmision= $about->groupBy('tipo');
            return response()->view('Administrador.Infor.about', ['about' => $about]);
            /*if($about->isEmpty()){
                return response()->view('Administrador.Infor.about', ['about' => $about]);
            }else{
                return response()->view('Administrador.Infor.about');
            }*/
        }else{
            return redirect('/loginadmineep');
            //return redirect()->to('/loginadmineep');
        }
    }

    public function registrar_about(Request $request){
        $id= $request->input('id');
        $descripcion= $request->input('descripcion');
        $date= now();

        if($id==''){
            $sql_insert = DB::connection('mysql')->insert('insert into tab_about_institucion (
                descripcion,
                created_at
            ) values (?,?)', [$descripcion, $date]);

            if($sql_insert){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }else{
            //$sql_update = DB::connection('mysql')->update('update tab_about_institucion set descripcion= ?, updated_at= ? WHERE id= ?', [$descripcion, $date, $id]);
            $sql_update= DB::table('tab_about_institucion')
                ->where('id', $id)
                ->update(['descripcion' => $descripcion, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }
    }
}
