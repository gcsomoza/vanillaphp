<?php
define('VANPHP_PATH', __DIR__);
define('BASE_PATH', realpath(VANPHP_PATH.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR));

require VANPHP_PATH.DIRECTORY_SEPARATOR.'VanillaPHP.php';

spl_autoload_register(function ($class) {
    //Autoload classes from BASE_PATH
    require VanillaPHP::fixDirSep( BASE_PATH."/$class.php" );
});

spl_autoload_register(function ($class) {
    //Autoload classes in vanillaphp
    $class = str_replace('vanillaphp\\', '');
    require VanillaPHP::fixDirSep( VANPHP_PATH."/$class.php" );
});

VanillaPHP::require(BASE_PATH.'/vendor/autoload.php');
VanillaPHP::require(BASE_PATH.'/config.php');

/**
 * Database
 */
function db() {
    static $db = null;
    if ($db === null) {
        $host = defined('DB_HOST') ? DB_HOST : 'localhost';
        $port = defined('DB_PORT') ? DB_PORT : 3307;
        $name = defined('DB_HOST') ? DB_NAME : '';
        $user = defined('DB_USER') ? DB_USER : 'root';
        $pass = defined('DB_PASS') ? DB_PASS : '';
        $db = new vanillaphp\Services\Database($host, $user, $pass, $name);
    }
    return $db;
}

/**
 * Prints php view
 */
function view($view, $data = []) {
    VanillaPHP::view($view, $data);
}

/**
 * Prints json string
 */
function json($data) {
    VanillaPHP::json($data);
}