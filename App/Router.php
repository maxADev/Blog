<?php

namespace App;
use App\SuperGlobal;

// Router.
class Router
{

    /**
     *
     * @var $superGlobal for SuperGlobal class
     */
    private $superGlobal;


    /**
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->superGlobal = new SuperGlobal;

    }//end __construct()


    /**
     * Get Route
     *
     * @return void
     */
    public function getRoute()
    {
        // Get request url.
        $httpRequest = getenv('REQUEST_URI');

        // Get xml file.
        $xml = new \DOMDocument;
        $xml->load(__DIR__.'/config/routes.xml');

        // Get all routes.
        $routes = $xml->getElementsByTagName('route');

        $listVars = [];

        $routeExist = false;

        $controllerFolder = "Controllers";

        if (strpos($httpRequest, '/admin/') !== false) {
            $controllerFolder = "AdminControllers";
        }

        // For each routes from xml file.
        foreach ($routes as $route) {
            $routeUrl = $route->getAttribute('url');

            if ($route->hasAttribute('vars') === true) {
                $routeVars = explode(',', $route->getAttribute('vars'));
            }

            // If url = route.
            if (preg_match('`^'.$routeUrl.'$`', $httpRequest, $matches) === 1) {
                $controllerValue = [];
                $routeController = $route->getAttribute('controller');
                $routeAction = $route->getAttribute('action');
                $routeExist = true;
                // Get route controller.
                $controller = ucfirst($routeController).'Controller';
                $requireController = 'src\\'.$controllerFolder.'\\'.$controller;

                $controllerValue['controller'] = $requireController;
                $controllerValue['action'] = $routeAction;
                $controllerValue['var'] = '';

                // Add match value to name value.
                $routeUrlVarsValue = $matches;

                if (isset($routeVars) === true) {
                    foreach ($routeUrlVarsValue as $key => $match) {
                        if ($key !== 0) {
                            $listVars[$routeVars[($key - 1)]] = $match;
                        }
                    }

                    $controllerValue['var'] = $listVars;
                }
            }//end if
        }//end foreach

        if ($routeExist === false) {
            $controllerValue['controller'] = '';
        }

        return $controllerValue;

    }//end getRoute()


    /**
     * Run the app, return view base on controller.
     *
     * @param $twig for render
     * @return void
     */
    public function run($twig)
    {
        $viewVar = [];
        $viewVarValue = [];
        $http = 'http';
        
        $routeValue = $this->getRoute();

        if ($_SERVER['HTTPS'] === 'on') {
            $http = 'https';
        }

        $urlDomain = $http.'://'.$_SERVER['SERVER_NAME'];

        $cssLink = $urlDomain."/public/assets/css/style.css";
        $bootstrapLink =  $urlDomain."/public/assets/css/bootstrap.min.css";
        $scriptLink =  $urlDomain."/public/assets/script/script.js";

        $view = 'templates\error\error.twig';

        if (empty($routeValue['controller']) === false) {
            $routeController = $routeValue['controller'];
            $routeAction = $routeValue['action'];

            if (empty($routeValue['var']) !== true) {
                $this->superGlobal->createGet($routeValue['var']);
            }
            
            // Use controller based on route.
            $controller = new $routeController;
            $viewValue = $controller->$routeAction();
            if (empty($viewValue['var']) !== true) {
                $viewVar = $viewValue['var'];
                $viewVarValue['varList'] = $viewVar;
            }

            if (empty($viewValue['flashMessageList']) !== true) {
                $flashMessageList = $viewValue['flashMessageList'];
                $viewVarValue['flashMessageList'] = $flashMessageList;
            }

            $view = $viewValue['folder'].'\\'.$viewValue['file'];
        } else {
            if (empty($this->superGlobal->getCurrentUser()) === false) {
                $viewVarValue['varList']['user'] = $this->superGlobal->getCurrentUser();
            }
        }//end if

        $viewVarValue['cssLink'] = $cssLink;
        $viewVarValue['bootstrapLink'] = $bootstrapLink;
        $viewVarValue['scriptLink'] = $scriptLink;
        $viewValue = $twig->render($view, $viewVarValue);
        return $viewValue;

    }//end run()


}//end class
