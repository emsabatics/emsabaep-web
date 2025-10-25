<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use File;
use App\ContadorHelper;

class LeyTransparenciaController extends Controller
{
    public function index()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $transparencia = DB::connection('mysql')->table('tab_ley_transparencia')->get();
            /*
            $transparencia = DB::table('tab_ley_transparencia')->where('estado','1')->get();
            if($transparencia->isEmpty()){
                return redirect()->to('/add-ley-transparencia');
            }else{
                return response()->view('Administrador.Documentos.transparencia.transparencia', ['transparencia' => $transparencia]);
            }*/
            return response()->view('Administrador.Documentos.transparencia.transparencia', ['transparencia' => $transparencia]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function add_ley_transparencia(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            return response()->view('Administrador.Documentos.transparencia.registrar_transparencia');
        }else{
            return redirect('/loginadmineep');
            //return redirect()->to('/loginadmineep');
        }
    }

    public function registrar_ley_transparencia(Request $r){
        if ($r->hasFile('file') ) {
            $filesr  = $r->file('file'); //obtengo el archivo POA

            $date= now();
            $name= $r->inputNameTransparencia;
            $aliasfile= $r->inputAliasFileTransparencia;

            if($name==$this->getDocument($name)){
                return response()->json(['resultado'=> 'existe']);
            }else{
                //NO HAY INFORMACIÓN
                foreach($filesr as $file){
                    $contentfiler= $file;
                    $filenamer= $file->getClientOriginalName();
                    $fileextensionr= $file->getClientOriginalExtension();
                }

                $newnamer= $aliasfile.".".$fileextensionr;

                $subpath = 'documentos/reglamentos';
                $path = storage_path('app/'.$subpath);
                if(!File::isDirectory($path)){
                    File::makeDirectory($path, 0777, true, true);
                }

                if($fileextensionr== $this->validarFile($fileextensionr)){
                    $storereglamento= Storage::disk('doc_reglamentos')->put($newnamer,  \File::get($contentfiler));
                    if($storereglamento){
                        $sql_insert = DB::connection('mysql')->insert('insert into tab_ley_transparencia (
                           nombre_archivo, archivo, created_at
                        ) values (?,?,?)', [$name, $newnamer, $date]);
        
                        if($sql_insert){
                            return response()->json(["resultado"=> true]);
                        }else{
                            return response()->json(["resultado"=> false]);
                        }
                    }else{
                        return response()->json(["resultado"=> false]);
                    }
                }else{
                    return response()->json(['resultado'=> 'nofile']);
                }

            }
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    private function getDocument($param){
        $reglamento = DB::table('tab_ley_transparencia')
                ->where('nombre_archivo', 'like', '%'.$param.'%')
                ->get();
        
        $resultado='';
        foreach($reglamento as $r){
            $resultado= $r->nombre_archivo;
        }

        return $resultado;
    }

    //FUNCION QUE VALIDA SI ES UN PDF
    private function validarFile($extension){
        $validar_extension= array("pdf");
        if(in_array($extension, $validar_extension)){
            return true;
        }else{
            return false;
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC REGLAMENTO
    public function view_ley($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $filer= DB::connection('mysql')->table('tab_ley_transparencia')
            ->where('id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.transparencia.viewtransparencia', ['filer'=> $filer]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA LEY TRANSPARENCIA
    public function inactivar_ley(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_ley_transparencia')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION PARA DESCARGAR LA LEY DE TRANSPARENCIA
    public function download_ley($id){
        //$id= base64_decode($id);
        $id = desencriptarNumero($id);

        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_ley_transparencia WHERE id=?', [$id]);

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }

        $subpath = 'documentos/reglamentos/'.$archivo;
        $path = storage_path('app/'.$subpath);

        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('doc_reglamentos')->exists($archivo))
        {
            return response()->download($path);
        }else{
            abort(404);
        }
        
    }

    public function ley_increment(Request $r){
        $id = $r->input('idfile');
        $id = desencriptarNumero($id);
        //Incrementar contador de descargas (llamada limpia y segura)
        ContadorHelper::incrementarDescarga('tab_ley_transparencia', $id);
        //return response()->json(['resultado'=>true]);
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR EL REGLAMENTO
    public function edit_ley($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $transparencia= DB::connection('mysql')->table('tab_ley_transparencia')
            ->where('id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.transparencia.editar_transparencia', ['transparencia'=> $transparencia]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTUALIZA EL REGLAMENTO EN LA BASE DE DATOS
    public function update_ley(Request $r){
        $date= now();
        $id= $r->idfilet;
        $nombre= $r->inputENameTransparencia;
        $aliasfileley= $r->inputAliasFileTranspE;
        $isley= $r->isley;

        if($isley=="false"){
            if ($r->hasFile('fileEdit')) {
                $filesley  = $r->file('fileEdit'); //obtengo el archivo ley
                foreach($filesley as $file){
                    $contentfileley= $file;
                    $filenameley= $file->getClientOriginalName();
                    $fileextensionley= $file->getClientOriginalExtension();
                }
                $newnameley= $aliasfileley.".".$fileextensionley;
                if($fileextensionley== $this->validarFile($fileextensionley)){
                    $storeley= Storage::disk('doc_reglamentos')->put($newnameley,  \File::get($contentfileley));

                    if($storeley){
                        $sql_update= DB::table('tab_ley_transparencia')
                            ->where('id', $id)
                            ->update(['nombre_archivo'=> $nombre, 'archivo'=> $newnameley,  
                                'updated_at'=> $date]);
    
                        if($sql_update){
                            return response()->json(["resultado"=> true]);
                        }else{
                            return response()->json(["resultado"=> false]);
                        }
                    }else{
                        return response()->json(["resultado"=> 'nocopy']);
                    }
                }else{
                    return response()->json(['resultado'=> 'nofile']);
                }
            }
        }else{
            $namefile= $this->getNameFileLey($id);
            $newnamefile= $aliasfileley.".pdf";

            if($namefile==$newnamefile){
                //return response()->json(["resultado"=> 'nocopy']);
                $sql_update= DB::table('tab_ley_transparencia')
                    ->where('id', $id)
                    ->update(['nombre_archivo'=> $nombre, 'archivo'=> $newnamefile,  
                            'updated_at'=> $date]);
            
                if($sql_update){
                    return response()->json(["resultado"=> true]);
                }else{
                    return response()->json(["resultado"=> false]);
                }
            }else{
                $storefile= Storage::disk('doc_reglamentos')->move($namefile,  $newnamefile);
                if($storefile){
                    $sql_update= DB::table('tab_ley_transparencia')
                        ->where('id', $id)
                        ->update(['nombre_archivo'=> $nombre, 'archivo'=> $newnamefile,  
                            'updated_at'=> $date]);
            
                    if($sql_update){
                        return response()->json(["resultado"=> true]);
                    }else{
                        return response()->json(["resultado"=> false]);
                    }
                }else{
                    return response()->json(["resultado"=> 'nocopy']);
                }
            }
        }
    }

    /************************************************************************************************************ */
    public function no_registrar_ley_transparencia(Request $request){
        $descripcion= $request->input('descripcion');
        $longitud= $request->input('longitud');
        $date= now();

        $arrayDesc= explode("//", $descripcion);
        $lengarr= sizeof($arrayDesc);
        $contar=0;

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
    }

    private function insertarTextoHistoria($texto, $date){
        $sql_insert = DB::connection('mysql')->insert('insert into tab_ley_transparencia (
            descripcion,
            created_at
        ) values (?,?)', [$texto, $date]);

        if($sql_insert){
            return true;
        }else{
            return false;
        }
    }

    public function update_ley_transparencia(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $data= array();
            $sql_texto= DB::connection('mysql')->select('SELECT * FROM tab_ley_transparencia WHERE descripcion!=?', ['']);

            return response()->view('Administrador.Documentos.transparencia.actualizar_transparencia', ['dataTexto'=> $sql_texto]);
        }else{
            return redirect('/loginadmineep');
            //return redirect()->to('/loginadmineep');
        }
    }

    public function store_up_ley_transparencia(Request $request){
        $descripcion= $request->input('descripcion');
        $longitud= $request->input('longitud');
        $date= now();
        $contar=0;
        $arrayDesc= explode("//", $descripcion);
        $lengarr= sizeof($arrayDesc);

        $this->limpiar_tabla();

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
    }
    
    private function limpiar_tabla(){
        $sql= DB::table('tab_ley_transparencia')->truncate();
        if($sql){
            return true;
        }else{
            return false;
        }
    }
}
