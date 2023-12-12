<?php

namespace Controllers;
use App\SuperGlobal;
use App\Redirect;

// Account Controller.
class AccountController
{

    /**
     *
     * @var $superGlobal for SuperGlobal class
     */
    private $superGlobal;

    /**
     *
     * @var $redirect for Redirect class
     */
    private $redirect;


    /**
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->superGlobal = new SuperGlobal();
        $this->redirect = new Redirect();

    }//end __construct()


    /**
     * Index page
     *
     * @return view
     */
    public function index()
    {
        if (empty($this->superGlobal->getCurrentUser()) === true) {
            $this->redirect->getRedirect('connexion');
        };

        $user = $this->superGlobal->getCurrentUser();

        $view = [];
        $view['folder'] = 'compte';
        $view['file'] = 'monCompte.twig';
        $view['var'] = ['user' => $user];
        return $view;

    }//end index()


}//end class
