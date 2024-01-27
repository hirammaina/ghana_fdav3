<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => 'pgsql',

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    | admin@#123 
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],
        'mysql_posgres' => array(
            'driver'   => 'pgsql',
            'host'     => 'localhost',
            'database' => 'tfdamis_serverv2',
            'username' => 'postgres',
            'password' => 'admin123',
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'mis_db',
        ),
        'mysql' => [
            'driver' => 'pgsql',
            'host' => aes_decrypt(env('DB_HOST', '127.0.0.1')),
            'port' => aes_decrypt(env('DB_PORT', '5432')),
            'database' => aes_decrypt(env('DB_DATABASE', 'ghana_irimsmis')),
            'username' => aes_decrypt(env('DB_USERNAME', 'forge')),
            'password' => aes_decrypt(env('DB_PASSWORD', '')),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            "schema" => "mis_db"
        ],
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => aes_decrypt(env('DB_HOST', '127.0.0.1')),
            'port' => aes_decrypt(env('DB_PORT', '5432')),
            'database' => aes_decrypt(env('DB_DATABASE', 'ghana_irimsmis')),
            'username' => aes_decrypt(env('DB_USERNAME', 'forge')),
            'password' => aes_decrypt(env('DB_PASSWORD', '')),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            "schema" => "mis_db"
        ],
        'portal_db' => [
            'driver' => 'pgsql',
            'host' =>  aes_decrypt(env('PORTAL_HOST', '127.0.0.1')),
            'port' =>  aes_decrypt(env('PORTAL_PORT', '3306')),
            'database' =>  aes_decrypt(env('PORTAL_DATABASE', 'forge')),
            'username' =>  aes_decrypt(env('PORTAL_USERNAME', 'forge')),
            'password' =>  aes_decrypt(env('PORTAL_PASSWORD', '')),
            'unix_socket' => env('PORTAL_SOCKET', ''),
            'charset' => 'utf8',
            // 'charset' => 'utf8mb4',
            // 'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            "schema" => "portal_db"
        ],
        'financial_db' => [
            'driver' => 'pgsql',
            'host' => aes_decrypt(env('FINANCIAL_HOST', '127.0.0.1')),
            'port' => aes_decrypt(env('FINANCIAL_PORT', '3306')),
            'database' => aes_decrypt(env('FINANCIAL_DATABASE', 'forge')),
            'username' => aes_decrypt(env('FINANCIAL_USERNAME', 'forge')),
            'password' => aes_decrypt(env('FINANCIAL_PASSWORD', '')),
            'unix_socket' => env('FINANCIALL_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            "schema" => "mis_db"
        ],
        'lims_db' => [
            'driver' => 'mysql',
            'host' => env('LIMS_HOST', '127.0.0.1'),
            'port' => env('LIMS_PORT', '3306'),
            'database' => env('LIMS_DATABASE', 'forge'),
            'username' => env('LIMS_USERNAME', 'forge'),
            'password' => env('LIMS_PASSWORD', ''),
            'unix_socket' => env('LIMS_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],
        'misv1_db' => [
            'driver' => 'mysql',
            'host' =>  aes_decrypt(env('PORTAL_HOST', '127.0.0.1')),
            'port' =>  aes_decrypt(env('PORTAL_PORT', '3306')),
            'database' =>  'tfda_mis',
            'username' =>  aes_decrypt(env('PORTAL_USERNAME', 'forge')),
            'password' =>  aes_decrypt(env('PORTAL_PASSWORD', '')),
            'unix_socket' => env('MISV1_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
        ],
        'audit_db' => [
            // 'driver' => 'mysql',
            // 'host' =>  aes_decrypt(env('AUDIT_HOST', '127.0.0.1')),
            // 'port' =>  aes_decrypt(env('AUDIT_PORT', '3306')),
            // 'database' =>  aes_decrypt(env('AUDIT_DATABASE', 'forge')),
            // 'username' =>  aes_decrypt(env('AUDIT_USERNAME', 'forge')),
            // 'password' =>  aes_decrypt(env('AUDIT_PASSWORD', '')),
            // 'unix_socket' => env('AUDIT_SOCKET', ''),
            // 'charset' => 'utf8mb4',
            // 'collation' => 'utf8mb4_unicode_ci',
            // 'prefix' => '',
            // 'strict' => false,
            // 'engine' => null,


            'driver' => 'pgsql',
            'host' => env('AUDIT_HOST', '127.0.0.1'),
            'port' => env('AUDIT_PORT', '3306'),
            'database' => env('AUDIT_DATABASE', 'forge'),
            'username' => env('AUDIT_USERNAME', 'forge'),
            'password' => env('AUDIT_PASSWORD', ''),
            'unix_socket' => env('AUDIT_SOCKET', ''),
            // 'charset' => 'utf8mb4',
            // 'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            'schema' => 'audit_db',
        ],

        'report_db' => [
            'driver' => 'mysql',
            'host' => env('REPORT_HOST', '127.0.0.1'),
            'port' => env('REPORT_PORT', '3306'),
            'database' => env('REPORT_DATABASE', 'forge'),
            'username' => env('REPORT_USERNAME', 'forge'),
            'password' => env('REPORT_PASSWORD', ''),
            'unix_socket' => env('REPORT_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => false,
            'engine' => null,
            "schema" => "mis_db"
        ],
        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
