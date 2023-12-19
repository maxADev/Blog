<?php

namespace src\AdminControllers;

use src\Entity\UserEntity;
use src\Models\UserModel;
use App\SuperGlobal;
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
        $this->userModel = new UserModel();
        $this->superGlobal = new SuperGlobal();
        $this->redirect = new Redirect();

    }//end __construct()


    /**
     * Admin Login
     *
     * @return void
     */
    public function adminLoginPage()
    {
        if (empty($this->superGlobal->getCurrentUser()) === false && $this->superGlobal->userIsAdmin() === true) {
            $this->redirect->getRedirect('/admin/account');
        }
        $flashMessageList = $this->superGlobal->getFlashMessage();

        if ($this->superGlobal->postExist() === true) {
            $postValue = $this->superGlobal->getPost();

            if (empty($postValue['login']) === false && empty($postValue['password']) === false) {
                $user = $this->userModel->loginAdmin($postValue['login']);
                if (empty($user) === false) {
                    if (password_verify($postValue['password'], $user['password']) === true) {
                        $this->superGlobal->createSession($user);
                        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Vous êtes bien connecté']);
                        $this->redirect->getRedirect('/admin/account');
                    }
                }
            }
        }

        $view = [];
        $view['folder'] = 'adminTemplates\login';
        $view['file'] = 'adminLogin.twig';
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end adminLoginPage()


    /**
     * Admin logout
     *
     * @return void
     */
    public function adminLogout()
    {
        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Vous êtes bien déconnecté']);
        unset($_SESSION['auth']);
        $this->redirect->getRedirect('/admin/login');

    }//end adminLogout()


}//end class
