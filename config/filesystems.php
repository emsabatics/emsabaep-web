<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'img_historia' => [
            'driver' => 'local',
            'root' => storage_path('app/img_historia'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'img_estructura' => [
            'driver' => 'local',
            'root' => storage_path('app/img_estructura'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'img_eventos' => [
            'driver' => 'local',
            'root' => storage_path('app/img_eventos'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'img_banner' => [
            'driver' => 'local',
            'root' => storage_path('app/img_banner'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'img_banner_alcaldia' => [
            'driver' => 'local',
            'root' => storage_path('app/img_banner_alcaldia'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'img_noticias' => [
            'driver' => 'local',
            'root' => storage_path('app/img_noticias'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'img_files' => [
            'driver' => 'local',
            'root' => storage_path('app/img_files'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'img_servicios' => [
            'driver' => 'local',
            'root' => storage_path('app/img_servicios'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'doc_poa' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/poa'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'doc_pac' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/pac'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'doc_reglamentos' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/reglamentos'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'doc_pliego_tarifario' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/pliego_tarifario'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'doc_lotaip' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/lotaip'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'doc_ley_transparencia' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/ley_transparencia'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'doc_auditoria' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/auditoria'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'doc_rendicion_c' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/rendicion_cuentas'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'doc_medios_v' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/medios_verificacion'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'doc_administrativo' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/doc_administrativo'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'doc_financiero' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/doc_financiero'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'doc_operativo' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/doc_operativo'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'doc_laboral' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/doc_laboral'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'biblioteca_virtual' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/biblioteca_virtual'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'galeria_virtual' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/biblioteca_virtual_galeria'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        'videos_virtual' => [
            'driver' => 'local',
            'root' => storage_path('app/documentos/biblioteca_virtual_videos'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
        public_path('historia-img')=> storage_path('app/img_historia'),
        public_path('estructura-img')=> storage_path('app/img_estructura'),
        public_path('eventos-img')=> storage_path('app/img_eventos'),
        public_path('banner-img')=> storage_path('app/img_banner'),
        public_path('noticias-img')=> storage_path('app/img_noticias'),
        public_path('files-img')=> storage_path('app/img_files'),
        public_path('servicios-img')=> storage_path('app/img_servicios'),
        public_path('doc-poa')=> storage_path('app/documentos/poa'),
        public_path('doc-pac')=> storage_path('app/documentos/pac'),
        public_path('doc-reglamentos')=> storage_path('app/documentos/reglamentos'),
        public_path('doc-pliego-tarifario')=> storage_path('app/documentos/pliego_tarifario'),
        public_path('doc-lotaip')=> storage_path('app/documentos/lotaip'),
        public_path('doc-ley-transparencia')=> storage_path('app/documentos/ley_transparencia'),
        public_path('doc-auditoria')=> storage_path('app/documentos/auditoria'),
        public_path('doc-rendicion-cuentas')=> storage_path('app/documentos/rendicion_cuentas'),
        public_path('doc-medios-verificacion')=> storage_path('app/documentos/medios_verificacion'),
        public_path('doc-financiero')=> storage_path('app/documentos/doc_financiero'),
        public_path('doc-operativo')=> storage_path('app/documentos/doc_operativo'),
        public_path('doc-laboral')=> storage_path('app/documentos/doc_laboral'),
        public_path('doc-administrativo')=> storage_path('app/documentos/doc_administrativo'),
        public_path('doc-bibliotecavirtual')=> storage_path('app/documentos/biblioteca_virtual'),
        public_path('banner-alcaldia-img')=> storage_path('app/img_banner_alcaldia'),
        public_path('galeria-bibliotecavirtual')=> storage_path('app/documentos/biblioteca_virtual_galeria'),
        public_path('videos-bibliotecavirtual')=> storage_path('app/documentos/biblioteca_virtual_videos'),
    ],

];
