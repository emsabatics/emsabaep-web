<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportesContadorController extends Controller
{
    public function index()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            // Si el usuario envía rango de fechas desde el selector
            $fechaInicio = now()->subDays(10)->toDateString();
            $fechaFin    = now()->toDateString();

            $visitas = DB::connection('mysql')->table('tab_visitas')
                ->select('fecha', DB::raw('SUM(contador) as total'))
                ->where('pagina', 'inicio')
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->groupBy('fecha')
                ->orderBy('fecha', 'asc')
                ->get();

            $contador = DB::connection('mysql')->table('tab_visitas')
                ->selectRaw('SUM(contador) as total')
                ->first();
            
            // Acceder al valor:
            $totalValor = $contador->total;

            // Preparamos arrays para Chart.js
            $labels = $visitas->pluck('fecha');
            $dataTotal = $visitas->pluck('total');

            return view('Administrador.reportesContador.reportescontador', compact('labels', 'dataTotal', 'fechaInicio', 'fechaFin', 'totalValor'));
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function filtrar(Request $r)
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            // Si el usuario envía rango de fechas desde el selector
            $fechaInicio = $r->fecha_inicio;
            $fechaFin    = $r->fecha_fin;

            $visitas = DB::connection('mysql')->table('tab_visitas')
                ->select('fecha', DB::raw('SUM(contador) as total'))
                ->where('pagina', 'inicio')
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->groupBy('fecha')
                ->orderBy('fecha', 'asc')
                ->get();

            $contador = DB::connection('mysql')->table('tab_visitas')
                ->selectRaw('SUM(contador) as total')
                ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->first();
            
            // Acceder al valor:
            $totalValor = $contador->total;

            // Preparamos arrays para Chart.js
            $labels = $visitas->pluck('fecha');
            $dataTotal   = $visitas->pluck('total');

            return response()->json(['labels'=> $labels, 'data' => $dataTotal, 'totalValor'=> $totalValor]);
        }else{
            return response()->json(['resultado'=> false]);
        }
    }

    public function index_descargas_admin()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            /**
             * TABLA LEY DE TRANSPARENCIA
            */
            $cont_leytransparencia = DB::connection('mysql')
            ->table('tab_ley_transparencia')
            ->select(DB::raw('SUM(contador_descargas) as total'))
            ->where('estado', '=', '1')
            ->first();
            $totalLT = $cont_leytransparencia->total;

            $getFilesLT = DB::connection('mysql')
            ->table('tab_ley_transparencia')
            ->select('nombre_archivo as titulo', 'contador_descargas')
            ->where('estado', '=', '1')
            ->get();

            /**
             * TABLA PAC & PAC HISTORY
            */
            $cont_pac_noref = DB::connection('mysql')
            ->table('tab_pac')
            ->select(DB::raw('SUM(contador_descargas) as total'))
            ->where('estado', '=', '1')
            ->first();
            $totalpacnoref = $cont_pac_noref->total;

            $cont_pac_ref = DB::connection('mysql')
            ->table('tab_pac_history')
            ->select(DB::raw('SUM(contador_descargas) as total'))
            ->where('estado', '=', '1')
            ->first();
            $totalpacref = $cont_pac_ref->total;

            $total_pac_docs = $totalpacnoref + $totalpacref;


            $cont_pac_resol_noref = DB::connection('mysql')
            ->table('tab_pac')
            ->select(DB::raw('SUM(contador_descargas_resol) as total'))
            ->where('estado', '=', '1')
            ->first();
            $totalpacresolnoref = $cont_pac_resol_noref->total;

            $cont_pac_resol_ref = DB::connection('mysql')
            ->table('tab_pac_history')
            ->select(DB::raw('SUM(contador_descargas_resol) as total'))
            ->where('estado', '=', '1')
            ->first();
            $totalpacresolref = $cont_pac_resol_ref->total;

            $total_pac_resol= $totalpacresolnoref + $totalpacresolref;

            $getFilesPac = DB::connection('mysql')
            ->table('tab_pac')
            ->select('titulo', 'contador_descargas', 'resol_admin as resolucion', 'contador_descargas_resol')
            ->where('estado', '=', '1')
            ->get();

            $getFilesPacRef = DB::connection('mysql')
            ->table('tab_pac_history')
            ->select('titulo', 'contador_descargas', 'resol_admin as resolucion', 'contador_descargas_resol')
            ->where('estado', '=', '1')
            ->get();

            $ik=1;
            foreach ($getFilesPacRef as $k) {
                $k->observacion = 'Reforma #'.$ik;
                $ik++;
            }

            $ik=1;
            $datapac = $getFilesPac->merge($getFilesPacRef);

            /**
             * TABLA POA & POA HISTORY
            */
            $cont_poa_noref = DB::connection('mysql')
            ->table('tab_poa')
            ->select(DB::raw('SUM(contador_descargas) as total'))
            ->where('estado', '=', '1')
            ->first();
            $totalpoanoref = $cont_poa_noref->total;

            $cont_poa_ref = DB::connection('mysql')
            ->table('tab_poa_history')
            ->select(DB::raw('SUM(contador_descargas) as total'))
            ->where('estado', '=', '1')
            ->first();
            $totalpoaref = $cont_poa_ref->total;

            $total_poa = $totalpoanoref + $totalpoaref;

            $getFilesPoa = DB::connection('mysql')
            ->table('tab_poa')
            ->select('titulo', 'contador_descargas')
            ->where('estado', '=', '1')
            ->get();

            $getFilesPoaRef = DB::connection('mysql')
            ->table('tab_poa_history')
            ->select('titulo', 'contador_descargas')
            ->where('estado', '=', '1')
            ->get();

            foreach ($getFilesPoaRef as $k) {
                $k->observacion = 'Reforma #'.$ik;
                $ik++;
            }

            $ik=1;

            $datapoa = $getFilesPoa->merge($getFilesPoaRef);

            /**
             * TABLA PLIEGO TARIFARIO
            */
            $cont_pliegot = DB::connection('mysql')
            ->table('tab_pliego_tarifario')
            ->select(DB::raw('SUM(contador_descargas) as total'))
            ->where('estado', '=', '1')
            ->first();
            $totalPT = $cont_pliegot->total;

            $getFilesPT = DB::connection('mysql')
            ->table('tab_ley_transparencia')
            ->select('contador_descargas')
            ->where('estado', '=', '1')
            ->get();

            $getFilesPT->transform(function ($item) {
                $item->titulo = 'Pliego Tarifario';
                return $item;
            });

            /**
             * DOCUMENTACION ADMINISTRATIVA
            */
            $cont_docadmin = DB::connection('mysql')
            ->table('tab_doc_administrativo')
            ->select(DB::raw('SUM(contador_descargas) as total'))
            ->where('estado', '=', '1')
            ->first();
            $totalDA = $cont_docadmin->total;

            $getFilesDA = DB::connection('mysql')
            ->table('tab_doc_administrativo')
            ->select('titulo', 'contador_descargas')
            ->where('estado', '=', '1')
            ->get();

            /*------------------------------------------------------------------------------------ */

            $totalDocAdmin = $totalLT+$total_pac_docs+$total_pac_resol+$total_poa+$totalPT+$totalDA;

            $resultado = [
                [
                    'tabla'=> 'Ley de Transparencia',
                    'total'=> (int) $totalLT,
                    'archivos' => $getFilesLT
                ],
                [
                    'tabla'=> 'PAC',
                    'total'=> (int) $total_pac_docs,
                    'archivos' => $datapac
                ],
                [
                    'tabla'=> 'Resoluciones de PAC',
                    'total'=> (int) $total_pac_resol,
                    'archivos' => null
                ],
                [
                    'tabla'=> 'POA',
                    'total'=> (int) $total_poa,
                    'archivos' => $datapoa
                ],
                [
                    'tabla'=> 'Pliego Tarifario',
                    'total'=> (int) $totalPT,
                    'archivos'=> $getFilesPT
                ],
                [
                    'tabla'=> 'Documentación Administrativa',
                    'total'=> (int) $totalDA,
                    'archivos'=> $getFilesDA
                ]
            ];
            //return $resultado;
            return view('Administrador.reportesContador.reportescontadordescargas', ['resultado'=> $resultado, 'totalGeneral'=> $totalDocAdmin]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function index_descargas_fin()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            /**
             * DOCUMENTACION FINANCIERA
            */
            $cont_docfin = DB::connection('mysql')
            ->table('tab_doc_financiero')
            ->select(DB::raw('SUM(contador_descargas) as total'))
            ->where('estado', '=', '1')
            ->first();
            $totalDF = $cont_docfin->total;

            /*$getFilesDF = DB::connection('mysql')
            ->table('tab_doc_financiero as tf')
            ->join('tab_anio as ta', 'tf.id_anio', '=', 'ta.id')
            ->select('ta.nombre as anio','tf.titulo', 'tf.contador_descargas')
            ->where('tf.estado', '=', '1')
            ->orderBy('tf.id_anio', 'asc')
            ->get();*/

            $getFilesDF = DB::connection('mysql')
            ->table('tab_doc_financiero as tf')
            ->join('tab_anio as ta', 'tf.id_anio', '=', 'ta.id')
            ->select('ta.nombre as anio', 'tf.titulo', 'tf.contador_descargas')
            ->where('tf.estado', '=', '1')
            ->orderBy('tf.id_anio', 'asc')
            ->get()
            ->groupBy('anio')
            ->map(function ($items, $anio) {
                return [
                    'anio' => $anio,
                    'total' => $items->sum('contador_descargas'),
                    'archivos' => $items->map(function ($item) {
                        return [
                            'titulo' => $item->titulo,
                            'contador_descargas' => $item->contador_descargas
                        ];
                    })->values()
                ];
            })
            ->values();

            /*------------------------------------------------------------------------------------ */

            $totalDocFin = $totalDF;

            /*$resultado = [
                [
                    'tabla'=> 'Documentación Financiera',
                    'total'=> (int) $totalDF,
                    'informacion'=> $getFilesDF
                ]
            ];*/
            //return $getFilesDF;
            return view('Administrador.reportesContador.reportescontadordescargasfin', ['resultado'=> $getFilesDF, 'totalGeneral'=> $totalDocFin]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function index_descargas_opt()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            /**
             * DOCUMENTACION OPERATIVA
            */
            $cont_docopt = DB::connection('mysql')
            ->table('tab_doc_operativo')
            ->select(DB::raw('SUM(contador_descargas) as total'))
            ->where('estado', '=', '1')
            ->first();
            $totalDO = $cont_docopt->total;

            /*$getFilesDA = DB::connection('mysql')
            ->table('tab_doc_operativo as tf')
            ->join('tab_anio as ta', 'tf.id_anio', '=', 'ta.id')
            ->select('ta.nombre as anio','tf.titulo', 'tf.contador_descargas')
            ->where('tf.estado', '=', '1')
            ->orderBy('tf.id_anio', 'asc')
            ->get();*/

            $getFilesDO = DB::connection('mysql')
            ->table('tab_doc_operativo as tf')
            ->join('tab_anio as ta', 'tf.id_anio', '=', 'ta.id')
            ->select('ta.nombre as anio', 'tf.titulo', 'tf.contador_descargas')
            ->where('tf.estado', '=', '1')
            ->orderBy('tf.id_anio', 'asc')
            ->get()
            ->groupBy('anio')
            ->map(function ($items, $anio) {
                return [
                    'anio' => $anio,
                    'total' => $items->sum('contador_descargas'),
                    'archivos' => $items->map(function ($item) {
                        return [
                            'titulo' => $item->titulo,
                            'contador_descargas' => $item->contador_descargas
                        ];
                    })->values()
                ];
            })
            ->values();

            /*------------------------------------------------------------------------------------ */

            $totalDocOpt = $totalDO;

            /*$resultado = [
                [
                    'tabla'=> 'Documentación Financiera',
                    'total'=> (int) $totalDF,
                    'informacion'=> $getFilesDO
                ]
            ];*/
            //return $getFilesDO;
            return view('Administrador.reportesContador.reportescontadordescargasoperativo', ['resultado'=> $getFilesDO, 'totalGeneral'=> $totalDocOpt]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function index_descargas_lab()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            /**
             * DOCUMENTACION LABORAL
            */
            $cont_doclab = DB::connection('mysql')
            ->table('tab_doc_laboral')
            ->select(DB::raw('SUM(contador_descargas) as total'))
            ->where('estado', '=', '1')
            ->first();
            $totalDL = $cont_doclab->total;

            /*$getFilesDA = DB::connection('mysql')
            ->table('tab_doc_laboral as tf')
            ->join('tab_anio as ta', 'tf.id_anio', '=', 'ta.id')
            ->select('ta.nombre as anio','tf.titulo', 'tf.contador_descargas')
            ->where('tf.estado', '=', '1')
            ->orderBy('tf.id_anio', 'asc')
            ->get();*/

            $getFilesDL = DB::connection('mysql')
            ->table('tab_doc_laboral as tf')
            ->join('tab_anio as ta', 'tf.id_anio', '=', 'ta.id')
            ->select('ta.nombre as anio', 'tf.titulo', 'tf.contador_descargas')
            ->where('tf.estado', '=', '1')
            ->orderBy('tf.id_anio', 'asc')
            ->get()
            ->groupBy('anio')
            ->map(function ($items, $anio) {
                return [
                    'anio' => $anio,
                    'total' => $items->sum('contador_descargas'),
                    'archivos' => $items->map(function ($item) {
                        return [
                            'titulo' => $item->titulo,
                            'contador_descargas' => $item->contador_descargas
                        ];
                    })->values()
                ];
            })
            ->values();

            /*------------------------------------------------------------------------------------ */

            $totalDocLab = $totalDL;

            /*$resultado = [
                [
                    'tabla'=> 'Documentación Financiera',
                    'total'=> (int) $totalDF,
                    'informacion'=> $getFilesDL
                ]
            ];*/
            //return $getFilesDL;
            return view('Administrador.reportesContador.reportescontadordescargaslab', ['resultado'=> $getFilesDL, 'totalGeneral'=> $totalDocLab]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function index_descargas_ley()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            /**
             * REGLAMENTOS
            */
            $cont_docley = DB::connection('mysql')
            ->table('tab_reglamentos')
            ->select(DB::raw('SUM(contador_descargas) as total'))
            ->where('estado', '=', '1')
            ->first();
            $totalDL = $cont_docley->total;

            $getFilesDL = DB::connection('mysql')
            ->table('tab_reglamentos as tf')
            ->select('tf.nombre_archivo as titulo', 'tf.contador_descargas as total')
            ->where('tf.estado', '=', '1')
            ->get();

            /*------------------------------------------------------------------------------------ */

            $totalDocLey = $totalDL;

            /*$resultado = [
                [
                    'tabla'=> 'Documentación Financiera',
                    'total'=> (int) $totalDF,
                    'informacion'=> $getFilesDL
                ]
            ];*/
            //return $getFilesDL;
            return view('Administrador.reportesContador.reportescontadordescargasley', ['resultado'=> $getFilesDL, 'totalGeneral'=> $totalDocLey]);
        }else{
            return redirect('/loginadmineep');
        }
    }
}
