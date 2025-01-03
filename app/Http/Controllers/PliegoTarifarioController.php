<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use File;

class PliegoTarifarioController extends Controller
{
    //INDEX PÁGINA PRINCIPAL PLIEGO TARIFARIO
    public function index()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $pliego = DB::table('tab_pliego_tarifario')->get();
            return response()->view('Administrador.Documentos.pliego.pliego', ['pliego'=> $pliego]);
        }else{
            return redirect('/login');
        }
    }

    //FUNCION ABRE INTERFAZ PARA REGISTRAR EL PLIEGO
    public function pliego_register(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            return response()->view('Administrador.Documentos.pliego.registrar_pliego');
        }else{
            return redirect('/login');
        }
    }

    //FUNCION QUE ALMACENA EL PLIEGO EN LA BASE DE DATOS
    public function store_pliego(Request $r){
        if ($r->hasFile('file') ) {
            $filespliego  = $r->file('file'); //obtengo el archivo PLIEGO

            $date= now();
            $descripcion= $r->inputObsr;
            $aliasfilepliego= $r->inputAliasFile;
            $tipofile= $r->tipo_file;

            foreach($filespliego as $file){
                $contentfilepliego= $file;
                $filenamepliego= $file->getClientOriginalName();
                $fileextensionpliego= $file->getClientOriginalExtension();
            }

            $newnamepliego= $aliasfilepliego.".".$fileextensionpliego;

            if($tipofile=="pdf"){
                if($fileextensionpliego== $this->validarFile($fileextensionpliego)){
                    $storepliego= Storage::disk('doc_pliego_tarifario')->put($newnamepliego,  \File::get($contentfilepliego));
                    if($storepliego){
                        $sql_insert = DB::connection('mysql')->insert('insert into tab_pliego_tarifario (
                           archivo, tipo_file, extension_file, observacion, created_at
                        ) values (?,?,?,?,?)', [$newnamepliego, $tipofile, $fileextensionpliego, $descripcion, $date]);
        
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
            }else if($tipofile=="image"){
                if($fileextensionpliego== $this->validarImg($fileextensionpliego)){
                    $storepliego= Storage::disk('doc_pliego_tarifario')->put($newnamepliego,  \File::get($contentfilepliego));
                    if($storepliego){
                        $sql_insert = DB::connection('mysql')->insert('insert into tab_pliego_tarifario (
                            archivo, tipo_file, extension_file, observacion, created_at
                         ) values (?,?,?,?,?)', [$newnamepliego, $tipofile, $fileextensionpliego, $descripcion, $date]);
        
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

    //FUNCION QUE VALIDA SI ES UNA IMAGEN
    private function validarImg($extension){
        $validar_extension= array("png","jpg","jpeg");
        if(in_array($extension, $validar_extension)){
            return true;
        }else{
            return false;
        }
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

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC
    public function view_pliego($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $filept= DB::connection('mysql')->table('tab_pliego_tarifario')
            ->where('id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.pliego.viewpliego', ['filept'=> $filept]);
        }else{
            return redirect('/login');
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA PLIEGO
    public function inactivar_pliego(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_pliego_tarifario')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR EL PLIEGO
    public function edit_pliego($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $filepliego= DB::connection('mysql')->table('tab_pliego_tarifario')
            ->where('id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.pliego.editar_pliego', ['filepliego'=> $filepliego]);
        }else{
            return redirect('/login');
        }
    }

    //FUNCION QUE ALMACENA EL PLIEGO EN LA BASE DE DATOS
    public function update_pliego(Request $r){
        $date= now();
        $id= $r->idpliego;
        $descripcion= $r->inputEObsr;
        $aliasfilepliego= $r->inputAliasFileE;
        $interface_tipofile= $r->tipofpliego;
        $tipofile= $r->tipo_file;
        $ispliego= $r->ispliego;

        if($ispliego=="false"){
            if ($r->hasFile('fileEdit') ) {
                $filespliego  = $r->file('fileEdit'); //obtengo el archivo PLIEGO
                foreach($filespliego as $file){
                    $contentfilepliego= $file;
                    $filenamepliego= $file->getClientOriginalName();
                    $fileextensionpliego= $file->getClientOriginalExtension();
                }
    
                $newnamepliego= $aliasfilepliego.".".$fileextensionpliego;

                if(Storage::disk('doc_pliego_tarifario')->exists($newnamepliego)){
                    Storage::disk('doc_pliego_tarifario')->delete($newnamepliego);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }
    
                if($tipofile=="pdf"){
                    if($fileextensionpliego== $this->validarFile($fileextensionpliego)){
                        $storepliego= Storage::disk('doc_pliego_tarifario')->put($newnamepliego,  \File::get($contentfilepliego));
                        if($storepliego){
                            $sql_update= DB::table('tab_pliego_tarifario')
                                ->where('id', $id)
                                ->update(['observacion'=> $descripcion, 'tipo_file'=> $tipofile, 'extension_file'=> $fileextensionpliego, 'archivo'=> $newnamepliego,  
                                    'updated_at'=> $date]);
            
                            if($sql_update){
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
                }else if($tipofile=="image"){
                    if($fileextensionpliego== $this->validarImg($fileextensionpliego)){
                        $storepliego= Storage::disk('doc_pliego_tarifario')->put($newnamepliego,  \File::get($contentfilepliego));
                        if($storepliego){
                            $sql_update= DB::table('tab_pliego_tarifario')
                                ->where('id', $id)
                                ->update(['observacion'=> $descripcion, 'tipo_file'=> $tipofile, 'extension_file'=> $fileextensionpliego, 'archivo'=> $newnamepliego,  
                                    'updated_at'=> $date]);
            
                            if($sql_update){
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
        }else{
            $namefile= $this->getNameFilePliego($id);
            $extension= $this->getExtFilePliego($id);

            if($interface_tipofile=="pdf"){
                $newnamefile= $aliasfilepliego.".".$extension;
            }else if($interface_tipofile=="image"){
                $newnamefile= $aliasfilepliego.".".$extension;
            }
            

            if($namefile==$newnamefile){
                //return response()->json(["resultado"=> 'nocopy']);
                $sql_update= DB::table('tab_pliego_tarifario')
                    ->where('id', $id)
                    ->update(['observacion'=> $descripcion,  'updated_at'=> $date]);
            
                if($sql_update){
                    return response()->json(["resultado"=> true]);
                }else{
                    return response()->json(["resultado"=> false]);
                }
            }else{
                $storefile= Storage::disk('doc_pliego_tarifario')->move($namefile,  $newnamefile);
                if($storefile){
                    $sql_update= DB::table('tab_pliego_tarifario')
                        ->where('id', $id)
                        ->update(['observacion'=> $descripcion, 'tipo_file'=> $interface_tipofile, 'extension_file'=> $extension, 'archivo'=> $newnamefile,  
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

    private function getNameFilePliego($id){
        $sql= DB::connection('mysql')->select('SELECT archivo FROM tab_pliego_tarifario WHERE id=?', [$id]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->archivo;
        }

        return $resultado;
    }

    private function getExtFilePliego($id){
        $sql= DB::connection('mysql')->select('SELECT extension_file FROM tab_pliego_tarifario WHERE id=?', [$id]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->extension_file;
        }

        return $resultado;
    }

    //FUNCION PARA DESCARGAR EL PLIEGO
    public function download_pliego($id){
        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_pliego_tarifario WHERE id=?', [$id]);

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }

        $subpath = 'documentos/pliego_tarifario/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/doc-pliego-tarifario/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('doc_pliego_tarifario')->exists($archivo))
        {
            //return Storage::disk('img_banner')->download($url);
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }
}
