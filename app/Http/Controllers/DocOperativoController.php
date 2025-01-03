<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DocOperativoController extends Controller
{
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            //$operativo = DB::table('tab_doc_operativo')->get();
            $operativo= DB::connection('mysql')->select('SELECT df.*, y.nombre as anio FROM tab_doc_operativo df, tab_anio y WHERE df.id_anio=y.id ORDER BY y.nombre DESC');
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.operativo.operativo', ['operativo'=> $operativo, 'mes'=> $mes]);
        }else{
            return redirect('/login');
        }
    }

    public function doc_operativo_register(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $dateyear= DB::connection('mysql')->table('tab_anio')->orderByDesc('nombre')->get();
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.operativo.registrar_docoperativo', ['anio'=> $dateyear, 'mes'=> $mes]);
        }else{
            return redirect('/login');
        }
    }

    public function store_doc_operativo(Request $r){
        if ($r->hasFile('file') ) {
            $filesdocoper  = $r->file('file'); //obtengo el archivo LOTAIP

            $date= now();
            $anio = $r->anio;
            $mes= $r->mes;
            $aliasfiledf= $r->inputAliasFileDocOper;
            $nombredocoper= $r->inputNameDocOper;
            /*$typefile= $r->typefile;
            $lengfile= $r->lengfile;*/

            $nmes= null;
            if($mes=='0'){
                $nmes= null;
            }else{
                $nmes= $mes;
            }

            foreach($filesdocoper as $file){
                $contentfiledf= $file;
                $filenamedf= $file->getClientOriginalName();
                $fileextensiondf= $file->getClientOriginalExtension();
            }

            $newnamedf= $aliasfiledf.".".$fileextensiondf;

            if($fileextensiondf== $this->validarFile($fileextensiondf)){
                $storepoa= Storage::disk('doc_operativo')->put($newnamedf,  \File::get($contentfiledf));
                if($storepoa){
                    $sql_insert = DB::connection('mysql')->insert('insert into tab_doc_operativo (
                        id_anio, id_mes, titulo, archivo, created_at
                    ) values (?,?,?,?,?)', [$anio, $nmes, $nombredocoper, $newnamedf, $date]);
    
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
        }else{
            return response()->json(['resultado'=> false]);
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

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC OPERATIVO
    public function view_doc_operativo($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $id= base64_decode($id);
            $filedocoper= DB::connection('mysql')
            ->select('SELECT titulo, archivo FROM tab_doc_operativo WHERE id=?', [$id]);
            return response()->view('Administrador.Documentos.operativo.viewdocoperativo', ['filedocoper'=> $filedocoper]);
        }else{
            return redirect('/login');
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA DOC OPERATIVO
    public function inactivar_doc_operativo(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_doc_operativo')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION PARA DESCARGAR DOC OPERATIVO
    public function download_doc_operativo($id){
        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_operativo WHERE id=?', [$id]);

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }
        
        $subpath = 'documentos/doc_operativo/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/doc-operativo/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('doc_operativo')->exists($archivo))
        {
            //return Storage::disk('img_banner')->download($url);
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR DOC OPERATIVO
    public function edit_doc_operativo($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $id= base64_decode($id);
            $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','asc')->get();
            $filedocoper = DB::table('tab_doc_operativo')
            ->join('tab_anio', 'tab_doc_operativo.id_anio', '=', 'tab_anio.id')
            ->select('tab_doc_operativo.*', 'tab_anio.nombre as anio')
            ->where('tab_doc_operativo.id','=', $id)
            ->get();
            //return $filedocoper;
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.operativo.editar_docoperativo', ['filedocoper'=> $filedocoper, 'anio'=> $anio, 'mes'=> $mes]);
        }else{
            return redirect('/login');
        }
    }

    //FUNCION QUE ACTUALIZA DOCUMENTACION OPERATIVA EN LA BASE DE DATOS
    public function update_doc_operativo(Request $r){
        $date= now();
        $iddp= $r->iddocoperativo;
        $tituloDocOperativo= $r->inputEDocTitle;
        $aliasfiledocoper= $r->inputEAliasFile;
        $isDocOperativo= $r->isDocOperativo;

        if($isDocOperativo=="false"){
            if ($r->hasFile('fileEdit')) {
                $filesdocoper  = $r->file('fileEdit'); //obtengo el archivo RENDICIONC
                foreach($filesdocoper as $file){
                    $contentfiledocoper= $file;
                    $filenamedocoper= $file->getClientOriginalName();
                    $fileextensiondocoper= $file->getClientOriginalExtension();
                }
                $newnamedocoper= $aliasfiledocoper.".".$fileextensiondocoper;

                if(Storage::disk('doc_operativo')->exists($newnamedocoper)){
                    Storage::disk('doc_operativo')->delete($newnamedocoper);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensiondocoper== $this->validarFile($fileextensiondocoper)){
                    $storedocfin= Storage::disk('doc_operativo')->put($newnamedocoper,  \File::get($contentfiledocoper));

                    if($storedocfin){
                        $sql_update= DB::table('tab_doc_operativo')
                            ->where('id',$iddp)
                            ->update(['titulo'=> $tituloDocOperativo, 'archivo'=> $newnamedocoper, 'updated_at'=> $date]);
    
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
            $oldnamefile= $this->name_file_doc($iddp);
            $newnamedocopt= $aliasfiledocoper.".pdf";
            if($oldnamefile!=$newnamedocopt){
                Storage::disk('doc_operativo')->move($oldnamefile, $newnamedocopt);
            }
            /*if(Storage::disk('doc_operativo')->exists($oldnamefile)){
                Storage::disk('doc_operativo')->move($oldnamefile, $newnamedocopt);
            }*/

            $sql_update= DB::table('tab_doc_operativo')
                ->where('id',$iddp)
                ->update(['titulo'=> $tituloDocOperativo, 'archivo'=> $newnamedocopt, 'updated_at'=> $date]);
    
            if($sql_update){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
    }

    private function name_file_doc($id){
        $resultado='';

        $sql= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_operativo WHERE id=?', [$id]);

        foreach($sql as $s){
            $resultado= $s->archivo;
        }

        return $resultado;
    }

    //FUNCION PARA ELIMINAR DEFINITIVAMENTE EL ARCHIVO
    public function delete_doc_operativo(Request $request){
        $id= $request->input('id');

        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_operativo WHERE id=?', [$id]);
        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }

        $subpath = 'documentos/doc_operativo/'.$archivo;
        $path = storage_path('app/'.$subpath);
        Storage::disk('doc_operativo')->delete($archivo);

        $deleted = DB::table('tab_doc_operativo')->where('id', '=', $id)->delete();

        if($deleted){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }
}
