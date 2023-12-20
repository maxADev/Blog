<?php

namespace src\Controllers;
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
        $varValue = [];

        if (empty($this->superGlobal->getCurrentUser()) === true) {
            $this->redirect->getRedirect('/login');
        };

        $varValue['user'] = $this->superGlobal->getCurrentUser();

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'templates\account';
        $view['file'] = 'account.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end index()


}//end class
