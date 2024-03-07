<?php
require './app/include.php';

$seeders = scandir( str_replace('/', DIRECTORY_SEPARATOR, './seeders') );

//Sort $seeders from oldest to newest
sort($seeders);

foreach ($seeders as $filename) {
    if ( in_array($filename, ['.', '..']) ) {
        continue;
    }

    $filepath = './seeders'.DIRECTORY_SEPARATOR.$filename;
    if ( !file_exists($filepath) ) {
        continue;
    }

    $seeder = include $filepath;
    if ( !is_object($seeder) ) {
        continue;
    }

    if ( method_exists($seeder, 'seed') ) {
        try {
            $seeder->seed();
        } catch (\Throwable $th) {
            echo $th->getMessage() . "\n";
        }
    }
}