<?php

/**
 * @param  string $route
 * @param  string $executeMethodString
 * @return void
 */
function create_route_file(string $route, string $executeMethodString): void {
    $directory = trim($route, '/');
    if (empty($directory)) {
        $directory = '.'; // Current directory
    }
    
    $requireString = 'require \'';
    if ($directory != '.') {
        $numFolders = count(explode('/', trim($route, '/')));
        for ($i=0; $i < $numFolders; $i++) { 
            $requireString .= '../';
        }
    }
    $requireString .= 'vanillaphp/include.php\';';
    $requireString = str_replace('/', DIRECTORY_SEPARATOR, $requireString);

    if ($directory != '.' && !file_exists($directory)) {
        mkdir($directory, 0755, true);
    }

    $filepath = $directory . '/index.php';
    $filepath = str_replace('/', DIRECTORY_SEPARATOR, $filepath);
    $comment = "// This is generated by vanillaphp/routes-create.php";
    $content = "<?php\n{$comment}\n{$requireString}\n{$executeMethodString}\n";

    file_put_contents($filepath, $content);
}

/**
 * @param  string $dir /path/to/your/directory
 * @return bool
 */
function is_dir_empty(string $dir): bool {
    // Get the list of files and directories in the specified directory
    $contents = scandir($dir);
    
    // Remove . and .. from the list
    $contents = array_diff($contents, array('.', '..'));
    
    // If the resulting array is empty, the directory is empty
    return empty($contents);
}

$currentRoutesFile = str_replace('/', DIRECTORY_SEPARATOR, './routes.php');
$previousRoutesFile = str_replace('/', DIRECTORY_SEPARATOR, './routes-previous.php');

if ( !file_exists($previousRoutesFile) ) {
    copy($currentRoutesFile, $previousRoutesFile);
}
else {
    // Delete previous route index.php files in the source code

    require $previousRoutesFile;

    $previousRoutes = $routes;
    $directories = [];
    foreach (array_keys($previousRoutes) as $route) {
        $directory = trim($route, '/');
        if (empty($directory)) {
            $directory = '.'; // Current directory
        }
    
        if ( $directory != '.' && !isset($directories[$directory]) ) {
            $directories[$directory] = $directory;
        }
    
        $filepath = $directory . '/index.php';
        $filepath = str_replace('/', DIRECTORY_SEPARATOR, $filepath);
    
        if ( file_exists($filepath) )
            unlink($filepath);
    }

    //Make sure child folders are deleted first before the parent folder
    rsort($directories);

    $parentDirs = [];
    foreach ($directories as $directory) {
        if ( is_dir($directory) && is_dir_empty($directory) ) {
            rmdir($directory);

            //Collect route parent folders to delete later if it's empty
            $parentDir = explode('/', trim( $filepath, '/' ) )[0] ?? '';
            $parentDirs[$parentDir] = $parentDir;
        }
    }

    //Delete route parent folders if empty
    foreach ($parentDirs as $directory) {
        if ( $directory == '/' ) continue; //Do not delete root folder
        if ( is_dir($directory) && is_dir_empty($directory) ) {
            rmdir($directory);
        }
    }
}

require $currentRoutesFile;

echo "Routes created:\n";

foreach ($routes as $route => $routeInfo) {
    if ( array_is_list($routeInfo) ) {
        //Route is for any REQUEST_METHOD
        $class = $routeInfo[0];
        $method = $routeInfo[1] ?? 'index';
        $executeMethodString = "(new {$class})->{$method}();";
        create_route_file($route, $executeMethodString);
    }
    else {
        //Route is for specific REQUEST_METHOD
        $executeMethodString = [];
        $executeMethodString[] = "match(\$_SERVER['REQUEST_METHOD']) {";
        foreach ($routeInfo as $request_method => $value) {
            $class = $value[0];
            $method = $value[1] ?? 'index';
            $executeMethodString[] = "\t'{$request_method}' => (new {$class})->{$method}(),";
        }
        $executeMethodString[] = "};";
        $executeMethodString = implode("\n", $executeMethodString);
        create_route_file($route, $executeMethodString);
    }

    echo "  * $route\n";
}

copy($currentRoutesFile, $previousRoutesFile);

echo "Done creating routes.\n";