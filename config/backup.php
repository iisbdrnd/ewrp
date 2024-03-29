<?php

return [

    /*
    |--------------------------------------------------------------------------
    | MYSQL
    |--------------------------------------------------------------------------
    */

    'mysql' => [

        /*
        |--------------------------------------------------------------------------
        | mysql Path
        |--------------------------------------------------------------------------
        |
        | Path to mysql, must be absolute on windows.
        |
        */

        'mysql_path' => 'mysql',

        /*
        |--------------------------------------------------------------------------
        | mysqldump Path
        |--------------------------------------------------------------------------
        |
        | Path to mysqldump, must be absolute on windows.
        |
        */

        'mysqldump_path' => 'mysqldump',
		// 'mysqldump_path' => 'D:\xampp\mysql\bin\mysqldump',

        /*
        |--------------------------------------------------------------------------
        | File compression
        |--------------------------------------------------------------------------
        |
        | Enable or disable file compression.
        |
        */

        'compress' => false,

        /*
        |--------------------------------------------------------------------------
        | Local Storage
        |--------------------------------------------------------------------------
        */

        'local-storage' => [

            /*
            |--------------------------------------------------------------------------
            | Local Storage Disk Name
            |--------------------------------------------------------------------------
            */

            'disk' => 'local',

            /*
            |--------------------------------------------------------------------------
            | Local Storage Path
            |--------------------------------------------------------------------------
            */

            'path' => 'backups',

        ],

        /*
        |--------------------------------------------------------------------------
        | Cloud Storage
        |--------------------------------------------------------------------------
        */

        'cloud-storage' => [

            /*
            |--------------------------------------------------------------------------
            | Enabled Cloud Storage?
            |--------------------------------------------------------------------------
            */

            'enabled' => true,

            /*
            |--------------------------------------------------------------------------
            | Cloud Storage Disk Name
            |--------------------------------------------------------------------------
            */

            'disk' => 's3',

            /*
            |--------------------------------------------------------------------------
            | Cloud Storage Path
            |--------------------------------------------------------------------------
            */

            'path' => 'backups',

            /*
            |--------------------------------------------------------------------------
            | Keep Local Files After Cloud Storage?
            |--------------------------------------------------------------------------
            */

            'keep-local' => true,

        ],

    ],

];
