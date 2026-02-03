<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title & Logo
    |--------------------------------------------------------------------------
    */

    'title' => 'Gestión Médica',
    'title_prefix' => '',
    'title_postfix' => '',

    'use_ico_only' => false,
    'use_full_favicon' => false,

    'google_fonts' => [
        'allowed' => true,
    ],

    'logo' => '<b>HOSPITAL</b> SISTEMA',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Admin Logo',

    /*
    |--------------------------------------------------------------------------
    | Preloader & User Menu
    |--------------------------------------------------------------------------
    */

    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'AdminLTE Preloader Image',
            'effect' => 'animation__shake',
            'width' => 60,
            'height' => 60,
        ],
    ],

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false, // Habilita el link de perfil en el botón superior derecho

    /*
    |--------------------------------------------------------------------------
    | Layout & Sidebar
    |--------------------------------------------------------------------------
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_nav_accordion' => true,

    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    */

    'menu' => [
        // Buscadores
        [
            'type' => 'navbar-search',
            'text' => 'search',
            'topnav_right' => true,
        ],
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],
        [
            'type' => 'sidebar-menu-search',
            'text' => 'Buscar...',
        ],

        // --- SECCIÓN GESTIÓN ---
        ['header' => 'GESTIÓN MÉDICA'],
        [
            'text'    => 'Servicios',
            'icon'    => 'fas fa-fw fa-hospital',
            'url'     => 'servicio', // Apunta directo a la lista
            'active'  => ['servicio*'], // Se mantiene iluminado si estás en ver/editar
        ],
        ['header' => 'GESTIÓN TURNO-USUARIO'],
        [
            'text' => 'Turnos',
            'icon'    => 'fas fa-fw fa-turno',
            'url'  => '/turnos/crear', // La vista que acabamos de crear
            'icon' => 'fas fa-calendar-alt',
            'label_color' => 'success',
        ],

        // --- SECCIÓN CUENTA ---
        ['header' => 'AJUSTES DE CUENTA'],
        [
            'text' => 'Mi Perfil',
            'url'  => 'perfil',  // ¡CORREGIDO! Ya no dará 404
            'icon' => 'fas fa-fw fa-user',
        ],
        ['header' => 'ADMINISTRACIÓN'],
        [
          'text' => 'Gestión de Personal',
            'url'  => 'personas',
            'icon' => 'fas fa-users-cog',
            'active' => ['personas*'], // Esto mantiene el botón resaltado si estás en esa sección
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins
    |--------------------------------------------------------------------------
    */

    'plugins' => [
        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@11',
                ],
            ],
        ],
    ],

    'livewire' => false,
];