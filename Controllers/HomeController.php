<?php

namespace Controllers;
use App\Superglobal;

// Home Controller.
class HomeController extends Superglobal
{


    /**
     * Index page
     *
     * @return view
     */
    public function index()
    {
        $varValue = '';
        if (empty($this->getCurrentUser()) === false) {
            $varValue = ['user' => $this->getCurrentUser()];
        };

        $view = [];
        $view['folder'] = 'accueil';
        $view['file'] = 'accueil.twig';
        $view['var'] = $varValue;
        return $view;

    }//end index()


}//end class
