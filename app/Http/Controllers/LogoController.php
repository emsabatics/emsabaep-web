<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class LogoController extends Controller
{
    //FUNCION QUE RETORNA LA VISTA PRINCIPAL DE INTERFAZ PARA EL LOGO
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $logo= DB::connection('mysql')->table('tab_logo')->get();
            $wordCount = $logo->count();
            return view('Administrador.Logo.logo', ['logo'=> $logo]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function registrar_logo(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            return view('Administrador.Logo.registro_logo');
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE REALIZA EL INGRESO CORRESPONDIENTE DE LAS IMAGENES EN LA BD
    public function storage_logo(Request $r){
        if ($r->hasFile('file')) {
            $files  = $r->file('file'); //obtengo el archivo
            $countfiles= count($r->file('file'));
            $date= now();
            $contar = 0;
            $i=0;
            foreach($files as $file){
                $filename= $file->getClientOriginalName();
                $fileextension= $file->getClientOriginalExtension();
                if($fileextension== $this->validarImg($fileextension)){
                    $storeimg= Storage::disk('img_files')->put($filename,  \File::get($file));
                    if($storeimg){
                        $numr= $this->existImgLogo($filename);
                        if($numr==0){
                            if($this->insertarImgLogo($filename, $date)){
                                $contar++;
                            }
                        }else{
                            if($this->updateImgLogo($filename, $date)){
                                $contar++;
                            }
                        }
                    }else{
                        return response()->json(["resultado"=>"nocopy"]);
                    }
                }else{
                    return response()->json(['resultado'=> 'noimagen']);
                }
                $i++;
            }
            if ($contar == $countfiles) {
                return response()->json(["resultado" => true]);
            } else {
                return response()->json(["resultado" => false]);
            }
        }else{
            return response()->json(['resultado'=> 'noimagen']);
        }
    }

    //FUNCION QUE VALIDA SI ES UNA IMAGEN
    private function validarImg($extension){
        $validar_extension= array("png","jpg","jpeg");
        if(in_array($extension, $validar_extension)){
            return $extension;
        }else{
            return '0';
        }
    }

    //FUNCION QUE VALIDA SI EXISTE LA IMG EN LA BD
    private function existImgLogo($archivo){
        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_logo WHERE archivo=?', [$archivo]);

        $get_imagen='';
        foreach ($sql_dato as $key) {
            $get_imagen= $key->archivo;
        }

        if($get_imagen==''){
            return 0;
        }else {
            return 1;
        }
    }

     //FUNCION QUE INSERTA LA IMG EN LA BD
     private function insertarImgLogo($name, $date){
        $sql_insert= DB::connection('mysql')->table('tab_logo')->insertGetId(
            ['archivo'=> $name, 'created_at'=> $date]
        );
        if($sql_insert){
            return true;
        }else{
            return false;
        }
    }

    //FUNCION QUE ACTUALIZA LA IMG EN LA BD
    private function updateImgLogo($name, $date){
        $sql_dato= DB::connection('mysql')->select('SELECT id FROM tab_logo WHERE archivo=?', [$name]);

        $get_id='';
        foreach ($sql_dato as $key) {
            $get_id= $key->id;
        }

        $estado= "1";

        $sql_update= DB::connection('mysql')->table('tab_logo')
            ->where('id', $get_id)
            ->update([ 'archivo'=> $name, 'estado'=> $estado, 'updated_at'=> $date]);
        
        if($sql_update){
            return true;
        }else{
            return false;
        }
    }

    //FUNCION PARA DESCARGAR EL LOGO
    public function download_logo($id){
        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_logo WHERE id=?', [$id]);

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }

        $subpath = 'img_files/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_files/" . $archivo);
        $url = public_path("/storage/files-img/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('img_files')->exists($archivo))
        {
            //return Storage::disk('img_files')->download($url);
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA LOGO
    public function inactivar_logo(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_logo')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION PARA ELIMINAR DEFINITIVAMENTE EL ARCHIVO
    public function delete_logo(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();

        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_logo WHERE id=?', [$id]);
        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }

        $subpath = 'img_files/'.$archivo;
        $path = storage_path('app/'.$subpath);
        Storage::disk('img_files')->delete($archivo);

        $sql_delete= DB::connection('mysql')->table('tab_logo')
        ->where('id', '=', $id)
        ->delete();

        if($sql_delete){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function get_logos(){
        $estado= '1';
        //$logo= DB::connection('mysql')->table('tab_logo')->where('estado','=', $estado)->get();
        $logo= DB::connection('mysql')->table('tab_logo')->select('archivo')->where('estado','=', $estado)->get();
        return $logo;
    }
}
