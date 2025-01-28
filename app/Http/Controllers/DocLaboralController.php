<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DocLaboralController extends Controller
{
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            //$laboral = DB::table('tab_doc_laboral')->get();
            $laboral= DB::connection('mysql')->select('SELECT df.*, y.nombre as anio FROM tab_doc_laboral df, tab_anio y WHERE df.id_anio=y.id ORDER BY y.nombre DESC');
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.laboral.laboral', ['laboral'=> $laboral, 'mes'=> $mes]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function doc_laboral_register(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $dateyear= DB::connection('mysql')->table('tab_anio')->orderByDesc('nombre')->get();
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.laboral.registrar_doclaboral', ['anio'=> $dateyear, 'mes'=> $mes]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function store_doc_laboral(Request $r){
        if ($r->hasFile('file') ) {
            $filesdoclab  = $r->file('file'); //obtengo el archivo Doc Laboral

            $date= now();
            $anio = $r->anio;
            $mes= $r->mes;
            $aliasfiledl= $r->inputAliasFileDocLab;
            $nombredoclab= $r->inputNameDocLab;
            /*$typefile= $r->typefile;
            $lengfile= $r->lengfile;*/

            $nmes= null;
            if($mes=='0'){
                $nmes= null;
            }else{
                $nmes= $mes;
            }

            foreach($filesdoclab as $file){
                $contentfiledl= $file;
                $filenamedl= $file->getClientOriginalName();
                $fileextensiondl= $file->getClientOriginalExtension();
            }

            $newnamedl= $aliasfiledl.".".$fileextensiondl;

            if($fileextensiondl== $this->validarFile($fileextensiondl)){
                $storepoa= Storage::disk('doc_laboral')->put($newnamedl,  \File::get($contentfiledl));
                if($storepoa){
                    $sql_insert = DB::connection('mysql')->insert('insert into tab_doc_laboral (
                        id_anio, id_mes, titulo, archivo, created_at
                    ) values (?,?,?,?,?)', [$anio, $nmes, $nombredoclab, $newnamedl, $date]);
    
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

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC LABORAL
    public function view_doc_laboral($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $id= base64_decode($id);
            $filedoclab= DB::connection('mysql')
            ->select('SELECT titulo, archivo FROM tab_doc_laboral WHERE id=?', [$id]);
            return response()->view('Administrador.Documentos.laboral.viewdoclaboral', ['filedoclab'=> $filedoclab]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA DOC LABORAL
    public function inactivar_doc_laboral(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_doc_laboral')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION PARA DESCARGAR DOC LABORAL
    public function download_doc_laboral($id){
        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_laboral WHERE id=?', [$id]);

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }
        
        $subpath = 'documentos/doc_laboral/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/doc-laboral/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('doc_laboral')->exists($archivo))
        {
            //return Storage::disk('img_banner')->download($url);
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR DOC LABORAL
    public function edit_doc_laboral($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $id= base64_decode($id);
            $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','asc')->get();
            $filedolab = DB::table('tab_doc_laboral')
            ->join('tab_anio', 'tab_doc_laboral.id_anio', '=', 'tab_anio.id')
            ->select('tab_doc_laboral.*', 'tab_anio.nombre as anio')
            ->where('tab_doc_laboral.id','=', $id)
            ->get();
            //return $filedolab;
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.laboral.editar_doclaboral', ['filedolab'=> $filedolab, 'anio'=> $anio, 'mes'=> $mes]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTUALIZA DOCUMENTACION LABORAL EN LA BASE DE DATOS
    public function update_doc_laboral(Request $r){
        $date= now();
        $iddl= $r->iddoclaboral;
        $tituloDocLaboral= $r->inputEDocTitle;
        $aliasfiledoclab= $r->inputEAliasFile;
        $isDocLaboral= $r->isDocLaboral;

        if($isDocLaboral=="false"){
            if ($r->hasFile('fileEdit')) {
                $filesdoclab  = $r->file('fileEdit'); //obtengo el archivo RENDICIONC
                foreach($filesdoclab as $file){
                    $contentfiledoclab= $file;
                    $filenamedoclab= $file->getClientOriginalName();
                    $fileextensiondoclab= $file->getClientOriginalExtension();
                }
                $newnamedoclab= $aliasfiledoclab.".".$fileextensiondoclab;

                if(Storage::disk('doc_laboral')->exists($newnamedoclab)){
                    Storage::disk('doc_laboral')->delete($newnamedoclab);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensiondoclab== $this->validarFile($fileextensiondoclab)){
                    $storedocfin= Storage::disk('doc_laboral')->put($newnamedoclab,  \File::get($contentfiledoclab));

                    if($storedocfin){
                        $sql_update= DB::table('tab_doc_laboral')
                            ->where('id',$iddl)
                            ->update(['titulo'=> $tituloDocLaboral, 'archivo'=> $newnamedoclab, 'updated_at'=> $date]);
    
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
            //return response()->json(["resultado"=> true]);
            $oldnamefile= $this->name_file_doc($iddl);
            $newnamedoclab= $aliasfiledoclab.".pdf";
            if($oldnamefile!=$newnamedoclab){
                Storage::disk('doc_laboral')->move($oldnamefile, $newnamedoclab);
            }
            /*if(Storage::disk('doc_laboral')->exists($oldnamefile)){
                Storage::disk('doc_laboral')->move($oldnamefile, $newnamedoclab);
            }*/

            $sql_update= DB::table('tab_doc_laboral')
                ->where('id',$iddl)
                ->update(['titulo'=> $tituloDocLaboral, 'archivo'=> $newnamedoclab, 'updated_at'=> $date]);
    
            if($sql_update){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
    }

    private function name_file_doc($id){
        $resultado='';

        $sql= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_laboral WHERE id=?', [$id]);

        foreach($sql as $s){
            $resultado= $s->archivo;
        }

        return $resultado;
    }

    //FUNCION PARA ELIMINAR DEFINITIVAMENTE EL ARCHIVO
    public function delete_doc_laboral(Request $request){
        $id= $request->input('id');

        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_laboral WHERE id=?', [$id]);
        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }

        $subpath = 'documentos/doc_laboral/'.$archivo;
        $path = storage_path('app/'.$subpath);
        Storage::disk('doc_laboral')->delete($archivo);

        $deleted = DB::table('tab_doc_laboral')->where('id', '=', $id)->delete();

        if($deleted){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }
}
