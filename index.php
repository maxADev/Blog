<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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
