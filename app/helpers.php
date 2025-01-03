<?php
    use Illuminate\Support\Facades\DB;

    function setActive($routeName){
        return request()->routeIs($routeName) ? 'active': '';
    }

    function setOpen($routeName, $opcion, $posicion){
        $arrayurl= array("registrar_noticia", "noticias", "mi-vi-va-ob");

        if($opcion==='noticias' && $posicion==1){
            return 'menu-open';
        }else if($opcion==='institucion' && $posicion==2){
            return 'menu-open';
        }else{
            return '';
        }
        /*$arraymenu= array("noticias","institucion");
        $key = array_search($opcion, $arraymenu);
        $key= $key+1;

        if(in_array($routeName, $arrayurl) && ($key==$posicion))
            return 'menu-open';
        else
            return  '';*/
    }

    function getNameInstitucion(){
        return "EMSABA EP";
    }

    function getFullNameInstitucion(){
        return "Empresa Pública Municipal de Saneamiento Ambiental de Babahoyo";
    }

    function getLogos(){
        $estado= '1';
        $logo= DB::connection('mysql')->table('tab_logo')->select('archivo')->where('estado','=', $estado)->get();

        return $logo;
    }
?>