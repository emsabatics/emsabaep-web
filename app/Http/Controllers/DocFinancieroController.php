<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DocFinancieroController extends Controller
{
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            //$financiero = DB::table('tab_doc_financiero')->get();
            $financiero= DB::connection('mysql')->select('SELECT df.*, y.nombre as anio FROM tab_doc_financiero df, tab_anio y WHERE df.id_anio=y.id ORDER BY y.nombre DESC');
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.financiero.financiero', ['financiero'=> $financiero, 'mes'=> $mes]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function doc_financiero_register(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $dateyear= DB::connection('mysql')->table('tab_anio')->orderByDesc('nombre')->get();
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.financiero.registrar_docfinanciero', ['anio'=> $dateyear, 'mes'=> $mes]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function store_doc_financiero(Request $r){
        if ($r->hasFile('file') ) {
            $filesdocfin  = $r->file('file'); //obtengo el archivo LOTAIP

            $date= now();
            $anio = $r->anio;
            $mes= $r->mes;
            $aliasfiledf= $r->inputAliasFileDocFin;
            $nombredocfin= $r->inputNameDocFin;
            /*$typefile= $r->typefile;
            $lengfile= $r->lengfile;*/

            $nmes= null;
            if($mes=='0'){
                $nmes= null;
            }else{
                $nmes= $mes;
            }

            foreach($filesdocfin as $file){
                $contentfiledf= $file;
                $filenamedf= $file->getClientOriginalName();
                $fileextensiondf= $file->getClientOriginalExtension();
            }

            $newnamedf= $aliasfiledf.".".$fileextensiondf;

            if($fileextensiondf== $this->validarFile($fileextensiondf)){
                $storepoa= Storage::disk('doc_financiero')->put($newnamedf,  \File::get($contentfiledf));
                if($storepoa){
                    $sql_insert = DB::connection('mysql')->insert('insert into tab_doc_financiero (
                        id_anio, id_mes, titulo, archivo, created_at
                    ) values (?,?,?,?,?)', [$anio, $nmes, $nombredocfin, $newnamedf, $date]);
    
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

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC FINANCIERO
    public function view_doc_financiero($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $id= base64_decode($id);
            $filedocfin= DB::connection('mysql')
            ->select('SELECT titulo, archivo FROM tab_doc_financiero WHERE id=?', [$id]);
            return response()->view('Administrador.Documentos.financiero.viewdocfinanciero', ['filedocfin'=> $filedocfin]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA DOC FINANCIERO
    public function inactivar_doc_financiero(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_doc_financiero')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION PARA DESCARGAR DOC FINANCIERO
    public function download_doc_financiero($id){
        $id = desencriptarNumero($id);
        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_financiero WHERE id=?', [$id]);

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }
        
        $subpath = 'documentos/doc_financiero/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/doc-financiero/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('doc_financiero')->exists($archivo))
        {
            //return Storage::disk('img_banner')->download($url);
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR DOC FINANCIERO
    public function edit_doc_financiero($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $id= base64_decode($id);
            $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','asc')->get();
            $filedocfin = DB::table('tab_doc_financiero')
            ->join('tab_anio', 'tab_doc_financiero.id_anio', '=', 'tab_anio.id')
            ->select('tab_doc_financiero.*', 'tab_anio.nombre as anio')
            ->where('tab_doc_financiero.id','=', $id)
            ->get();
            //return $filedocfin;
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.financiero.editar_docfinanciero', ['filedocfin'=> $filedocfin, 'anio'=> $anio, 'mes'=> $mes]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTUALIZA LA DOCUMENTACION FINANCIERA EN LA BASE DE DATOS
    public function update_doc_financiero(Request $r){
        $date= now();
        $iddf= $r->iddocfinanciero;
        $tituloDocFinanciero= $r->inputEDocTitle;
        $aliasfiledocfin= $r->inputEAliasFile;
        $isDocFinanciero= $r->isDocFinanciero;

        if($isDocFinanciero=="false"){
            if ($r->hasFile('fileEdit')) {
                $filesdocfin  = $r->file('fileEdit'); //obtengo el archivo RENDICIONC
                foreach($filesdocfin as $file){
                    $contentfiledocfin= $file;
                    $filenamedocfin= $file->getClientOriginalName();
                    $fileextensiondocfin= $file->getClientOriginalExtension();
                }
                $newnamedocfin= $aliasfiledocfin.".".$fileextensiondocfin;

                if(Storage::disk('doc_financiero')->exists($newnamedocfin)){
                    Storage::disk('doc_financiero')->delete($newnamedocfin);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensiondocfin== $this->validarFile($fileextensiondocfin)){
                    $storedocfin= Storage::disk('doc_financiero')->put($newnamedocfin,  \File::get($contentfiledocfin));

                    if($storedocfin){
                        $sql_update= DB::table('tab_doc_financiero')
                            ->where('id',$iddf)
                            ->update(['titulo'=> $tituloDocFinanciero, 'archivo'=> $newnamedocfin, 'updated_at'=> $date]);
    
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
            $oldnamefile= $this->name_file_doc($iddf);
            $newnamedocfin= $aliasfiledocfin.".pdf";
            if($oldnamefile!=$newnamedocfin){
                Storage::disk('doc_financiero')->move($oldnamefile, $newnamedocfin);
            }
            /*if(Storage::disk('doc_financiero')->exists($oldnamefile)){
                Storage::disk('doc_financiero')->move($oldnamefile, $newnamedocfin);
            }*/

            $sql_update= DB::table('tab_doc_financiero')
                ->where('id',$iddf)
                ->update(['titulo'=> $tituloDocFinanciero, 'archivo'=> $newnamedocfin, 'updated_at'=> $date]);
    
            if($sql_update){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
            //return response()->json(["resultado"=> true]);
        }
    }

    private function name_file_doc($id){
        $resultado='';

        $sql= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_financiero WHERE id=?', [$id]);

        foreach($sql as $s){
            $resultado= $s->archivo;
        }

        return $resultado;
    }

    //FUNCION PARA ELIMINAR DEFINITIVAMENTE EL ARCHIVO
    public function delete_doc_financiero(Request $request){
        $id= $request->input('id');

        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_financiero WHERE id=?', [$id]);
        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }

        $subpath = 'documentos/doc_financiero/'.$archivo;
        $path = storage_path('app/'.$subpath);
        Storage::disk('doc_financiero')->delete($archivo);

        $deleted = DB::table('tab_doc_financiero')->where('id', '=', $id)->delete();

        if($deleted){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }
}
