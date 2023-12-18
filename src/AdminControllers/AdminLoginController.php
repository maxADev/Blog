<?php

namespace src\AdminControllers;

use src\Entity\UserEntity;
use src\Models\UserModel;
use App\Superglobal;
use App\Redirect;

// Admin Login Controller.
class AdminLoginController
{

    /**
     *
     * @var $userModel for UserModel class
     */
    private $userModel;

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
        $this->userModel = new UserModel();
        $this->superglobal = new Superglobal();
        $this->redirect = new Redirect();

    }//end __construct()


    /**
     * Admin Login
     *
     * @return void
     */
    public function adminLoginPage()
    {
        if ($this->superglobal->postExist() === true) {
            $postValue = $this->superglobal->getPost();

            if (empty($postValue['login']) === false && empty($postValue['password']) === false) {
                $user = $this->userModel->loginAdmin($postValue['login']);
                if (empty($user) === false) {
                    if (password_verify($postValue['password'], $user['password']) === true) {
                        $this->superglobal->createSession($user);
                        $this->redirect->getRedirect('account');
                    }
                }
            }
        }

        $view = [];
        $view['folder'] = 'adminTemplates\login';
        $view['file'] = 'adminLogin.twig';
        return $view;

    }//end adminLoginPage()


}//end class
