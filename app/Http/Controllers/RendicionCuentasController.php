<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class RendicionCuentasController extends Controller
{
    //FUNCION QUE RETORNA LA VISTA PRINCIPAL DE AJUSTES RENDICION CUENTAS
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $getRendicionC= DB::connection('mysql')->select('SELECT rc.*, y.nombre FROM tab_rendicion_cuentas rc, tab_anio y WHERE rc.id_anio=y.id');
            $resultado= array();
            $tipovideo= array();
            $tipomedio= array();
            foreach($getRendicionC as $grc){
                $id= $grc->id;
                $getMonthRc= DB::connection('mysql')->select('SELECT DISTINCT tipo FROM tab_archivos_rendicion_cuentas WHERE id_rendicion_cuenta=?', [$id]);
                foreach($getMonthRc as $tiporc){
                    $gettipofile= $tiporc->tipo;
                    $getFilesRC= DB::connection('mysql')->select('SELECT * FROM tab_archivos_rendicion_cuentas WHERE id_rendicion_cuenta=? AND tipo=?', [$id, $gettipofile]);
                    foreach($getFilesRC as $frc){
                        $archivo= $frc->archivo;
                        if($gettipofile=="video"){
                            $tipovideo[]= array('id'=> $frc->id, 'id_rendicion_cuenta'=> $frc->id_rendicion_cuenta, 'tipo'=> $frc->tipo,
                            'titulo'=> $frc->titulo, 'archivo'=> $frc->archivo,'estado'=> $frc->estado);
                            if(empty($tipomedio)){
                                $tipomedio = [];
                            }
                        }else if($gettipofile=="medio"){
                            $tipomedio[]= array('id'=> $frc->id, 'id_rendicion_cuenta'=> $frc->id_rendicion_cuenta, 'tipo'=> $frc->tipo,
                            'titulo'=> $frc->titulo, 'archivo'=> $frc->archivo,'estado'=> $frc->estado);
                            if(empty($tipovideo)){
                                $tipovideo = [];
                            }
                        }
                    }
                    
                    //$tipofiles[]= array('idmes'=> $idmes, 'mes'=> $mes->mes, 'archivos'=> $archivos);
                    //unset($archivos);*/
                }
                $resultado[]= array('id'=> $grc->id, 'id_anio'=>$grc->id_anio, 'anio'=> $grc->nombre, 'tipov'=> 'video', 
                    'longitudv'=> sizeof($tipovideo), 'archivos_v'=> $tipovideo, 'tipom'=>'medio', 
                    'longitudm'=> sizeof($tipomedio), 'archivos_m'=> $tipomedio);
                unset($tipomedio);
                unset($tipovideo);
            }
            json_encode($resultado);
            //return $resultado;
            return view('Administrador.Documentos.rendicionc.rendicionc', ['rendicionc'=> $resultado]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION ABRE INTERFAZ PARA REGISTRAR LOS DOCS DE RENDICION DE CUENTAS
    public function registro_rendicionc(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','desc')->get();
            return response()->view('Administrador.Documentos.rendicionc.registrar_rendicionc', ['anio'=> $anio]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ALMACENA EL LOTAIP EN LA BASE DE DATOS
    public function store_rendicionc(Request $r){
        if ($r->hasFile('file') ) {
            $filesrendicionc  = $r->file('file'); //obtengo el archivo LOTAIP

            $date= now();
            $anio = $r->anio;
            $aliasfilerc= $r->inputAliasFile;
            $titulo= $r->titulo;
            $sel_tipo= $r->sel_tipo;
            $typefile= $r->typefile;
            $lengfile= $r->lengfile;
            $LAST_ID='';

            //$gtfile= $this->getTipoFileRendicionC($anio);
            //$glrc= $this->getLiteralRendicionC($anio);
            //echo $gtfile.' '.$glrc;
            if($this->getTipoFileRendicionC($anio)=='video' && $sel_tipo=='video' && $anio==$this->getLiteralRendicionC($anio)){
                return response()->json(['resultado'=> 'existe']);
            }else{
                //NO HAY INFORMACIÓN
                $contval=0;
                /*$subpath = 'documentos/lotaip/'.$n_mes;
                $path = storage_path('app/'.$subpath);
                if(!File::isDirectory($path)){
                    File::makeDirectory($path, 0777, true, true);
                }*/

                foreach($filesrendicionc as $file){
                    $fileextensionrendicionc= $file->getClientOriginalExtension();

                    if($fileextensionrendicionc== $this->validarFile($fileextensionrendicionc)){
                        $contval++;
                    }
                }

                if($lengfile==$contval){
                    $LAST_ID= $this->getIdRendicionC($anio);
                    if($LAST_ID==''){
                        $sql_insert= DB::connection('mysql')->table('tab_rendicion_cuentas')->insertGetId([
                            'id_anio'=> $anio, 'created_at'=> $date
                        ]);

                        if($sql_insert){
                            $LAST_ID= $sql_insert;
                            foreach($filesrendicionc as $file){
                                $contentfilerendicionc= $file;
                                $filenamerendicionc= $file->getClientOriginalName();
                                $fileextensionrendicionc= $file->getClientOriginalExtension();
            
                                $newnamerendicionc= $aliasfilerc.".".$fileextensionrendicionc;

                                $storelotaip= Storage::disk('doc_rendicion_c')->put($newnamerendicionc,  \File::get($contentfilerendicionc));
                                if($storelotaip){
                                    $sql_insert_2 = DB::connection('mysql')->insert('insert into tab_archivos_rendicion_cuentas (
                                        id_rendicion_cuenta	, tipo, titulo, archivo, created_at
                                    ) values (?,?,?,?,?)', [$LAST_ID, $sel_tipo, $titulo, $newnamerendicionc, $date]);
        
                                    if($sql_insert_2){
                                        return response()->json(["resultado"=> true]);
                                    }else{
                                        return response()->json(["resultado"=> false]);
                                    }
                                }else{
                                    return response()->json(["resultado"=> 'nocopy']);
                                }
                            }
                        }else{
                            return response()->json(["resultado"=> false]);
                        }
                    }else{
                        foreach($filesrendicionc as $file){
                            $contentfilerendicionc= $file;
                            $filenamerendicionc= $file->getClientOriginalName();
                            $fileextensionrendicionc= $file->getClientOriginalExtension();
        
                            $newnamerendicionc= $aliasfilerc.".".$fileextensionrendicionc;

                            $storelotaip= Storage::disk('doc_rendicion_c')->put($newnamerendicionc,  \File::get($contentfilerendicionc));
                            if($storelotaip){
                                $sql_insert_2 = DB::connection('mysql')->insert('insert into tab_archivos_rendicion_cuentas (
                                    id_rendicion_cuenta	, tipo, titulo, archivo, created_at
                                ) values (?,?,?,?,?)', [$LAST_ID, $sel_tipo, $titulo, $newnamerendicionc, $date]);
    
                                if($sql_insert_2){
                                    return response()->json(["resultado"=> true]);
                                }else{
                                    return response()->json(["resultado"=> false]);
                                }
                            }else{
                                return response()->json(["resultado"=> 'nocopy']);
                            }
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

    //FUNCION QUE DEVUELVE EL ID_ANIO DE LA TABLA RENDICION CUENTAS
    private function getLiteralRendicionC($year){
        $sql= DB::connection('mysql')->select('SELECT id_anio FROM tab_rendicion_cuentas WHERE id_anio=?', [$year]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->id_anio;
        }

        return $resultado;
    }

    //FUNCION QUE DEVUELVE EL TIPO DE LA TABLA ARCHIVOS RENDICION CUENTAS
    private function getTipoFileRendicionC($year){
        $id= $this->getIdRendicionC($year);
        $sql= DB::connection('mysql')->select('SELECT tipo FROM tab_archivos_rendicion_cuentas WHERE id_rendicion_cuenta=?', [$id]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->tipo;
        }

        return $resultado;
    }

    //FUNCION QUE DEVUELVE EL ID DE LA TABLA RENDICION CUENTAS
    private function getIdRendicionC($year){
        $sql= DB::connection('mysql')->select('SELECT id FROM tab_rendicion_cuentas WHERE id_anio=?', [$year]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->id;
        }

        return $resultado;
    }

    //FUNCION QUE VALIDA SI ES UN PDF
    private function validarFile($extension){
        $validar_extension= array("pdf", "mp4");
        if(in_array($extension, $validar_extension)){
            return true;
        }else{
            return false;
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC RENDICIÓN DE CUENTAS
    public function view_rendicionc($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $filerendicionc= DB::connection('mysql')
            ->select('SELECT tipo, titulo, archivo FROM tab_archivos_rendicion_cuentas WHERE id=?', [$id]);
            return response()->view('Administrador.Documentos.rendicionc.viewrendicionc', ['filerendicionc'=> $filerendicionc]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA ARCHIVOS RENDICION CUENTAS
    public function inactivar_rendicionc(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_archivos_rendicion_cuentas')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION PARA DESCARGAR RENDICIÓN DE CUENTAS
    public function download_rendicionc($id){
        $id = desencriptarNumero($id);
        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_archivos_rendicion_cuentas WHERE id=?', [$id]);

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }
        
        $subpath = 'documentos/rendicion_cuentas/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/doc-rendicion-cuentas/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('doc_rendicion_c')->exists($archivo))
        {
            //return Storage::disk('img_banner')->download($url);
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

     //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR RENDICIÓN DE CUENTAS
     public function edit_rendicionc($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','asc')->get();
            $filerendicionc = DB::table('tab_archivos_rendicion_cuentas')
            ->join('tab_rendicion_cuentas', 'tab_archivos_rendicion_cuentas.id_rendicion_cuenta', '=', 'tab_rendicion_cuentas.id')
            ->join('tab_anio', 'tab_rendicion_cuentas.id_anio', '=', 'tab_anio.id')
            ->select('tab_archivos_rendicion_cuentas.*', 'tab_rendicion_cuentas.id as idrc', 'tab_rendicion_cuentas.id_anio', 'tab_anio.nombre as anio')
            ->where('tab_archivos_rendicion_cuentas.id','=', $id)
            ->get();
            //return $filerendicionc;
            return response()->view('Administrador.Documentos.rendicionc.editar_rendicionc', ['filerendicionc'=> $filerendicionc, 'anio'=> $anio]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function edit_rendicionc_original($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','asc')->get();
            $filerendicionc= array();
            $archivosrc= array();
            $item_rc= DB::connection('mysql')->select('SELECT rc.id, rc.id_anio, y.nombre FROM tab_rendicion_cuentas rc, tab_anio y WHERE rc.id_anio=y.id AND rc.id=?', [$id]);
            foreach($item_rc as $rc){
                $idrc= $rc->id;
                $file_rc= DB::connection('mysql')->select('SELECT id, tipo, titulo, archivo FROM tab_archivos_rendicion_cuentas WHERE id_rendicion_cuenta=?', [$idrc]);
                foreach($file_rc as $frc){
                    $archivosrc[] = array('idfrc'=> $frc->id, 'tipo'=> $frc->tipo, 'titulo'=> $frc->titulo, 'archivo'=> $frc->archivo);
                }
                $filerendicionc[]= array('id'=> $idrc, 'id_anio'=> $rc->id_anio, 'anio'=> $rc->nombre, 'archivos'=> $archivosrc);
                unset($archivosrc);
            }
            json_encode($filerendicionc);
            //return json_encode($filerendicionc);
            return response()->view('Administrador.Documentos.rendicionc.editar_rendicionc', ['filerendicionc'=> $filerendicionc, 'anio'=> $anio]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTUALIZA LA RENDICIÓN DE CUENTAS EN LA BASE DE DATOS
    public function update_rendicionc(Request $r){
        $date= now();
        $idrc= $r->idrendicionc;
        $idfrc= $r->id_filerc;
        $aliasfilerendicionc= $r->inputEAliasFile;
        $isRendicionc= $r->isRendicionc;

        if($isRendicionc=="false"){
            if ($r->hasFile('fileEdit')) {
                $filesrendicionc  = $r->file('fileEdit'); //obtengo el archivo RENDICIONC
                foreach($filesrendicionc as $file){
                    $contentfilerendicionc= $file;
                    $filenamerendicionc= $file->getClientOriginalName();
                    $fileextensionrendicionc= $file->getClientOriginalExtension();
                }
                $newnamerendicionc= $aliasfilerendicionc.".".$fileextensionrendicionc;

                if(Storage::disk('doc_rendicion_c')->exists($newnamerendicionc)){
                    Storage::disk('doc_rendicion_c')->delete($newnamerendicionc);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensionrendicionc== $this->validarFile($fileextensionrendicionc)){
                    $storerendicionc= Storage::disk('doc_rendicion_c')->put($newnamerendicionc,  \File::get($contentfilerendicionc));

                    if($storerendicionc){
                        $sql_update= DB::table('tab_archivos_rendicion_cuentas')
                            ->where('id',$idfrc)
                            ->where('id_rendicion_cuenta', $idrc)
                            ->update(['archivo'=> $newnamerendicionc, 'updated_at'=> $date]);
    
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
            return response()->json(["resultado"=> true]);
        }
    }
}
