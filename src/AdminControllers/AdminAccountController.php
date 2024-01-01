<?php

namespace src\AdminControllers;
use App\SuperGlobal;
use App\Redirect;

// Admin Account Controller.
class AdminAccountController
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
        $this->superGlobal->userIsAdmin();

        $user = $this->superGlobal->getCurrentUser();

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'adminTemplates\account';
        $view['file'] = 'adminAccount.twig';
        $view['var'] = ['userAdmin' => $user];
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end index()


}//end class
