<?php

namespace Controllers;
use App\Superglobal;

// Account Controller.
class AccountController
{


    /**
     *
     * @var $superglobal for Superglobal class
     */
    private $superglobal;


    /**
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->superglobal = new Superglobal;

    }//end __construct()


    /**
     * Index page
     *
     * @return view
     */
    public function index()
    {
        if($this->superglobal->authSessionExist() === false) {
            header('Location: connexion');
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
