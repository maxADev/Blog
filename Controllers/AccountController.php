<?php

namespace Controllers;
use App\Superglobal;
use App\Redirect;

// Account Controller.
class AccountController
{

    /**
     *
     * @var $superglobal for Superglobal class
     */
    private $superglobal;


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
        $this->superglobal = new Superglobal;
        $this->redirect = new Redirect;

    }//end __construct()


    /**
     * Index page
     *
     * @return view
     */
    public function index()
    {
        if ($this->superglobal->authSessionExist() === false) {
            $this->redirect->getRedirect('connexion');
        };

        $session = $this->superglobal->getSession();
        $user = $session['auth'];

        $view = [];
        $view['folder'] = 'compte';
        $view['file'] = 'monCompte.twig';
        $view['var'] = ['user' => $user];
        return $view;

    }//end index()


}//end class
