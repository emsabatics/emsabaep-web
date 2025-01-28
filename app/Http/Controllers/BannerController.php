<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    //FUNCION QUE RETORNA LA VISTA PRINCIPAL DE SOCIAL MEDIA
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $banner= DB::connection('mysql')->table('tab_img_banner')->get();
            $wordCount = $banner->count();
            return view('Administrador.Banner.banner', ['banner'=> $banner]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function registro_banner(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            return view('Administrador.Banner.registro_banner');
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE REALIZA EL INGRESO CORRESPONDIENTE DE LAS IMAGENES EN LA BD
    public function store_banner(Request $r){
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
                    $order= $this->lastOrderImgBanner();
                    $storeimg= Storage::disk('img_banner')->put($filename,  \File::get($file));
                    if($storeimg){
                        $numr= $this->existImgBanner($filename);
                        if($numr==0){
                            if($this->insertarImgBanner($filename, ($order+1), $date)){
                                $contar++;
                            }
                        }else{
                            if($this->updateImgBanner($filename, ($order+1), $date)){
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
            return true;
        }else{
            return false;
        }
    }

    //FUNCION QUE VALIDA SI EXISTE LA IMG EN LA BD
    private function existImgBanner($archivo){
        $sql_dato= DB::connection('mysql')->select('SELECT imagen FROM tab_img_banner WHERE imagen=?', [$archivo]);

        $get_imagen='';
        foreach ($sql_dato as $key) {
            $get_imagen= $key->imagen;
        }

        if($get_imagen==''){
            return 0;
        }else {
            return 1;
        }
    }

    //FUNCION QUE VALIDA EL ULTIMO ORDEN DE BANNER EN LA BD
    private function lastOrderImgBanner(){
        $orders = DB::table('tab_img_banner')->where('observacion','no_eliminado')->max('orden');
        return $orders;
    }

    //FUNCION QUE INSERTA LA IMG EN LA BD
    private function insertarImgBanner($name, $pos, $date){
        $sql_insert= DB::connection('mysql')->table('tab_img_banner')->insertGetId(
            ['imagen'=> $name, 'orden'=> $pos, 'created_at'=> $date]
        );
        if($sql_insert){
            return true;
        }else{
            return false;
        }
    }

    //FUNCION QUE ACTUALIZA LA IMG EN LA BD
    private function updateImgBanner($name, $pos, $date){
        $sql_dato= DB::connection('mysql')->select('SELECT id FROM tab_img_banner WHERE imagen=?', [$name]);

        $get_id='';
        foreach ($sql_dato as $key) {
            $get_id= $key->id;
        }

        $estado= "1";
        $observacion="no_eliminado";

        $sql_update= DB::table('tab_img_banner')
            ->where('id', $get_id)
            ->update([ 'orden'=> $pos, 'observacion'=> $observacion, 'estado'=> $estado, 'updated_at'=> $date]);
        
        if($sql_update){
            return true;
        }else{
            return false;
        }
    }

    //FUNCION QUE ACTUALIZA EL ORDEN DE VISUALIZACION DE LAS IMAGENES
    public function registro_orden_banner(Request $r){
        $res= $r->getContent();
        $array = json_decode($res, true);
        $longcadena= sizeof($array);
        $date= now();
        $i=0;

        foreach ($array as $value) {
            if($this->updateOrderBanner($value['id'],  $value['orden'], $date)){
                $i++;
            }
        }

        if($longcadena==$i){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    private function updateOrderBanner($id, $orden, $date){
        $sql_update= DB::table('tab_img_banner')
            ->where('id', $id)
            ->update(['orden'=> $orden, 'updated_at'=> $date]);
        
        if($sql_update){
            return true;
        }else{
            return false;
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA BANNER
    public function inactivar_banner(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_img_banner')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION PARA DESCARGAR EL BANNER
    public function download_banner($id){
        $sql_dato= DB::connection('mysql')->select('SELECT imagen FROM tab_img_banner WHERE id=?', [$id]);

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->imagen;
        }

        $subpath = 'img_banner/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/banner-img/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('img_banner')->exists($archivo))
        {
            //return Storage::disk('img_banner')->download($url);
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

    //FUNCION PARA ELIMINAR DEFINITIVAMENTE EL ARCHIVO
    public function delete_banner(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $observacion= "eliminado";
        $date= now();

        $sql_update= DB::table('tab_img_banner')
                ->where('id', $id)
                ->update(['estado' => $estado, 'observacion'=> $observacion, 'updated_at'=> $date]);

        if($sql_update){
            $sql_dato= DB::connection('mysql')->select('SELECT imagen FROM tab_img_banner WHERE id=?', [$id]);
            $archivo='';
            foreach ($sql_dato as $key) {
                $archivo= $key->imagen;
            }

            $subpath = 'img_banner/'.$archivo;
            $path = storage_path('app/'.$subpath);
            Storage::disk('img_banner')->delete($archivo);
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }
}
