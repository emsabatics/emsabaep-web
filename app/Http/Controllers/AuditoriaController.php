<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AuditoriaController extends Controller
{
    //INDEX PÁGINA PRINCIPAL
    public function index()
    {
        if(Session::get('usuario') && (Session::get('usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $auditoria= DB::connection('mysql')->select('SELECT p.*, y.nombre as year FROM tab_auditoria p, tab_anio y WHERE p.id_anio=y.id ORDER BY p.id_anio DESC');
            return response()->view('Administrador.Documentos.auditoria.auditoria', ['auditoria'=> $auditoria]);
        }else{
            return redirect('/login');
        }
    }

    //FUNCION ABRE INTERFAZ PARA REGISTRAR LA AUDITORIA
    public function auditoria_register(){
        if(Session::get('usuario') && (Session::get('usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $dateyear= DB::connection('mysql')->table('tab_anio')->orderByDesc('nombre')->get();
            return response()->view('Administrador.Documentos.auditoria.registrar_auditoria', ['dateyear'=> $dateyear]);
        }else{
            return redirect('/login');
        }
    }

    //FUNCION QUE ALMACENA LA AUDITORIA EN LA BASE DE DATOS
    public function store_auditoria(Request $r){
        if ($r->hasFile('file') ) {
            $filesauditoria  = $r->file('file'); //obtengo el archivo AUDITORIA

            $date= now();
            $anio = $r->anio;
            $area=  $r->area;
            $titulo= $r->inputTitulo;
            $aliasfilepac= $r->inputAliasFile;
            $observacion= $r->inputObsr;
            $seltipo= $r->seltipo;
            $resarr= array();
            $resarr= $this->getNameAuditoria($anio);

            if($anio==$this->getNameAuditoria($anio)){
                return response()->json(['resultado'=> 'existe']);
            }else{
                //NO HAY INFORMACIÓN
                foreach($filesauditoria as $file){
                    $contentfileauditoria= $file;
                    $filenameauditoria= $file->getClientOriginalName();
                    $fileextensionauditoria= $file->getClientOriginalExtension();
                }
    
                $newnameauditoria= $aliasfilepac.".".$fileextensionauditoria;
    
                if($fileextensionauditoria== $this->validarFile($fileextensionauditoria)){
                    $storeauditoria= Storage::disk('doc_auditoria')->put($newnameauditoria,  \File::get($contentfileauditoria));
                    if($storeauditoria){
                        $sql_insert = DB::connection('mysql')->insert('insert into tab_auditoria (
                            id_anio, titulo, archivo, created_at
                        ) values (?,?,?,?)', [$anio, $titulo, $newnameauditoria, $date]);
        
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

    private function getNameAuditoria($year){
        $sql= DB::connection('mysql')->select('SELECT id_anio FROM tab_auditoria WHERE id_anio=?', [$year]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->id_anio;
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

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC DE AUDITORIA
    public function view_auditoria($id){
        if(Session::get('usuario') && (Session::get('usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $fileauditoria= DB::connection('mysql')->table('tab_auditoria')
            ->join('tab_anio', 'tab_auditoria.id_anio','=', 'tab_anio.id')
            ->select('tab_auditoria.*', 'tab_anio.nombre')
            ->where('tab_auditoria.id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.auditoria.viewauditoria', ['fileauditoria'=> $fileauditoria]);
        }else{
            return redirect('/login');
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA AUDITORIA
    public function inactivar_auditoria(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_auditoria')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION PARA DESCARGAR EL DOC DE AUDITORIA
    public function download_auditoria($id){
        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_auditoria WHERE id=?', [$id]);

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }
        
        $subpath = 'documentos/auditoria/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/doc-auditoria/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('doc_auditoria')->exists($archivo))
        {
            //return Storage::disk('img_banner')->download($url);
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR EL REGISTRO DE AUDITORIA
    public function edit_auditoria($id){
        if(Session::get('usuario') && (Session::get('usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $dateyear= DB::connection('mysql')->table('tab_anio')->orderByDesc('nombre')->get();
            $fileauditoria= DB::connection('mysql')->table('tab_auditoria')
            ->join('tab_anio', 'tab_auditoria.id_anio','=', 'tab_anio.id')
            ->select('tab_auditoria.*', 'tab_anio.nombre')
            ->where('tab_auditoria.id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.auditoria.editar_auditoria', ['fileauditoria'=> $fileauditoria, 'dateyear'=> $dateyear]);
        }else{
            return redirect('/login');
        }
    }

    //FUNCION QUE ACTUALIZA EL REGISTRO DE AUDITORIA EN LA BASE DE DATOS
    public function update_auditoria(Request $r){
        $date= now();
        $id= $r->idauditoria;
        $anio = $r->anio;
        $titulo= $r->inputETitulo;
        $aliasfileauditoria= $r->inputEAliasFile;
        $observacion= $r->inputEObsr;
        $isauditoria= $r->isauditoria;

        if($isauditoria=="false"){
            if ($r->hasFile('fileEdit')) {
                $filesauditoria  = $r->file('fileEdit'); //obtengo el archivo POA
                foreach($filesauditoria as $file){
                    $contentfileauditoria= $file;
                    $filenameauditoria= $file->getClientOriginalName();
                    $fileextensionauditoria= $file->getClientOriginalExtension();
                }
                $newnameauditoria= $aliasfileauditoria.".".$fileextensionauditoria;

                if(Storage::disk('doc_auditoria')->exists($newnameauditoria)){
                    Storage::disk('doc_auditoria')->delete($newnameauditoria);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensionauditoria== $this->validarFile($fileextensionauditoria)){
                    $storepoa= Storage::disk('doc_auditoria')->put($newnameauditoria,  \File::get($contentfileauditoria));

                    if($storepoa){
                        $sql_update= DB::table('tab_auditoria')
                            ->where('id', $id)
                            ->update(['id_anio'=> $anio, 'titulo'=> $titulo, 'archivo'=> $newnameauditoria, 
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
            $namefile= $this->getNameFileAuditoria($anio);
            $newnameauditoria= $aliasfileauditoria.".pdf";
            //$storepoa= Storage::disk('doc_auditoria')->copy($namefile,  $newnameauditoria);
            $storepoa= Storage::disk('doc_auditoria')->move($namefile,  $newnameauditoria);
            if($storepoa){
                $sql_update= DB::table('tab_auditoria')
                    ->where('id', $id)
                    ->update(['id_anio'=> $anio, 'titulo'=> $titulo, 'archivo'=> $newnameauditoria, 'updated_at'=> $date]);
        
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

    private function getNameFileAuditoria($year){
        $sql= DB::connection('mysql')->select('SELECT archivo FROM tab_auditoria WHERE id_anio=?', [$year]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->archivo;
        }

        return $resultado;
    }
}
