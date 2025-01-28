<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class EstructuraController extends Controller
{
    public function index()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $estructura = DB::table('tab_estructura_institucion')->where('estado','1')->get();
            //$groupmision= $estructura->groupBy('tipo');
            return response()->view('Administrador.Infor.estructura', ['estructura' => $estructura]);
            //return response()->view('Administrador.Infor.estructura');
        }else{
            return redirect('/loginadmineep');
            //return redirect()->to('/loginadmineep');
        }
    }

    public function registrar_estructura(Request $request){
        $id= $request->input('id');
        $descripcion= $request->input('descripcion');
        $date= now();

        if($id==''){
            $sql_insert = DB::connection('mysql')->insert('insert into tab_estructura_institucion (
                descripcion,
                created_at
            ) values (?,?)', [$descripcion, $date]);

            if($sql_insert){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }else{
            //$sql_update = DB::connection('mysql')->update('update tab_estructura_institucion set descripcion= ?, updated_at= ? WHERE id= ?', [$descripcion, $date, $id]);
            $sql_update= DB::table('tab_estructura_institucion')
                ->where('id', $id)
                ->update(['descripcion' => $descripcion, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }
    }

    public function save_estructura(Request $r){
        if ($r->hasFile('file') ) {
            return true;
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function inactivar_img_estructura(Request $r){
        $id= $r->id;
        $imagen= null;

        $sql_update= DB::table('tab_estructura_institucion')
            ->where('id', $id)
            ->update(['archivo'=> $imagen]);
        
        if($sql_update){
            if (Storage::disk('img_estructura')->exists($imagen)) {
                Storage::disk('img_estructura')->delete($imagen);
            }
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    public function actualizar_estructura_img(Request $r){
        if ($r->hasFile('file')) {
            $files  = $r->file('file'); //obtengo el archivo
            $id= $r->input('idstructurapics');
            $num_img= $r->num_img;
            $date= now();
            $contar=0;

            foreach($files as $file){
                // here is your file object
                $filename= $file->getClientOriginalName();
                $fileextension= $file->getClientOriginalExtension();
                //$filesize= $file->getSize();//
                /*$data = getimagesize($file->getRealPath());
                $width = $data[0];
                $height = $data[1];*/

                if($fileextension== $this->validarArchivo($fileextension)){
                    $storeimg= Storage::disk('img_estructura')->put($filename,  \File::get($file));
                    if($storeimg){
                        $sql_update= DB::table('tab_estructura_institucion')
                        ->where('id', $id)
                        ->update(['archivo'=> $filename, 'tipo_archivo'=> $fileextension]);
                        if($sql_update){
                            $contar++;
                        }
                    }else{
                        return response()->json(["resultado"=>"nocopy"]);
                    }
                }else{
                    return response()->json(['resultado'=> 'nofile']);
                }
            }

            if($contar==$num_img){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
    }

    //FUNCION QUE VALIDA SI ES UNA IMAGEN
    private function validarArchivo($extension){
        $validar_extension= array("png","jpg","jpeg", "pdf");
        if(in_array($extension, $validar_extension)){
            return $extension;
        }else{
            return "";
        }
    }
}
