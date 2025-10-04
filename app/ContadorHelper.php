<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ContadorHelper
{
    public static function incrementarDescarga(string $tabla, int $id): void
    {
        try {
            // Lista blanca de tablas donde se permite incrementar el contador
            $permitidas = [
                'tab_archivos_mediosv',
                'tab_archivos_rendicion_cuentas',
                'tab_auditoria',
                'tab_bv_archivos',
                'tab_doc_administrativo',
                'tab_doc_financiero',
                'tab_doc_laboral',
                'tab_doc_operativo',
                'tab_ley_transparencia',
                'tab_lotaip',
                'tab_poa',
                'tab_poa_history',
                'tab_reglamentos',
                'tab_subservicio_files'
            ];

            if (!in_array($tabla, $permitidas)) {
                Log::warning("Intento de actualización en tabla no permitida: {$tabla}");
                return;
            }
            
            DB::table($tabla)->where('id', $id)->increment('contador_descargas');
            
        } catch (\Throwable $e) {
            Log::error("Error incrementando contador en {$tabla} ID {$id}: " . $e->getMessage());
        }
    }

    public static function incrementarDescargaEspecial(string $tabla, int $id, string $parametro): void
    {
        try {
            // Lista blanca de tablas donde se permite incrementar el contador
            $permitidas = [
                'tab_lotaip_v2',
                'tab_pac',
                'tab_pac_history'
            ];

            if (!in_array($tabla, $permitidas)) {
                Log::warning("Intento de actualización en tabla no permitida: {$tabla}");
                return;
            }


            if($tabla == 'tab_lotaip_v2'){
                if($parametro=='datos'){
                    DB::table($tabla)->where('id', $id)->increment('contador_descargas');
                }else if($parametro=='cdatos'){
                    DB::table($tabla)->where('id', $id)->increment('contador_descargas_cdatos');
                }else if($parametro=='mdatos'){
                    DB::table($tabla)->where('id', $id)->increment('contador_descargas_mdatos');
                }else if($parametro=='ddatos'){
                    DB::table($tabla)->where('id', $id)->increment('contador_descargas_ddatos');
                }
            }else if($tabla == 'tab_pac'){
                if($parametro=='archivo'){
                    DB::table($tabla)->where('id', $id)->increment('contador_descargas');
                }else if($parametro=='resoladmin'){
                    DB::table($tabla)->where('id', $id)->increment('contador_descargas_resol');
                }
            }else if($tabla == 'tab_pac_history'){
                if($parametro=='archivo'){
                    DB::table($tabla)->where('id', $id)->increment('contador_descargas');
                }else if($parametro=='resoladmin'){
                    DB::table($tabla)->where('id', $id)->increment('contador_descargas_resol');
                }
            }
            
        } catch (\Throwable $e) {
            Log::error("Error incrementando contador en {$tabla} ID {$id}: " . $e->getMessage());
        }
    }

    public static function incrementarDescargaLotaip(string $tabla, int $id, string $parametro): void
    {
        try {
            // Lista blanca de tablas donde se permite incrementar el contador
            $permitidas = [
                'tab_lotaip_v2'
            ];

            if (!in_array($tabla, $permitidas)) {
                Log::warning("Intento de actualización en tabla no permitida: {$tabla}");
                return;
            }

            if($parametro=='datos'){
                DB::table($tabla)->where('id', $id)->increment('contador_descargas');
            }else{
                $campo = "contador_descargas_{$parametro}";
                DB::table($tabla)->where('id', $id)->increment($campo);
            }
            
        } catch (\Throwable $e) {
            Log::error("Error incrementando contador en {$tabla} ID {$id}: " . $e->getMessage());
        }
    }
}