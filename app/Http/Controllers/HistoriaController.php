<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class HistoriaController extends Controller
{
    public function index()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $historia = DB::table('tab_historia_institucion')->where('estado','1')->get();
            //return response()->json(['historia'=> $historia, 'empty'=> $historia->isEmpty()]);
            if($historia->isEmpty()){
                return redirect()->to('/add-historia');
            }else{
                return response()->view('Administrador.Infor.historia', ['historia' => $historia]);
            }
            //$groupmision= $historia->groupBy('tipo');
            //return response()->view('Administrador.Infor.historia', ['historia' => $historia]);
            //return response()->view('Administrador.Infor.historia');
        }else{
            return redirect('/loginadmineep');
            //return redirect()->to('/loginadmineep');
        }
    }

    public function store_history(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            return response()->view('Administrador.Infor.registrar_historia');
        }else{
            return redirect('/loginadmineep');
            //return redirect()->to('/loginadmineep');
        }
    }

    public function registrar_historia_original(Request $request){
        /*//Storage::put("texto.txt","Hola");
        //$file->storeAS('', $filename, 'public');
        Storage::disk('img_historia')->put("texto.txt","Hola");
        return $this->index();*/

        $posicion= $request->input('posicion');
        $descripcion= $request->input('descripcion');
        $longitud= $request->input('longitud');
        $date= now();
        /*$request->validate([
            //'image.*' => 'mimes:doc,pdf,docx,zip,jpeg,png,jpg,gif,svg',
            'file.*' => 'jpeg,png,jpg'
        ]);*/

        //DB::table('users')->truncate();
        
        if ($request->hasFile('file')) {
            $files  = $request->file('file'); //obtengo el archivo
            $arrayDesc= explode("//", $descripcion);
            $lengarr= sizeof($arrayDesc);
            $contar=0;

            foreach($files as $file){
                // here is your file object
                $filename= $file->getClientOriginalName();
                $fileextension= $file->getClientOriginalExtension();
                $filerealpath= $file->getRealPath();
                //$filesize= $file->getSize();

                Storage::disk('img_historia')->put($filename,  \File::get($file)); 
            }

            //return "archivo guardado";

            if($this->validarImg($fileextension)){
                foreach ($arrayDesc as $key => $value) {
                    if($this->insertarTextoHistoria($value, $date)){
                      $contar++;
                    }
                }

                if($contar == $lengarr){
                    //$replaceName = str_replace(' ', '_', $filename);
                    $replaceName= $this->replaceCaracter($filename);
                    //return Storage::disk('img_historia')->putFile('img_historia'.'/'.$filename, file_get_contents($filerealpath));
                    return $files->store('/', 'img_historia');
                    /*$destination_path= 'images/historia';
                    $path= $file->move(public_path($destination_path), $filename);
                    if($path){
                        if($this->insertarImagenHistoria($filename, $posicion, $date)){
                            return response()->json(['resultado'=> true]);
                        }else{
                            return response()->json(['resultado'=> false]);
                        }
                    }else{
                        return response()->json(['resultado'=> 'nocopy']);
                    }*/
                }else{
                    return response()->json(['resultado'=> false]);
                }

            }else{
                return response()->json(['resultado'=> 'noimagen']);
            }

        }else{
            return response()->json(['file'=> false]);
        }
    }

    public function registrar_historia(Request $request){
        $posicion= $request->input('posicion');
        $descripcion= $request->input('descripcion');
        $longitud= $request->input('longitud');
        $date= now();

        if ($request->hasFile('file')) {
            $files  = $request->file('file'); //obtengo el archivo
            $arrayDesc= explode("//", $descripcion);
            $lengarr= sizeof($arrayDesc);
            $contar=0;

            foreach($files as $file){
                // here is your file object
                $filename= $file->getClientOriginalName();
                $fileextension= $file->getClientOriginalExtension();
                $filerealpath= $file->getRealPath();
                //$filesize= $file->getSize();
            }

            if($fileextension== $this->validarImg($fileextension)){
                $storehistoria= Storage::disk('img_historia')->put($filename,  \File::get($file));
                if($storehistoria){
                    $sql_insert = DB::connection('mysql')->insert('insert into tab_historia_institucion (
                        imagen,
                        posicion,
                        created_at
                    ) values (?,?,?)', [$filename, $posicion, $date]);
                    if($sql_insert){
                        $insert= true;
                    }else{
                        $insert= false;
                    }

                    if($insert==true){
                        foreach ($arrayDesc as $key => $value) {
                            if($this->insertarTextoHistoria($value, $date)){
                                $contar++;
                            }
                        }
                        if($contar == $lengarr){
                            return json_encode(["resultado"=> true]);
                        }else{
                            return json_encode(["resultado"=> false]);
                        }
                    }else{
                        return response()->json(["resultado"=> false]);
                    }
                }else{
                    return response()->json(["resultado"=> 'nocopy']);
                }
            }else{
                return response()->json(['resultado'=> 'noimagen']);
            }
        }
    }

    public function download_img($archivo){
        $public_path = public_path();
        //return $public_path;
        //$url = $public_path.'/storage/app/img_historia/'.$archivo;
        $url = public_path("/storage/app/img_historia/" . $archivo);
        //print($url);
        //return $url;
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('img_historia')->exists($archivo))
        {
            //return response()->download($url);
            //return Storage::disk($programacion)->download($request->get("url"));
            return Storage::disk('img_historia')->download($url);
        }else{
            return response()->json(['DATO: '=> 'no existe']);
        }
        //si no se encuentra lanzamos un error 404.
        //abort(404);
    }

    private function validarImg($extension){
        $validar_extension= array("png","jpg","jpeg");
        if(in_array($extension, $validar_extension)){
            return true;
        }else{
            return false;
        }
    }

    public function add_history(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $data= array();
            $sql_img= DB::connection('mysql')->select('SELECT * FROM tab_historia_institucion WHERE imagen!=?', ['']);
            /*foreach($sql_img as $k){
                $data[]= array('tipo'=>'imagen', 'id'=> $k['id'], 'descripcion'=> $k['descripcion'], 'imagen'=> $k['imagen'], 'posicion'=> $k['posicion'], 'estado'=> $k['estado']);
            }*/

            $sql_texto= DB::connection('mysql')->select('SELECT * FROM tab_historia_institucion WHERE descripcion!=?', ['']);
            /*foreach($sql_texto as $k){
                $data[]= array('tipo'=>'texto', 'id'=> $k['id'], 'descripcion'=> $k['descripcion'], 'imagen'=> $k['imagen'], 'posicion'=> $k['posicion'], 'estado'=> $k['estado']);
            }*/

            return response()->view('Administrador.Infor.actualizar_historia', ['dataImg'=> $sql_img, 'dataTexto'=> $sql_texto]);
        }else{
            return redirect('/loginadmineep');
            //return redirect()->to('/loginadmineep');
        }
    }

    private function insertarTextoHistoria($texto, $date){
        $sql_insert = DB::connection('mysql')->insert('insert into tab_historia_institucion (
            descripcion,
            created_at
        ) values (?,?)', [$texto, $date]);

        if($sql_insert){
            return true;
        }else{
            return false;
        }
    }

    private function insertarImagenHistoria($texto, $posicion, $date){
        $sql_insert = DB::connection('mysql')->insert('insert into tab_historia_institucion (
            imagen,
            posicion,
            created_at
        ) values (?,?,?)', [$texto, $posicion, $date]);

        if($sql_insert){
            return true;
        }else{
            return false;
        }
    }

    private function replaceCaracter($title) {
        $title = preg_replace('![\s]+!u', '-', strtolower($title));
        $title = preg_replace('![^-\pL\pN\s]+!u', '', $title);
        $title = preg_replace('![-\s]+!u', '-', $title);
        
        return trim($title, '-');
    }

    public function activar_imghistoria_delete(Request $r){
        $id= $r->id;
        $estado= $r->estado;
        $date= now();

        $sql_update= DB::table('tab_historia_institucion')
        ->where('id', $id)
        ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function actualizar_historia(Request $request){
        $posicion= $request->input('posicion');
        $descripcion= $request->input('descripcion');
        $longitud= $request->input('longitud');
        $tipo= $request->input('tipo');
        $imagendb= $request->input('imagenbd');
        $date= now();
        $contar=0;
        $arrayDesc= explode("//", $descripcion);
        $lengarr= sizeof($arrayDesc);

        $this->limpiar_tabla();

        if($tipo=="texto"){
            $arrayimagen= explode(",", $imagendb);
            if($this->insertarImagenHistoria($arrayimagen[0], $posicion, $date)){
                $insert= true;
            }else{
                $insert= false;
            }

            foreach ($arrayDesc as $key => $value) {
                if($this->insertarTextoHistoria($value, $date)){
                  $contar++;
                }
            }

            if($insert==true){
                if($contar == $lengarr){
                  echo json_encode(["resultado"=> true]);
                }else{
                  echo json_encode(["resultado"=> false]);
                }
              }else{
                echo json_encode(["resultado"=> false]);
            }
        }else if($tipo=="imagen"){
            if ($request->hasFile('file')) {
                $files  = $request->file('file'); //obtengo el archivo
                $contar=0;

                foreach($files as $file){
                    // here is your file object
                    $filename= $file->getClientOriginalName();
                    $fileextension= $file->getClientOriginalExtension();

                    if($fileextension== $this->validarImg($fileextension)){
                        $storeimg= Storage::disk('img_historia')->put($filename,  \File::get($file)); 
                        if($storeimg){
                            $sql_insert = DB::connection('mysql')->insert('insert into tab_historia_institucion (
                                imagen,
                                posicion,
                                created_at
                            ) values (?,?,?)', [$filename, $posicion, $date]);
                            if($sql_insert){
                                $insert= true;
                            }else{
                                $insert= false;
                            }

                            if($insert==true){
                                foreach ($arrayDesc as $key => $value) {
                                    if($this->insertarTextoHistoria($value, $date)){
                                        $contar++;
                                    }
                                }
                                if($contar == $lengarr){
                                    echo json_encode(["resultado"=> true]);
                                }else{
                                    echo json_encode(["resultado"=> false]);
                                }
                            }else{
                                return response()->json(["resultado"=> false]);
                            }
                        }else{
                            return response()->json(["resultado"=>"nocopy"]);
                        }
                    }else{
                        return response()->json(['resultado'=> 'noimagen']);
                    }
                }

            }else{
                return response()->json(['file'=> false]);
            }
        }
    }

    private function limpiar_tabla(){
        $sql= DB::table('tab_historia_institucion')->truncate();
        if($sql){
            return true;
        }else{
            return false;
        }
    }
}
