<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\ContadorHelper;
use Illuminate\Support\Collection;

class DocOperativoController extends Controller
{
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){

            $countCat= DB::connection('mysql')->table('tab_doc_operativo_categoria')->count();
            $countSucCat= DB::connection('mysql')->table('tab_doc_operativo_subcategoria')->count();
            $countFileCat= DB::connection('mysql')->table('tab_doc_operativo_archivos')->count();

            $totalArchivos= (int) $countFileCat;

            $arcat= array();

            $getCatBiblioteca= DB::connection('mysql')->table('tab_doc_operativo_categoria')->get();
            foreach($getCatBiblioteca as $c){
                $arsubcat= array();
                //$arfile= array();
                $idcat= $c->id;
                $getSubcatBiblioteca= DB::connection('mysql')->select('SELECT id, descripcion, estado FROM tab_doc_operativo_subcategoria 
                    WHERE id_do_categoria=?', [$idcat]);
                
                $idsubcat='';
                /* GET SUBCATEGORIA */
                //$wordCount = count($getSubcatBiblioteca);
                foreach($getSubcatBiblioteca as $sc){
                    $idsubcat= $sc->id;
                    $arfilesubcat= array();

                    $getFileBiblioteca= DB::connection('mysql')->select('SELECT id, archivo, estado FROM tab_doc_operativo_archivos 
                        WHERE id_do_categoria=? AND id_do_subcategoria=?', [$idcat, $idsubcat]);
                    foreach($getFileBiblioteca as $fc){
                        $arfilesubcat[] = array('archivo'=> $fc->archivo, 'estado'=> $fc->estado);
                    }
                    

                    $arsubcat[]= array('idsubcat'=> $idsubcat, 'descripcionsubcat'=> $sc->descripcion, 'estadosubcat'=> $sc->estado, 'idcategoria'=> $idcat, 'archivossubcat'=> $arfilesubcat);
                    unset($arfilesubcat);
                }
                /* GET SUBCATEGORIA */

                /* GET ARCHIVOS SIN SUBCATEGORIA */
                /*$getFileBv= DB::connection('mysql')->select('SELECT id, archivo, estado FROM tab_bv_archivos 
                    WHERE id_bv_categoria=? AND id_bv_subcategoria IS NULL', [$idcat]);
                foreach($getFileBv as $fc){
                    $arfile[] = array('idfile'=> $fc->id, 'archivo'=> $fc->archivo, 'estado'=> $fc->estado);
                }*/
                /* GET ARCHIVOS SIN SUBCATEGORIA */

                $arcat[]= array('idcat'=> $idcat, 'descripcioncat'=> $c->descripcion, 'estadocat'=> $c->estado, 'subcategoria'=> $arsubcat);
                //unset($arfile);
                unset($arsubcat);
            }

            
            json_encode($arcat);
            //return $arcat;
            return response()->view('Administrador.Documentos.operativo.operativo', ['operativo'=> collect($arcat), 'totalC'=> $countCat, 
                'totalSC'=> $countSucCat, 'totalFC'=> $totalArchivos]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function registro_categoria_operativo(Request $r){
        $categoria= $r->categoria;
        $date= now();

        $sql_insert = DB::connection('mysql')->insert('insert into tab_doc_operativo_categoria (
            descripcion, created_at
        ) values (?,?)', [$categoria, $date]);
        
        if($sql_insert){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    //FUNCION QUE OBTIENE NOMBRE DE LA CATEGORÍA
    public function get_namecat_docop($id){
        $sql= DB::connection('mysql')->select('SELECT descripcion, estado FROM tab_doc_operativo_categoria WHERE id=?',[$id]);

        return $sql;
    }

    //FUNCION QUE REGISTRA LA SUBCATEGORIA DE LA DOCUMENTACIÓN OPERATIVA
    public function registro_subcategoria_operativo(Request $r){
        $idcategoria= $r->idcategoria;
        $subcategoria= $r->subcategoria;
        $date= now();

        $sql_insert= DB::connection('mysql')->table('tab_doc_operativo_subcategoria')->insertGetId(
            ['id_do_categoria'=> $idcategoria, 'descripcion'=> $subcategoria, 'created_at'=> $date]
        );
        
        if($sql_insert){
            $idsubcat = encriptarNumero($sql_insert);
            $idcat = encriptarNumero($idcategoria);

            $countSucCat= DB::connection('mysql')->table('tab_doc_operativo_subcategoria')->count();
            $countSucCatFilter= DB::connection('mysql')->table('tab_doc_operativo_subcategoria')->where('id_do_categoria', $idcategoria)->count();
            $countFileSucCat= DB::connection('mysql')->table('tab_doc_operativo_archivos')->where('id_do_subcategoria', $sql_insert)->count();
            
            return response()->json(["resultado"=> true, "codecat"=> $idcat, "codesubcat"=> $idsubcat, "contsubcatgeneral"=> $countSucCat, 'totalsubcat'=> $countSucCatFilter, 'totalfile'=> $countFileSucCat]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    public function get_table_datos($idcat, $idsubcat){
        $idcategoria = desencriptarNumero($idcat);
        $idsubcat = desencriptarNumero($idsubcat);
        
        $subcategoria= array();
        
        $getSubcatBiblioteca= DB::connection('mysql')->select('SELECT id, descripcion, estado FROM tab_doc_operativo_subcategoria 
                    WHERE id_do_categoria=?', [$idcategoria]);
            
        /* GET SUBCATEGORIA */
        //$wordCount = count($getSubcatBiblioteca);
        foreach($getSubcatBiblioteca as $sc){
            $arfilesubcat= array();

            $getFileBiblioteca= DB::connection('mysql')->select('SELECT id, archivo, estado FROM tab_doc_operativo_archivos 
                    WHERE id_do_categoria=? AND id_do_subcategoria=?', [$idcategoria, $idsubcat]);
            foreach($getFileBiblioteca as $fc){
                    $arfilesubcat[] = array('archivo'=> $fc->archivo, 'estado'=> $fc->estado);
            }
                    

            $subcategoria[]= array('idsubcat'=> $idsubcat, 'descripcionsubcat'=> $sc->descripcion, 'estadosubcat'=> $sc->estado, 'idcategoria'=> $idcategoria, 'archivossubcat'=> $arfilesubcat);
            unset($arfilesubcat);
        }

        // Retornar HTML renderizado
        return view('Administrador.Documentos.operativo.tabla', compact('subcategoria'));
    }

    private function get_datacat($id){
        $sql= DB::connection('mysql')->table('tab_doc_operativo_archivos')->where('id_do_categoria', '=', $id)->count();
        
        return $sql;
    }

    //FUNCION QUE ACTUALIZA LA CATEGORIA DE LA DOCUMENTACIÓN OPERATIVA
    public function actualizar_categoria_operativo(Request $r){
        $idcategoria= $r->idcategoria;
        $categoria= $r->categoria;
        $estado= $r->estadocategoria;
        $date= now();

        $validacion = $this->get_datacat($idcategoria);

        if($validacion==0){
            $sql_update= DB::connection('mysql')->table('tab_doc_operativo_categoria')
            ->where('id', $idcategoria)
            ->update(['descripcion'=> $categoria, 'estado' => $estado, 'updated_at'=> $date]);
            
            if($sql_update){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }else if($validacion>0){
            return response()->json(["resultado"=> 'con_data']);
        }
        
    }

    public function docs_operativo_register($idcat, $idsubcat, $opcion){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $idcat = desencriptarNumero($idcat);
            $idsubcat = desencriptarNumero($idsubcat);
            $dateyear= DB::connection('mysql')->table('tab_anio')->orderByDesc('nombre')->get();
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();

            $sql= DB::connection('mysql')->table('tab_doc_operativo_categoria')->where('id','=', $idcat)->value('descripcion');
            $getSubCat= DB::connection('mysql')->table('tab_doc_operativo_subcategoria')->where('id', '=',$idsubcat)->value('descripcion');
            //return $sql;
            //return $getSubCat;
            return response()->view('Administrador.Documentos.operativo.registrar_docoperativo', ['code'=>$idcat, 'categoria'=> $sql, 'idsubcat'=> $idsubcat, 'subcategoria'=> $getSubCat,
                'anio'=> $dateyear, 'mes'=> $mes, 'opcion'=> $opcion]);
        }else{
            return redirect('/loginadmineep');
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
            $idcat = $r->idcat;
            $idsubcat = $r->idsubcat;
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
                    $sql_insert = DB::connection('mysql')->insert('insert into tab_doc_operativo_archivos (
                        id_do_categoria, id_do_subcategoria, id_anio, id_mes, titulo, archivo, created_at
                    ) values (?,?,?,?,?,?,?)', [$idcat, $idsubcat, $anio, $nmes, $nombredocoper, $newnamedf, $date]);
    
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

     //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA BV SUBCATEGORIA
    public function inactivar_docop_subcategoria(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $id = desencriptarNumero($id);
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::connection('mysql')->table('tab_doc_operativo_subcategoria')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE OBTIENE NOMBRE DE LA SUBCATEGORÍA
    public function get_docop_namesubcat($id){
        $id = desencriptarNumero($id);
        $sql= DB::connection('mysql')->select('SELECT descripcion, estado FROM tab_doc_operativo_subcategoria WHERE id=?',[$id]);

        return $sql;
    }

    //FUNCION QUE ACTUALIZA LA SUBCATEGORIA DE LA BIBLIOTECA VIRTUAL
    public function actualizar_subcategoria_operativo(Request $r){
        $idsubcategoria= $r->idsubcategoria;
        $subcategoria= $r->subcategoria;
        $date= now();

        $idsubcategoria = desencriptarNumero($idsubcategoria);

        $sql_update= DB::connection('mysql')->table('tab_doc_operativo_subcategoria')
        ->where('id', $idsubcategoria)
        ->update(['descripcion'=> $subcategoria, 'updated_at'=> $date]);
        
        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    public function delete_filedocop_sure_subcategoria(Request $r){
        $idcat = $r->input('idcat');
        $idsubcat = $r->input('idsubcat');

        $idcat = desencriptarNumero($idcat);
        $idsubcat = desencriptarNumero($idsubcat);

        $sqlfiles = DB::connection('mysql')->table('tab_doc_operativo_archivos')->where('id_do_categoria','=',$idcat)->where('id_do_subcategoria','=',$idsubcat)->get();

        $i=0; $j=0;
        foreach ($sqlfiles as $f) {
            $archivo = $f->archivo;
            $filedel = Storage::disk('doc_operativo')->delete($archivo);
            if($filedel){
                $i++;
                $deleted = DB::table('tab_doc_operativo_archivos')->where('id', '=', $f->id)->delete();
                if($deleted){
                    $j++;
                }
            }
        }

        if($i==$j){
            $deletedsc = DB::table('tab_doc_operativo_subcategoria')->where('id', '=', $idsubcat)->delete();

            if($deletedsc){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }else{
            return response()->json(['resultado'=> 'no_all_delete']);
        }
    }

    public function listdocop_subcat($idcat, $idsubcat, $tipo){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            //$getSubCat= DB::connection('mysql')->table('tab_bv_subcategoria')->where('id_bv_categoria', $idcat)->get();
            /*$getFile= DB::connection('mysql')->table('tab_bv_archivos as tbf')
            ->join('tab_bv_categoria as c', 'tbf.id_bv_categoria', '=', 'c.id')
            ->join('tab_bv_subcategoria as sc', 'tbf.id_bv_subcategoria', '=', 'sc.id')
            ->select('tbf.*', 'c.descripcion as categoria', 'sc.descripcion as subcategoria')
            ->where('tbf.id_bv_subcategoria', $idsubcat)
            ->where('tbf.id_bv_categoria', $idcat)
            ->get();*/
            $idcat = desencriptarNumero($idcat);
            $idsubcat = desencriptarNumero($idsubcat);

            $operativo = DB::connection('mysql')
            ->table('tab_doc_operativo_archivos as df')
            ->join('tab_anio as y', 'df.id_anio','=', 'y.id')
            ->select('df.*', 'y.nombre as anio')
            ->where('id_do_categoria','=',$idcat)
            ->where('id_do_subcategoria','=', $idsubcat)
            ->orderBy('y.nombre','DESC')
            ->get();

            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();

            $sqlcat= DB::connection('mysql')->table('tab_doc_operativo_categoria')->where('id','=', $idsubcat)->value('descripcion');

            $sqlsubcat= DB::connection('mysql')->table('tab_doc_operativo_subcategoria')->where('id','=', $idsubcat)->value('descripcion');

            return response()->view('Administrador.Documentos.operativo.list_docsoperativo', ['idcat'=> $idcat, 'idsubcat'=> $idsubcat,'categoria'=> $sqlcat, 'subcategoria'=> $sqlsubcat, 'operativo'=> $operativo, 'mes'=> $mes]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC OPERATIVO
    public function view_doc_operativo($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            //$id= base64_decode($id);
            $id = desencriptarNumero($id);

            $datos = DB::table('tab_doc_operativo_archivos')
                ->where('id', '=', $id)
                ->select('id_do_categoria', 'id_do_subcategoria')
                ->first();

            $cat = $datos->id_do_categoria;
            $subcat = $datos->id_do_subcategoria;

            $filedocoper= DB::connection('mysql')
            ->select('SELECT titulo, archivo FROM tab_doc_operativo_archivos WHERE id=?', [$id]);
            return response()->view('Administrador.Documentos.operativo.viewdocoperativo', ['filedocoper'=> $filedocoper, 'idcat'=> $cat, 'idsubcat'=> $subcat]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR DOC OPERATIVO
    public function edit_doc_operativo($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            //$id= base64_decode($id);
            $id = desencriptarNumero($id);
            $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','asc')->get();
            $filedocoper = DB::table('tab_doc_operativo_archivos')
            ->join('tab_anio', 'tab_doc_operativo_archivos.id_anio', '=', 'tab_anio.id')
            ->select('tab_doc_operativo_archivos.*', 'tab_anio.nombre as anio')
            ->where('tab_doc_operativo_archivos.id','=', $id)
            ->get();

            $datos = DB::table('tab_doc_operativo_archivos as do')
                ->join('tab_doc_operativo_categoria as tc', 'do.id_do_categoria','=', 'tc.id')
                ->join('tab_doc_operativo_subcategoria as tsc', 'do.id_do_subcategoria','=', 'tsc.id')
                ->where('do.id', '=', $id)
                ->select('do.id_do_categoria', 'tc.descripcion as categoria','do.id_do_subcategoria', 'tsc.descripcion as subcategoria')
                ->first();

            $idcat = $datos->id_do_categoria;
            $idsubcat = $datos->id_do_subcategoria;
            $cat = $datos->categoria;
            $subcat = $datos->subcategoria;

            //return $filedocoper;
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            return response()->view('Administrador.Documentos.operativo.editar_docoperativo', ['filedocoper'=> $filedocoper, 'anio'=> $anio, 'mes'=> $mes,
                'idcat'=> $idcat, 'categoria'=> $cat, 'idsubcat'=> $idsubcat, 'subcategoria'=> $subcat]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION PARA ELIMINAR DEFINITIVAMENTE EL ARCHIVO
    public function delete_doc_operativo(Request $request){
        $id= $request->input('id');

        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_operativo_archivos WHERE id=?', [$id]);
        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }

        $subpath = 'documentos/doc_operativo/'.$archivo;
        $path = storage_path('app/'.$subpath);
        Storage::disk('doc_operativo')->delete($archivo);

        $deleted = DB::table('tab_doc_operativo_archivos')->where('id', '=', $id)->delete();

        if($deleted){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
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
                        $sql_update= DB::table('tab_doc_operativo_archivos')
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

            $sql_update= DB::table('tab_doc_operativo_archivos')
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

        $sql= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_operativo_archivos WHERE id=?', [$id]);

        foreach($sql as $s){
            $resultado= $s->archivo;
        }

        return $resultado;
    }

    //------------------------------------------------------
      
    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA DOC OPERATIVO
    public function inactivar_doc_operativo(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $id = desencriptarNumero($id);

        $sql_update= DB::table('tab_doc_operativo_archivos')
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
        $id = desencriptarNumero($id);

        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_doc_operativo_archivos WHERE id=?', [$id]);

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

    public function docoperativo_increment(Request $r){
        $id = $r->input('idfile');
        $id = desencriptarNumero($id);
        //Incrementar contador de descargas (llamada limpia y segura)
        ContadorHelper::incrementarDescarga('tab_doc_operativo_archivos', $id);
        //return response()->json(['resultado'=>true]);
    }
}
