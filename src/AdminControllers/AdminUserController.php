<?php

namespace src\AdminControllers;

use src\Entity\UserEntity;
use src\AdminModels\AdminUserModel;
use App\SuperGlobal;
use App\Redirect;

// Admin User Controller.
class AdminUserController
{

    /**
     *
     * @var $adminUserModel for adminUserModel class
     */
    private $adminUserModel;

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
        $this->adminUserModel = new adminUserModel();
        $this->superGlobal = new SuperGlobal();
        $this->redirect = new Redirect();

    }//end __construct()


    /**
     * User page
     *
     * @return view
     */
    public function index()
    {
        $errors;
        $userSetting = '';

        $this->superGlobal->userIsAdmin();

        $varValue = ['userAdmin' => $this->superGlobal->getCurrentUser()];

        if ($this->superGlobal->getDataExist('userSetting') === true) {
            $userSetting = $this->superGlobal->getGetData('userSetting');
        }

        if ($userSetting === 'list') {
            $userList = $this->getUserList();
            $varValue['userList'] = $userList;
        }

        if ($this->superGlobal->postExist() === true) {
            $this->superGlobal->checkToken($this->superGlobal->getGetData('token'));
            $postValue = $this->superGlobal->getPost();
            $errors = $this->superGlobal->checkPostData($postValue);

            if (empty($errors) === true) {
                if ($this->adminUserModel->adminUpdateUser($postValue) === true) {
                    $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'L\'utilisateur a été modifié']);
                    $this->redirect->getRedirect('/admin/users');
                }
            } else {
                $this->superGlobal->createFlashMessage($errors);
            }
        }

        $typeUserList = $this->adminUserModel->adminGetTypeUserList();
        $flashMessageList = $this->superGlobal->getFlashMessage();

        $varValue['typeUserList'] = $typeUserList;
        $view = [];
        $view['folder'] = 'adminTemplates\user';
        $view['file'] = 'adminUserList.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end index()


    /**
     * Get User List
     *
     * @return view
     */
    public function getUserList()
    {
        $this->superGlobal->userIsAdmin();

        $userList = $this->adminUserModel->adminGetUserList();

        return $userList;

    }//end getUserList()


}//end class
