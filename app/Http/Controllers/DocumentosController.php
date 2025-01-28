<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use File;

class DocumentosController extends Controller
{
    //INDEX PÁGINA PRINCIPAL PAC
    public function pac_index()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $pac= DB::connection('mysql')->table('tab_pac')
            ->join('tab_anio', 'tab_pac.id_anio', '=', 'tab_anio.id')
            ->select('tab_pac.*', 'tab_anio.nombre')
            ->get();

            return response()->view('Administrador.Documentos.pac.pac', ['pac'=> $pac]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //INDEX PÁGINA PRINCIPAL REFORMAS PAC REFORMADO
    public function view_reforma_pac()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $pac= DB::connection('mysql')->table('tab_pac_history')
            ->join('tab_anio', 'tab_pac_history.id_anio', '=', 'tab_anio.id')
            ->select('tab_pac_history.*', 'tab_anio.nombre')
            ->get();
            
            return response()->view('Administrador.Documentos.pac.reforma_pac', ['pac'=> $pac]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION ABRE INTERFAZ PARA REGISTRAR EL PAC
    public function pac_register(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $dateyear= DB::connection('mysql')->table('tab_anio')->orderByDesc('nombre')->get();
            return response()->view('Administrador.Documentos.pac.registrar_pac', ['dateyear'=> $dateyear]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ALMACENA EL PAC EN LA BASE DE DATOS
    public function store_pac(Request $r){
        if ($r->hasFile('file') && $r->hasFile('filera')) {
            $filespac  = $r->file('file'); //obtengo el archivo PAC
            $filesra  = $r->file('filera'); //obtengo el archivo RA

            $date= now();
            $anio = $r->anio;
            $titulo= $r->inputTitulo;
            $aliasfilepac= $r->inputAliasFile;
            $observacion= $r->inputObsr;

            $resadmin= $r->inputResolucion;
            $aliasfilera= $r->inputAliasFileRA;

            $continsert=0;

            if($this->getNamePac($anio)== $anio){
                return response()->json(['resultado'=> 'existe']);
            }else{

            foreach($filespac as $file){
                $contentfilepac= $file;
                $filenamepac= $file->getClientOriginalName();
                $fileextensionpac= $file->getClientOriginalExtension();
            }

            $newnamepac= $aliasfilepac.".".$fileextensionpac;
            /*print_r($filenamepac);
            print_r("<br>");
            print_r($newnamepac);
            print_r("<br>");*/

            if($fileextensionpac== $this->validarFile($fileextensionpac)){
                foreach($filesra as $file){
                    $contentfilera= $file;
                    $filenamera= $file->getClientOriginalName();
                    $fileextensionra= $file->getClientOriginalExtension();
                }
    
                $newnamera= $aliasfilera.".".$fileextensionra;
                /*print_r($filenamera);
                print_r("<br>");
                print_r($newnamera);
                print_r("<br>");*/

                if($fileextensionra== $this->validarFile($fileextensionra)){
                    $storepac= Storage::disk('doc_pac')->put($newnamepac,  \File::get($contentfilepac));
                    $storera= Storage::disk('doc_pac')->put($newnamera,  \File::get($contentfilera));
        
                    if($storepac){
                        $continsert++;
                    }
        
                    if($storera){
                        $continsert++;
                    }
        
                    if($continsert==2){
                        $sql_insert = DB::connection('mysql')->insert('insert into tab_pac (
                            id_anio, titulo, observacion, archivo, resol_admin, archivo_resoladmin,
                            created_at
                        ) values (?,?,?,?,?,?,?)', [$anio, $titulo, $observacion, $newnamepac, $resadmin, $newnamera, $date]);

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
                return response()->json(['resultado'=> 'nofile']);
            }

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

    private function getNamePac($year){
        $sql= DB::connection('mysql')->select('SELECT id_anio FROM tab_pac WHERE id_anio=?', [$year]);

        $resultado= "";

        foreach($sql as $r){
            $resultado= $r->id_anio;
        }

        return $resultado;
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC PAC
    public function view_pac($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            //$filepac= DB::connection('mysql')->select('SELECT archivo, archivo_resoladmin FROM tab_pac WHERE id=?', [$id]);
            $filepac= DB::connection('mysql')->table('tab_pac')
            ->join('tab_anio', 'tab_pac.id_anio','=', 'tab_anio.id')
            ->select('tab_pac.*', 'tab_anio.nombre')
            ->where('tab_pac.id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.pac.viewpac', ['filepac'=> $filepac]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC PAC REFORMADO
    public function view_pac_reformado($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            //$filepac= DB::connection('mysql')->select('SELECT archivo, archivo_resoladmin FROM tab_pac WHERE id=?', [$id]);
            $filepac= DB::connection('mysql')->table('tab_pac_history')
            ->join('tab_anio', 'tab_pac_history.id_anio','=', 'tab_anio.id')
            ->select('tab_pac_history.*', 'tab_anio.nombre')
            ->where('tab_pac_history.id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.pac.view_reformas.viewrpac', ['filepac'=> $filepac, 'code'=> $id]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR EL PAC
    public function edit_pac($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $dateyear= DB::connection('mysql')->table('tab_anio')->orderByDesc('nombre')->get();
            $filepac= DB::connection('mysql')->table('tab_pac')
            ->join('tab_anio', 'tab_pac.id_anio','=', 'tab_anio.id')
            ->select('tab_pac.*', 'tab_anio.nombre')
            ->where('tab_pac.id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.pac.editar_pac', ['filepac'=> $filepac, 'dateyear'=> $dateyear]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR EL PAC REFORMADO
    public function edit_ref_pac($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $dateyear= DB::connection('mysql')->table('tab_anio')->orderByDesc('nombre')->get();
            $filepac= DB::connection('mysql')->table('tab_pac_history')
            ->join('tab_anio', 'tab_pac_history.id_anio','=', 'tab_anio.id')
            ->select('tab_pac_history.*', 'tab_anio.nombre')
            ->where('tab_pac_history.id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.pac.view_reformas.editar_rpac', ['filepac'=> $filepac, 'dateyear'=> $dateyear, 'code'=> $id]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA PAC
    public function inactivar_pac(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $tipo= $request->input('tipo');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        if($tipo=='noref'){
            $sql_update= DB::table('tab_pac')
                    ->where('id', $id)
                    ->update(['estado' => $estado, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }else if($tipo=='ref'){
            $sql_update= DB::table('tab_pac_history')
                    ->where('id', $id)
                    ->update(['estado' => $estado, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }
    }

    //FUNCION PARA DESCARGAR EL PAC
    public function download_pac($id, $tipo){
        if($tipo=='noref'){
            $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_pac WHERE id=?', [$id]);
        }else if($tipo=='ref'){
            $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_pac_history WHERE id=?', [$id]);
        }

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }

        $subpath = 'documentos/pac/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/doc-pac/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('doc_pac')->exists($archivo))
        {
            //return Storage::disk('img_banner')->download($url);
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

    //FUNCION PARA DESCARGAR EL RA
    public function download_ra($id, $tipo){
        if($tipo=='noref'){
            $sql_dato= DB::connection('mysql')->select('SELECT archivo_resoladmin FROM tab_pac WHERE id=?', [$id]);
        }else if($tipo=='ref'){
            $sql_dato= DB::connection('mysql')->select('SELECT archivo_resoladmin FROM tab_pac_history WHERE id=?', [$id]);
        }

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo_resoladmin;
        }

        $subpath = 'documentos/pac/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/doc-pac/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('doc_pac')->exists($archivo))
        {
            //return Storage::disk('img_banner')->download($url);
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

    //FUNCION QUE ACTUALIZA EL PAC EN LA BASE DE DATOS
    public function update_pac(Request $r){
        $date= now();
        $id= $r->idpac;
        $anio = $r->anio;
        $titulo= $r->inputETitulo;
        $aliasfilepac= $r->inputEAliasFile;
        $observacion= $r->inputEObsr;
        $resadmin= $r->inputEResolucion;
        $aliasfilera= $r->inputEAliasFileRA;
        $ispac= $r->ispac;
        $isra= $r->isra;
        $isreforma= $r->isReforma;
        $continsert=0;

        if($isreforma=="true"){
            $oldnamefilepac=''; $oldnamefileresolpac='';
            $newnamedocpac=''; $newnamedocresolpac='';
            $selectOr= DB::connection('mysql')->select('SELECT * FROM tab_pac WHERE id=?', [$id]);

            foreach($selectOr as $it){
                $id_anio= $it->id_anio;
                $titulopac= $it->titulo;
                $observacionpac= $it->observacion;
                $archivo= $it->archivo;
                $resol_admin= $it->resol_admin;
                $archivo_resoladmin= $it->archivo_resoladmin;
                $oldnamefilepac= $archivo;
                $oldnamefileresolpac= $archivo_resoladmin;
            }

            //$setdate= $date->toDateTimeString();
            /*
            echo $dt->toDateString();                          // 1975-12-25
            echo $dt->toFormattedDateString();                 // Dec 25, 1975
            echo $dt->toTimeString();                          // 14:15:16
            echo $dt->toDateTimeString();                      // 1975-12-25 14:15:16
            echo $dt->toDayDateTimeString(); 
            */
            $setdate= $date->toDateString();
            $setdate= str_replace('-','_', $setdate);
            //$setdate= str_replace(':','_', $setdate);

            $newnamedocpac= substr($oldnamefilepac,0,-4).'_res_'.$setdate.".pdf";
            $newnamedocresolpac= substr($oldnamefileresolpac,0,-4).'_res_'.$setdate.".pdf";
            
            if(Storage::disk('doc_pac')->exists($oldnamefilepac)){
                Storage::disk('doc_pac')->copy($oldnamefilepac, $newnamedocpac);
            }

            if(Storage::disk('doc_pac')->exists($oldnamefileresolpac)){
                Storage::disk('doc_pac')->copy($oldnamefileresolpac, $newnamedocresolpac);
            }

            $sql_insert = DB::connection('mysql')->insert('insert into tab_pac_history (
                id_pac, id_anio, titulo, observacion, archivo, resol_admin, archivo_resoladmin,
                created_at
            ) values (?,?,?,?,?,?,?,?)', [$id, $id_anio, $titulopac, $observacionpac, $newnamedocpac, $resol_admin, $newnamedocresolpac, $date]);
        }

        if($ispac=="false" && $isra=="false"){
           //print_r('AMBOS FALSOS');
            if ($r->hasFile('fileEdit') && $r->hasFile('fileEra')) {
                $filespac  = $r->file('fileEdit'); //obtengo el archivo PAC
                $filesra  = $r->file('fileEra'); //obtengo el archivo RA

                foreach($filespac as $file){
                    $contentfilepac= $file;
                    $filenamepac= $file->getClientOriginalName();
                    $fileextensionpac= $file->getClientOriginalExtension();
                }

                $newnamepac= $aliasfilepac.".".$fileextensionpac;

                if(Storage::disk('doc_pac')->exists($newnamepac)){
                    Storage::disk('doc_pac')->delete($newnamepac);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensionpac== $this->validarFile($fileextensionpac)){
                    foreach($filesra as $file){
                        $contentfilera= $file;
                        $filenamera= $file->getClientOriginalName();
                        $fileextensionra= $file->getClientOriginalExtension();
                    }
        
                    $newnamera= $aliasfilera.".".$fileextensionra;

                    if(Storage::disk('doc_pac')->exists($newnamera)){
                        Storage::disk('doc_pac')->delete($newnamera);
                        /*
                            Delete Multiple files this way
                            Storage::delete(['upload/test.png', 'upload/test2.png']);
                        */
                    }
    
                    if($fileextensionra== $this->validarFile($fileextensionra)){
                        $storepac= Storage::disk('doc_pac')->put($newnamepac,  \File::get($contentfilepac));
                        $storera= Storage::disk('doc_pac')->put($newnamera,  \File::get($contentfilera));
            
                        if($storepac){
                            $continsert++;
                        }
            
                        if($storera){
                            $continsert++;
                        }
            
                        if($continsert==2){
                            $sql_update= DB::table('tab_pac')
                            ->where('id', $id)
                            ->update(['id_anio'=> $anio, 'titulo'=> $titulo, 'observacion' => $observacion, 'archivo'=> $newnamepac, 
                                'resol_admin'=> $resadmin, 'archivo_resoladmin' => $newnamera, 'updated_at'=> $date]);
    
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
                }else{
                    return response()->json(['resultado'=> 'nofile']);
                }
            }
        }else if($ispac=="false" && $isra=="true"){
            //print_r('ISPAC FALSO');
            if ($r->hasFile('fileEdit')) {
                $filespac  = $r->file('fileEdit'); //obtengo el archivo PAC

                foreach($filespac as $file){
                    $contentfilepac= $file;
                    $filenamepac= $file->getClientOriginalName();
                    $fileextensionpac= $file->getClientOriginalExtension();
                }

                $newnamepac= $aliasfilepac.".".$fileextensionpac;

                if(Storage::disk('doc_pac')->exists($newnamepac)){
                    Storage::disk('doc_pac')->delete($newnamepac);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensionpac== $this->validarFile($fileextensionpac)){
                    $storepac= Storage::disk('doc_pac')->put($newnamepac,  \File::get($contentfilepac));
            
                    if($storepac){
                        $continsert++;
                    }

                    if($continsert==1){
                        $sql_update= DB::table('tab_pac')
                        ->where('id', $id)
                        ->update(['id_anio'=> $anio, 'titulo'=> $titulo, 'observacion' => $observacion, 'archivo'=> $newnamepac, 
                            'resol_admin'=> $resadmin, 'updated_at'=> $date]);

                        if($sql_update){
                            return response()->json(["resultado"=> true]);
                        }else{
                            return response()->json(["resultado"=> false]);
                        }
                    }else{
                        return response()->json(["resultado"=> false]);
                    }
                }
            }
        }else if($ispac=="true" && $isra=="false"){
            //print_r('ISRA FALSO');
            if ($r->hasFile('fileEra')) {
                $filesra  = $r->file('fileEra'); //obtengo el archivo RA
                foreach($filesra as $file){
                    $contentfilera= $file;
                    $filenamera= $file->getClientOriginalName();
                    $fileextensionra= $file->getClientOriginalExtension();
                }
                $newnamera= $aliasfilera.".".$fileextensionra;

                if(Storage::disk('doc_pac')->exists($newnamera)){
                    Storage::disk('doc_pac')->delete($newnamera);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }
    
                if($fileextensionra== $this->validarFile($fileextensionra)){
                    $storera= Storage::disk('doc_pac')->put($newnamera,  \File::get($contentfilera));

                    if($storera){
                        $continsert++;
                    }

                    if($continsert==1){
                        $sql_update= DB::table('tab_pac')
                        ->where('id', $id)
                        ->update(['id_anio'=> $anio, 'titulo'=> $titulo, 'observacion' => $observacion, 
                            'resol_admin'=> $resadmin, 'archivo_resoladmin' => $newnamera, 'updated_at'=> $date]);

                        if($sql_update){
                            return response()->json(["resultado"=> true]);
                        }else{
                            return response()->json(["resultado"=> false]);
                        }
                    }else{
                        return response()->json(["resultado"=> false]);
                    }
                }
            }
        }else if($ispac=="true" && $isra=="true"){
            //print_r('AMBOS VERDADEROS');
            $sql_update= DB::table('tab_pac')
                ->where('id', $id)
                ->update(['id_anio'=> $anio, 'titulo'=> $titulo, 'observacion' => $observacion,  
                    'resol_admin'=> $resadmin, 'updated_at'=> $date]);
    
            if($sql_update){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
    }

    //FUNCION QUE ACTUALIZA EL PAC REFORMADO EN LA BASE DE DATOS
    public function update_ref_pac(Request $r){
        $date= now();
        $id= $r->idpac;
        $anio = $r->anio;
        $titulo= $r->inputETitulo;
        $aliasfilepac= $r->inputEAliasFile;
        $observacion= $r->inputEObsr;
        $resadmin= $r->inputEResolucion;
        $aliasfilera= $r->inputEAliasFileRA;
        $ispac= $r->ispac;
        $isra= $r->isra;
        $continsert=0;

        if($ispac=="false" && $isra=="false"){
           //print_r('AMBOS FALSOS');
            if ($r->hasFile('fileEdit') && $r->hasFile('fileEra')) {
                $filespac  = $r->file('fileEdit'); //obtengo el archivo PAC
                $filesra  = $r->file('fileEra'); //obtengo el archivo RA

                foreach($filespac as $file){
                    $contentfilepac= $file;
                    $filenamepac= $file->getClientOriginalName();
                    $fileextensionpac= $file->getClientOriginalExtension();
                }

                $newnamepac= $aliasfilepac.".".$fileextensionpac;

                if(Storage::disk('doc_pac')->exists($newnamepac)){
                    Storage::disk('doc_pac')->delete($newnamepac);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensionpac== $this->validarFile($fileextensionpac)){
                    foreach($filesra as $file){
                        $contentfilera= $file;
                        $filenamera= $file->getClientOriginalName();
                        $fileextensionra= $file->getClientOriginalExtension();
                    }
        
                    $newnamera= $aliasfilera.".".$fileextensionra;

                    if(Storage::disk('doc_pac')->exists($newnamera)){
                        Storage::disk('doc_pac')->delete($newnamera);
                        /*
                            Delete Multiple files this way
                            Storage::delete(['upload/test.png', 'upload/test2.png']);
                        */
                    }
    
                    if($fileextensionra== $this->validarFile($fileextensionra)){
                        $storepac= Storage::disk('doc_pac')->put($newnamepac,  \File::get($contentfilepac));
                        $storera= Storage::disk('doc_pac')->put($newnamera,  \File::get($contentfilera));
            
                        if($storepac){
                            $continsert++;
                        }
            
                        if($storera){
                            $continsert++;
                        }
            
                        if($continsert==2){
                            $sql_update= DB::table('tab_pac_history')
                            ->where('id', $id)
                            ->update(['id_anio'=> $anio, 'titulo'=> $titulo, 'observacion' => $observacion, 'archivo'=> $newnamepac, 
                                'resol_admin'=> $resadmin, 'archivo_resoladmin' => $newnamera, 'updated_at'=> $date]);
    
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
                }else{
                    return response()->json(['resultado'=> 'nofile']);
                }
            }
        }else if($ispac=="false" && $isra=="true"){
            //print_r('ISPAC FALSO');
            if ($r->hasFile('fileEdit')) {
                $filespac  = $r->file('fileEdit'); //obtengo el archivo PAC

                foreach($filespac as $file){
                    $contentfilepac= $file;
                    $filenamepac= $file->getClientOriginalName();
                    $fileextensionpac= $file->getClientOriginalExtension();
                }

                $newnamepac= $aliasfilepac.".".$fileextensionpac;

                if(Storage::disk('doc_pac')->exists($newnamepac)){
                    Storage::disk('doc_pac')->delete($newnamepac);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensionpac== $this->validarFile($fileextensionpac)){
                    $storepac= Storage::disk('doc_pac')->put($newnamepac,  \File::get($contentfilepac));
            
                    if($storepac){
                        $continsert++;
                    }

                    if($continsert==1){
                        $sql_update= DB::table('tab_pac_history')
                        ->where('id', $id)
                        ->update(['id_anio'=> $anio, 'titulo'=> $titulo, 'observacion' => $observacion, 'archivo'=> $newnamepac, 
                            'resol_admin'=> $resadmin, 'updated_at'=> $date]);

                        if($sql_update){
                            return response()->json(["resultado"=> true]);
                        }else{
                            return response()->json(["resultado"=> false]);
                        }
                    }else{
                        return response()->json(["resultado"=> false]);
                    }
                }
            }
        }else if($ispac=="true" && $isra=="false"){
            //print_r('ISRA FALSO');
            if ($r->hasFile('fileEra')) {
                $filesra  = $r->file('fileEra'); //obtengo el archivo RA
                foreach($filesra as $file){
                    $contentfilera= $file;
                    $filenamera= $file->getClientOriginalName();
                    $fileextensionra= $file->getClientOriginalExtension();
                }
                $newnamera= $aliasfilera.".".$fileextensionra;

                if(Storage::disk('doc_pac')->exists($newnamera)){
                    Storage::disk('doc_pac')->delete($newnamera);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }
    
                if($fileextensionra== $this->validarFile($fileextensionra)){
                    $storera= Storage::disk('doc_pac')->put($newnamera,  \File::get($contentfilera));

                    if($storera){
                        $continsert++;
                    }

                    if($continsert==1){
                        $sql_update= DB::table('tab_pac_history')
                        ->where('id', $id)
                        ->update(['id_anio'=> $anio, 'titulo'=> $titulo, 'observacion' => $observacion, 
                            'resol_admin'=> $resadmin, 'archivo_resoladmin' => $newnamera, 'updated_at'=> $date]);

                        if($sql_update){
                            return response()->json(["resultado"=> true]);
                        }else{
                            return response()->json(["resultado"=> false]);
                        }
                    }else{
                        return response()->json(["resultado"=> false]);
                    }
                }
            }
        }else if($ispac=="true" && $isra=="true"){
            //print_r('AMBOS VERDADEROS');
            $sql_update= DB::table('tab_pac_history')
                ->where('id', $id)
                ->update(['id_anio'=> $anio, 'titulo'=> $titulo, 'observacion' => $observacion,  
                    'resol_admin'=> $resadmin, 'updated_at'=> $date]);
    
            if($sql_update){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
    }

    /*--------------------------------------------------------------------------------------------------*/
    //POA
    /*--------------------------------------------------------------------------------------------------*/
    //INDEX PÁGINA PRINCIPAL POA
    public function poa_index()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            /*$poa= DB::connection('mysql')->table('tab_poa')
            ->join('tab_anio', 'tab_poa.id_anio', '=', 'tab_anio.id')
            ->join('tab_direccion_dep', 'tab_poa.id_area','=', 'tab_direccion_dep.id')
            ->select('tab_poa.*', 'tab_anio.nombre as year', 'tab_direccion_dep.nombre as area')
            ->get();
            return response()->view('Administrador.Documentos.poa.poa', ['poa'=> $poa]);*/
            $poa= DB::connection('mysql')->select('SELECT p.*, y.nombre as year FROM tab_poa p, tab_anio y WHERE p.id_anio=y.id ORDER BY p.id_anio DESC');
            $direccion = DB::table('tab_direccion_dep')->where('estado','1')->get();
            return response()->view('Administrador.Documentos.poa.poa', ['poa'=> $poa, 'direccion'=> $direccion]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //INDEX PÁGINA PRINCIPAL POA REFORMADO
    public function view_reforma_poa()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $poa= DB::connection('mysql')->select('SELECT p.*, y.nombre as year FROM tab_poa_history p, tab_anio y WHERE p.id_anio=y.id ORDER BY p.id_anio DESC');
            $direccion = DB::table('tab_direccion_dep')->where('estado','1')->get();
            return response()->view('Administrador.Documentos.poa.reforma_poa', ['poa'=> $poa, 'direccion'=> $direccion]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION ABRE INTERFAZ PARA REGISTRAR EL POA
    public function poa_register(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $dateyear= DB::connection('mysql')->table('tab_anio')->orderByDesc('nombre')->get();

            $gerencia= DB::table('tab_gerencia_dep')->where('estado','1')->get();
            $direccion = DB::table('tab_direccion_dep')->where('estado','1')->get();
            $array_resultado= array();
            foreach($gerencia as $data){
                $array_resultado[] = array('id'=> $data->id, 'nombre' => $data->nombre, 'tipo'=> 'gerencia');
            }

            foreach($direccion as $data){
                $array_resultado[] = array('id'=> $data->id, 'nombre' => $data->nombre, 'tipo'=> 'direccion');
            }

            return response()->view('Administrador.Documentos.poa.registrar_poa', ['dateyear'=> $dateyear, 'area'=> $array_resultado]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ALMACENA EL POA EN LA BASE DE DATOS
    public function store_poa(Request $r){
        if ($r->hasFile('file') ) {
            $filespoa  = $r->file('file'); //obtengo el archivo POA

            $date= now();
            $anio = $r->anio;
            $area=  $r->area;
            $titulo= $r->inputTitulo;
            $aliasfilepac= $r->inputAliasFile;
            $observacion= $r->inputObsr;
            $seltipo= $r->seltipo;
            $resarr= array();
            $resarr= $this->getNamePoa($anio);

            if($anio==$this->getNamePoa($anio)){
                return response()->json(['resultado'=> 'existe']);
            }else{
                //NO HAY INFORMACIÓN
                foreach($filespoa as $file){
                    $contentfilepoa= $file;
                    $filenamepoa= $file->getClientOriginalName();
                    $fileextensionpoa= $file->getClientOriginalExtension();
                }
    
                $newnamepoa= $aliasfilepac.".".$fileextensionpoa;
    
                if($fileextensionpoa== $this->validarFile($fileextensionpoa)){
                    $storepoa= Storage::disk('doc_poa')->put($newnamepoa,  \File::get($contentfilepoa));
                    if($storepoa){
                        $sql_insert = DB::connection('mysql')->insert('insert into tab_poa (
                            id_anio, tipo_sel, titulo, archivo, observacion, created_at
                        ) values (?,?,?,?,?,?)', [$anio, $seltipo, $titulo, $newnamepoa, $observacion, $date]);
        
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

    public function store_poa_original(Request $r){
        if ($r->hasFile('file') ) {
            $filespoa  = $r->file('file'); //obtengo el archivo POA

            $date= now();
            $anio = $r->anio;
            $area=  $r->area;
            $titulo= $r->inputTitulo;
            $aliasfilepac= $r->inputAliasFile;
            $observacion= $r->inputObsr;
            $seltipo= $r->seltipo;
            $resarr= array();
            $resarr= $this->getNamePoa($anio);

            if($anio==$resarr[0]){
                $tipobd= $resarr[1];
                if($seltipo!=$tipobd){
                    return response()->json(['resultado'=> 'diferente']);
                }else if($seltipo==$tipobd){
                    if($seltipo=="general"){
                        return response()->json(['resultado'=> 'onetime']);
                    }else if($seltipo=="area"){
                        $idareabd= $resarr[2];
                        if($idareabd== $area){
                            return response()->json(['resultado'=> 'areaexist']);
                        }else{
                            //INSERTAR INFO
                            foreach($filespoa as $file){
                                $contentfilepoa= $file;
                                $filenamepoa= $file->getClientOriginalName();
                                $fileextensionpoa= $file->getClientOriginalExtension();
                            }

                            $newnamepoa= $aliasfilepac.".".$fileextensionpoa;

                            if($fileextensionpoa== $this->validarFile($fileextensionpoa)){
                                if($seltipo=="area"){
                                    $storepoa= Storage::disk('doc_poa')->put($newnamepoa,  \File::get($contentfilepoa));
                                    if($storepoa){
                                        $sql_insert = DB::connection('mysql')->insert('insert into tab_poa (
                                            id_anio, id_area, tipo_sel, titulo, archivo, observacion, created_at
                                        ) values (?,?,?,?,?,?,?)', [$anio, $area, $seltipo, $titulo, $newnamepoa, $observacion, $date]);
                    
                                        if($sql_insert){
                                            return response()->json(["resultado"=> true]);
                                        }else{
                                            return response()->json(["resultado"=> false]);
                                        }
                                    }else{
                                        return response()->json(["resultado"=> false]);
                                    }
                                }else if($seltipo=="general"){
                                    $storepoa= Storage::disk('doc_poa')->put($newnamepoa,  \File::get($contentfilepoa));
                                    if($storepoa){
                                        $sql_insert = DB::connection('mysql')->insert('insert into tab_poa (
                                            id_anio, tipo_sel, titulo, archivo, observacion, created_at
                                        ) values (?,?,?,?,?,?)', [$anio, $seltipo, $titulo, $newnamepoa, $observacion, $date]);
                    
                                        if($sql_insert){
                                            return response()->json(["resultado"=> true]);
                                        }else{
                                            return response()->json(["resultado"=> false]);
                                        }
                                    }else{
                                        return response()->json(["resultado"=> false]);
                                    }
                                }
                            }else{
                                return response()->json(['resultado'=> 'nofile']);
                            }
                        }
                    }
                }
            }else{
                //NO HAY INFORMACIÓN
                foreach($filespoa as $file){
                    $contentfilepoa= $file;
                    $filenamepoa= $file->getClientOriginalName();
                    $fileextensionpoa= $file->getClientOriginalExtension();
                }
    
                $newnamepoa= $aliasfilepac.".".$fileextensionpoa;
    
                if($fileextensionpoa== $this->validarFile($fileextensionpoa)){
                    if($seltipo=="area"){
                        $storepoa= Storage::disk('doc_poa')->put($newnamepoa,  \File::get($contentfilepoa));
                        if($storepoa){
                            $sql_insert = DB::connection('mysql')->insert('insert into tab_poa (
                                id_anio, id_area, tipo_sel, titulo, archivo, observacion, created_at
                            ) values (?,?,?,?,?,?,?)', [$anio, $area, $seltipo, $titulo, $newnamepoa, $observacion, $date]);
        
                            if($sql_insert){
                                return response()->json(["resultado"=> true]);
                            }else{
                                return response()->json(["resultado"=> false]);
                            }
                        }else{
                            return response()->json(["resultado"=> false]);
                        }
                    }else if($seltipo=="general"){
                        $storepoa= Storage::disk('doc_poa')->put($newnamepoa,  \File::get($contentfilepoa));
                        if($storepoa){
                            $sql_insert = DB::connection('mysql')->insert('insert into tab_poa (
                                id_anio, tipo_sel, titulo, archivo, observacion, created_at
                            ) values (?,?,?,?,?,?)', [$anio, $seltipo, $titulo, $newnamepoa, $observacion, $date]);
        
                            if($sql_insert){
                                return response()->json(["resultado"=> true]);
                            }else{
                                return response()->json(["resultado"=> false]);
                            }
                        }else{
                            return response()->json(["resultado"=> false]);
                        }
                    }
                }else{
                    return response()->json(['resultado'=> 'nofile']);
                }
            }
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    private function getNamePoa($year){
        $sql= DB::connection('mysql')->select('SELECT id_anio FROM tab_poa WHERE id_anio=?', [$year]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->id_anio;
        }

        return $resultado;
    }

    private function getNameFilePoa($year){
        $sql= DB::connection('mysql')->select('SELECT archivo FROM tab_poa WHERE id_anio=?', [$year]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->archivo;
        }

        return $resultado;
    }

    private function getNamePoa_original($year){
        $maxId = DB::table('tab_poa')->where('id_anio','=', $year)->max('id');
        $sql= DB::connection('mysql')->select('SELECT id_anio, tipo_sel, id_area FROM tab_poa WHERE id=?', [$maxId]);

        $resultado= array();
        $anio=''; $sel=''; $id_area='';

        foreach($sql as $r){
            $anio= $r->id_anio;
            $sel= $r->tipo_sel;
            $id_area= $r->id_area;
        }

        array_push($resultado, $anio, $sel, $id_area);

        return $resultado;
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC POA
    public function view_poa($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $filepoa= DB::connection('mysql')->table('tab_poa')
            ->join('tab_anio', 'tab_poa.id_anio','=', 'tab_anio.id')
            ->select('tab_poa.*', 'tab_anio.nombre')
            ->where('tab_poa.id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.poa.viewpoa', ['filepoa'=> $filepoa]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC POA REFORMADO
    public function view_poa_reformado($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $filepoa= DB::connection('mysql')->table('tab_poa_history')
            ->join('tab_anio', 'tab_poa_history.id_anio','=', 'tab_anio.id')
            ->select('tab_poa_history.*', 'tab_anio.nombre')
            ->where('tab_poa_history.id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.poa.view_reformas.viewpoa', ['filepoa'=> $filepoa, 'code'=> $id]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR EL POA
    public function edit_poa($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $dateyear= DB::connection('mysql')->table('tab_anio')->orderByDesc('nombre')->get();
            $filepoa= DB::connection('mysql')->table('tab_poa')
            ->join('tab_anio', 'tab_poa.id_anio','=', 'tab_anio.id')
            ->select('tab_poa.*', 'tab_anio.nombre')
            ->where('tab_poa.id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.poa.editar_poa', ['filepoa'=> $filepoa, 'dateyear'=> $dateyear]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA POA
    public function inactivar_poa(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $tipo= $request->input('tipo');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);
        if($tipo=='noref'){
            $sql_update= DB::table('tab_poa')
                    ->where('id', $id)
                    ->update(['estado' => $estado, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }else if($tipo=='ref'){
            $sql_update= DB::table('tab_poa_history')
                    ->where('id', $id)
                    ->update(['estado' => $estado, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR EL POA REFORMADO
    public function edit_ref_poa($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $dateyear= DB::connection('mysql')->table('tab_anio')->orderByDesc('nombre')->get();
            $filepoa= DB::connection('mysql')->table('tab_poa_history')
            ->join('tab_anio', 'tab_poa_history.id_anio','=', 'tab_anio.id')
            ->select('tab_poa_history.*', 'tab_anio.nombre')
            ->where('tab_poa_history.id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.poa.view_reformas.editar_rpoa', ['filepoa'=> $filepoa, 'dateyear'=> $dateyear, 'code'=> $id]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION PARA DESCARGAR EL POA
    public function download_poa($id, $tipo){
        if($tipo=='noref'){
            $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_poa WHERE id=?', [$id]);
        }else if($tipo=='ref'){
            $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_poa_history WHERE id=?', [$id]);
        }

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }
        
        $subpath = 'documentos/poa/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/doc-poa/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('doc_poa')->exists($archivo))
        {
            //return Storage::disk('img_banner')->download($url);
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

    //FUNCION QUE ACTUALIZA EL POA EN LA BASE DE DATOS
    public function update_poa(Request $r){
        $date= now();
        $id= $r->idpoa;
        $anio = $r->anio;
        $titulo= $r->inputETitulo;
        $aliasfilepoa= $r->inputEAliasFile;
        $observacion= $r->inputEObsr;
        $isreforma= $r->isReforma;
        $ispoa= $r->ispoa;

        if($isreforma=="true"){
            $oldnamefilepoa='';
            $newnamedocpoa='';
            $selectOr= DB::connection('mysql')->select('SELECT * FROM tab_poa WHERE id=?', [$id]);

            foreach($selectOr as $it){
                $id_anio= $it->id_anio;
                $id_area= $it->id_area;
                $tipo_sel= $it->tipo_sel;
                $titulopoa= $it->titulo;
                $observacionpoa= $it->observacion;
                $archivo= $it->archivo;
                $oldnamefilepoa= $archivo;
            }

            $setdate= $date->toDateTimeString();
            /*
            echo $dt->toDateString();                          // 1975-12-25
            echo $dt->toFormattedDateString();                 // Dec 25, 1975
            echo $dt->toTimeString();                          // 14:15:16
            echo $dt->toDateTimeString();                      // 1975-12-25 14:15:16
            echo $dt->toDayDateTimeString(); 
            */
            //$setdate= $date->toDateString();
            $setdate= str_replace(' ','_', $setdate);
            $setdate= str_replace('-','', $setdate);
            $setdate= str_replace(':','', $setdate);

            $newnamedocpoa= substr($oldnamefilepoa,0,-4).'_res_'.$setdate.".pdf";
            
            if(Storage::disk('doc_poa')->exists($oldnamefilepoa)){
                /*if(Storage::disk('doc_poa')->exists($newnamedocpoa)){
                    return 'existe doc res';
                }*/
                Storage::disk('doc_poa')->copy($oldnamefilepoa, $newnamedocpoa);
            }

            $sql_insert = DB::connection('mysql')->insert('insert into tab_poa_history (
                id_poa, id_anio, tipo_sel, titulo, archivo, observacion, created_at
            ) values (?,?,?,?,?,?,?)', [$id, $id_anio, $tipo_sel, $titulopoa, $newnamedocpoa, $observacionpoa, $date]);
        }

        if($ispoa=="false"){
            if ($r->hasFile('fileEdit')) {
                $filespoa  = $r->file('fileEdit'); //obtengo el archivo POA
                foreach($filespoa as $file){
                    $contentfilepoa= $file;
                    $filenamepoa= $file->getClientOriginalName();
                    $fileextensionpoa= $file->getClientOriginalExtension();
                }
                $newnamepoa= $aliasfilepoa.".".$fileextensionpoa;

                if(Storage::disk('doc_poa')->exists($newnamepoa)){
                    Storage::disk('doc_poa')->delete($newnamepoa);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensionpoa== $this->validarFile($fileextensionpoa)){
                    $storepoa= Storage::disk('doc_poa')->put($newnamepoa,  \File::get($contentfilepoa));

                    if($storepoa){
                        $sql_update= DB::table('tab_poa')
                            ->where('id', $id)
                            ->update(['id_anio'=> $anio, 'titulo'=> $titulo, 'observacion' => $observacion, 'archivo'=> $newnamepoa, 
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
            $namefile= $this->getNameFilePoa($anio);
            $newnamepoa= $aliasfilepoa.".pdf";
            //$storepoa= Storage::disk('doc_poa')->copy($namefile,  $newnamepoa);
            /*if(Storage::disk('doc_poa')->exists($namefile)){
                $storepoa= Storage::disk('doc_poa')->move($namefile,  $newnamepoa);
            }else{
                $storepoa= Storage::disk('doc_poa')->move($namefile,  $newnamepoa);
            }*/

            if(!Storage::disk('doc_poa')->exists($newnamepoa)){
                $storepoa= Storage::disk('doc_poa')->move($namefile,  $newnamepoa);
            }else{
                $storepoa= true;
            }
            
            if($storepoa){
                $sql_update= DB::table('tab_poa')
                    ->where('id', $id)
                    ->update(['id_anio'=> $anio, 'titulo'=> $titulo, 'archivo'=> $newnamepoa, 'observacion' => $observacion,  
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

    //FUNCION QUE ACTUALIZA EL POA REFORMADO EN LA BASE DE DATOS
    public function update_ref_poa(Request $r){
        $date= now();
        $id= $r->idpoa;
        $anio = $r->anio;
        $titulo= $r->inputETitulo;
        $aliasfilepoa= $r->inputEAliasFile;
        $observacion= $r->inputEObsr;
        $ispoa= $r->ispoa;

        if($ispoa=="false"){
            if ($r->hasFile('fileEdit')) {
                $filespoa  = $r->file('fileEdit'); //obtengo el archivo POA
                foreach($filespoa as $file){
                    $contentfilepoa= $file;
                    $filenamepoa= $file->getClientOriginalName();
                    $fileextensionpoa= $file->getClientOriginalExtension();
                }
                $newnamepoa= $aliasfilepoa.".".$fileextensionpoa;

                if(Storage::disk('doc_poa')->exists($newnamepoa)){
                    Storage::disk('doc_poa')->delete($newnamepoa);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensionpoa== $this->validarFile($fileextensionpoa)){
                    $storepoa= Storage::disk('doc_poa')->put($newnamepoa,  \File::get($contentfilepoa));

                    if($storepoa){
                        $sql_update= DB::table('tab_poa_history')
                            ->where('id', $id)
                            ->update(['id_anio'=> $anio, 'titulo'=> $titulo, 'observacion' => $observacion, 'archivo'=> $newnamepoa, 
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
            $namefile= $this->getNameFilePoa($anio);
            $newnamepoa= $aliasfilepoa.".pdf";
            //$storepoa= Storage::disk('doc_poa')->copy($namefile,  $newnamepoa);
            /*if(Storage::disk('doc_poa')->exists($namefile)){
                $storepoa= Storage::disk('doc_poa')->move($namefile,  $newnamepoa);
            }else{
                $storepoa= Storage::disk('doc_poa')->move($namefile,  $newnamepoa);
            }*/

            if(!Storage::disk('doc_poa')->exists($newnamepoa)){
                $storepoa= Storage::disk('doc_poa')->move($namefile,  $newnamepoa);
            }else{
                $storepoa= true;
            }
            
            if($storepoa){
                $sql_update= DB::table('tab_poa_history')
                    ->where('id', $id)
                    ->update(['id_anio'=> $anio, 'titulo'=> $titulo, 'archivo'=> $newnamepoa, 'observacion' => $observacion,  
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


    /*--------------------------------------------------------------------------------------------------*/
    //PROCESO CONTRATACION
    /*--------------------------------------------------------------------------------------------------*/
    //INDEX PÁGINA PRINCIPAL PROCESO CONTRATACION
    public function procesoc_index()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $procesoc = DB::table('tab_proceso_contratacion')->where('estado','1')->get();
            return response()->view('Administrador.Procesoc.procesoc', ['procesoc'=> $procesoc]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    function store_proceso(Request $r){
        $nombre= $r->nombre;
        $enlace= $r->enlace;
        $date= now();

        $sql_insert= DB::connection('mysql')->insert('INSERT INTO tab_proceso_contratacion (nombre, enlace, created_at) 
            VALUES (?,?,?)', [$nombre, $enlace, $date]);
        
        if($sql_insert){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    public function get_infor_proceso($id){
        $procesoc = DB::table('tab_proceso_contratacion')->where('id','=', $id)->get();
        return $procesoc;
    }

    public function update_proceso(Request $r){
        $id= $r->id;
        $nombre= $r->nombre;
        $enlace= $r->enlace;
        $date= now();

        $sql_update= DB::table('tab_proceso_contratacion')
            ->where('id', '=', $id)
            ->update(['nombre'=> $nombre, 'enlace'=> $enlace, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    /*--------------------------------------------------------------------------------------------------*/
    //LEYES Y REGLAMENTOS
    /*--------------------------------------------------------------------------------------------------*/
    //INDEX PÁGINA PRINCIPAL LEYES
    public function leyes_index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $reglamento = DB::table('tab_reglamentos')->get();
            return response()->view('Administrador.Documentos.reglamento.reglamento', ['reglamento'=> $reglamento]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function ley_register(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            return response()->view('Administrador.Documentos.reglamento.registrar_reglamento');
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ALMACENA EL REGLAMENTO EN LA BASE DE DATOS
    public function store_ley(Request $r){
        if ($r->hasFile('file') ) {
            $filesr  = $r->file('file'); //obtengo el archivo POA

            $date= now();
            $name= $r->inputName;
            $aliasfile= $r->inputAliasFileLey;

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
                        $sql_insert = DB::connection('mysql')->insert('insert into tab_reglamentos (
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
        $reglamento = DB::table('tab_reglamentos')
                ->where('nombre_archivo', 'like', '%'.$param.'%')
                ->get();
        
        $resultado='';
        foreach($reglamento as $r){
            $resultado= $r->nombre_archivo;
        }

        return $resultado;
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC REGLAMENTO
    public function view_ley($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $filer= DB::connection('mysql')->table('tab_reglamentos')
            ->where('id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.reglamento.viewley', ['filer'=> $filer]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA POA
    public function inactivar_ley(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_reglamentos')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR EL REGLAMENTO
    public function edit_ley($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $reglamento= DB::connection('mysql')->table('tab_reglamentos')
            ->where('id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.reglamento.editar_reglamento', ['reglamento'=> $reglamento]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION PARA DESCARGAR EL REGLAMENTO
    public function download_ley($id){
        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_reglamentos WHERE id=?', [$id]);

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

    //FUNCION QUE ACTUALIZA EL REGLAMENTO EN LA BASE DE DATOS
    public function update_ley(Request $r){
        $date= now();
        $id= $r->idfile;
        $nombre= $r->inputEName;
        $aliasfileley= $r->inputAliasFileLeyE;
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
                        $sql_update= DB::table('tab_reglamentos')
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
                $sql_update= DB::table('tab_reglamentos')
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
                    $sql_update= DB::table('tab_reglamentos')
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

    private function getNameFileLey($id){
        $sql= DB::connection('mysql')->select('SELECT archivo FROM tab_reglamentos WHERE id=?', [$id]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->archivo;
        }

        return $resultado;
    }
}
