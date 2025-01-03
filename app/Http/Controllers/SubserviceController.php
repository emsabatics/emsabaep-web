<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DOMDocument;

class SubserviceController extends Controller
{
    //FUNCION QUE RETORNA LA VISTA PRINCIPAL DE SUBSERVICIOS
    public function index($idservicio){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $idservicio= base64_decode($idservicio);
            $idservicio= intval($idservicio);

            $countCat= DB::connection('mysql')->table('tab_servicios_subservicio')->where('id_servicio','=', $idservicio)->count();

            $arcat= array();

            $getSubservicio= DB::connection('mysql')->table('tab_servicios_subservicio')->where('id_servicio','=', $idservicio)->get();
            /*foreach($getCatBiblioteca as $c){
                $arsubcat= array();
                $arfile= array();
                $idcat= $c->id;
                $getSubcatBiblioteca= DB::connection('mysql')->select('SELECT id, descripcion, estado FROM tab_bv_subcategoria 
                    WHERE id_bv_categoria=?', [$idcat]);
                
                $idsubcat='';
                // GET SUBCATEGORIA //
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
                // GET SUBCATEGORIA //

                // GET ARCHIVOS SIN SUBCATEGORIA //
                $getFileBv= DB::connection('mysql')->select('SELECT id, archivo, estado FROM tab_bv_archivos 
                    WHERE id_bv_categoria=? AND id_bv_subcategoria IS NULL', [$idcat]);
                foreach($getFileBv as $fc){
                    $arfile[] = array('idfile'=> $fc->id, 'archivo'=> $fc->archivo, 'estado'=> $fc->estado);
                }
                // GET ARCHIVOS SIN SUBCATEGORIA //

                $arcat[]= array('idcat'=> $idcat, 'descripcioncat'=> $c->descripcion, 'estadocat'=> $c->estado, 'subcategoria'=> $arsubcat, 'archivos'=> $arfile);
                unset($arfile);
                unset($arsubcat);
            }*/

            $nameservice= $this->getNameService($idservicio);
            $arsubservice= array();
            foreach($getSubservicio as $c){
                
                $idsubservicio= $c->id;
                
                $arsubservice[]= array('idsubservice'=> $idsubservicio, 'namesubservice'=> $c->nombre, 'imagensubservice'=> $c->imagen, 'iconsubservice'=> $c->icon, 
                    'tiposervice'=> $c->tipo, 'estadosubservice'=> $c->estado);
            }
            $arcat[]= array('idservicio'=> $idservicio, 'nameservicio'=> $nameservice, 'subservicio'=> $arsubservice);
            unset($arsubservice);
            
            json_encode($arcat);
            //return $countCat;
            return response()->view('Administrador.Servicios.Subservicios.subservicio', ['idservicio'=> $idservicio, 'subservicio'=> $arcat, 'totalC'=> $countCat]);
        }else{
            return redirect('/login');
        }
    }

    private function getNameService($id){
        $resultado= '';

        $sql= DB::connection('mysql')->select('SELECT titulo FROM tab_servicios WHERE id=?', [$id]);

        foreach($sql as $s){
            $resultado= $s->titulo;
        }

        return $resultado;
    }

    //FUNCION QUE RETORNA LA VISTA PARA EL REGISTRO DE SERVICIO
    public function registrar_subservicio($idservicio){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion') ){
            $idservicio= base64_decode($idservicio);
            $idservicio= intval($idservicio);
            $nameservice= $this->getNameService($idservicio);
            //return $idservicio;
            return response()->view('Administrador.Servicios.Subservicios.registrar_subservicio', ['idservicio'=> $idservicio, 'nameservicio'=> $nameservice]);
        }else{
            return redirect('/login');
        }
    }

    //FUNCION QUE ALMACEMA EL REGISTRO DEL SERVICIO
    public function store_subservice(Request $r){
        if ($r->hasFile('file') && $r->hasFile('fileIcon')) {
            $filesimg  = $r->file('file'); //obtengo el archivo imagen del servicio
            $filesicon  = $r->file('fileIcon'); //obtengo el archivo icono del servicio
            $idservicio= $r->idservicio; //idservicio
            $titulo= $r->inputTitleSubservice; //titulo subservicio
            $tregistro= $r->tregistro;
            $date= now();
            $fileextensionimg= '';
            $fileextensionicon= '';

            foreach($filesimg as $file){
                $contentfileimg= $file;
                $filenameimg= $file->getClientOriginalName();
                $fileextensionimg= $file->getClientOriginalExtension();
            }

            $newnameimg= $filenameimg;

            foreach($filesicon as $filei){
                $contentfileicon= $filei;
                $filenameicon= $filei->getClientOriginalName();
                $fileextensionicon= $filei->getClientOriginalExtension();
            }

            $newnameicon= $filenameicon;

            //echo $fileextensionimg.' - '.$this->validarFile($fileextensionimg);
            if($fileextensionimg== $this->validarFile($fileextensionimg) && $fileextensionicon== $this->validarFile($fileextensionicon)){
                $storeimg= Storage::disk('img_servicios')->put($newnameimg,  \File::get($contentfileimg));
                $storeicon= Storage::disk('img_servicios')->put($newnameicon,  \File::get($contentfileicon));
                if($storeimg && $storeicon){
                    $sql_insert= DB::connection('mysql')->table('tab_servicios_subservicio')->insertGetId(
                        ['id_servicio'=> $idservicio, 'nombre'=> $titulo, 'imagen'=> $newnameimg,
                            'icon'=> $newnameicon, 'tipo'=> $tregistro, 'created_at'=> $date]
                    );

                    if($sql_insert){
                        return response()->json(["resultado"=> true]);
                    }else{
                        return response()->json(["resultado"=> false]);
                    }
                }else{
                    return response()->json(['resultado'=> false]);
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
        $validar_extension= array("png", "jpg", "jpeg", "svg", "gif", "pdf");
        if(in_array($extension, $validar_extension)){
            return $extension;
        }else{
            return "0";
        }
    }

    //FUNCION QUE OBTIENE NOMBRE DEL SUBSERVICIO
    public function get_namesubservice($id){
        $sql= DB::connection('mysql')->select('SELECT id_servicio, nombre, estado FROM tab_servicios_subservicio WHERE id=?',[$id]);

        return $sql;
    }

    //FUNCION QUE ACTUALIZA EL SUBSERVICIO
    public function actualizar_subservicio(Request $r){
        $idservicio= $r->idservicio;
        $idsubservicio= $r->idsubservicio;
        $nombre= $r->nombre;
        $estadosubservicio= $r->estadosubservicio;
        $date= now();

        $sql_update= DB::connection('mysql')->table('tab_servicios_subservicio')
        ->where('id', $idsubservicio)
        ->update(['nombre'=> $nombre, 'estado'=> $estadosubservicio, 'updated_at'=> $date]);
        
        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    //FUNCION QUE ELIMINA EL REGISTO DE LA TABLA SUBSERVICIO
    public function eliminar_subservicio(Request $request){
        $id= $request->input('id');
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_servicios_subservicio')
                ->where('id', $id)
                ->delete();

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function getIdSubserviceBySs($id, $table){
        $resultado= '';

        $sql= DB::connection('mysql')->table($table)->select('id_subservicio')
        ->where('id','=', $id)->get();

        foreach($sql as $s){
            $resultado= $s->id_subservicio;
        }

        return $resultado;
    }

    private function getIdServiceFromSubService($id){
        $resultado= '';

        $sql= DB::connection('mysql')->select('SELECT id_servicio FROM tab_servicios_subservicio WHERE id=?', [$id]);

        foreach($sql as $s){
            $resultado= $s->id_servicio;
        }

        return $resultado;
    }

    //FUNCION PARA DESCARGAR LA IMAGEN DEL SUBSERVICIO
    public function download_archivo_subservice($id, $table){
        $archivo='';
        if($table=='infodetail'){
            $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_subservicio_informativo WHERE id=?', [$id]);
            foreach ($sql_dato as $key) {
                $archivo= $key->archivo;
            }
        }else if($table=='filetext'){
            $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_subservicio_text_file WHERE id=?', [$id]);
            foreach ($sql_dato as $key) {
                $archivo= $key->archivo;
            }
        }

        $subpath = 'img_servicios/'.$archivo;
        $path = storage_path('app/'.$subpath);
        $url = public_path("/storage/servicios-img/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('img_servicios')->exists($archivo))
        {
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

    //FUNCION QUE ACTUALIZA EL REGISTRO DEL SUBSERVICIO INFO DETAIL
    public function update_subservice_infodetail(Request $r){
        $idregistro= $r->iddetailinfo;
        $idsubservicio= $r->idsubservice;
        $descripcion= $r->descripcion;
        $date= now();

        $sql_update= DB::table('tab_subservicio_informativo')
            ->where('id', $idregistro)
            ->update(['descripcion'=> $descripcion, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    /* ----------------------------------------------------------------------------------------------------  */
    //                             CONFIGURACION DETALLE INFORMATIVO
    /* ----------------------------------------------------------------------------------------------------  */
    public function register_detail_info($idsubservice, $opcion, $interface){
        $idsubservice= base64_decode($idsubservice);
        $idsubservice= intval($idsubservice);

        $subservice= $this->get_namesubservice($idsubservice);

        if($opcion=='v1'){
            return response()->view('Administrador.Servicios.Subservicios.subservicio_detailinfo', ['idsubservice'=> $idsubservice, 
                'subservicio'=> $subservice, 'interface'=> $interface]);
        }
    }

    public function store_detailinfor_subservice(Request $r){
        if ($r->hasFile('file')) {
            $filesimg  = $r->file('file'); //obtengo el archivo imagen del servicio
            $idsubservicio= $r->idsubservicio;
            $descripcion= $r->descripcion;
            $date= now();
            $fileextensionimg= '';

            foreach($filesimg as $file){
                $contentfileimg= $file;
                $filenameimg= $file->getClientOriginalName();
                $fileextensionimg= $file->getClientOriginalExtension();
            }

            $newnameimg= $filenameimg;

            //echo $fileextensionimg.' - '.$this->validarFile($fileextensionimg);
            if($fileextensionimg== $this->validarFile($fileextensionimg)){
                $storeimg= Storage::disk('img_servicios')->put($newnameimg,  \File::get($contentfileimg));
                if($storeimg){
                    $sql_insert= DB::connection('mysql')->table('tab_subservicio_informativo')->insertGetId(
                        ['id_subservicio'=> $idsubservicio, 'descripcion'=> $descripcion, 'archivo'=> $newnameimg,
                            'created_at'=> $date]
                    );

                    if($sql_insert){
                        return response()->json(["resultado"=> true]);
                    }else{
                        return response()->json(["resultado"=> false]);
                    }
                }else{
                    return response()->json(['resultado'=> false]);
                }
            }else{
                return response()->json(['resultado'=> 'nofile']);
            }
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function view_detail_info($idsubservice, $opcion){
        $idsubservice= base64_decode($idsubservice);
        $idsubservice= intval($idsubservice);

        $idservicio= $this->getIdServiceFromSubService($idsubservice);

        $subservice= $this->get_namesubservice($idsubservice);
        $tabsubinfo= DB::connection('mysql')->table('tab_subservicio_informativo')
            ->join('tab_servicios_subservicio', 'tab_subservicio_informativo.id_subservicio', '=', 'tab_servicios_subservicio.id')
            ->select('tab_subservicio_informativo.*', 'tab_servicios_subservicio.nombre as subservicio')
            ->where('tab_subservicio_informativo.id_subservicio','=', $idsubservice)
            ->orderBy('id_subservicio','asc')->get();

        if($opcion=='v1'){
            return response()->view('Administrador.Servicios.Subservicios.view_subservicio_detailinfo', ['idsubservice'=> $idsubservice, 
                'subservicio'=> $subservice, 'tabsubinfo'=> $tabsubinfo, 'idservicio'=> $idservicio]);
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA SUBSERVICIO INFORMATIVO
    public function inactivar_subservice_detailinfo(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_subservicio_informativo')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE ELIMINA EL REGISTO DE LA TABLA SUBSERVICIO INFORMATIVO
    public function delete_subservice_detailinfo(Request $request){
        $id= $request->input('id');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_subservicio_informativo')
                ->where('id', $id)
                ->delete();

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function update_detail_info($idinfodetail, $opcion){
        $idinfodetail= base64_decode($idinfodetail);
        $idsubservice= $this->getIdSubserviceBySs($idinfodetail, 'tab_subservicio_informativo');
        $subservice= $this->get_namesubservice($idsubservice);

        $subserviceinfodetail= DB::connection('mysql')->table('tab_subservicio_informativo')->where('id','=', $idinfodetail)->get();
        //return $subserviceinfodetail;
        if($opcion=='v1'){
            return response()->view('Administrador.Servicios.Subservicios.subservicio_updatedetailinfo', ['idsubservice'=> $idsubservice, 
                'subservicio'=> $subservice, 'informacion'=>$subserviceinfodetail]);
        }
    }

    //FUNCION PARA ACTUALIZAR IMAGEN DEL SERVICIO
    public function actualizar_subservicio_img_infodetail(Request $r){
        if ($r->hasFile('fileImgEdit')) {
            $files  = $r->file('fileImgEdit'); //obtengo el archivo
            $id= $r->input('idsubservicioinfodettoimg');
            $num_img= $r->num_img;
            $date= now();
            $contar=0;

            foreach($files as $file){
                // here is your file object
                $filename= $file->getClientOriginalName();
                $fileextension= $file->getClientOriginalExtension();
                //$filesize= $file->getSize();//
                /*$data = getimagesize($file->getRealPath());
                $width = $data[0];
                $height = $data[1];*/

                if($fileextension== $this->validarFile($fileextension)){
                    $oldnamefile= $this->name_file_doc($id, 'infodetail');
                    if($oldnamefile!=$filename){
                        $storeimg= Storage::disk('img_servicios')->put($filename,  \File::get($file));
                    }else{
                        $storeimg= true;
                    }
                    
                    if($storeimg){
                        $sql_update= DB::connection('mysql')->table('tab_subservicio_informativo')
                        ->where('id', $id)
                        ->update(['archivo'=> $filename, 'updated_at'=> $date]);

                        if($sql_update){
                            return response()->json(["resultado"=> true]);
                        }else{
                            return response()->json(["resultado"=> false]);
                        }
                    }else{
                        return response()->json(["resultado"=>"nocopy"]);
                    }
                }else{
                    return response()->json(['resultado'=> 'nofile']);
                }
            }
        }
    }

    private function name_file_doc($id, $tipo){
        $resultado='';

        if($tipo=='infodetail'){
            $sql= DB::connection('mysql')->select('SELECT archivo FROM tab_subservicio_informativo WHERE id=?', [$id]);

            foreach($sql as $s){
                $resultado= $s->archivo;
            }
        }else if($tipo=='filetext'){
            $sql= DB::connection('mysql')->select('SELECT archivo FROM tab_subservicio_text_file WHERE id=?', [$id]);

            foreach($sql as $s){
                $resultado= $s->archivo;
            }
        }
        
        return $resultado;
    }

    /* ----------------------------------------------------------------------------------------------------  */
    //                             CONFIGURACION LISTA DESPLEGABLE
    /* ----------------------------------------------------------------------------------------------------  */
    public function view_detail_lista($idsubservice, $opcion){
        $idsubservice= base64_decode($idsubservice);
        $idsubservice= intval($idsubservice);

        $idservicio= $this->getIdServiceFromSubService($idsubservice);

        $subservice= $this->get_namesubservice($idsubservice);
        $tabsubinfo= DB::connection('mysql')->table('tab_subservicio_listdesplegable')
            ->join('tab_servicios_subservicio', 'tab_subservicio_listdesplegable.id_subservicio', '=', 'tab_servicios_subservicio.id')
            ->select('tab_subservicio_listdesplegable.*', 'tab_servicios_subservicio.nombre as subservicio')
            ->where('tab_subservicio_listdesplegable.id_subservicio','=', $idsubservice)
            ->orderBy('id_subservicio','asc')->get();

        if($opcion=='v1'){
            return response()->view('Administrador.Servicios.Subservicios.view_subservicio_detaillista', ['idsubservice'=> $idsubservice, 
                'subservicio'=> $subservice, 'tabsubinfo'=> $tabsubinfo, 'idservicio'=> $idservicio]);
        }
    }

    public function view_list_large($idsubservice, $opcion, $interface){
        $idsubservice= base64_decode($idsubservice);
        $idsubservice= intval($idsubservice);

        $subservice= $this->get_namesubservice($idsubservice);

        if($opcion=='v1'){
            return response()->view('Administrador.Servicios.Subservicios.subservicio_registerlist', ['idsubservice'=> $idsubservice, 
                'subservicio'=> $subservice, 'interface'=> $interface]);
        }
    }

    public function store_showlist_subservice(Request $r){
        $idsubservicio= $r->idsubservicio;
        $titulo= $r->titulo;
        $descripcion= $r->descripcion;
        $date= now();

        $sql_insert= DB::connection('mysql')->table('tab_subservicio_listdesplegable')->insertGetId(
            ['id_subservicio'=> $idsubservicio, 'titulo'=> $titulo, 'descripcion'=> $descripcion,
                'created_at'=> $date]
        );

        if($sql_insert){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    public function update_detail_list($iddetaillist, $opcion){
        $iddetaillist= base64_decode($iddetaillist);
        $idsubservice= $this->getIdSubserviceBySs($iddetaillist, 'tab_subservicio_listdesplegable');
        $subservice= $this->get_namesubservice($idsubservice);

        $subserviceinfodetail= DB::connection('mysql')->table('tab_subservicio_listdesplegable')->where('id','=', $iddetaillist)->get();
        //return $subserviceinfodetail;
        if($opcion=='v1'){
            return response()->view('Administrador.Servicios.Subservicios.subservicio_updatelist', ['idsubservice'=> $idsubservice, 
                'subservicio'=> $subservice, 'informacion'=>$subserviceinfodetail]);
        }
    }

    public function update_showlist_subservice(Request $r){
        $id= $r->input('idlistitem');
        $titulo= $r->input('titulo');
        $descripcion= $r->input('descripcion');
        $date= now();

        $sql_update= DB::connection('mysql')->table('tab_subservicio_listdesplegable')
            ->where('id', $id)
            ->update(['titulo'=> $titulo, 'descripcion'=> $descripcion, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA tab_subservicio_listdesplegable
    public function inactivar_subservice_detaillist(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_subservicio_listdesplegable')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE ELIMINA EL REGISTO DE LA TABLA tab_subservicio_listdesplegable
    public function delete_subservice_detaillist(Request $request){
        $id= $request->input('id');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_subservicio_listdesplegable')
                ->where('id', $id)
                ->delete();

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    /* ----------------------------------------------------------------------------------------------------  */
    //                             CONFIGURACION TEXTO Y ARCHIVO
    /* ----------------------------------------------------------------------------------------------------  */
    public function view_detail_filelist($idsubservice, $opcion){
        $idsubservice= base64_decode($idsubservice);
        $idsubservice= intval($idsubservice);

        $idservicio= $this->getIdServiceFromSubService($idsubservice);

        $subservice= $this->get_namesubservice($idsubservice);
        $tabsubinfo= DB::connection('mysql')->table('tab_subservicio_text_file')
            ->join('tab_servicios_subservicio', 'tab_subservicio_text_file.id_subservicio', '=', 'tab_servicios_subservicio.id')
            ->select('tab_subservicio_text_file.*', 'tab_servicios_subservicio.nombre as subservicio')
            ->where('tab_subservicio_text_file.id_subservicio', '=', $idsubservice)
            ->orderBy('id_subservicio','asc')->get();

        if($opcion=='v1'){
            return response()->view('Administrador.Servicios.Subservicios.view_subservicio_filelist', ['idsubservice'=> $idsubservice, 
                'subservicio'=> $subservice, 'tabsubinfo'=> $tabsubinfo, 'idservicio'=> $idservicio]);
        }
    }

    public function file_list_subservice($idsubservice, $opcion, $interface){
        $idsubservice= base64_decode($idsubservice);
        $idsubservice= intval($idsubservice);

        $subservice= $this->get_namesubservice($idsubservice);

        if($opcion=='v1'){
            return response()->view('Administrador.Servicios.Subservicios.subservicio_textwithfile', ['idsubservice'=> $idsubservice, 'subservicio'=> $subservice,
            'interface'=> $interface]);
        }
    }

    public function store_textfile_subservice(Request $r){
        if ($r->hasFile('file') ) {
            $idsubservicio= $r->idsubservicio;
            $posicion= $r->input('posicion');
            $descripcion= $r->input('descripcion');
            $date= now();
            $filesdoc  = $r->file('file'); //obtengo el archivo
            $tipofile= $r->input('tipo_file');

            foreach($filesdoc as $file){
                $contentfiledf= $file;
                $filenamedf= $file->getClientOriginalName();
                $fileextensiondf= $file->getClientOriginalExtension();                
            }
            $newnamedf= $filenamedf;

            if($fileextensiondf== $this->validarFile($fileextensiondf)){
                $storepoa= Storage::disk('img_servicios')->put($newnamedf,  \File::get($contentfiledf));
                if($storepoa){
                    $sql_insert_img = DB::connection('mysql')->insert('insert into tab_subservicio_text_file (
                        id_subservicio, archivo, posicion, tipo_file, created_at
                    ) values (?,?,?,?,?)', [$idsubservicio,$newnamedf,$posicion, $tipofile,$date]);
    
                    if($sql_insert_img){
                        $sql_insert = DB::connection('mysql')->insert('insert into tab_subservicio_text_file (
                            id_subservicio, descripcion, created_at
                        ) values (?,?,?)', [$idsubservicio,$descripcion,$date]);

                        if($sql_insert){
                            return response()->json(["resultado"=> true]);
                        }else{
                            return response()->json(["resultado"=> false]);
                        }
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

        /*$description= $r->summernote;
        $dom = new \DomDocument();
        $dom->loadHtml($description, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        $imageFile = $dom->getElementsByTagName('imageFile');
        foreach($imageFile as $item => $image)
        {
            $data = $img->getAttribute('src');

            list($type, $data) = explode(';', $data);

            list(, $data)      = explode(',', $data);

            $imgeData = base64_decode($data);

            $image_name= "/upload/" . time().$item.'.png';

            $path = public_path() . $image_name;

            file_put_contents($path, $imgeData);
            
            $image->removeAttribute('src');

            $image->setAttribute('src', $image_name);
        }
        $description = $dom->saveHTML();*/

        //$descripcion= $r->summernote;
        /*$descripcion = $r->descripcion;
        //$descripcion= $r->getContent();
        $dom= new DOMDocument();
        $dom->loadHTML($descripcion, 9);
        /*$dom->loadHTML( mb_convert_encoding( $descripcion, 'HTML-ENTITIES', 'UTF-8' ),
          LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );//
        $images= $dom->getElementsByTagName('img');
        foreach ($images as $img) {
            $data = base64_decode(explode(',',explode(';',$img->getAttribute('src'))[1])[1]);
            $image_name = "/upload/" . time().'-'.$img->getAttribute('alt').'.png';
            echo 'name_img: '.$image_name;

            $img->removeAttribute('src');
            $img->setAttribute('src',$image_name);
        }
        $description = $dom->saveHTML();*/
        
    }

    public function store_doc_subservicio(Request $r){
        if ($r->hasFile('file') ) {
            $filesdoc  = $r->file('file'); //obtengo el archivo
            $idsubservicio= $r->idsubservicio;
            $aliasfiledf= $r->inputAliasFileDocAdj;
            $nombredoc= $r->inputNameDocAdj;
            $date= now();

            foreach($filesdoc as $file){
                $contentfiledf= $file;
                $filenamedf= $file->getClientOriginalName();
                $fileextensiondf= $file->getClientOriginalExtension();
            }

            $newnamedf= $aliasfiledf.".".$fileextensiondf;

            if($fileextensiondf== $this->validarFile($fileextensiondf)){
                $storepoa= Storage::disk('img_servicios')->put($newnamedf,  \File::get($contentfiledf));
                if($storepoa){
                    $sql_insert = DB::connection('mysql')->insert('insert into tab_subservicio_files (
                        id_subservicio, titulo, archivo, created_at
                    ) values (?,?,?,?)', [$idsubservicio, $nombredoc, $newnamedf, $date]);
    
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

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA SUBSERVICIO TEXTO Y ARCHIVO
    public function inactivar_subservice_filelist(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_subservicio_text_file')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION QUE ELIMINA EL REGISTO DE LA TABLA SUBSERVICIO TEXTO Y ARCHIVO
    public function delete_subservice_filelist(Request $request){
        $id= $request->input('id');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_subservicio_text_file')
                ->where('id', $id)
                ->delete();

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function update_detail_filelist($idinfodetail, $opcion){
        $idinfodetail= base64_decode($idinfodetail);
        $idsubservice= $this->getIdSubserviceBySs($idinfodetail, 'tab_subservicio_text_file');
        $subservice= $this->get_namesubservice($idsubservice);

        $subserviceinfodetail= DB::connection('mysql')->table('tab_subservicio_text_file')->where('id','=', $idinfodetail)->get();
        //return $subserviceinfodetail;
        if($opcion=='v1'){
            return response()->view('Administrador.Servicios.Subservicios.subservicio_updatedetailfilelist', ['idsubservice'=> $idsubservice, 
                'subservicio'=> $subservice, 'informacion'=>$subserviceinfodetail]);
        }
    }

    //FUNCION PARA ACTUALIZAR ARCHIVO DEL SUBSERVICIO
    public function actualizar_subservicio_file_filelist(Request $r){
        if ($r->hasFile('fileImgEdit')) {
            $files  = $r->file('fileImgEdit'); //obtengo el archivo
            $id= $r->input('idsubserviciofile');
            $tipofile= $r->input('tipo_file');
            $posicion= $r->input('posicion');
            $num_img= $r->input('num_img');
            $date= now();
            $contar=0;

            foreach($files as $file){
                // here is your file object
                $filename= $file->getClientOriginalName();
                $fileextension= $file->getClientOriginalExtension();
                //$filesize= $file->getSize();//
                /*$data = getimagesize($file->getRealPath());
                $width = $data[0];
                $height = $data[1];*/

                if($fileextension== $this->validarFile($fileextension)){
                    $oldnamefile= $this->name_file_doc($id, 'filetext');
                    if($oldnamefile!=$filename){
                        $storeimg= Storage::disk('img_servicios')->put($filename,  \File::get($file));
                    }else{
                        $storeimg= true;
                    }
                    
                    if($storeimg){
                        $sql_update= DB::connection('mysql')->table('tab_subservicio_text_file')
                        ->where('id', $id)
                        ->update(['archivo'=> $filename, 'posicion'=> $posicion, 'tipo_file'=> $tipofile, 'updated_at'=> $date]);

                        if($sql_update){
                            return response()->json(["resultado"=> true]);
                        }else{
                            return response()->json(["resultado"=> false]);
                        }
                    }else{
                        return response()->json(["resultado"=>"nocopy"]);
                    }
                }else{
                    return response()->json(['resultado'=> 'nofile']);
                }
            }
        }
    }

    public function actualizar_subservicio_positionfile_filelist(Request $r){
        $id= $r->input('idsubserviciofile');
        $posicion= $r->input('posicion');
        $date= now();

        $sql_update= DB::connection('mysql')->table('tab_subservicio_text_file')
            ->where('id', $id)
            ->update(['posicion'=> $posicion, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }

    //FUNCION PARA ACTUALIZAR  TEXTO DEL SUBSERVICIO
    public function actualizar_subservicio_textfilelist(Request $r){
        $id= $r->input('idtextfile_descp');
        $descripcion= $r->input('descripcion');
        $date= now();

        $sql_update= DB::connection('mysql')->table('tab_subservicio_text_file')
            ->where('id', $id)
            ->update(['descripcion'=> $descripcion, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(["resultado"=> true]);
        }else{
            return response()->json(["resultado"=> false]);
        }
    }
}
