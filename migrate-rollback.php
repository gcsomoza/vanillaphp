<?php
require 'include.php';

$migrations = scandir( VanillaPHP::fixDirSep(BASE_PATH.'/migrations') );
var_dump($migrations); exit;

//Sort $migrations from newest to oldest
rsort($migrations);

foreach ($migrations as $filename) {
    if ( in_array($filename, ['.', '..', 'Migration.php']) ) {
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

    if ( method_exists($migrate, 'down') ) {
        try {
            $migrate->down();
        } catch (\Throwable $th) {
            echo $th->getMessage() . "\n";
        }
    }
}