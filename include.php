<?php
define('VANPHP_PATH', __DIR__);
define('BASE_PATH', realpath(VANPHP_PATH.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR));

require VANPHP_PATH.DIRECTORY_SEPARATOR.'VanillaPHP.php';
require VANPHP_PATH.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

spl_autoload_register(function ($class) {
    //Autoload classes from BASE_PATH
    require VanillaPHP::fixDirSep( BASE_PATH."/$class.php" );
});

VanillaPHP::require(BASE_PATH.'/vendor/autoload.php');
VanillaPHP::require(BASE_PATH.'/config.php');
VanillaPHP::setupEloquentDB();