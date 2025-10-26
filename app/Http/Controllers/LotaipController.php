<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use File;
use App\ContadorHelper;

class LotaipController extends Controller
{
    //FUNCION QUE RETORNA LA VISTA PRINCIPAL DE LITERAL LOTAIP
    public function index(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            //$itemlotaip= DB::connection('mysql')->table('tab_item_lotaip')->orderBy('literal','asc')->get();
            $itemlotaip= DB::connection('mysql')->table('tab_item_lotaip')
            ->join('tab_art_lotaip', 'tab_item_lotaip.id_articulo', '=', 'tab_art_lotaip.id')
            ->select('tab_item_lotaip.*', 'tab_art_lotaip.descripcion as articulo')
            ->orderBy('literal','asc')->get();
            $artlotaip= DB::connection('mysql')->table('tab_art_lotaip')->orderBy('descripcion','asc')->get();
            return view('Administrador.Documentos.lotaip.settingslotaip', ['itemlotaip'=> $itemlotaip, 'artlotaip'=> $artlotaip]);
        }else{
            return redirect('/loginadmineep');
        }
    }

     //FUNCION QUE REGISTRA EL ITEM LOTAIP
    public function registro_item_lotaip(Request $r){
        $articulo= $r->articulo;
        $literal= $r->literal;
        $descripcion= $r->descripcion;
        $date= now();

        if($this->getLiteral($literal)==$literal){
            return response()->json(["resultado"=> 'existe']);
        }else{
            $sql_insert= DB::connection('mysql')->table('tab_item_lotaip')->insertGetId(
                ['literal'=> $literal, 'descripcion'=> $descripcion, 'id_articulo'=> $articulo, 'created_at'=> $date]
            );
            $LAST_ID= $sql_insert;
            if($sql_insert){
                return response()->json(["resultado"=> true,"ID"=>$LAST_ID]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
        /*$sql_insert = DB::connection('mysql')->insert('insert into tab_item_lotaip (
            literal, descripcion, created_at
        ) values (?,?,?)', [$literal, $descripcion, $date]);*/
    }

    private function getLiteral($dato){
        $sql= DB::connection('mysql')->select('SELECT literal FROM tab_item_lotaip WHERE literal=?', [$dato]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->literal;
        }

        return $resultado;
    }

    //FUNCION QUE OBTIENE EL ID
    public function get_item_lotaip($id){
        $sql= DB::connection('mysql')->table('tab_item_lotaip')->where('id', $id)->get();

        return $sql;
    }

    //FUNCION QUE ACTUALIZA EL ITEM LOTAIP
    public function update_item_lotaip(Request $r){
        $id= $r->id;
        $literal= $r->literal;
        $descripcion= $r->descripcion;
        $date= now();

        if($this->getLiteral($literal)!=$literal){
            return response()->json(["resultado"=> 'diferente']);
        }else{
            $sql_update= DB::table('tab_item_lotaip')
            ->where('id', $id)
            ->update(['literal'=> $literal, 'descripcion'=> $descripcion, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
    }

    public function inactivar_item_lotaip(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();

        if($estado=='1'){
            $sql_update= DB::table('tab_item_lotaip')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }else{
            $sql_update= DB::table('tab_item_lotaip')
            ->where('id', $id)
            ->update(['estado' => $estado, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }
        
    }

    /*******************************************************************************
     * LOTAIP ARTÍCULOS
    *******************************************************************************/

    //FUNCION QUE RETORNA LA VISTA PRINCIPAL DE ARTÍCULOS LOTAIP
    public function index_artlotaip(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $artlotaip= DB::connection('mysql')->table('tab_art_lotaip')->orderBy('descripcion','asc')->get();
            return view('Administrador.Documentos.lotaip.articleslotaip', ['artlotaip'=> $artlotaip]);
        }else{
            return redirect('/loginadmineep');
        }
    }

     //FUNCION QUE REGISTRA EL ARTICULO LOTAIP
     public function registro_articulo_lotaip(Request $r){
        $descripcion= $r->descripcion;
        $date= now();

        if($this->getDescpArtLotaip($descripcion)==$descripcion){
            return response()->json(["resultado"=> 'existe']);
        }else{
            $sql_insert= DB::connection('mysql')->table('tab_art_lotaip')->insertGetId(
                ['descripcion'=> $descripcion,'created_at'=> $date]
            );
            $LAST_ID= $sql_insert;
            if($sql_insert){
                return response()->json(["resultado"=> true,"ID"=>$LAST_ID]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
    }

    //FUNCION QUE OBTIENE EL ID
    public function get_articulo_lotaip($id){
        $sql= DB::connection('mysql')->table('tab_art_lotaip')->where('id', $id)->get();

        return $sql;
    }

    //FUNCION QUE ACTUALIZA EL ARTÍCULO LOTAIP
    public function update_articulo_lotaip(Request $r){
        $id= $r->id;
        $descripcion= $r->descripcion;
        $date= now();

        /*if($this->getDescpArtLotaip($descripcion)!=$descripcion){
            return response()->json(["resultado"=> 'diferente']);
        }else{*/
            $sql_update= DB::table('tab_art_lotaip')
            ->where('id', $id)
            ->update(['descripcion'=> $descripcion, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        //}
    }

    public function inactivar_articulo_lotaip(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();

        if($estado=='1'){
            $sql_update= DB::table('tab_art_lotaip')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }else{
            $sql_update= DB::table('tab_art_lotaip')
            ->where('id', $id)
            ->update(['estado' => $estado, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }
        
    }

    private function getDescpArtLotaip($dato){
        $sql= DB::connection('mysql')->select('SELECT descripcion FROM tab_art_lotaip WHERE descripcion=?', [$dato]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->descripcion;
        }

        return $resultado;
    }

    /*******************************************************************************
     * LOTAIP OPCIONES
    *******************************************************************************/

    //FUNCION QUE RETORNA LA VISTA PRINCIPAL DE ARTÍCULOS LOTAIP
    public function index_optlotaip(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador' || Session::get('tipo_usuario')=='semiadministrador')){
            $optlotaip= DB::connection('mysql')->table('tab_opciones_lotaip')->orderBy('descripcion','asc')->get();
            return view('Administrador.Documentos.lotaip.opcioneslotaip', ['optlotaip'=> $optlotaip]);
        }else{
            return redirect('/loginadmineep');
        }
    }

     //FUNCION QUE REGISTRA LA OPCION LOTAIP
    public function registro_opcion_lotaip(Request $r){
        $descripcion= $r->descripcion;
        $date= now();

        if($this->getDescpOptLotaip($descripcion)==$descripcion){
            return response()->json(["resultado"=> 'existe']);
        }else{
            $sql_insert= DB::connection('mysql')->table('tab_opciones_lotaip')->insertGetId(
                ['descripcion'=> $descripcion,'created_at'=> $date]
            );
            $LAST_ID= $sql_insert;
            if($sql_insert){
                return response()->json(["resultado"=> true,"ID"=>$LAST_ID]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
    }

    //FUNCION QUE OBTIENE EL ID
    public function get_opcion_lotaip($id){
        $sql= DB::connection('mysql')->table('tab_opciones_lotaip')->where('id', $id)->get();

        return $sql;
    }

    //FUNCION QUE ACTUALIZA EL ARTÍCULO LOTAIP
    public function update_opcion_lotaip(Request $r){
        $id= $r->id;
        $descripcion= $r->descripcion;
        $date= now();

        /*if($this->getDescpArtLotaip($descripcion)!=$descripcion){
            return response()->json(["resultado"=> 'diferente']);
        }else{*/
            $sql_update= DB::table('tab_opciones_lotaip')
            ->where('id', $id)
            ->update(['descripcion'=> $descripcion, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        //}
    }

    public function inactivar_opciones_lotaip(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();

        if($estado=='1'){
            $sql_update= DB::table('tab_opciones_lotaip')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }else{
            $sql_update= DB::table('tab_opciones_lotaip')
            ->where('id', $id)
            ->update(['estado' => $estado, 'updated_at'=> $date]);

            if($sql_update){
                return response()->json(['resultado'=> true]);
            }else{
                return response()->json(['resultado'=> false]);
            }
        }
        
    }

    private function getDescpOptLotaip($dato){
        $sql= DB::connection('mysql')->select('SELECT descripcion FROM tab_opciones_lotaip WHERE descripcion=?', [$dato]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->descripcion;
        }

        return $resultado;
    }


    /*******************************************************************************
     * LOTAIP INTERFAZ PRINCIPAL
    *******************************************************************************/

    //FUNCION QUE RETORNA LA VISTA PRINCIPAL DE AJUSTES LOTAIP
    public function index_lotaip_original(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $lotaip= DB::connection('mysql')->table('tab_lotaip')
            ->join('tab_anio','tab_anio.id','=','tab_lotaip.id_anio')
            ->join('tab_meses','tab_meses.id','=','tab_lotaip.id_mes')
            ->join('tab_item_lotaip','tab_item_lotaip.id','=','tab_lotaip.id_item_lotaip')
            ->select('tab_lotaip.*','tab_anio.nombre as year', 'tab_meses.mes', 'tab_item_lotaip.literal', 'tab_item_lotaip.descripcion')
            ->orderByDesc('tab_lotaip.id_anio')->get();
            $year= DB::connection('mysql')->table('tab_anio')->where('estado','=','1')->get();
            $orderby= DB::connection('mysql')->select('SELECT l.id_anio FROM tab_lotaip l, tab_anio y WHERE l.id_anio=y.id GROUP BY l.id_anio');
            $ordermes= DB::connection('mysql')->select('SELECT l.id_anio, l.id_mes, m.mes FROM tab_lotaip l, tab_meses m WHERE l.id_mes=m.id ORDER BY id_anio ASC');
            return view('Administrador.Documentos.lotaip.lotaip', ['lotaip'=> $lotaip, 'year'=> $year, 'orderby'=> $orderby, 'ordermes'=> $ordermes]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function index_lotaip(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $getYearLotaip= DB::connection('mysql')->select('SELECT DISTINCT l.id_anio, y.nombre FROM tab_lotaip l, tab_anio y WHERE l.id_anio=y.id;');
            $resultado= array();
            $meses= array();
            $archivos= array();
            foreach($getYearLotaip as $anio){
                $idyear= $anio->id_anio;
                $getMonthLotaip= DB::connection('mysql')->select('SELECT DISTINCT l.id_mes, m.mes FROM tab_lotaip l, tab_meses m WHERE l.id_mes=m.id AND l.id_anio=?', [$idyear]);
                foreach($getMonthLotaip as $mes){
                    $idmes= $mes->id_mes;
                    $getItemLotaip= DB::connection('mysql')->select('SELECT l.*, il.literal, il.descripcion FROM tab_lotaip l, tab_item_lotaip il WHERE l.id_item_lotaip=il.id AND id_anio=? AND id_mes=? ORDER BY l.id_item_lotaip ASC', [$idyear, $idmes]);
                    foreach($getItemLotaip as $lot){
                        $archivo= $lot->archivo;
                        $archivos[]= array('id'=> $lot->id, 'id_anio'=> $lot->id_anio, 'id_mes'=> $lot->id_mes, 'id_item_lotaip'=> $lot->id_item_lotaip,
                            'archivo'=> $archivo, 'literal'=> $lot->literal, 'descripcion'=> $lot->descripcion, 'estado'=> $lot->estado);
                    }
                    $meses[]= array('idmes'=> $idmes, 'mes'=> $mes->mes, 'archivos'=> $archivos);
                    unset($archivos);
                }
                $resultado[]= array('anio'=> $anio->nombre, 'nmes'=> $meses);
                unset($meses);
            }
            json_encode($resultado);
            return view('Administrador.Documentos.lotaip.nlotaip', ['lotaip'=> $resultado]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION ABRE INTERFAZ PARA REGISTRAR EL LOTAIP
    public function registro_lotaip(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','DESC')->get();
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            $item_lotaip= DB::connection('mysql')->table('tab_item_lotaip')->orderBy('literal','asc')->get();
            return response()->view('Administrador.Documentos.lotaip.registrar_lotaip', ['anio'=> $anio, 'mes'=> $mes, 'item_lotaip'=> $item_lotaip]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ALMACENA EL LOTAIP EN LA BASE DE DATOS
    public function store_lotaip(Request $r){
        if ($r->hasFile('file') ) {
            $fileslotaip  = $r->file('file'); //obtengo el archivo LOTAIP

            $date= now();
            $anio = $r->anio;
            $mes=  $r->mes;
            $literal= $r->literal;
            $aliasfilepac= $r->inputAliasFile;
            $n_mes= $r->n_mes;

            if($literal==$this->getLiteralLotaip($anio, $mes)){
                return response()->json(['resultado'=> 'existe']);
            }else{
                //NO HAY INFORMACIÓN

                /*$subpath = 'documentos/lotaip/'.$n_mes;
                $path = storage_path('app/'.$subpath);
                if(!File::isDirectory($path)){
                    File::makeDirectory($path, 0777, true, true);
                }*/

                foreach($fileslotaip as $file){
                    $contentfilelotaip= $file;
                    $filenamelotaip= $file->getClientOriginalName();
                    $fileextensionlotaip= $file->getClientOriginalExtension();
                }
    
                $newnamelotaip= $aliasfilepac.".".$fileextensionlotaip;
    
                if($fileextensionlotaip== $this->validarFile($fileextensionlotaip)){
                    $storelotaip= Storage::disk('doc_lotaip')->put($newnamelotaip,  \File::get($contentfilelotaip));
                    if($storelotaip){
                        $sql_insert = DB::connection('mysql')->insert('insert into tab_lotaip (
                            id_anio, id_mes, id_item_lotaip, archivo, created_at
                        ) values (?,?,?,?,?)', [$anio, $mes, $literal, $newnamelotaip, $date]);
        
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

    private function getMesLotaip($year){
        $sql= DB::connection('mysql')->select('SELECT id_mes FROM tab_lotaip WHERE id_anio=?', [$year]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->id_mes;
        }

        return $resultado;
    }

    private function getLiteralLotaip($year, $mes){
        $sql= DB::connection('mysql')->select('SELECT id_item_lotaip FROM tab_lotaip WHERE id_anio=? AND id_mes=?', [$year, $mes]);

        $resultado='';

        foreach($sql as $r){
            $resultado= $r->id_item_lotaip;
        }

        return $resultado;
    }

    //FUNCION QUE VALIDA SI ES UN PDF/CSV
    private function validarFile($extension){
        $validar_extension= array("pdf","csv");
        if(in_array($extension, $validar_extension)){
            return true;
        }else{
            return false;
        }
    }
    	
    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC LOTAIP
    public function view_lotaip($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $filelotaip= DB::connection('mysql')
            ->select('SELECT y.nombre, m.mes, CONCAT(i.literal,".- ",i.descripcion) as literal, l.archivo 
                FROM tab_lotaip l, tab_anio y, tab_meses m, tab_item_lotaip i 
                WHERE l.id_anio=y.id AND l.id_mes=m.id AND l.id_item_lotaip=i.id AND l.id=?', [$id]);
            return response()->view('Administrador.Documentos.lotaip.viewlotaip', ['filelotaip'=> $filelotaip]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR EL LOTAIP
    public function edit_lotaip($id){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','asc')->get();
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            $item_lotaip= DB::connection('mysql')->table('tab_item_lotaip')->orderBy('literal','asc')->get();
            $filelotaip= DB::connection('mysql')->table('tab_lotaip')
            ->where('id','=', $id)
            ->get();
            return response()->view('Administrador.Documentos.lotaip.editar_lotaip', ['filelotaip'=> $filelotaip, 'anio'=> $anio, 'mes'=> $mes, 'item_lotaip'=> $item_lotaip]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION QUE ACTIVA/INACTIVA EL REGISTO DE LA TABLA LOTAIP
    public function inactivar_lotaip(Request $request){
        $id= $request->input('id');
        $estado= $request->input('estado');
        $date= now();
        //return response()->json(['id'=> $id,'estado'=> $estado]);

        $sql_update= DB::table('tab_lotaip')
                ->where('id', $id)
                ->update(['estado' => $estado, 'updated_at'=> $date]);

        if($sql_update){
            return response()->json(['resultado'=> true]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    //FUNCION PARA DESCARGAR EL LOTAIP
    public function download_lotaip($id){
        $id = desencriptarNumero($id);

        $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_lotaip WHERE id=?', [$id]);

        $archivo='';
        foreach ($sql_dato as $key) {
            $archivo= $key->archivo;
        }
        
        $subpath = 'documentos/lotaip/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/doc-lotaip/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('doc_lotaip')->exists($archivo))
        {
            //return Storage::disk('img_banner')->download($url);
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
        
    }

    public function lotaipv1_increment(Request $r){
        $id = $r->input('idfile');
        $id = desencriptarNumero($id);
        //Incrementar contador de descargas (llamada limpia y segura)
        ContadorHelper::incrementarDescarga('tab_lotaip', $id);
        //return response()->json(['resultado'=>true]);
    }

    //FUNCION QUE ACTUALIZA EL LOTAIP EN LA BASE DE DATOS
    public function update_lotaip(Request $r){
        $date= now();
        $id= $r->idlotaip;
        $aliasfilelotaip= $r->inputAliasEFile;
        $islotaip= $r->islotaip;

        if($islotaip=="false"){
            if ($r->hasFile('fileEdit')) {
                $fileslotaip  = $r->file('fileEdit'); //obtengo el archivo LOTAIP
                foreach($fileslotaip as $file){
                    $contentfilelotaip= $file;
                    $filenamelotaip= $file->getClientOriginalName();
                    $fileextensionlotaip= $file->getClientOriginalExtension();
                }
                $newnamelotaip= $aliasfilelotaip.".".$fileextensionlotaip;

                if(Storage::disk('doc_lotaip')->exists($newnamelotaip)){
                    Storage::disk('doc_lotaip')->delete($newnamelotaip);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensionlotaip== $this->validarFile($fileextensionlotaip)){
                    $storelotaip= Storage::disk('doc_lotaip')->put($newnamelotaip,  \File::get($contentfilelotaip));

                    if($storelotaip){
                        $sql_update= DB::table('tab_lotaip')
                            ->where('id', $id)
                            ->update(['archivo'=> $newnamelotaip, 'updated_at'=> $date]);
    
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

    /*******************************************************************************
     * LOTAIP V2 INTERFAZ PRINCIPAL
    *******************************************************************************/
    public function index_lotaip_v2(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $getYearLotaip= DB::connection('mysql')->select('SELECT DISTINCT l.id_anio, y.nombre FROM tab_lotaip_v2 l, tab_anio y WHERE l.id_anio=y.id;');
            $resultado= array();
            $meses= array();
            $articulos= array();
            
            foreach($getYearLotaip as $anio){
                $idyear= $anio->id_anio;
                $getMonthLotaip= DB::connection('mysql')->select('SELECT DISTINCT l.id_mes, m.mes FROM tab_lotaip_v2 l, tab_meses m WHERE l.id_mes=m.id AND l.id_anio=?', [$idyear]);
                foreach($getMonthLotaip as $mes){
                    $idmes= $mes->id_mes;
                    $getArtOptLotaip= DB::connection('mysql')->select('SELECT DISTINCT l.id_art_lotaip, ar.descripcion FROM tab_lotaip_v2 l, tab_art_lotaip ar WHERE 
                        l.id_art_lotaip=ar.id AND l.id_opt_lotaip IS NULL AND l.id_anio=?', [$idyear]);
                    foreach($getArtOptLotaip as $gao){
                        $archivos= array();
                        $idart= $gao->id_art_lotaip;
                        if($idart=='1'){
                            $getItemLotaip= DB::connection('mysql')->select('SELECT l.*, il.literal, il.descripcion FROM tab_lotaip_v2 l, tab_item_lotaip il WHERE 
                                l.id_item_lotaip=il.id AND l.id_art_lotaip=? AND id_anio=? AND id_mes=? ORDER BY l.id_item_lotaip ASC', [$idart, $idyear, $idmes]);
                            foreach($getItemLotaip as $lot){
                                $archivo= $lot->archivo;
                                $archivocd= $lot->archivo_cdatos;
                                $archivomd= $lot->archivo_mdatos;
                                $archivodd= $lot->archivo_ddatos;
                                $archivos[]= array('id'=> $lot->id, 'id_anio'=> $lot->id_anio, 'id_mes'=> $lot->id_mes, 'id_item_lotaip'=> $lot->id_item_lotaip,
                                    'archivo'=> $archivo, 'archivocd'=> $archivocd, 'archivomd'=> $archivomd, 'archivodd'=> $archivodd, 'literal'=> $lot->literal, 'descripcion'=> $lot->descripcion, 'estado'=> $lot->estado);
                            }
                            $articulos[]= array('id_articulo'=> $idart, 'articulo'=> $gao->descripcion, 'tipo'=>'articulo', 'archivos'=> $archivos);
                            unset($archivos);
                        }else{
                            $getItemLotaip= DB::connection('mysql')->select('SELECT l.* FROM tab_lotaip_v2 l WHERE l.id_art_lotaip=? AND id_anio=? AND id_mes=?', [$idart, $idyear, $idmes]);
                            $namefile='';
                            foreach($getItemLotaip as $lot){
                                $archivo= $lot->archivo;
                                $namefile= substr($archivo, 0, -7); 
                                $newphrase = str_replace('_', ' ', $namefile);
                                $archivocd= $lot->archivo_cdatos;
                                $archivomd= $lot->archivo_mdatos;
                                $archivodd= $lot->archivo_ddatos;
                                $archivos[]= array('id'=> $lot->id, 'id_anio'=> $lot->id_anio, 'id_mes'=> $lot->id_mes, 'id_item_lotaip'=> $lot->id_item_lotaip,
                                    'archivo'=> $archivo, 'archivocd'=> $archivocd, 'archivomd'=> $archivomd, 'archivodd'=> $archivodd, 'estado'=> $lot->estado, 'descripcion'=> $newphrase);
                            }
                            
                            $articulos[]= array('id_articulo'=> $idart, 'articulo'=> $gao->descripcion, 'tipo'=>'articulo', 'archivos'=> $archivos);
                            unset($archivos);
                        }
                    }

                    $getArtOptLotaip2= DB::connection('mysql')->select('SELECT DISTINCT l.id_opt_lotaip, op.descripcion FROM tab_lotaip_v2 l, tab_opciones_lotaip op WHERE 
                            l.id_opt_lotaip=op.id AND l.id_art_lotaip IS NULL AND l.id_anio=?', [$idyear]);
                    $count= count($getArtOptLotaip2);
                    if($count>0){
                        $archivos2= array();
                        foreach($getArtOptLotaip2 as $gao2){
                            $idopt= $gao2->id_opt_lotaip;
                            $getItemLotaip= DB::connection('mysql')->select('SELECT l.*, il.descripcion FROM tab_lotaip_v2 l, tab_opciones_lotaip il WHERE 
                                    l.id_opt_lotaip=il.id AND l.id_opt_lotaip=? AND id_anio=? AND id_mes=? ORDER BY l.id_opt_lotaip ASC', [$idopt, $idyear, $idmes]);
                            if(count($getItemLotaip)>0){
                            foreach($getItemLotaip as $lot){
                                $archivo= $lot->archivo;
                                $archivos2[]= array('id'=> $lot->id, 'id_anio'=> $lot->id_anio, 'id_mes'=> $lot->id_mes, 'archivo'=> $archivo, 
                                    'descripcion'=> $lot->descripcion, 'estado'=> $lot->estado);
                            }
                            $articulos[]= array('id_opcion'=> $idopt, 'opcion'=> $gao2->descripcion, 'tipo'=>'opcion', 'archivos'=> $archivos2);
                            unset($archivos2);
                            }
                        }
                        unset($archivos2);
                    }
                    $meses[]= array('idmes'=> $idmes, 'mes'=> $mes->mes, 'articulos'=> $articulos);
                    unset($articulos);
                }
                $resultado[]= array('anio'=> $anio->nombre, 'nmes'=> $meses);
                unset($meses);
            }
            json_encode($resultado);
            //return $resultado;
            return view('Administrador.Documentos.lotaip.nlotaipv2', ['lotaip'=> $resultado]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION ABRE INTERFAZ PARA REGISTRAR EL LOTAIP
    public function register_lotaip_v2(){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','desc')->get();
            $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
            $art= DB::connection('mysql')->table('tab_art_lotaip')->orderBy('descripcion','asc')->get();
            $opt= DB::connection('mysql')->table('tab_opciones_lotaip')->orderBy('descripcion','asc')->get();
            //$item_lotaip= DB::connection('mysql')->table('tab_item_lotaip')->orderBy('literal','asc')->get();
            return response()->view('Administrador.Documentos.lotaip.registrar_lotaip_v2', ['anio'=> $anio, 'mes'=> $mes, 'art_lotaip'=> $art, 'opt_lotaip'=> $opt]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function get_literal_lotaip($id){
        $item_lotaip= DB::connection('mysql')->table('tab_item_lotaip')
            ->where('id_articulo','=', $id)
            ->orderBy('literal','asc')->get();
        
        return $item_lotaip;
    }

    //FUNCION QUE ALMACENA EL LOTAIP EN LA BASE DE DATOS
    public function store_lotaip_v2(Request $r){
        if (($r->hasFile('fileCCD') && $r->hasFile('fileMD') && $r->hasFile('fileDD')) || $r->hasFile('file')) {
            $fileslotaip  = $r->file('file'); //obtengo el archivo LOTAIP
            $fileslotaipCD  = $r->file('fileCCD'); //obtengo el archivo LOTAIP CD
            $fileslotaipMD  = $r->file('fileMD'); //obtengo el archivo LOTAIP MD
            $fileslotaipDD  = $r->file('fileDD'); //obtengo el archivo LOTAIP DD

            $date= now();
            $anio = $r->anio;
            $mes=  $r->mes;
            $literal= $r->literal;
            $aliasfilepac= $r->inputAliasFile;
            $n_mes= $r->n_mes;
            $tipeartopt= $r->tipeartopt;
            $idartopt= $r->idartopt;
            $textart= $r->num_art;
            
            if($literal==$this->getLiteralLotaipv2($anio, $mes, $literal, $tipeartopt, $textart, $idartopt) && $literal!=0){
                return response()->json(['resultado'=> 'existe']);
            }else if($idartopt==$this->getLiteralLotaipv2($anio, $mes, $literal, $tipeartopt, $textart, $idartopt) && $literal==0){
                return response()->json(['resultado'=> 'existe']);
            }else{
                //NO HAY INFORMACIÓN

                /*$subpath = 'documentos/lotaip/'.$n_mes;
                $path = storage_path('app/'.$subpath);
                if(!File::isDirectory($path)){
                    File::makeDirectory($path, 0777, true, true);
                }*/

                if($tipeartopt=="opt"){
                    foreach($fileslotaip as $file){
                        $contentfilelotaip= $file;
                        $filenamelotaip= $file->getClientOriginalName();
                        $fileextensionlotaip= $file->getClientOriginalExtension();
                    }
        
                    $newnamelotaip= $aliasfilepac.".".$fileextensionlotaip;
        
                    if($fileextensionlotaip== $this->validarFile($fileextensionlotaip)){
                        $storelotaip= Storage::disk('doc_lotaip')->put($newnamelotaip,  \File::get($contentfilelotaip));
                        if($storelotaip){
                            /*if($tipeartopt=="art"){
                                if($literal=='0'){
                                    $sql_insert = DB::connection('mysql')->insert('insert into tab_lotaip_v2 (
                                        id_anio, id_mes, id_art_lotaip, archivo, created_at
                                    ) values (?,?,?,?,?)', [$anio, $mes, $idartopt, $newnamelotaip, $date]);
                                }else{
                                    $sql_insert = DB::connection('mysql')->insert('insert into tab_lotaip_v2 (
                                        id_anio, id_mes, id_art_lotaip, id_item_lotaip, archivo, created_at
                                    ) values (?,?,?,?,?,?)', [$anio, $mes, $idartopt, $literal, $newnamelotaip, $date]);
                                }
                                
                            }else if($tipeartopt=="opt"){
                                $sql_insert = DB::connection('mysql')->insert('insert into tab_lotaip_v2 (
                                    id_anio, id_mes, id_opt_lotaip, archivo, created_at
                                ) values (?,?,?,?,?)', [$anio, $mes, $idartopt, $newnamelotaip, $date]);
                            }*/
                            $sql_insert = DB::connection('mysql')->insert('insert into tab_lotaip_v2 (
                                id_anio, id_mes, id_opt_lotaip, archivo, created_at
                            ) values (?,?,?,?,?)', [$anio, $mes, $idartopt, $newnamelotaip, $date]);
            
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

                }else if($tipeartopt=="art"){
                    if($textart=='19'){
                        foreach($fileslotaipCD as $file){
                            $cflcd= $file;
                            $fnldd= $file->getClientOriginalName();
                            $felcd= $file->getClientOriginalExtension();
                        }
            
                        $nnlcd= $aliasfilepac."_cd.".$felcd;
                        if($felcd== $this->validarFile($felcd)){
                            $storelotaip= Storage::disk('doc_lotaip')->put($nnlcd,  \File::get($cflcd));

                            foreach($fileslotaipMD as $file){
                                $cflmd= $file;
                                $fnlmd= $file->getClientOriginalName();
                                $felmd= $file->getClientOriginalExtension();
                            }
                            $nnlmd= $aliasfilepac."_md.".$felmd;
                            if($felmd== $this->validarFile($felmd)){
                                $storelotaipmd= Storage::disk('doc_lotaip')->put($nnlmd,  \File::get($cflmd));

                                foreach($fileslotaipDD as $file){
                                    $cfldd= $file;
                                    $fnldd= $file->getClientOriginalName();
                                    $feldd= $file->getClientOriginalExtension();
                                }
                                $nnldd= $aliasfilepac."_dd.".$feldd;
                                if($feldd== $this->validarFile($feldd)){
                                    $storelotaipmd= Storage::disk('doc_lotaip')->put($nnldd,  \File::get($cfldd));

                                    if($literal=='0'){
                                        $sql_insert = DB::connection('mysql')->insert('insert into tab_lotaip_v2 (
                                            id_anio, id_mes, id_art_lotaip, archivo, created_at
                                        ) values (?,?,?,?,?)', [$anio, $mes, $idartopt, $nnldd, $date]); //ojo
                                    }else{
                                        $sql_insert = DB::connection('mysql')->insert('insert into tab_lotaip_v2 (
                                            id_anio, id_mes, id_art_lotaip, id_item_lotaip, archivo_cdatos, archivo_mdatos, archivo_ddatos, created_at
                                        ) values (?,?,?,?,?,?,?,?)', [$anio, $mes, $idartopt, $literal, $nnlcd, $nnlmd, $nnldd, $date]);
                                    }

                                    if($sql_insert){
                                        return response()->json(["resultado"=> true]);
                                    }else{
                                        return response()->json(["resultado"=> false]);
                                    }

                                }else{
                                    return response()->json(['resultado'=> 'nofile']);
                                }

                            }else{
                                return response()->json(['resultado'=> 'nofile']);
                            }

                        }else{
                            return response()->json(['resultado'=> 'nofile']);
                        }
                    }else if($textart=='23'){
                        foreach($fileslotaip as $file){
                            $contentfilelotaip= $file;
                            $filenamelotaip= $file->getClientOriginalName();
                            $fileextensionlotaip= $file->getClientOriginalExtension();
                        }
            
                        $newnamelotaip= $aliasfilepac.".".$fileextensionlotaip;
            
                        if($fileextensionlotaip== $this->validarFile($fileextensionlotaip)){
                            $storelotaip= Storage::disk('doc_lotaip')->put($newnamelotaip,  \File::get($contentfilelotaip));
                            if($storelotaip){
                                $sql_insert = DB::connection('mysql')->insert('insert into tab_lotaip_v2 (
                                    id_anio, id_mes, id_art_lotaip, archivo, created_at
                                ) values (?,?,?,?,?)', [$anio, $mes, $idartopt, $newnamelotaip, $date]);
                
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
                }
            }
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    private function getLiteralLotaipv2($year, $mes, $literal, $tipo, $textart, $idartopt){
        $resultado='';
        if($tipo=="art"){
            if($textart=='19'){
                $sentence= "SELECT id_item_lotaip FROM tab_lotaip_v2 WHERE id_anio=? AND id_mes=? AND id_item_lotaip=?";
                $sql= DB::connection('mysql')->select($sentence, [$year, $mes, $literal]);
                foreach($sql as $r){
                    $resultado= $r->id_item_lotaip;
                }
            }else if($textart=='23'){
                $sentence= "SELECT id_art_lotaip FROM tab_lotaip_v2 WHERE id_anio=? AND id_mes=? AND id_art_lotaip=?";
                $sql= DB::connection('mysql')->select($sentence, [$year, $mes, $idartopt]);
                foreach($sql as $r){
                    $resultado= $r->id_art_lotaip;
                }
            }
            
        }else if($tipo=="opt"){
            $sentence= "SELECT MAX(id_opt_lotaip FROM tab_lotaip_v2 WHERE id_anio=? AND id_mes=? AND id_opt_lotaip= ?";
            $sql= DB::connection('mysql')->select($sentence, [$year, $mes, $literal]);
            foreach($sql as $r){
                $resultado= $r->id_opt_lotaip;
            }
        }
        return $resultado;
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA VISUALIZAR EL DOC LOTAIP
    public function view_lotaip_v2($id, $tipo){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            $tipe='';
            if($tipo=='cd'){
                $tipe= 'Artículo 19 - Conjunto de Datos';
                $filelotaip= DB::connection('mysql')
                    ->select('SELECT y.nombre as anio, m.mes, CONCAT(i.literal,".- ",i.descripcion) as literal, l.archivo, l.archivo_cdatos as ccd 
                        FROM tab_lotaip_v2 l, tab_anio y, tab_meses m, tab_item_lotaip i 
                        WHERE l.id_anio=y.id AND l.id_mes=m.id AND l.id_item_lotaip=i.id AND l.id=?', [$id]);
            }else if($tipo=='md'){
                $tipe= 'Artículo 19 - Metadatos';
                $filelotaip= DB::connection('mysql')
                    ->select('SELECT y.nombre as anio, m.mes, CONCAT(i.literal,".- ",i.descripcion) as literal, l.archivo, l.archivo_mdatos as md 
                        FROM tab_lotaip_v2 l, tab_anio y, tab_meses m, tab_item_lotaip i 
                        WHERE l.id_anio=y.id AND l.id_mes=m.id AND l.id_item_lotaip=i.id AND l.id=?', [$id]);
            }else if($tipo=='dd'){
                $tipe= 'Artículo 19 - Diccionario de Datos';
                $filelotaip= DB::connection('mysql')
                    ->select('SELECT y.nombre as anio, m.mes, CONCAT(i.literal,".- ",i.descripcion) as literal, l.archivo, l.archivo_ddatos as dd 
                        FROM tab_lotaip_v2 l, tab_anio y, tab_meses m, tab_item_lotaip i 
                        WHERE l.id_anio=y.id AND l.id_mes=m.id AND l.id_item_lotaip=i.id AND l.id=?', [$id]);
            }else if($tipo=='art23'){
                $tipe= 'Artículo 23';
                $filelotaip= DB::connection('mysql')
                    ->select('SELECT y.nombre as anio, m.mes, l.archivo 
                        FROM tab_lotaip_v2 l, tab_anio y, tab_meses m WHERE l.id_anio=y.id 
                        AND l.id_mes=m.id AND l.id=?', [$id]);
            }else if($tipo=='optoth'){
                $tipe= '';
                $filelotaip= DB::connection('mysql')
                    ->select('SELECT y.nombre as anio, m.mes, ol.descripcion, l.archivo FROM 
                        tab_lotaip_v2 l, tab_anio y, tab_meses m, tab_opciones_lotaip ol WHERE 
                        l.id_anio=y.id AND l.id_mes=m.id AND l.id_opt_lotaip=ol.id AND l.id=?', [$id]);
            }
            return response()->view('Administrador.Documentos.lotaip.viewlotaipv2', ['filelotaip'=> $filelotaip, 'tipo'=> $tipe, 'typeabr'=> $tipo]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    //FUNCION PARA DESCARGAR EL LOTAIP
    public function download_lotaip_v2($id, $tipo){
        $archivo='';
        $id = desencriptarNumero($id);

        if($tipo=='cd'){
            $sql_dato= DB::connection('mysql')->select('SELECT archivo_cdatos FROM tab_lotaip_v2 WHERE id=?', [$id]);
            foreach ($sql_dato as $key) {
                $archivo= $key->archivo_cdatos;
            }
        }else if($tipo=='md'){
            $sql_dato= DB::connection('mysql')->select('SELECT archivo_mdatos FROM tab_lotaip_v2 WHERE id=?', [$id]);
            foreach ($sql_dato as $key) {
                $archivo= $key->archivo_mdatos;
            }
        }else if($tipo=='dd'){
            $sql_dato= DB::connection('mysql')->select('SELECT archivo_ddatos FROM tab_lotaip_v2 WHERE id=?', [$id]);
            foreach ($sql_dato as $key) {
                $archivo= $key->archivo_ddatos;
            }
        }else if($tipo=='art23'){
            $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_lotaip_v2 WHERE id=?', [$id]);
            foreach ($sql_dato as $key) {
                $archivo= $key->archivo;
            }
        }else if($tipo=='optoth'){
            $sql_dato= DB::connection('mysql')->select('SELECT archivo FROM tab_lotaip_v2 WHERE id=?', [$id]);
            foreach ($sql_dato as $key) {
                $archivo= $key->archivo;
            }
        }

        $subpath = 'documentos/lotaip/'.$archivo;
        $path = storage_path('app/'.$subpath);
        //$url = public_path("/storage/app/img_banner/" . $archivo);
        $url = public_path("/storage/doc-lotaip/" . $archivo);
        //verificamos si el archivo existe y lo retornamos
        if (Storage::disk('doc_lotaip')->exists($archivo))
        {
            //return Storage::disk('img_banner')->download($url);
            
            return response()->download($path);
        }else{
            //return response()->json(['DATO: '=> 'no existe']);
            //si no se encuentra lanzamos un error 404.
            abort(404);
        }
    }

    public function lotaip_v2_increment_cd(Request $r){
        $id = $r->input('idcd');
        $id = desencriptarNumero($id);
        //Incrementar contador de descargas (llamada limpia y segura)
        ContadorHelper::incrementarDescargaLotaip2cdatos('tab_lotaip_v2', $id);
        //return response()->json(['resultado'=>true]);
    }

    public function lotaip_v2_increment_md(Request $r){
        $id = $r->input('idmd');
        $id = desencriptarNumero($id);
        //Incrementar contador de descargas (llamada limpia y segura)
        ContadorHelper::incrementarDescargaLotaip2mdatos('tab_lotaip_v2', $id);
        //return response()->json(['resultado'=>true]);
    }

    public function lotaip_v2_increment_dd(Request $r){
        $id = $r->input('iddd');
        $id = desencriptarNumero($id);
        //Incrementar contador de descargas (llamada limpia y segura)
        ContadorHelper::incrementarDescargaLotaip2ddatos('tab_lotaip_v2', $id);
        //return response()->json(['resultado'=>true]);
    }

    public function lotaip_v2_increment(Request $r){
        $id = $r->input('idff');
        $id = desencriptarNumero($id);
        //Incrementar contador de descargas (llamada limpia y segura)
        ContadorHelper::incrementarDescargaLotaip2('tab_lotaip_v2', $id);
        //return response()->json(['resultado'=>true]);
    }

    private function formatDia($day){
        $dia="";
        switch ($day) {
            case "Sunday":
                $dia="Domingo";
                break;
            case "Monday":
                $dia="Lunes";
                break;
            case "Tuesday":
                $dia="Martes";
                break;
            case "Wednesday":
                $dia="Miércoles";
                break;
            case "Thursday":
                $dia="Jueves";
                break;
            case "Friday":
                $dia="Viernes";
                break;
            case "Saturday":
                $dia="Sábado";
                break;
        }
        return $dia;
    }

    private function setFecha($date){
        $arraymes= array('','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre',
        'Noviembre','Diciembre');
        $anio= substr($date, 0, 4);
        $mes= substr($date,-5,2);
        $dia= substr($date, 8, strlen($date));
    
        $mes= intval($mes);
        $diaN= $this->formatDia(date('l', strtotime($date)));
        //return $dia.' de '.$arraymes[$mes].' del '.$anio;
        return $diaN.', '.$dia.' de '.$arraymes[$mes].' del '.$anio;
    }

    //FUNCION QUE ABRE LA INTERFAZ PARA ACTUALIZAR EL LOTAIP
    public function edit_lotaip_v2($id, $tipo){
        if(Session::get('usuario') && (Session::get('tipo_usuario')!='comunicacion')){
            if($tipo=='cd' || $tipo=='md' || $tipo=='dd'){
                $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','asc')->get();
                $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
                $art= DB::connection('mysql')->table('tab_art_lotaip')->orderBy('descripcion','asc')->get();
                $idarttoitem= $this->getIdItemFromLotaip($id);
                $item_lotaip= DB::connection('mysql')->table('tab_item_lotaip')->where('id_articulo', $idarttoitem)->orderBy('literal','asc')->get();
                $filelotaip= DB::connection('mysql')->table('tab_lotaip_v2')
                ->where('id','=', $id)
                ->get();
                $fechatoshow= $this->setFormatDatetoView($id);
                $horatoshow = $this->setFormatHourtoView($id);
                return response()->view('Administrador.Documentos.lotaip.editar_lotaip_v2', ['filelotaip'=> $filelotaip, 'anio'=> $anio, 'mes'=> $mes, 
                    'art_lotaip'=> $art, 'item_lotaip'=> $item_lotaip, 'typeop'=> $tipo, 'fechatoshow'=> $fechatoshow, 'horatoshow'=> $horatoshow]);
            }else if($tipo=='art23'){
                $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','asc')->get();
                $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
                $art= DB::connection('mysql')->table('tab_art_lotaip')->orderBy('descripcion','asc')->get();
                $filelotaip= DB::connection('mysql')->table('tab_lotaip_v2')
                ->where('id','=', $id)
                ->get();
                $fechatoshow= $this->setFormatDatetoView($id);
                $horatoshow = $this->setFormatHourtoView($id);
                return response()->view('Administrador.Documentos.lotaip.editar_lotaip_v2', ['filelotaip'=> $filelotaip, 'anio'=> $anio, 'mes'=> $mes, 
                    'art_lotaip'=> $art, 'typeop'=> $tipo, 'fechatoshow'=> $fechatoshow, 'horatoshow'=> $horatoshow]);
            }else if($tipo=='optoth'){
                $anio= DB::connection('mysql')->table('tab_anio')->orderBy('nombre','asc')->get();
                $mes= DB::connection('mysql')->table('tab_meses')->orderBy('id','asc')->get();
                $opt_bd= DB::connection('mysql')->table('tab_opciones_lotaip')->orderBy('descripcion','asc')->get();
                $filelotaip= DB::connection('mysql')->table('tab_lotaip_v2')
                ->where('id','=', $id)
                ->get();
                $fechatoshow= $this->setFormatDatetoView($id);
                $horatoshow = $this->setFormatHourtoView($id);
                return response()->view('Administrador.Documentos.lotaip.editar_lotaip_v2', ['filelotaip'=> $filelotaip, 'anio'=> $anio, 'mes'=> $mes, 
                    'opt_lotaip'=> $opt_bd, 'typeop'=> $tipo, 'fechatoshow'=> $fechatoshow, 'horatoshow'=> $horatoshow]);
            }
        }else{
            return redirect('/loginadmineep');
        }
    }

    private function getIdItemFromLotaip($id){
        $result= DB::connection('mysql')->table('tab_lotaip_v2')->where('id','=', $id)->value('id_art_lotaip');
        return $result;
    }

    private function setFormatDatetoView($id){
        $result= DB::connection('mysql')->table('tab_lotaip_v2')->where('id','=', $id)->value('updated_at');
        if($result==null || $result==''){
            return '-Sin especificar-';
        }else{
            $posicion = substr($result, 0, 10);
            return $this->setFecha($posicion);
        }
    }

    private function setFormatHourtoView($id){
        $result= DB::connection('mysql')->table('tab_lotaip_v2')->where('id','=', $id)->value('updated_at');
        if($result!=null || $result!=''){
            return $posicion = substr($result, 11, strlen($result));
        }else{
            return '';
        }
    }

    //FUNCION QUE ACTUALIZA EL LOTAIP EN LA BASE DE DATOS
    public function update_lotaip_v2(Request $r){
        $date= now();
        $id= $r->idlotaipv2;
        $aliasfilelotaip= $r->inputAliasEFile;
        $islotaip= $r->islotaip;
        $tiposel= $r->tipopcion;
        $estado = $r->estadodocumento;
        $estado = strval($estado);

        if($islotaip=="false"){
            if ($r->hasFile('fileEdit')) {
                $fileslotaip  = $r->file('fileEdit'); //obtengo el archivo LOTAIP
                foreach($fileslotaip as $file){
                    $contentfilelotaip= $file;
                    $filenamelotaip= $file->getClientOriginalName();
                    $fileextensionlotaip= $file->getClientOriginalExtension();
                }
                $newnamelotaip= $aliasfilelotaip.".".$fileextensionlotaip;

                if(Storage::disk('doc_lotaip')->exists($newnamelotaip)){
                    Storage::disk('doc_lotaip')->delete($newnamelotaip);
                    /*
                        Delete Multiple files this way
                        Storage::delete(['upload/test.png', 'upload/test2.png']);
                    */
                }

                if($fileextensionlotaip== $this->validarFile($fileextensionlotaip)){
                    $storelotaip= Storage::disk('doc_lotaip')->put($newnamelotaip,  \File::get($contentfilelotaip));

                    if($storelotaip){
                        if($tiposel=='cd'){
                            $sql_update= DB::table('tab_lotaip_v2')
                                ->where('id', $id)
                                ->update(['archivo_cdatos'=> $newnamelotaip, 'estado'=> $estado, 'updated_at'=> $date]);
                        }else if($tiposel=='md'){
                            $sql_update= DB::table('tab_lotaip_v2')
                                ->where('id', $id)
                                ->update(['archivo_mdatos'=> $newnamelotaip, 'estado'=> $estado, 'updated_at'=> $date]);
                        }else if($tiposel=='dd'){
                            $sql_update= DB::table('tab_lotaip_v2')
                                ->where('id', $id)
                                ->update(['archivo_ddatos'=> $newnamelotaip, 'estado'=> $estado, 'updated_at'=> $date]);
                        }else if($tiposel=='art23'){
                            $sql_update= DB::table('tab_lotaip_v2')
                                ->where('id', $id)
                                ->update(['archivo'=> $newnamelotaip, 'updated_at'=> $date]);
                        }else if($tiposel=='optoth'){
                            $sql_update= DB::table('tab_lotaip_v2')
                                ->where('id', $id)
                                ->update(['archivo'=> $newnamelotaip, 'updated_at'=> $date]);
                        }
    
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
            $sql_update= DB::table('tab_lotaip_v2')
                ->where('id', $id)
                ->update(['estado'=> $estado, 'updated_at'=> $date]);
            if($sql_update){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
    }
}
