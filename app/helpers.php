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

    function getAllSocialMediaGeneral(){
        $socialmedia= DB::connection('mysql')->table('tab_social_media')
            ->join('tab_red_social', 'tab_social_media.id_red_social', '=', 'tab_red_social.id')
            ->select('tab_social_media.*', 'tab_red_social.nombre')
            ->where('tab_social_media.estado','1')
            ->get();

        return $socialmedia;
    }

    function getPhoneNumber(){
        $contactos= DB::connection('mysql')->table('tab_contactos')->where('tipo_contacto','=','telefono')->where('estado','1')->get();
        $resultado='';
        foreach($contactos as $ct){
            $resultado = $ct->telefono;
        }
        $subcadena = substr($resultado, 1, strlen($resultado)-1); 
        return $subcadena;
    }

    if (!function_exists('encriptarNumero')) {
        function encriptarNumero($numero)
        {
            // Obtener la clave desde el archivo de configuración
            $clave = config('encryption.key');
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
            $numeroEncriptado = openssl_encrypt($numero, 'aes-256-cbc', $clave, 0, $iv);
            //return base64_encode($numeroEncriptado . '::' . $iv);

            // Convertir a Base64 y reemplazar caracteres problemáticos
            $base64 = base64_encode($numeroEncriptado . '::' . $iv);
            //$safeBase64 = strtr($base64, ['+' => '-', '/' => '_', '=' => '']);
            $safeBase64 = strtr($base64, ['/' => '_']);

            return $safeBase64;
        }
    }
    
    if (!function_exists('desencriptarNumero')) {
        function desencriptarNumero($valorEncriptado)
        {
            // Obtener la clave desde el archivo de configuración
            $clave = config('encryption.key');

            // Restaurar los caracteres originales
            //$base64 = strtr($valorEncriptado, ['-' => '+', '_' => '/']);
            $base64 = strtr($valorEncriptado, ['_' => '/']);

            //list($numeroEncriptado, $iv) = explode('::', base64_decode($valorEncriptado), 2);
            list($numeroEncriptado, $iv) = explode('::', base64_decode($base64), 2);
            return openssl_decrypt($numeroEncriptado, 'aes-256-cbc', $clave, 0, $iv);
        }
    }

    if(!function_exists('convertirABase64')){
        function convertirABase64($dato)
        {
            return base64_encode($dato);
        }
    }

    if(!function_exists('convertirDesdeBase64')){
        function convertirDesdeBase64($datoBase64)
        {
            return base64_decode($datoBase64);
        }
    }

    function cortar_texto($texto, $limite = 100)
    {
        if (strlen($texto) <= $limite) return $texto;

        $vocales = ['a','e','i','o','u','á','é','í','ó','ú'];
        $anterior = strtolower($texto[$limite - 1] ?? '');
        $actual = strtolower($texto[$limite] ?? '');

        // Si el corte cae entre dos vocales (posible diptongo o hiato)
        if (in_array($anterior, $vocales) && in_array($actual, $vocales)) {
            $limite = strpos($texto, ' ', $limite) ?: $limite;
        } elseif ($texto[$limite] !== ' ' && $texto[$limite + 1] !== ' ') {
            $limite = strpos($texto, ' ', $limite) ?: $limite;
        }

        $parte1 = substr($texto, 0, $limite) . '-';
        $parte2 = ltrim(substr($texto, $limite));

        return $parte1 . '<br>' . $parte2;
    }
?>