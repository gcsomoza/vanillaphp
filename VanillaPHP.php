<?php

use vanillaphp\Services\Database;

class VanillaPHP {
    /**
     * Includes a php view
     */
    public static function view($view, $data = []) {
        extract($data);
        include self::fixDirSep(BASE_PATH."/$view");
    }

    /**
     * Get json input from request
     */
    public static function jsonInput() {
        return json_decode(file_get_contents('php://input'));
    }

    /**
     * Outputs the data in json format in the browser
     */
    public static function jsonOutput($data) {
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

    private static function sanitize($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    public static function get($key, $default = '') {
        return self::sanitize($_GET[$key] ?? $default);
    }

    public static function post($key, $default = '') {
        return self::sanitize($_POST[$key] ?? $default);
    }
}