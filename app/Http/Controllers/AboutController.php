<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

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

    public function actualizar_img_about(Request $r){
        if ($r->hasFile('file')) {
            $files  = $r->file('file'); //obtengo el archivo
            $id= $r->input('id_img');
            $num_img= $r->num_img;
            $date= now();
            $contar=0;

            foreach($files as $file){
                // here is your file object
                $filename= $file->getClientOriginalName();
                $fileextension= $file->getClientOriginalExtension();
                //$filesize= $file->getSize();//
                $data = getimagesize($file->getRealPath());
                $width = $data[0];
                $height = $data[1];

                if($fileextension== $this->validarImg($fileextension)){
                    $storeimg= Storage::disk('img_files')->put($filename,  \File::get($file));
                    if($storeimg){
                        $sql_insert_img= DB::connection('mysql')->table('tab_about_institucion')
                        ->where('id', $id)
                        ->update(['imagen'=> $filename, 'updated_at'=> $date]);

                        if($sql_insert_img){
                            $contar++;
                        }
                    }else{
                        return response()->json(['resultado'=>false]);
                    }
                    /*if($width < 600 && $height < 700){
                        $cont_noformat++;
                    }else{
                        $storeimg= Storage::disk('img_files')->put($filename,  \File::get($file));
                        if($storeimg){
                            $sql_insert_img= DB::connection('mysql')->table('tab_img_noticias')->insertGetId(
                                ['id_noticia'=> $LAST_ID, 'imagen'=> $filename,  'created_at'=> $date]
                            );
                            if($sql_insert_img){
                                $contar++;
                            }
                        }else{
                            return response()->json(['resultado'=>false]);
                        }
                    }*/
                }else{
                    return response()->json(['resultado'=> 'noimagen']);
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
     private function validarImg($extension){
        $validar_extension= array("png","jpg","jpeg");
        if(in_array($extension, $validar_extension)){
            return true;
        }else{
            return false;
        }
    }

    public function inactivar_img_about(Request $r){
        $id= $r->id;
        $imagen_campo = null;
        //$estado= $r->estado;
        $sql_update= DB::table('tab_about_institucion')
            ->where('id', $id)
            ->update(['imagen'=> $imagen_campo]);
        
        if($sql_update){
            $imagen= $this->getImagen($id);
            $conimg= $this->getcountImagen($imagen);
            $numpics= $this->getNumPics($id);
            if($conimg==1){
                if (Storage::disk('img_files')->exists($imagen)) {
                    Storage::disk('img_files')->delete($imagen);
                }
            }
            return response()->json(["resultado"=> true, "numimg"=> $numpics]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    private function getImagen($id){
        $sql= DB::connection('mysql')->select('SELECT imagen FROM tab_about_institucion WHERE id=?', [$id]);
        $resultado= 0;
        foreach($sql as $r){
            $resultado= $r->imagen;
        }
        return $resultado;
    }

    private function getcountImagen($imagen){
        $sql= DB::connection('mysql')->select('SELECT COUNT(imagen) as total FROM tab_about_institucion WHERE imagen=?', [$imagen]);
        $resultado= 0;
        foreach($sql as $r){
            $resultado= $r->total;
        }
        return $resultado;
    }

    private function getNumPics($idn){
        $estado="1";
        $resultado= 0;

        $sql= DB::connection('mysql')->select('SELECT COUNT(imagen) as total FROM tab_about_institucion WHERE id=? AND estado=?',[$idn,$estado]); 

        foreach($sql as $r){
            $resultado= $r->total;
        }

        return $resultado;
    }
}
