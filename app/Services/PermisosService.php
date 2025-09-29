<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class PermisosService
{
    public function generarPermisosOriginal($userId)
    {
        // 1. Consultar permisos desde BD
        $permisos = DB::connection('mysql')->select('SELECT 
                m.id AS idmodulo,
                m.nombre AS modulo,
                m.estado_vis_novis AS mod_visible,
                m.icono,
                s.id AS idsubmodulo,
                s.submodulo,
                s.estado_vis_novis AS submod_visible,
                p.guardar,
                p.actualizar,
                p.eliminar,
                p.descargar,
                p.configurar
                FROM tab_permisos p
                INNER JOIN tab_modulo m ON m.id = p.idmodulo
                LEFT JOIN tab_submodulo s ON s.id = p.idsubmodulo
                WHERE p.idusuario = ?', [$userId]);

        // 2. Cargar JSON de rutas
        $menuJson = json_decode(Storage::get('menu_config.json'), true);

        // 3. Vincular rutas a permisos
        foreach ($permisos as $permiso) {
            // Inicializamos las propiedades para que siempre existan
            $permiso->ruta_modulo = null;
            $permiso->ruta_submodulo = null;

            foreach ($menuJson as $mod) {
                if ($mod['modulo'] === $permiso->modulo) {
                    $permiso->ruta_modulo = $mod['ruta'] ?? '#';

                    foreach ($mod['submodulos'] as $sub) {
                        if ($sub['nombre'] === $permiso->submodulo) {
                            $permiso->ruta_submodulo = $sub['ruta'] ?? '#';
                        }
                    }
                }
            }
        }

        return $permisos;
    }

    public function generarPermisos($userId){
        return Cache::remember("permisos_user_{$userId}", now()->addMinutes(30), function() use ($userId){
           // 1. Consultar permisos desde BD
            $permisos = DB::connection('mysql')->select('SELECT 
                    m.id AS idmodulo,
                    m.nombre AS modulo,
                    m.estado_vis_novis AS mod_visible,
                    m.icono,
                    s.id AS idsubmodulo,
                    s.submodulo,
                    s.estado_vis_novis AS submod_visible,
                    p.guardar,
                    p.actualizar,
                    p.eliminar,
                    p.descargar,
                    p.configurar
                    FROM tab_permisos p
                    INNER JOIN tab_modulo m ON m.id = p.idmodulo
                    LEFT JOIN tab_submodulo s ON s.id = p.idsubmodulo
                    WHERE p.idusuario = ? ORDER BY m.nivel_prioridad ASC', [$userId]);

            // 2. Cargar JSON de rutas
            $menuJson = json_decode(Storage::get('menu_config.json'), true);

            // 3. Vincular rutas a permisos
            foreach ($permisos as $permiso) {
                // Inicializamos las propiedades para que siempre existan
                $permiso->ruta_modulo = null;
                $permiso->ruta_submodulo = null;

                foreach ($menuJson as $mod) {
                    if ($mod['modulo'] === $permiso->modulo) {
                        $permiso->ruta_modulo = $mod['ruta'] ?? '#';

                        foreach ($mod['submodulos'] as $sub) {
                            if ($sub['nombre'] === $permiso->submodulo) {
                                $permiso->ruta_submodulo = $sub['ruta'] ?? '#';
                            }
                        }
                    }
                }
            }

            return $permisos;
        });
    }

    public function clearPermisos($userId)
    {
        Cache::forget("permisos_user_{$userId}");
    }
}