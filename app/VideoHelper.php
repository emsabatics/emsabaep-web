<?php

namespace App;


class VideoHelper
{
    public static function getPlatformData($url)
    {
        $url = strtolower($url);

        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            return [
                'name' => 'YouTube',
                'logo' => asset('assets/viewmain/img/logos_socialmedia/logo_youtube_blanco.png'),
                'color' => '#FF0000',
            ];
        }

        if (str_contains($url, 'facebook.com')) {
            return [
                'name' => 'Facebook',
                'logo' => asset('assets/viewmain/img/logos_socialmedia/logo_facebook_blanco.png'),
                'color' => '#1877F2',
            ];
        }

        if (str_contains($url, 'instagram.com')) {
            return [
                'name' => 'Instagram',
                'logo' => asset('assets/viewmain/img/logos_socialmedia/logo_instagram_blanco.png'),
                'color' => 'linear-gradient(to right, #feda75, #fa7e1e, #d62976, #962fbf, #4f5bd5)',
            ];
        }

        if (str_contains($url, 'tiktok.com')) {
            return [
                'name' => 'TikTok',
                'logo' => asset('assets/viewmain/img/logos_socialmedia/logo_tik_tok_blanco.png'),
                'color' => '#010101',
            ];
        }

        return [
            'name' => 'su Plataforma',
            'logo' => asset('assets/viewmain/img/logos_socialmedia/logo_default_otro.png'),
            'color' => '#0059a5',
        ];
    }
}