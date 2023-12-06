<?php

namespace App;

// Router.
class Router
{


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
                $requireController = 'Controllers\\'.$controller;

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
        $routeVar = [];
        $viewVar = [];
        
        $routeValue = $this->getRoute();

        $view = 'error\error.twig';

        if (empty($routeValue['controller']) === false) {
            $routeController = $routeValue['controller'];
            $routeAction = $routeValue['action'];

            if(empty($routeValue['var']) !== true)
            {
                $_GET = array_merge($_GET, $routeValue['var']);
            }
            
            // Use controller based on route.
            $controller = new $routeController;
            $viewPath = $controller->$routeAction();
            if(empty($viewPath['var']) !== true)
            {
                $viewVar = $viewPath['var'];
            }
            $view = $viewPath['folder'].'\\'.$viewPath['file'];
        }


        $viewValue = $twig->render($view, ['varList' => $viewVar]);
        return $viewValue;

    }//end run()


}//end class
