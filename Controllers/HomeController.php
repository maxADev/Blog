<?php

namespace Controllers;

// Home Controller.
class HomeController
{


    /**
     * Index page
     *
     * @return view
     */
    public function index()
    {
        $view = [];
        $view['folder'] = 'accueil';
        $view['file'] = 'accueil.twig';
        return $view;

    }//end index()


}//end class
