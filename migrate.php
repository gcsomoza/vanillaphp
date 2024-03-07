<?php
require 'include.php';

$migrations = scandir( VanillaPHP::fixDirSep(BASE_PATH.'/migrations') );

//Sort $migrations from oldest to newest
sort($migrations);

foreach ($migrations as $filename) {
    if ( in_array($filename, ['.', '..']) ) {
        continue;
    }

    $filepath = VanillaPHP::fixDirSep( './migrations'.DIRECTORY_SEPARATOR.$filename );
    if ( !file_exists($filepath) ) {
        continue;
    }

    $migrate = include $filepath;
    if ( !is_object($migrate) ) {
        continue;
    }

    if ( method_exists($migrate, 'up') ) {
        try {
            $migrate->up();
        } catch (\Throwable $th) {
            echo $th->getMessage() . "\n";
        }
    }
}