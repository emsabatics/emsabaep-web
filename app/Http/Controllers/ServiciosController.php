<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ServiciosController extends Controller
{
    //FUNCION QUE RETORNA LA VISTA PRINCIPAL DE SERVICIOS
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion') ){
            $servicios= DB::connection('mysql')->select('SELECT * FROM tab_servicios');
            return response()->view('Administrador.Servicios.servicios', ['servicios'=> $servicios]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE RETORNA LA VISTA PARA EL REGISTRO DE SERVICIO
    public function registrar_servicio(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion') ){
            return response()->view('Administrador.Servicios.registrar_servicio');
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ALMACEMA EL REGISTRO DEL SERVICIO
    public function store_service(Request $r){
        if ($r->hasFile('file') && $r->hasFile('fileIcon')) {
            $filesimg  = $r->file('file'); //obtengo el archivo imagen del servicio
            $filesicon  = $r->file('fileIcon'); //obtengo el archivo icono del servicio
            $tiposervicio= $r->tiposervicio;
            $titulo= $r->inputTitleService;
            $descpshort= $r->descripcioncorta;
            $descripcion= $r->descripcion;
            $enlace= $r->inputLinkService;
            $date= now();
            $fileextensionimg= '';
            $fileextensionicon= '';

            foreach($filesimg as $file){
                $contentfileimg= $file;
                $filenameimg= $file->getClientOriginalName();
                $fileextensionimg= $file->getClientOriginalExtension();
            }

            $newnameimg= $filenameimg;

            foreach($filesicon as $filei){
                $contentfileicon= $filei;
                $filenameicon= $filei->getClientOriginalName();
                $fileextensionicon= $filei->getClientOriginalExtension();
            }

            $newnameicon= $filenameicon;

            //echo $fileextensionimg.' - '.$this->validarFile($fileextensionimg);
            if($fileextensionimg== $this->validarFile($fileextensionimg) && $fileextensionicon== $this->validarFile($fileextensionicon)){
                $storeimg= Storage::disk('img_servicios')->put($newnameimg,  \File::get($contentfileimg));
                $storeicon= Storage::disk('img_servicios')->put($newnameicon,  \File::get($contentfileicon));
                if($storeimg && $storeicon){
                    if($tiposervicio=='interno'){
                        /*$sql_insert = DB::connection('mysql')->insert('insert into tab_servicios (
                            tipo, titulo, descripcion, imagen, icon, created_at
                        ) values (?,?,?,?,?,?)', [$tiposervicio, $titulo, $descripcion, $newnameimg, $newnameicon, $date]);*/

                        $sql_insert= DB::connection('mysql')->table('tab_servicios')->insertGetId(
                            ['tipo'=> $tiposervicio, 'titulo'=> $titulo, 'imagen'=> $newnameimg, 'icon'=> $newnameicon,
                                'created_at'=> $date]
                        );
                    }else if($tiposervicio=='externo'){
                        /*$sql_insert = DB::connection('mysql')->insert('insert into tab_servicios (
                            tipo, titulo, descripcion, enlace, imagen, icon, created_at
                        ) values (?,?,?,?,?,?,?)', [$tiposervicio, $titulo, $descripcion, $enlace, $newnameimg, $newnameicon, $date]);*/
                        $sql_insert= DB::connection('mysql')->table('tab_servicios')->insertGetId(
                            ['tipo'=> $tiposervicio, 'titulo'=> $titulo, 'enlace'=> $enlace, 'imagen'=> $newnameimg,
                                'icon'=> $newnameicon, 'created_at'=> $date]
                        );
                    }

                    $LAST_ID= $sql_insert;

                    if($sql_insert){
                        $sql_insert_descp = DB::connection('mysql')->insert('insert into tab_servicio_descripcion (
                            id_servicio, descripcion_corta, descripcion, created_at
                        ) values (?,?,?,?)', [$LAST_ID, $descpshort, $descripcion, $date]);
                        if($sql_insert_descp){
                            return response()->json(["resultado"=> true]);
                        }else{
                            return response()->json(['resultado'=> false]);
                        }
                    }else{
                        return response()->json(["resultado"=> false]);
                    }
                }else{
                    return response()->json(['resultado'=> false]);
                }
            }else{
                return response()->json(['resultado'=> 'nofile']);
            }
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE VALIDA SI ES UN PDF
    private function validarFile($extension){
        $validar_extension= array("png", "jpg", "jpeg", "svg", "gif");
        if(in_array($extension, $validar_extension)){
            return $extension;
        }else{
            return "0";
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA SERVICIO
    public function inactivar_servicio(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_servicios')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR SERVICIO
    public function edit_service($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $id= base64_decode($id);
            /*$servicio = DB::table('tab_servicios')
            ->where('tab_servicios.id','=', $id)
            ->get();*/
            $servicio = DB::connection('mysql')->table('tab_servicios')
            ->join('tab_servicio_descripcion as tsd', 'tsd.id_servicio', '=', 'tab_servicios.id')
            ->select('tab_servicios.*', 'tsd.descripcion_corta as descp_corta', 'tsd.descripcion')
            ->where('tab_servicios.id','=', $id)->get();
            //return $servicio;
            return response()->view('Administrador.Servicios.actualizar_servicio', ['servicio'=> $servicio]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTUALIZA EL REGISTRO DEL SERVICIO
    public function update_service(Request $r){
        $ids= $r->idservicio;
        $tiposervicio= $r->tiposervicio;
        $titulo= $r->inputTitleServiceE;
        $descpshort= $r->descripcioncorta;
        $descripcion= $r->descripcion;
        $enlace= $r->inputLinkServiceE;
        $date= now();

        if($tiposervicio=='interno'){
            /*$sql_update= DB::table('tab_servicios')
            ->where('id', $ids)
            ->update(['titulo'=> $titulo, 'descripcion'=> $descripcion, 'updated_at'=> $date]);*/
            $sql_update_ts= DB::connection('mysql')->table('tab_servicios')
            ->where('id', $ids)
            ->update(['tipo'=> $tiposervicio, 'titulo'=> $titulo, 'updated_at'=> $date]);
        }else if($tiposervicio=='externo'){
            $sql_update_ts=  DB::connection('mysql')->table('tab_servicios')
            ->where('id', $ids)
            ->update(['tipo'=> $tiposervicio, 'titulo'=> $titulo, 'enlace'=> $enlace, 'updated_at'=> $date]);
        }

        if($sql_update_ts){
            $sql_update_descp =  DB::connection('mysql')->table('tab_servicio_descripcion')
            ->where('id_servicio', $ids)
            ->update(['descripcion_corta'=> $descpshort, 'descripcion'=> $descripcion, 'updated_at'=> $date]);
            
            if($sql_update_descp){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
            //return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    //FUNCION PARA ACTUALIZAR IMAGEN DEL SERVICIO
    public function actualizar_servicio_img(Request $r){
        if ($r->hasFile('fileImgEdit')) {
            $files  = $r->file('fileImgEdit'); //obtengo el archivo
            $id= $r->input('idserviciotoimg');
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

                if($fileextension== $this->validarFile($fileextension)){
                    $oldnamefile= $this->name_file_doc($id, 'img');
                    if($oldnamefile!=$filename){
                        $storeimg= Storage::disk('img_servicios')->put($filename,  \File::get($file));
                    }else{
                        $storeimg= true;
                    }
                    
                    if($storeimg){
                        $sql_update= DB::connection('mysql')->table('tab_servicios')
                        ->where('id', $id)
                        ->update(['imagen'=> $filename, 'updated_at'=> $date]);

                        if($sql_update){
                            return response()->json(["resultado"=> true]);
                        }else{
                            return response()->json(["resultado"=> false]);
                        }
                    }else{
                        return response()->json(["resultado"=>"nocopy"]);
                    }
                }else{
                    return response()->json(['resultado'=> 'nofile']);
                }
            }
        }
    }

    //FUNCION PARA ACTUALIZAR ÍCONO DEL SERVICIO
    public function actualizar_servicio_icono(Request $r){
        if ($r->hasFile('fileIconEdit')) {
            $files  = $r->file('fileIconEdit'); //obtengo el archivo
            $id= $r->input('idserviciotoicon');
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

                if($fileextension== $this->validarFile($fileextension)){
                    $oldnamefile= $this->name_file_doc($id, 'icon');
                    if($oldnamefile!=$filename){
                        $storeimg= Storage::disk('img_servicios')->put($filename,  \File::get($file));
                    }else{
                        $storeimg= true;
                    }
                    if($storeimg){
                        $sql_update_icon= DB::connection('mysql')->table('tab_servicios')
                        ->where('id', $id)
                        ->update(['icon'=> $filename, 'updated_at'=> $date]);
                        
                        if($sql_update_icon){
                            return response()->json(["resultado"=> true]);
                        }else{
                            return response()->json(["resultado"=> false]);
                        }
                    }else{
                        return response()->json(["resultado"=>"nocopy"]);
                    }
                }else{
                    return response()->json(['resultado'=> 'nofile']);
                }
            }
        }
    }

    private function name_file_doc($id, $tipo){
        $resultado='';

        if($tipo=='img'){
            $sql= DB::connection('mysql')->select('SELECT imagen FROM tab_servicios WHERE id=?', [$id]);

            foreach($sql as $s){
                $resultado= $s->imagen;
            }
        }else if($tipo=='icon'){
            $sql= DB::connection('mysql')->select('SELECT icon FROM tab_servicios WHERE id=?', [$id]);

            foreach($sql as $s){
                $resultado= $s->icon;
            }
        }
        
        return $resultado;
    }

    //FUNCION PARA DESCARGAR LA IMAGEN DEL SERVICIO
    public function download_archivo_service($id, $option){
        $archivo='';
        if($option=='img'){
            $sql_dato= DB::connection('mysql')->select('SELECT imagen FROM tab_servicios WHERE id=?', [$id]);
            foreach ($sql_dato as $key) {
                $archivo= $key->imagen;
            }
        }else if($option=='icon'){
            $sql_dato= DB::connection('mysql')->select('SELECT icon FROM tab_servicios WHERE id=?', [$id]);
            foreach ($sql_dato as $key) {
                $archivo= $key->icon;
            }
        }

        $subpath = 'img_servicios/'.$archivo;
        $path = storage_path('app/'.$subpath);
        $url = public_path("/storage/servicios-img/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('img_servicios')->exists($archivo))
        {
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

    public function inactivar_archivo_estructura(Request $r){
        $id= $r->id;
        $imagen= null;

        $sql_update= DB::table('tab_servicios')
            ->where('id', $id)
            ->update(['imagen'=> $imagen]);
        
        if($sql_update){
            if (Storage::disk('img_servicios')->exists($imagen)) {
                Storage::disk('img_servicios')->delete($imagen);
            }
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    //FUNCION QUE ELIMINA EL REGISTO DE LA TABLA SUBSERVICIO INFORMATIVO
    public function eliminar_servicio(Request $request){
        $id= $request->input('id');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_servicios')
                ->where('id', $id)
                ->delete();

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }
}
