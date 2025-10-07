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
                'tab_subservicio_files',
                'tab_pliego_tarifario'
            ];

            if (!in_array($tabla, $permitidas)) {
                Log::warning("Intento de actualización en tabla no permitida: {$tabla}");
                return;
            }
            
            DB::table($tabla)->where('id', '=', $id)->increment('contador_descargas');
            
        } catch (\Throwable $e) {
            Log::error("Error incrementando contador en {$tabla} ID {$id}: " . $e->getMessage());
        }
    }

    public static function incrementarDescargaLotaip2cdatos(string $tabla, int $id): void
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

            DB::table($tabla)->where('id', '=', $id)->increment('contador_descargas_cdatos');

        } catch (\Throwable $e) {
            Log::error("Error incrementando contador en {$tabla} ID {$id}: " . $e->getMessage());
        }
    }

    public static function incrementarDescargaLotaip2mdatos(string $tabla, int $id): void
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
            Log::info("Incremento ejecutado para {$tabla} ID {$id}");
            DB::table($tabla)->where('id', $id)->increment('contador_descargas_mdatos');
            
        } catch (\Throwable $e) {
            Log::error("Error incrementando contador en {$tabla} ID {$id}: " . $e->getMessage());
        }
    }

    public static function incrementarDescargaLotaip2ddatos(string $tabla, int $id): void
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
            Log::info("Incremento ejecutado para {$tabla} ID {$id}");
            DB::table($tabla)->where('id', $id)->increment('contador_descargas_ddatos');
            
        } catch (\Throwable $e) {
            Log::error("Error incrementando contador en {$tabla} ID {$id}: " . $e->getMessage());
        }
    }

    public static function incrementarDescargaLotaip2(string $tabla, int $id): void
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
            
            DB::table($tabla)->where('id', $id)->increment('contador_descargas');
            
        } catch (\Throwable $e) {
            Log::error("Error incrementando contador en {$tabla} ID {$id}: " . $e->getMessage());
        }
    }

    public static function incrementarDescargaPac(string $tabla, int $id): void
    {
        try {
            // Lista blanca de tablas donde se permite incrementar el contador
            $permitidas = [
                'tab_pac',
                'tab_pac_history'
            ];

            if (!in_array($tabla, $permitidas)) {
                Log::warning("Intento de actualización en tabla no permitida: {$tabla}");
                return;
            }
            
            DB::table($tabla)->where('id', '=', $id)->increment('contador_descargas');
            
        } catch (\Throwable $e) {
            Log::error("Error incrementando contador en {$tabla} ID {$id}: " . $e->getMessage());
        }
    }

    public static function incrementarDescargaPacResol(string $tabla, int $id): void
    {
        try {
            // Lista blanca de tablas donde se permite incrementar el contador
            $permitidas = [
                'tab_pac',
                'tab_pac_history'
            ];

            if (!in_array($tabla, $permitidas)) {
                Log::warning("Intento de actualización en tabla no permitida: {$tabla}");
                return;
            }
            
            DB::table($tabla)->where('id', '=', $id)->increment('contador_descargas_resol');
            
        } catch (\Throwable $e) {
            Log::error("Error incrementando contador en {$tabla} ID {$id}: " . $e->getMessage());
        }
    }
}