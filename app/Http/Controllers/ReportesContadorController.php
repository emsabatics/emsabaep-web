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

    public function index_descargas_auditoria()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            /**
             * DOCUMENTACION AUDITORIA
            */
            $cont_docaud = DB::connection('mysql')
            ->table('tab_auditoria')
            ->select(DB::raw('SUM(contador_descargas) as total'))
            ->where('estado', '=', '1')
            ->first();
            $totalDL = $cont_docaud->total;

            /*$getFilesDA = DB::connection('mysql')
            ->table('tab_auditoria as tf')
            ->join('tab_anio as ta', 'tf.id_anio', '=', 'ta.id')
            ->select('ta.nombre as anio','tf.titulo', 'tf.contador_descargas')
            ->where('tf.estado', '=', '1')
            ->orderBy('tf.id_anio', 'asc')
            ->get();*/

            $getFilesDL = DB::connection('mysql')
            ->table('tab_auditoria as tf')
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
            return view('Administrador.reportesContador.reportescontadordescargasauditoria', ['resultado'=> $getFilesDL, 'totalGeneral'=> $totalDocLab]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function index_descargas_rendicionc()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            /**
             * DOCUMENTACION RENDICION DE CUENTAS
            */
            $cont_docrc = DB::connection('mysql')
            ->table('tab_archivos_rendicion_cuentas')
            ->select('tipo', DB::raw('SUM(contador_descargas) as total'))
            ->where('estado', '=', '1')
            ->groupBy('tipo')
            ->orderBy('tipo', 'asc')
            ->get()
            ->map(function ($item) {
                $item->total = (int) $item->total;
                return $item;
            });

            $sumGeneral = 0;
            foreach($cont_docrc as $rc){
                $sumGeneral += $rc->total; 
            }

            $getFilesRC = DB::connection('mysql')
            ->table('tab_archivos_rendicion_cuentas as arc')
            ->join('tab_rendicion_cuentas as rc', 'arc.id_rendicion_cuenta', '=', 'rc.id')
            ->join('tab_anio as anio', 'rc.id_anio', '=', 'anio.id')
            ->select(
                'anio.nombre as anio',
                'arc.tipo',
                'arc.titulo',
                'arc.contador_descargas'
            )
            ->where('arc.estado', '=', '1')
            ->orderBy('anio.nombre', 'asc')
            ->orderBy('arc.tipo', 'asc')
            ->get()
            ->groupBy('anio')
            ->map(function ($porAnio, $anio) {
                return [
                    'anio' => $anio,
                    'categorias' => $porAnio->groupBy('tipo')->map(function ($items, $tipo) {
                        return [
                            'tipo' => $tipo,
                            'total' => (int) $items->sum('contador_descargas'),
                            'archivos' => $items->map(function ($i) {
                                return [
                                    'titulo' => $i->titulo,
                                    'contador' => (int) $i->contador_descargas,
                                ];
                            })->values()
                        ];
                    })->values()
                ];
            })
            ->values();

            /*------------------------------------------------------------------------------------ */

            $seriesData = $getFilesRC
                ->flatMap(function ($anioData) {
                    return collect($anioData['categorias'])->map(function ($tipoData) use ($anioData) {
                        return [
                            'anio' => $anioData['anio'],
                            'tipo' => $tipoData['tipo'],
                            'total' => $tipoData['total'],
                        ];
                    });
                })
                ->groupBy('tipo')
                ->map(function ($items, $tipo) {
                    return [
                        'name' => ucfirst($tipo).'s',
                        'data' => $items->pluck('total')->map(function($v) {
                            return (int) $v;
                        })->values()
                    ];
                })
                ->values();

            $categories = $getFilesRC->pluck('anio')->values();

            return view('Administrador.reportesContador.reportescontadordescargasrendicionc', ['resultado'=> $getFilesRC, 'totalportipo'=> $cont_docrc, 'totalGeneral'=> $sumGeneral, 'seriesData' => $seriesData,
                    'categories' => $categories]);
        }else{
            return redirect('/loginadmineep');
        }
    }

    public function index_descargas_lotaipv1()
    {
        if(Session::get('usuario') && (Session::get('tipo_usuario')=='administrador')){
            $mesesReferencia = [
                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
            ];

            /**
             * DOCUMENTACION LOTAIP
            */
            $cont_lotaip = DB::connection('mysql')
            ->table('tab_lotaip as tl')
            ->join('tab_anio as anio', 'tl.id_anio', '=', 'anio.id')
            ->select('tl.id_anio', 'anio.nombre as anio', DB::raw('SUM(tl.contador_descargas) as total'))
            ->where('tl.estado', '=', '1')
            ->groupBy('tl.id_anio', 'anio.nombre')
            ->get()
            ->map(function ($items) {
                return [
                    'anio' => $items->anio,
                    'total' =>  (int) $items->total
                ];
            })
            ->values();

            $sumGeneral = 0;
            foreach($cont_lotaip as $rc){
                $sumGeneral += $rc['total']; 
            }

            $getFilesRC = DB::connection('mysql')
            ->table('tab_lotaip as lt')
            ->join('tab_meses as m', 'lt.id_mes', '=', 'm.id')
            ->join('tab_anio as anio', 'lt.id_anio', '=', 'anio.id')
            ->join('tab_item_lotaip as ilt', 'lt.id_item_lotaip', '=', 'ilt.id')
            ->select(
                'anio.nombre as anio',
                'm.id as num_mes',
                'm.mes',
                'ilt.literal',
                'ilt.descripcion',
                'lt.contador_descargas'
            )
            ->where('lt.estado', '=', '1')
            ->orderBy('anio.nombre', 'asc')
            ->orderBy('m.id', 'asc')
            ->orderBy('ilt.id', 'asc')
            ->get()
            ->groupBy('anio')
            ->map(function($porAnio, $anio) use ($mesesReferencia){
                // Agrupar los meses con datos
                $meses = $porAnio->groupBy('num_mes')->map(function($items){
                    $primer = $items->first();
                    return [
                        'mes' => $primer->mes,
                        'total' => (int) $items->sum('contador_descargas'),
                        'archivos' => $items->map(function($i){
                            return [
                                'titulo' => $i->literal.' - '.$i->descripcion,
                                'contador' => (int) $i->contador_descargas,
                            ];
                        })->values()
                    ];
                });

                // Agregar meses sin datos con total 0
                foreach ($mesesReferencia as $num => $nombreMes) {
                    if (!isset($meses[$num])) {
                        $meses[$num] = [
                            'mes' => $nombreMes,
                            'total' => 0,
                            'archivos' => []
                        ];
                    }
                }

                // Ordenar por número de mes (usando sortKeys)
                $meses = $meses->sortKeys()->values();

                return [
                    'anio' => $anio,
                    'meses' => $meses
                ];
            })
            ->values();
            /*->map(function ($porAnio, $anio) {
                return [
                    'anio' => $anio,
                    'meses' => $porAnio->groupBy('num_mes')->map(function ($items) {
                        $primer = $items->first();
                        return [
                            'mes' => $primer->mes,
                            'total' => (int) $items->sum('contador_descargas'),
                            'archivos' => $items->map(function ($i) {
                                return [
                                    'titulo' => $i->literal.' - '.$i->descripcion,
                                    'contador' => (int) $i->contador_descargas,
                                ];
                            })->values()
                        ];
                    })->values()
                ];
            })
            ->values();*/

            /*------------------------------------------------------------------------------------ */

            $seriesData = $getFilesRC
                ->flatMap(function ($anioData) {
                    return collect($anioData['meses'])->map(function ($tipoData) use ($anioData) {
                        return [
                            'anio' => $anioData['anio'],
                            'mes' => $tipoData['mes'],
                            'total' => $tipoData['total'],
                        ];
                    });
                })
                ->groupBy('mes')
                ->map(function ($items, $mes) {
                    return [
                        'name' => $mes,
                        'data' => $items->pluck('total')->map(function($v) {
                            return (int) $v;
                        })->values()
                    ];
                })
                ->values();

            $categories = $getFilesRC->pluck('anio')->values();

            return view('Administrador.reportesContador.reportescontadordescargasrendicionc', ['resultado'=> $getFilesRC, 'totalGeneral'=> $sumGeneral, 'seriesData' => $seriesData,
                    'categories' => $categories]);
        }else{
            return redirect('/loginadmineep');
        }
    }
}
