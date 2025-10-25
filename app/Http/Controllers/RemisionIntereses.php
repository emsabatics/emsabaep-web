<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\ContadorHelper;

class RemisionIntereses extends Controller
{
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $remisioni= DB::connection('mysql')->select('SELECT da.*, y.nombre as anio FROM tab_doc_remision_interes da, tab_anio y WHERE da.id_anio=y.id ORDER BY y.nombre DESC');
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.remision.remisioni', ['remisioni'=> $remisioni, 'mes'=> $mes]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function doc_remisioni_register(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $dateyear= DB::connection('mysql')->table('tab_anio')->orderByDesc('nombre')->get();
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.remision.registrar_docremision', ['anio'=> $dateyear, 'mes'=> $mes]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function store_doc_remisioni(Request $r){
        if ($r->hasFile('file') ) {
            $filesdocfin  = $r->file('file'); //obtengo el archivo LOTAIP

            $date= now();
            $anio= $r->anio;
            $mes= $r->mes;
            $aliasfiledf= $r->inputAliasFileDocRemisionI;
            $nombredocfin= $r->inputNameDocRemisionI;
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
                $storepoa= Storage::disk('remision_intereses')->put($newnamedf,  \File::get($contentfiledf));
                if($storepoa){
                    $sql_insert = DB::connection('mysql')->insert('insert into tab_doc_remision_interes (
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

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC
    public function view_doc_remisioni($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $id= base64_decode($id);
            $filedocremision= DB::connection('mysql')
            ->select('SELECT titulo, archivo FROM tab_doc_remision_interes WHERE id=?', [$id]);
            return response()->view('Administrador.Documentos.remision.viewdocremision', ['filedocremision'=> $filedocremision]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA DOC ADMINISTRATIVO
    public function inactivar_doc_remisioni(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_doc_remision_interes')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION PARA DESCARGAR DOC
    public function download_doc_remisioni($id){
        $id = desencriptarNumero($id);
        
        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_remision_interes WHERE id=?', [$id]);

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }
        
        $subpath = 'documentos/remision_intereses/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/doc-administrativo/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('remision_intereses')->exists($archivo))
        {
            //return Storage::disk('img_banner')->download($url);
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR DOC
    public function edit_doc_remisioni($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $id= base64_decode($id);
            $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','asc')->get();
            $filedocadmin = DB::table('tab_doc_remision_interes')
            ->join('tab_anio', 'tab_doc_remision_interes.id_anio', '=', 'tab_anio.id')
            ->select('tab_doc_remision_interes.*', 'tab_anio.nombre as anio')
            ->where('tab_doc_remision_interes.id','=', $id)
            ->get();
            //return $filedocadmin;
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.remision.editar_docremisioni', ['filedocadmin'=> $filedocadmin, 'anio'=> $anio, 'mes'=> $mes]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTUALIZA LA DOCUMENTACION EN LA BASE DE DATOS
    public function update_doc_remisioni(Request $r){
        $date= now();
        $idadmin= $r->iddocremisioni;
        $tituloDocAdministrativo= $r->inputEDocTitle;
        $aliasfiledocadmin= $r->inputEAliasFile;
        $isDocRemisionI= $r->isDocRemisionI;

        if($isDocRemisionI=="false"){
            if ($r->hasFile('fileEdit')) {
                $filesdocadmin  = $r->file('fileEdit'); //obtengo el archivo RENDICIONC
                foreach($filesdocadmin as $file){
                    $contentfiledocadmin= $file;
                    $filenamedocadmin= $file->getClientOriginalName();
                    $fileextensiondocadmin= $file->getClientOriginalExtension();
                }
                $newnamedocadmin= $aliasfiledocadmin.".".$fileextensiondocadmin;

                if(Storage::disk('remision_intereses')->exists($newnamedocadmin)){
                    Storage::disk('remision_intereses')->delete($newnamedocadmin);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensiondocadmin== $this->validarFile($fileextensiondocadmin)){
                    $storedocadmin= Storage::disk('remision_intereses')->put($newnamedocadmin,  \File::get($contentfiledocadmin));

                    if($storedocadmin){
                        $sql_update= DB::table('tab_doc_remision_interes')
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
                Storage::disk('remision_intereses')->move($oldnamefile, $newnamedocadmin);
            }
            /*if(Storage::disk('remision_intereses')->exists($oldnamefile)){
                Storage::disk('remision_intereses')->move($oldnamefile, $newnamedocadmin);
            }*/

            $sql_update= DB::table('tab_doc_remision_interes')
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

        $sql= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_remision_interes WHERE id=?', [$id]);

        foreach($sql as $s){
            $resultado= $s->archivo;
        }

        return $resultado;
    }

    //FUNCION PARA ELIMINAR DEFINITIVAMENTE EL ARCHIVO
    public function delete_doc_remision(Request $request){
        $id= $request->input('id');

        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_remision_interes WHERE id=?', [$id]);
        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }

        $subpath = 'documentos/remision_intereses/'.$archivo;
        $path = storage_path('app/'.$subpath);
        Storage::disk('remision_intereses')->delete($archivo);

        $deleted = DB::table('tab_doc_remision_interes')->where('id', '=', $id)->delete();

        if($deleted){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function remisioni_increment(Request $r){
        $id = $r->input('idfile');
        $id = desencriptarNumero($id);
        //Incrementar contador de descargas (llamada limpia y segura)
        ContadorHelper::incrementarDescarga('tab_doc_remision_interes', $id);
        //return response()->json(['resultado'=>true]);
    }
}
