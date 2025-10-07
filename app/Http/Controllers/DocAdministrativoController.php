<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\ContadorHelper;

class DocAdministrativoController extends Controller
{
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $administrativo= DB::connection('mysql')->select('SELECT da.*, y.nombre as anio FROM tab_doc_administrativo da, tab_anio y WHERE da.id_anio=y.id ORDER BY y.nombre DESC');
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.administrativo.administrativo', ['administrativo'=> $administrativo, 'mes'=> $mes]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function doc_administrativo_register(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $dateyear= DB::connection('mysql')->table('tab_anio')->orderByDesc('nombre')->get();
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.administrativo.registrar_docadministrativo', ['anio'=> $dateyear, 'mes'=> $mes]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function store_doc_administrativo(Request $r){
        if ($r->hasFile('file') ) {
            $filesdocfin  = $r->file('file'); //obtengo el archivo LOTAIP

            $date= now();
            $anio= $r->anio;
            $mes= $r->mes;
            $aliasfiledf= $r->inputAliasFileDocAdmin;
            $nombredocfin= $r->inputNameDocAdmin;
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
                $storepoa= Storage::disk('doc_administrativo')->put($newnamedf,  \File::get($contentfiledf));
                if($storepoa){
                    $sql_insert = DB::connection('mysql')->insert('insert into tab_doc_administrativo (
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
            return $extension;
        }else{
            return "0";
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC ADMINISTRATIVO
    public function view_doc_administrativo($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $id= base64_decode($id);
            $filedocadmin= DB::connection('mysql')
            ->select('SELECT titulo, archivo FROM tab_doc_administrativo WHERE id=?', [$id]);
            return response()->view('Administrador.Documentos.administrativo.viewdocadministrativo', ['filedocadmin'=> $filedocadmin]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA DOC ADMINISTRATIVO
    public function inactivar_doc_administrativo(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_doc_administrativo')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION PARA DESCARGAR DOC ADMINISTRATIVO
    public function download_doc_administrativo($id){
        $id = desencriptarNumero($id);
        
        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_administrativo WHERE id=?', [$id]);

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }
        
        $subpath = 'documentos/doc_administrativo/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/doc-administrativo/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('doc_administrativo')->exists($archivo))
        {
            //return Storage::disk('img_banner')->download($url);
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

    public function docadmin_increment(Request $r){
        $id = $r->input('idfile');
        $id = desencriptarNumero($id);
        //Incrementar contador de descargas (llamada limpia y segura)
        ContadorHelper::incrementarDescarga('tab_doc_administrativo', $id);
        //return response()->json(['resultado'=>true]);
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR DOC ADMINISTRATIVO
    public function edit_doc_administrativo($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $id= base64_decode($id);
            $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','asc')->get();
            $filedocadmin = DB::table('tab_doc_administrativo')
            ->join('tab_anio', 'tab_doc_administrativo.id_anio', '=', 'tab_anio.id')
            ->select('tab_doc_administrativo.*', 'tab_anio.nombre as anio')
            ->where('tab_doc_administrativo.id','=', $id)
            ->get();
            //return $filedocadmin;
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.administrativo.editar_docadministrativo', ['filedocadmin'=> $filedocadmin, 'anio'=> $anio, 'mes'=> $mes]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTUALIZA LA DOCUMENTACION ADMINISTRATIVA EN LA BASE DE DATOS
    public function update_doc_administrativo(Request $r){
        $date= now();
        $idadmin= $r->iddocadministrativo;
        $tituloDocAdministrativo= $r->inputEDocTitle;
        $aliasfiledocadmin= $r->inputEAliasFile;
        $isDocAdministrativo= $r->isDocAdministrativo;

        if($isDocAdministrativo=="false"){
            if ($r->hasFile('fileEdit')) {
                $filesdocadmin  = $r->file('fileEdit'); //obtengo el archivo RENDICIONC
                foreach($filesdocadmin as $file){
                    $contentfiledocadmin= $file;
                    $filenamedocadmin= $file->getClientOriginalName();
                    $fileextensiondocadmin= $file->getClientOriginalExtension();
                }
                $newnamedocadmin= $aliasfiledocadmin.".".$fileextensiondocadmin;

                if(Storage::disk('doc_administrativo')->exists($newnamedocadmin)){
                    Storage::disk('doc_administrativo')->delete($newnamedocadmin);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensiondocadmin== $this->validarFile($fileextensiondocadmin)){
                    $storedocadmin= Storage::disk('doc_administrativo')->put($newnamedocadmin,  \File::get($contentfiledocadmin));

                    if($storedocadmin){
                        $sql_update= DB::table('tab_doc_administrativo')
                            ->where('id',$idadmin)
                            ->update(['titulo'=> $tituloDocAdministrativo, 'archivo'=> $newnamedocadmin, 'updated_at'=> $date]);
    
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
            $oldnamefile= $this->name_file_doc($idadmin);
            $newnamedocadmin= $aliasfiledocadmin.".pdf";
            if($oldnamefile!=$newnamedocadmin){
                Storage::disk('doc_administrativo')->move($oldnamefile, $newnamedocadmin);
            }
            /*if(Storage::disk('doc_administrativo')->exists($oldnamefile)){
                Storage::disk('doc_administrativo')->move($oldnamefile, $newnamedocadmin);
            }*/

            $sql_update= DB::table('tab_doc_administrativo')
                ->where('id',$idadmin)
                ->update(['titulo'=> $tituloDocAdministrativo, 'archivo'=> $newnamedocadmin, 'updated_at'=> $date]);
    
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

        $sql= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_administrativo WHERE id=?', [$id]);

        foreach($sql as $s){
            $resultado= $s->archivo;
        }

        return $resultado;
    }

    //FUNCION PARA ELIMINAR DEFINITIVAMENTE EL ARCHIVO
    public function delete_doc_administrativo(Request $request){
        $id= $request->input('id');

        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_administrativo WHERE id=?', [$id]);
        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }

        $subpath = 'documentos/doc_administrativo/'.$archivo;
        $path = storage_path('app/'.$subpath);
        Storage::disk('doc_administrativo')->delete($archivo);

        $deleted = DB::table('tab_doc_administrativo')->where('id', '=', $id)->delete();

        if($deleted){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }
}
