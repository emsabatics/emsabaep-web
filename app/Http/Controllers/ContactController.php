<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class ContactController extends Controller
{
    //FUNCION QUE CARGA INFORMACION DE CONTACTOS
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $contactos= DB::connection('mysql')->table('tab_contactos')->get();
            return view('Administrador.Contactos.contacto', ['contactos'=> $contactos]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ DE REGISTRO DE CONTACTOS
    public function open_interface_registro(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            return view('Administrador.Contactos.registro_contacto');
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ DE REGISTRO DE UBICACIONES EN EL MAPA
    public function open_interface_location(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            return view('Administrador.Contactos.registro_location_map');
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ALMACENA LA INFORMACION DE CONTACTO
    public function store_registro(Request $r){
        $res= $r->getContent();
        $array = json_decode($res, true);
        $longcadena= sizeof($array);
        $date= now();
        $i=0;

        foreach ($array as $value) {
            if($value['tipo']=="geolocalizacion"){
                $word = "&";
                $pos = strpos($value['detalle'], $word);
                $latitud= substr($value['detalle'], 0, $pos); 
                $longitud= substr($value['detalle'], ($pos+1), strlen($value['detalle']));
                if($this->insertContact($value['tipo'], 'Oficina Matriz EMSABA EP', $latitud, $longitud, null, null, null, null, null, $date)){
                    $i++;
                }
            }else if($value['tipo']=="houratencion"){
                $hora= $value['hora'];
                $horac= $value['horac'];
                if(substr($hora, 0,2)<12){
                    //$hora= $hora.' AM';
                    $hora= $hora;
                }else{
                    //$hora= $hora.' PM';
                    $hora= $hora;
                }
                if(substr($horac, 0,2)<12){
                    //$horac= $horac.' AM';
                    $horac= $horac;
                }else{
                    //$horac= $horac.' PM';
                    $horac= $horac;
                }
                if($this->insertContact($value['tipo'], $value['detalle'], null, null, $hora, $horac, null, null, null, $date)){
                    $i++;
                }
            }else if($value['tipo']=="telefono"){
                $telefono= $value['telefono'];
                $telefono2= $value['telefono2'];
                if($telefono2==''){
                    $telefono2=null;
                }
                if($this->insertContact($value['tipo'], null, null, null, null, null, $telefono, $telefono2, null, $date)){
                    $i++;
                }
            }else{
                if($this->insertContact($value['tipo'], $value['detalle'], null, null, null, null, null, null, null, $date)){
                    $i++;
                }
            }
        }

        if($longcadena==$i){
            return response()->json(['resultado'=>true]);
        }else{
            return response()->json(['resultado'=>false]);
        }
    }

    private function insertContact($tipo, $detalle, $latitud, $longitud, $hora, $horac, $telefono, $telefono2, $detalle2, $date){
        $sql_insert = DB::connection('mysql')->insert('insert into tab_contactos (
            tipo_contacto, detalle, latitud, longitud, hora_a, hora_c, telefono, telefono_2, detalle_2, created_at
        ) values (?,?,?,?,?,?,?,?,?,?)', [$tipo, $detalle, $latitud, $longitud, $hora, $horac, $telefono, $telefono2, $detalle2, $date]);
        
        if($sql_insert){
            return true;
        }else{
            return false;
        }
    }

    //FUNCION QUE ALMACENA LA INFORMACION DE NUEVA UBICACION DE CONTACTO
    public function store_location_registro(Request $r){
        $res= $r->getContent();
        $array = json_decode($res, true);
        $longcadena= sizeof($array);
        $date= now();
        $i=0;

        foreach ($array as $value) {
            if($value['tipo']=="geolocalizacion"){
                $word = "&";
                $pos = strpos($value['coordenadas'], $word);
                $latitud= substr($value['coordenadas'], 0, $pos); 
                $longitud= substr($value['coordenadas'], ($pos+1), strlen($value['coordenadas']));
                if($this->insertContact($value['tipo'], $value['nombre'], $latitud, $longitud, null, null, null, null, $value['direccion'], $date)){
                    $i++;
                }
            }
        }

        if($longcadena==$i){
            return response()->json(['resultado'=>true]);
        }else{
            return response()->json(['resultado'=>false]);
        }
    }

    //FUNCION QUE OBTIENE LA INFORMACION DE CONTACTO POR ID
    public function get_contact_item($id){
        //$id= base64_decode($id);
        $resultado= DB::connection('mysql')->table('tab_contactos')->where('id', $id)->get();

        return $resultado;
    }

    public function open_interface_update_location($id, $tipo){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $id= base64_decode($id);
            if($tipo=='vextend'){
                $coordenadas= [];
                $resultado= DB::connection('mysql')->table('tab_contactos')->where('id', $id)->get();
                $getcoord= DB::connection('mysql')->table('tab_contactos')->select('latitud','longitud')->where('id', $id)->get();

                foreach($getcoord as $g){
                    array_push($coordenadas, $g->longitud);
                    array_push($coordenadas, $g->latitud);
                }

                return view('Administrador.Contactos.editar_location_map', ['resultado'=> $resultado, 'coordenadas'=> $coordenadas]);
            }
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTUALIZA LA INFORMACION DE CONTACTO
    public function actualizar_contacto_geo(Request $r){
        $id= $r->id;
        $latitud= $r->latitud;
        $longitud= $r->longitud;
        $detalle= $r->detalle;
        $detalle2= $r->detalle2;
        if($detalle2==''){
            $detalle2= null;
        }
        $date= now();

        $sql_update= DB::table('tab_contactos')
            ->where('id', $id)
            ->update(['latitud' => $latitud, 'longitud'=> $longitud, 'detalle'=> $detalle, 'detalle_2'=> $detalle2, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE ACTUALIZA LA INFORMACION DE CONTACTO
    public function actualizar_contacto(Request $r){
        $id= $r->id;
        $detalle= $r->detalle;
        $telefono2= $r->telefono2;
        $tipo= $r->tipo;
        $date= now();

        if($tipo=="numero"){
            if($telefono2==null || $telefono2==''){
                $detalle= $detalle.'&';
            }else{
                $detalle= $detalle.'&'.$telefono2;
            }
        }

        $sql_update= DB::table('tab_contactos')
            ->where('id', $id)
            ->update(['detalle' => $detalle, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE ACTUALIZA LA INFORMACION DE CONTACTO
    public function actualizar_contacto_hour(Request $r){
        $id= $r->id;
        $hora= $r->hora;
        $horac= $r->horac;
        $date= now();

        if(substr($hora, 0,2)<12){
            //$hora= $hora.' AM';
            $hora= $hora;
        }else{
            //$hora= $hora.' PM';
            $hora= $hora;
        }
     
     
        if(substr($horac, 0,2)<12){
            //$horac= $horac.' AM';
            $horac= $horac;
        }else{
            //$horac= $horac.' PM';
            $horac= $horac;
        }

        $sql_update= DB::table('tab_contactos')
            ->where('id', $id)
            ->update(['hora_a' => $hora, 'hora_c'=> $horac, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function index_settings_count(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $filecuenta= DB::connection('mysql')->table('tab_img_infor_cuenta')->get();
            return view('Administrador.InforCuentaView.inforcuentaview', ['filecuenta'=> $filecuenta]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function registro_files_cuenta(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            return view('Administrador.InforCuentaView.registro_inforcuentaview');
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE REALIZA EL INGRESO CORRESPONDIENTE DE LAS IMAGENES EN LA BD
    public function store_file_cuenta(Request $r){
        if ($r->hasFile('file')) {
            $files  = $r->file('file'); //obtengo el archivo
            $countfiles= count($r->file('file'));
            $date= now();
            $contar = 0;
            foreach($files as $file){
                $filename= $file->getClientOriginalName();
                $fileextension= $file->getClientOriginalExtension();
                if($fileextension== $this->validarImg($fileextension)){
                    $getTypeFileQuery= $this->getTypeFile($fileextension);
                    $storeimg= Storage::disk('img_files')->put($filename,  \File::get($file));
                    if($storeimg){
                        $sql_insert= DB::connection('mysql')->table('tab_img_infor_cuenta')->insertGetId(
                            ['archivo'=> $filename, 'tipo'=> $getTypeFileQuery, 'created_at'=> $date]
                        );
                        if($sql_insert){
                            $contar++;
                        }
                    }else{
                        return response()->json(["resultado"=>"nocopy"]);
                    }
                }else{
                    return response()->json(['resultado'=> 'noimagen']);
                }
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
        $validar_extension= array("png", "jpg", "jpeg", "svg", "gif", "mp4");
        if(in_array($extension, $validar_extension)){
            return true;
        }else{
            return false;
        }
    }

    private function getTypeFile($extension){
        $validar_extension= array("png", "jpg", "jpeg", "svg", "gif");
        $validar_extension_video= array("mp4");
        if(in_array($extension, $validar_extension)){
            return "imagen";
        }else if(in_array($extension, $validar_extension_video)){
            return "video";
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA BANNER
    public function inactivar_cuentafile(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_img_infor_cuenta')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION PARA DESCARGAR EL BANNER
    public function download_cuenta_file($id){
        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_img_infor_cuenta WHERE id=?', [$id]);

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }

        $subpath = 'img_files/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/files-img/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('img_files')->exists($archivo))
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
    public function delete_cuentafile(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();

        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_img_infor_cuenta WHERE id=?', [$id]);
        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }

        $subpath = 'img_files/'.$archivo;
        $path = storage_path('app/'.$subpath);
        Storage::disk('img_files')->delete($archivo);

        $sql_update= DB::table('tab_img_infor_cuenta')
            ->where('id', $id)
            ->delete();

        if($sql_update){ 
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }
}
