<?php

namespace AdminControllers;
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
        if (empty($this->superGlobal->getCurrentUser()) === true) {
            $this->redirect->getRedirect('admin/login');
        };

        $user = $this->superGlobal->getCurrentUser();

        $view = [];
        $view['folder'] = 'adminTemplates\account';
        $view['file'] = 'adminAccount.twig';
        $view['var'] = ['user' => $user];
        return $view;

    }//end index()


}//end class
