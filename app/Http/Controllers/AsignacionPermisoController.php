<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AsignacionPermisoController extends Controller
{
    public function mostrarAsignacion($idRol)
    {   
        $idRol = desencriptarNumero($idRol);

        $namerol = $this->getNamePerfil($idRol);

        // Obtener todos los módulos
        $modulos = DB::table('tab_modulo')->select('id','nombre')->get();

        // Para cada módulo, obtener sus submódulos manualmente
        foreach ($modulos as $modulo) {
            $submodulos = DB::table('tab_submodulo')
                ->select('id','submodulo')
                ->where('idmodulo', $modulo->id)
                ->get();
            $modulo->submodulos = $submodulos;
        }

        // Obtener todos los permisos actuales para el rol
        $permisos = DB::table('tab_asig_rol_mod')
            ->where('idperfil', $idRol)
            ->get()
            ->map(function ($permiso) {
                return [
                    'id' => $permiso->id,
                    'modulo' => $permiso->idmodulo,
                    'submodulo' => $permiso->idsubmodulo,
                ];
            });

        return view('Administrador.Rol.rolpermiso', compact('modulos', 'idRol', 'permisos', 'namerol'));
    }

    public function actualizarPermiso(Request $request)
    {
        $fecha = now();
        $datos = $request->only(['id_rol', 'id_modulo', 'id_submodulo', 'asignar']);

        if ($datos['asignar']) {
            $sql_insert = DB::connection('mysql')->insert('insert into tab_asig_rol_mod (
                idperfil, idmodulo, idsubmodulo, created_at
            ) values (?,?,?,?)', [$datos['id_rol'], $datos['id_modulo'], $datos['id_submodulo'], $fecha]);
            
            if($sql_insert){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        } else {
            $sql_delete = DB::connection('mysql')->table('tab_asig_rol_mod')
            ->where('idperfil','=', $datos['id_rol'])
            ->where('idmodulo', $datos['id_modulo'])
            ->where(function ($q) use ($datos) {
                    if ($datos['id_submodulo']) {
                        $q->where('idsubmodulo', $datos['id_submodulo']);
                    } else {
                        $q->whereNull('idsubmodulo');
                    }
                })
            ->delete();

            if($sql_delete){
                return response()->json(["resultado"=> true]);
            }else{
                return response()->json(["resultado"=> false]);
            }
        }
    }

    private function getNamePerfil($id){
        $sql= DB::connection('mysql')->select('SELECT nombre FROM tab_perfil_usuario WHERE id=?', [$id]);

        $resultado= "";

        foreach($sql as $r){
            $resultado= $r->nombre;
        }

        return $resultado;
    }
}
