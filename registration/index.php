<?php

session_start();

define('ROOT', dirname(__FILE__));
define('MAIN', ROOT . '\main');
define('CONFIG', ROOT . '\config');

spl_autoload_register(function ($class_name) {

    $array_paths = [
        '/component/',
        '/controller/',
        '/model/',
    ];

    foreach ($array_paths as $path) {

        $path = MAIN . $path . $class_name . '.php';

        if (is_file($path)) {
            include_once $path;
        }
    }
});

$router = new Router();
$router->run();