<?php

return [

    'models' => [
        /*
         * Si usas modelos personalizados para Permission o Role, cámbialos aquí.
         * Por defecto, usamos los de Spatie.
         */
        'permission' => Spatie\Permission\Models\Permission::class,
        'role' => Spatie\Permission\Models\Role::class,
    ],

    'table_names' => [
        /*
         * Tablas de Spatie para roles y permisos.
         */
        'roles' => 'roles',
        'permissions' => 'permissions',
        'model_has_permissions' => 'model_has_permissions',
        'model_has_roles' => 'model_has_roles',
        'role_has_permissions' => 'role_has_permissions',

        /*
         * CONFIGURACIÓN CRÍTICA:
         * Cambiamos 'users' por 'usuario' para que coincida con tu DB.
         */
        'users' => 'usuario',
    ],

    'column_names' => [
        /*
         * Nombres de las llaves foráneas en las tablas pivote.
         */
        'role_pivot_key' => null, // default 'role_id'
        'permission_pivot_key' => null, // default 'permission_id'

        /*
         * 'model_id' es la columna en model_has_roles que guardará el ID de tu Usuario.
         */
        'model_morph_key' => 'model_id',

        'team_foreign_key' => 'team_id',
    ],

    /*
     * Registro automático en el Gate de Laravel para usar @can() en las vistas.
     */
    'register_permission_check_method' => true,

    'register_octane_reset_listener' => false,

    'events_enabled' => false,

    'teams' => false,

    'team_resolver' => \Spatie\Permission\DefaultTeamResolver::class,

    'use_passport_client_credentials' => false,

    'display_permission_in_exception' => false,

    'display_role_in_exception' => false,

    'enable_wildcard_permission' => false,

    'cache' => [
        /*
         * Los permisos se guardan en caché por 24 horas para mejorar el rendimiento.
         */
        'expiration_time' => \DateInterval::createFromDateString('24 hours'),
        'key' => 'spatie.permission.cache',
        'store' => 'default',
    ],
];