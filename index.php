<?php

require_once 'vendor/autoload.php';

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// Load TWIG.
$loader = new FilesystemLoader(__DIR__.'/templates');
$twig = new Environment($loader);

// Return controller based on url.
$routerClass = 'App\\Router';
$router = new $routerClass;
$view = $router->run($twig);

print_r($view);
