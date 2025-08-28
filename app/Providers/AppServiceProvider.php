<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        View::composer('*', function ($view) {
            $userId = $this->getIdUser();

            $permisos = DB::table('tab_permisos as p')
            ->join('tab_modulo as m', 'm.id', '=', 'p.idmodulo')
            ->leftJoin('tab_submodulo as s', 's.id', '=', 'p.idsubmodulo')  // LEFT JOIN
            ->where('p.idusuario', $userId)
            ->select(
                'm.id as idmodulo',
                'm.nombre as modulo',
                'm.icono',
                's.id as idsubmodulo',
                's.submodulo',
                'p.guardar',
                'p.actualizar',
                'p.eliminar',
                'p.descargar'
            )
            ->orderBy('m.id')
            ->orderBy('s.id')
            ->get();

            // 1. Obtener permisos desde BD
            // Cache del menú por usuario (10 minutos de ejemplo)
            /*$permisos = Cache::remember("menu_usuario_{$userId}", 5, function () use ($userId) {
                return DB::table('tab_permisos as p')
                ->join('tab_modulo as m', 'm.id', '=', 'p.idmodulo')
                ->leftJoin('tab_submodulo as s', 's.id', '=', 'p.idsubmodulo')  // LEFT JOIN
                ->where('p.idusuario', $userId)
                ->select(
                    'm.id as idmodulo',
                    'm.nombre as modulo',
                    'm.icono',
                    's.id as idsubmodulo',
                    's.submodulo',
                    'p.guardar',
                    'p.actualizar',
                    'p.eliminar',
                    'p.descargar'
                )
                ->orderBy('m.id')
                ->orderBy('s.id')
                ->get();
            });*/

            // 2. Cargar JSON de rutas
            $menuJson = json_decode(Storage::get('menu_config.json'), true);

            // 3. Vincular rutas a permisos
            foreach ($permisos as $permiso) {
                // Inicializamos las propiedades para que siempre existan
                /*$permiso->ruta_modulo = null;
                $permiso->ruta_submodulo = null;*/

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

            // 4. Variables para JS — permisos agrupados por submódulo
            $permisosJS = $permisos->map(function ($item) {
                return [
                    'modulo' => $item->modulo,
                    'submodulo' => $item->submodulo,
                    'guardar' => $item->guardar,
                    'actualizar' => $item->actualizar,
                    'eliminar' => $item->eliminar,
                    'descargar' => $item->descargar
                ];
            });

            // 5. Compartir con todas las vistas
            $view->with('permisosMenu', $permisos);
            $view->with('permisosJS', $permisosJS);
        });
    }

    private function getIdUser(){
        $user = Session::get('usuario');

        $sql = DB::connection('mysql')->table('users')->select('id')->where('user','=', $user)->get();

        $iduser = 0;

        foreach($sql as $s){
            $iduser = $s->id;
        }

        return $iduser;
    }
}
