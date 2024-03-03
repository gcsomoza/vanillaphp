<?php

use Illuminate\Database\Capsule\Manager as Capsule;

class VanillaPHP {
    /**
     * Includes a partial php view to another php view
     */
    public static function partial($view, $data = []) {
        extract($data);
        include self::fixDirSep(BASE_PATH."/$view");
    }

    /**
     * Outputs the php view file in the browser
     */
    public static function view($view, $data = []) {
        self::partial($view, $data);
        exit;
    }

    /**
     * Outputs the data in json format in the browser
     */
    public static function json($data) {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    /**
     * Fix directory separator used depending on
     * Operating system of the server.
     */
    public static function fixDirSep($path) {
        return str_replace("\\",DIRECTORY_SEPARATOR,$path);
    }

    public static function require($file) {
        $file = self::fixDirSep($file);
        if ( file_exists($file) ) {
            require $file;
        }
    }

    public static function setupEloquentDB() {
        $capsule = new Capsule;

        $host = defined('DB_HOST') ? DB_HOST : 'localhost';
        $port = defined('DB_PORT') ? DB_PORT : 3307;
        $name = defined('DB_HOST') ? DB_NAME : '';
        $user = defined('DB_USER') ? DB_USER : 'root';
        $pass = defined('DB_PASS') ? DB_PASS : '';
        $charset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8';
        $collation = defined('DB_COLLATION') ? DB_COLLATION : 'utf8_unicode_ci';

        $capsule->addConnection([
            'driver'    => 'mysql',
            'host'      => $host,
            'port'      => $port,
            'database'  => $name,
            'username'  => $user,
            'password'  => $pass,
            'charset'   => $charset,
            'collation' => $collation,
            'prefix'    => '',
        ]);

        $capsule->setAsGlobal();
        $capsule->bootEloquent();
    }
}