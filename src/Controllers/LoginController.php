<?php

namespace src\Controllers;

use src\Entity\UserEntity;
use src\Models\UserModel;
use App\SuperGlobal;
use App\Redirect;

// Login Controller.
class LoginController
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
     * Registration
     *
     * @return void
     */
    public function registration()
    {
        $flashMessageList = $this->superGlobal->getFlashMessage();
        $errors;

        if ($this->superGlobal->postExist() === true) {
            $user = [];
            $errors = [];

            $postValue = $this->superGlobal->getPost();

            foreach ($postValue as $key => $value) {
                if (empty($value) === true) {
                    $errors[] = [
                                'type' => 'danger',
                                'message'   => 'Le champ est obligatoire : '.$key.''
                                ];
                } else {
                    $user[$key] = $this->superGlobal->getPostData($key);
                }
            }

            if (empty($errors) === true) {
                $user['FKIdTypeUser'] = 3;

                $userValues = new UserEntity($user);
                $createUser = $this->userModel->createUser($userValues);

                if ($createUser !== false) {
                    $this->sendEmailRegistration($createUser);
                    $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Votre a bien été créé, vous allez recevoir un mail pour le valider']);
                    $this->redirect->getRedirect('login');
                } else {
                    $this->superGlobal->createFlashMessage(['type' => 'danger', 'message' => 'Cet utilisateur existe déjà']);
                    $flashMessageList = $this->superGlobal->getFlashMessage();
                }
            } else {
                $this->superGlobal->createFlashMessage($errors);
                $flashMessageList = $this->superGlobal->getFlashMessage();
            }
        }//end if

        $view = [];
        $view['folder'] = 'templates\login';
        $view['file'] = 'registration.twig';
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end registration()


    /**
     * Login
     *
     * @return void
     */
    public function loginPage()
    {
        $flashMessageList = $this->superGlobal->getFlashMessage();
        $getValueToken;
        $getValueUserId;

        if ($this->superGlobal->getExist() === true) {
            $getValueToken = $this->superGlobal->getGetData('token');
            $getValueUserId = $this->superGlobal->getGetData('userId');
        }

        if (empty($getValueUserId) !== true && empty($getValueToken) !== true) {
            if ($this->userModel->validationUser($getValueUserId, $getValueToken) === true) {
                $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Votre compte a bien été validé, vous pouvez vous connecter']);
            }
        }

        if ($this->superGlobal->postExist() === true) {
            $postValue = $this->superGlobal->getPost();

            if (empty($postValue['login']) === false && empty($postValue['password']) === false) {
                $user = $this->userModel->login($postValue['login']);
                if (empty($user) === false) {
                    if (password_verify($postValue['password'], $user['password']) === true) {
                        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Vous êtes bien connecté']);
                        $this->superGlobal->createSession($user);
                        $this->redirect->getRedirect('account');
                    }
                }
            }
        }

        $view = [];
        $view['folder'] = 'templates\login';
        $view['file'] = 'login.twig';
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end loginPage()


    /**
     * Send Email Registration
     *
     * @param $createUser array with userID, userToken and userEmail
     * @return void
     */
    public function sendEmailRegistration($createUser)
    {
        $userId = $createUser['userId'];
        $userToken = $createUser['userToken'];
        $userEmail = $createUser['userEmail'];

        $message = 'Valider votre compte en cliquant sur le lien : http://mablog.projetformationma.com/login-validation-'.$userId.'-'.$userToken.'';

        mail($userEmail, 'Ma-Blog inscription', $message);

    }//end sendEmailRegistration()


    /**
     * Logout
     *
     * @return void
     */
    public function logout()
    {
        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Vous êtes bien déconnecté']);
        $this->superGlobal->deleteSession('auth');
        $this->redirect->getRedirect('login');

    }//end logout()


}//end class
