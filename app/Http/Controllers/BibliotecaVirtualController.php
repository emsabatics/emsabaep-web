<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class BibliotecaVirtualController extends Controller
{
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            //$administrativo= DB::connection('mysql')->select('SELECT da.*, y.nombre as anio FROM tab_doc_administrativo da, tab_anio y WHERE da.id_anio=y.id ORDER BY y.nombre ASC');
            /*$getBiblioteca= DB::connection('mysql')->table('tab_bv_categoria as tbvc')
            ->join('tab_bv_subcategoria as tbvsc', 'tbvc.id', '=', 'tbvsc.id_bv_categoria')
            ->join('tab_bv_archivos as tbvf', 'tbvc.id', '=', 'tbvf.id_bv_categoria')
            ->get();*/

            $countCat= DB::connection('mysql')->table('tab_bv_categoria')->count();
            $countSucCat= DB::connection('mysql')->table('tab_bv_subcategoria')->count();
            $countFileCat= DB::connection('mysql')->table('tab_bv_archivos')->count();

            $arcat= array();

            $getCatBiblioteca= DB::connection('mysql')->table('tab_bv_categoria')->get();
            foreach($getCatBiblioteca as $c){
                $arsubcat= array();
                $arfile= array();
                $idcat= $c->id;
                $getSubcatBiblioteca= DB::connection('mysql')->select('SELECT id, descripcion, estado FROM tab_bv_subcategoria 
                    WHERE id_bv_categoria=?', [$idcat]);
                
                $idsubcat='';
                /* GET SUBCATEGORIA */
                //$wordCount = count($getSubcatBiblioteca);
                foreach($getSubcatBiblioteca as $sc){
                    $idsubcat= $sc->id;
                    $arfilesubcat= array();

                    $getFileBiblioteca= DB::connection('mysql')->select('SELECT id, archivo, estado FROM tab_bv_archivos 
                    WHERE id_bv_categoria=? AND id_bv_subcategoria=?', [$idcat, $idsubcat]);
                    foreach($getFileBiblioteca as $fc){
                        $arfilesubcat[] = array('idfile'=> $fc->id, 'archivo'=> $fc->archivo, 'estado'=> $fc->estado);
                    }

                    $arsubcat[]= array('idsubcat'=> $idsubcat, 'descripcionsubcat'=> $sc->descripcion, 'estadosubcat'=> $sc->estado, 'archivossubcat'=> $arfilesubcat);
                    unset($arfilesubcat);
                }
                /* GET SUBCATEGORIA */

                /* GET ARCHIVOS SIN SUBCATEGORIA */
                $getFileBv= DB::connection('mysql')->select('SELECT id, archivo, estado FROM tab_bv_archivos 
                    WHERE id_bv_categoria=? AND id_bv_subcategoria IS NULL', [$idcat]);
                foreach($getFileBv as $fc){
                    $arfile[] = array('idfile'=> $fc->id, 'archivo'=> $fc->archivo, 'estado'=> $fc->estado);
                }
                /* GET ARCHIVOS SIN SUBCATEGORIA */

                $arcat[]= array('idcat'=> $idcat, 'descripcioncat'=> $c->descripcion, 'estadocat'=> $c->estado, 'subcategoria'=> $arsubcat, 'archivos'=> $arfile);
                unset($arfile);
                unset($arsubcat);
            }

            
            json_encode($arcat);
            //return $arcat;
            return response()->view('Administrador.Documentos.virtual.virtual', ['biblioteca'=> $arcat, 'totalC'=> $countCat, 
                'totalSC'=> $countSucCat, 'totalFC'=> $countFileCat]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE OBTIENE NOMBRE DE LA CATEGORÍA
    public function get_namecat($id){
        $sql= DB::connection('mysql')->select('SELECT descripcion, estado FROM tab_bv_categoria WHERE id=?',[$id]);

        return $sql;
    }

    //FUNCION QUE REGISTRA LA CATEGORIA DE LA BIBLIOTECA VIRTUAL
    public function registro_categoria(Request $r){
        $categoria= $r->categoria;
        $date= now();

        $sql_insert = DB::connection('mysql')->insert('insert into tab_bv_categoria (
            descripcion, created_at
        ) values (?,?)', [$categoria, $date]);
        
        if($sql_insert){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    //FUNCION QUE ACTUALIZA LA CATEGORIA DE LA BIBLIOTECA VIRTUAL
    public function actualizar_categoria(Request $r){
        $idcategoria= $r->idcategoria;
        $categoria= $r->categoria;
        $estado= $r->estadocategoria;
        $date= now();

        $sql_update= DB::connection('mysql')->table('tab_bv_categoria')
        ->where('id', $idcategoria)
        ->update(['descripcion'=> $categoria, 'estado' => $estado, 'updated_at'=> $date]);
        
        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    //FUNCION QUE REGISTRA LA SUBCATEGORIA DE LA BIBLIOTECA VIRTUAL
    public function registro_subcategoria(Request $r){
        $idcategoria= $r->idcategoria;
        $subcategoria= $r->subcategoria;
        $date= now();

        $sql_insert= DB::connection('mysql')->table('tab_bv_subcategoria')->insertGetId(
            ['id_bv_categoria'=> $idcategoria, 'descripcion'=> $subcategoria, 'created_at'=> $date]
        );

        $LAST_ID= $sql_insert;
        
        if($sql_insert){
            $countSucCat= DB::connection('mysql')->table('tab_bv_subcategoria')->count();
            $countSucCatFilter= DB::connection('mysql')->table('tab_bv_subcategoria')->where('id_bv_categoria', $idcategoria)->count();
            $countFileSucCat= DB::connection('mysql')->table('tab_bv_archivos')->where('id_bv_subcategoria', $LAST_ID)->count();
            return response()->json(["resultado"=> true, "ID"=>$LAST_ID, "contsubcatgeneral"=> $countSucCat, 'totalsubcat'=> $countSucCatFilter, 'totalfile'=> $countFileSucCat]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    public function doc_virtual_register($idcat, $idsubcat, $tipo){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $sql= DB::connection('mysql')->select('SELECT descripcion FROM tab_bv_categoria WHERE id=?',[$idcat]);
            $getSubCat= DB::connection('mysql')->table('tab_bv_subcategoria')->where('id_bv_categoria', $idcat)->get();
            return response()->view('Administrador.Documentos.virtual.registrar_docfilevirtual', ['code'=>$idcat, 'categoria'=> $sql, 'idsubcat'=> $idsubcat, 'subcategoria'=> $getSubCat]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function listdoc_virtual_subcat($idcat, $idsubcat, $tipo){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            //$getSubCat= DB::connection('mysql')->table('tab_bv_subcategoria')->where('id_bv_categoria', $idcat)->get();
            /*$getFile= DB::connection('mysql')->table('tab_bv_archivos as tbf')
            ->join('tab_bv_categoria as c', 'tbf.id_bv_categoria', '=', 'c.id')
            ->join('tab_bv_subcategoria as sc', 'tbf.id_bv_subcategoria', '=', 'sc.id')
            ->select('tbf.*', 'c.descripcion as categoria', 'sc.descripcion as subcategoria')
            ->where('tbf.id_bv_subcategoria', $idsubcat)
            ->where('tbf.id_bv_categoria', $idcat)
            ->get();*/

            $getFile= DB::connection('mysql')->table('tab_bv_archivos as tbf')->where('tbf.id_bv_subcategoria', $idsubcat)
            ->where('tbf.id_bv_categoria', $idcat)
            ->get();

            $sqlcat= DB::connection('mysql')->select('SELECT id, descripcion FROM tab_bv_categoria WHERE id=?',[$idcat]);
            $sqlsubcat= DB::connection('mysql')->select('SELECT id, descripcion FROM tab_bv_subcategoria WHERE id=?',[$idsubcat]);

            return response()->view('Administrador.Documentos.virtual.view_listdocsvirtual', ['code'=>$idcat, 'categoria'=> $sqlcat, 
                'idsubcat'=> $idsubcat, 'subcategoria'=> $sqlsubcat, 'archivos'=> $getFile]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function store_doc_bibliovirtual(Request $r){
        if ($r->hasFile('file') ) {
            $filesdocbv  = $r->file('file'); //obtengo el archivo LOTAIP

            $date= now();
            $categoria= $r->idcategoriadoc;
            $subcategoria = $r->subcategoria;
            $aliasfiledbv= $r->inputAliasFileDocBiVir;
            $nombredocbv= $r->inputNameDocBiVir;
            /*$typefile= $r->typefile;
            $lengfile= $r->lengfile;*/

            foreach($filesdocbv as $file){
                $contentfilebv= $file;
                $filenamedf= $file->getClientOriginalName();
                $fileextensionbv= $file->getClientOriginalExtension();
            }

            $newnamebv= $aliasfiledbv.".".$fileextensionbv;

            if($fileextensionbv== $this->validarFile($fileextensionbv)){
                $storepoa= Storage::disk('biblioteca_virtual')->put($newnamebv,  \File::get($contentfilebv));
                if($storepoa){
                    if($subcategoria==0){
                        $sql_insert = DB::connection('mysql')->insert('insert into tab_bv_archivos (
                            id_bv_categoria, titulo, archivo, created_at
                        ) values (?,?,?,?)', [$categoria, $nombredocbv, $newnamebv, $date]);
                    }else{
                        $sql_insert = DB::connection('mysql')->insert('insert into tab_bv_archivos (
                            id_bv_categoria, id_bv_subcategoria, titulo, archivo, created_at
                        ) values (?,?,?,?,?)', [$categoria, $subcategoria, $nombredocbv, $newnamebv, $date]);
                    }
                    
    
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

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA BV SUBCATEGORIA
    public function inactivar_doc_subcategoria(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::connection('mysql')->table('tab_bv_subcategoria')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE OBTIENE NOMBRE DE LA SUBCATEGORÍA
    public function get_namesubcat($id){
        $sql= DB::connection('mysql')->select('SELECT descripcion, estado FROM tab_bv_subcategoria WHERE id=?',[$id]);

        return $sql;
    }

    //FUNCION QUE ACTUALIZA LA SUBCATEGORIA DE LA BIBLIOTECA VIRTUAL
    public function actualizar_subcategoria(Request $r){
        $idsubcategoria= $r->idsubcategoria;
        $subcategoria= $r->subcategoria;
        $date= now();

        $sql_update= DB::connection('mysql')->table('tab_bv_subcategoria')
        ->where('id', $idsubcategoria)
        ->update(['descripcion'=> $subcategoria, 'updated_at'=> $date]);
        
        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA BV ARCHIVOS
    public function inactivar_doc_filesubcategoria(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::connection('mysql')->table('tab_bv_archivos')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE DESPLIEGA LA VISTA PARA EDITAR DOCUMENTO DE LA SUBCATEGORIA
    public function edit_virtual_filesubcat($idf, $opcion, $tipo){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $getFile= DB::connection('mysql')->table('tab_bv_archivos')->where('id', $idf)->get();
            $idcat='';
            $idsubcat='0';

            foreach($getFile as $f){
                $idcat= $f->id_bv_categoria;
                $idsubcat= $f->id_bv_subcategoria;
            }

            $sqlcat= DB::connection('mysql')->select('SELECT id, descripcion FROM tab_bv_categoria WHERE id=?',[$idcat]);
            if($idsubcat!=''){
                $sqlsubcat= DB::connection('mysql')->select('SELECT id, descripcion FROM tab_bv_subcategoria WHERE id=?',[$idsubcat]);
            }else{
                $idsubcat='0';
                $sqlsubcat= array();
            }
            
            return response()->view('Administrador.Documentos.virtual.editar_docfilevirtual', ['idcat'=>$idcat, 'categoria'=> $sqlcat, 
                'idsubcat'=> $idsubcat, 'subcategoria'=> $sqlsubcat, 'idfile'=> $idf, 'archivos'=> $getFile]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTUALIZA LA DOCUMENTACION ADMINISTRATIVA EN LA BASE DE DATOS
    public function update_doc_virtual(Request $r){
        $date= now();
        $idfile= $r->idfilevirtual;
        $tituloDocVirtual= $r->inputNameDocBiVirEdit;
        $aliasfiledocvirtual= $r->inputAliasFileDocBiVirEdit;
        $isDocVirtual= $r->isDocVirtual;

        if($isDocVirtual=="false"){
            if ($r->hasFile('fileEdit')) {
                $filesdocvirtual  = $r->file('fileEdit'); //obtengo el archivo RENDICIONC
                foreach($filesdocvirtual as $file){
                    $contentfiledocvirtual= $file;
                    $filenamedocvirtual= $file->getClientOriginalName();
                    $fileextensiondocvirtual= $file->getClientOriginalExtension();
                }
                $newnamedocvirtual= $aliasfiledocvirtual.".".$fileextensiondocvirtual;

                if(Storage::disk('biblioteca_virtual')->exists($newnamedocvirtual)){
                    Storage::disk('biblioteca_virtual')->delete($newnamedocvirtual);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensiondocvirtual== $this->validarFile($fileextensiondocvirtual)){
                    $storedocvirtual= Storage::disk('biblioteca_virtual')->put($newnamedocvirtual,  \File::get($contentfiledocvirtual));

                    if($storedocvirtual){
                        $sql_update= DB::table('tab_bv_archivos')
                            ->where('id',$idfile)
                            ->update(['titulo'=> $tituloDocVirtual, 'archivo'=> $newnamedocvirtual, 'updated_at'=> $date]);
    
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
            $oldnamefile= $this->name_file_doc($idfile);
            $newnamedocvirtual= $aliasfiledocvirtual.".pdf";
            if(!Storage::disk('biblioteca_virtual')->exists($oldnamefile)){
                Storage::disk('biblioteca_virtual')->move($oldnamefile, $newnamedocvirtual);
            }

            $sql_update= DB::table('tab_bv_archivos')
                ->where('id',$idfile)
                ->update(['titulo'=> $tituloDocVirtual, 'archivo'=> $newnamedocvirtual, 'updated_at'=> $date]);
    
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

        $sql= DB::connection('mysql')->select('SELECT archivo FROM tab_bv_archivos WHERE id=?', [$id]);

        foreach($sql as $s){
            $resultado= $s->archivo;
        }

        return $resultado;
    }

    //FUNCION PARA ELIMINAR DEFINITIVAMENTE EL ARCHIVO
    public function delete_file_oncat(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');

        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_bv_archivos WHERE id=?', [$id]);
        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }

        $subpath = 'documentos/biblioteca_virtual/'.$archivo;
        $path = storage_path('app/'.$subpath);
        Storage::disk('biblioteca_virtual')->delete($archivo);

        $deleted = DB::table('tab_bv_archivos')->where('id', '=', $id)->delete();

        if($deleted){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION PARA DESCARGAR DOC VIRTUAL
    public function download_doc_virtual($idf, $opcion){
        $idf = desencriptarNumero($idf);
        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_bv_archivos WHERE id=?', [$idf]);
        
        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }
        
        $subpath = 'documentos/biblioteca_virtual/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/doc-bibliotecavirtual/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('biblioteca_virtual')->exists($archivo))
        {
            //return Storage::disk('img_banner')->download($url);
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC VIRTUAL
    public function view_doc_filevirtual($idf, $opcion){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $filedocvirtual= DB::connection('mysql')
            ->select('SELECT id_bv_categoria, id_bv_subcategoria, titulo, archivo FROM tab_bv_archivos WHERE id=?', [$idf]);

            return response()->view('Administrador.Documentos.virtual.viewfiledocvirtual', ['filedocvirtual'=> $filedocvirtual]);
        }else{
            return redirect('/loginadmineep');
        }
    }
}
