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
        $view['folder'] = 'home';
        $view['file'] = 'home.twig';
        $view['var'] = $varValue;
        return $view;

    }//end index()


}//end class
