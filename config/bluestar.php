<?php


// +----------------------------------------------------------------------
// | Copyright (c) 2023 ~ now https://daygiare.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: HuyPham[ huyad1102@gmail.com ]
// +----------------------------------------------------------------------

return [
    /*
    |--------------------------------------------------------------------------
    | default middleware
    |--------------------------------------------------------------------------
    |
    | where you can set default middlewares
    |
    */
    'middleware_group' => [],

    /*
    |--------------------------------------------------------------------------
    | bluestar_auth_middleware_alias
    |--------------------------------------------------------------------------
    |
    | where you can set default middlewares
    |
    */
    'bluestar_auth_middleware_alias' => [],

    /*
    |--------------------------------------------------------------------------
    |
    | where you can set super admin id
    |
    */
    'super_admin' => 1,

    'request_allowed' => true,

    /*
    |--------------------------------------------------------------------------
    | the root where module generate
    | the namespace is module root namespace
    | the default dirs is module generate default dirs
    */
    'module' => [
        'root' => 'modules',

        'namespace' => 'Modules',

        'default' => ['develop', 'user', 'common'],

        'default_dirs' => [
            'Http' . DIRECTORY_SEPARATOR,

            'Http' . DIRECTORY_SEPARATOR . 'Requests' . DIRECTORY_SEPARATOR,

            'Http' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR,

            'Models' . DIRECTORY_SEPARATOR,

            'views' . DIRECTORY_SEPARATOR,
        ],

        // storage module information
        // which driver should be used?
        'driver' => [
            // currently, support file and database
            // the default is driver
            'default' => 'file',

            // use database driver
            'table_name' => 'admin_modules'
        ],

        /**
         * module routes collection
         *
         */
        'routes' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    */
    'response' => [
        // it's a controller middleware, it's set in BlueStarController
        // if you not need json response, don't extend BlueStarController
        'always_json' => \BlueStar\Middleware\JsonResponseMiddleware::class,

        // response listener
        // it  listens [RequestHandled] event, if you don't need this
        // you can change this config
        'request_handled_listener' => \BlueStar\Listeners\RequestHandledListener::class
    ],

    /*
   |--------------------------------------------------------------------------
   | database sql log
   |--------------------------------------------------------------------------
   */
    'listen_db_log' => true,

    /*
   |--------------------------------------------------------------------------
   | admin auth model
   |--------------------------------------------------------------------------
   */
    'auth_model' => \Modules\User\Models\User::class,

    /*
   |--------------------------------------------------------------------------
   | route config
   |--------------------------------------------------------------------------
   */
    'route' => [
        'prefix' => 'api',

        'middlewares' => [
            \BlueStar\Middleware\AuthMiddleware::class,
            \BlueStar\Middleware\JsonResponseMiddleware::class
        ],

        // 'cache_path' => base_path('bootstrap/cache') . DIRECTORY_SEPARATOR . 'admin_route_cache.php'
    ],
];
