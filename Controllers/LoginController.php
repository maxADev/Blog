<?php

namespace Controllers;

use Entity\UserEntity;
use Models\UserModel;
use App\Superglobal;
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
     * Registration
     *
     * @return void
     */
    public function registration()
    {
        $errors = '';

        if ($this->superglobal->postExist() === true) {
            $user = [];
            $errors = [];

            $postValue = $this->superglobal->getPost();

            foreach ($postValue as $key => $value) {
                if (empty($value) === true) {
                    $errors[] = ['message' => 'Le champ est obligatoire : ',
                                 'value' => $key];
                } else {
                    $user[$key] = $this->superglobal->getPostData($key);
                }
            }

            $errors;

            if (empty($errors) === true) {
                $user['FKIdTypeUser'] = 3;

                $userValues = new UserEntity($user);
                $createUser = $this->userModel->createUser($userValues);

                if ($createUser !== false) {
                    $this->sendEmailRegistration($createUser);
                    $this->redirect->getRedirect('connexion-redirect-true');
                } else {
                    $errors[] = ['message' => "Cet utilisateur existe déjà"];
                }
            }
        }//end if

        $view = [];
        $view['folder'] = 'connexion';
        $view['file'] = 'inscription.twig';
        $view['errorLog'] = $errors;
        return $view;

    }//end registration()


    /**
     * Login
     *
     * @return void
     */
    public function loginPage()
    {
        $success = [];
        $messageValue = '';
        $getValueRedirect = '';
        $getValueLogout = '';
        $getValueToken = '';
        $getValueUserId = '';

        if ($this->superglobal->getExist() === true) {
            $getValueRedirect = $this->superglobal->getGetData('redirect');
        }

        if ($getValueRedirect === 'true') {
            $messageValue = "Un email vous a été envoyé pour valider votre compte.";
        }

        if ($this->superglobal->getExist() === true) {
            $getValueLogout = $this->superglobal->getGetData('logout');
        }

        if ($getValueLogout === 'true') {
            $messageValue = "Vous êtes bien déconnecté";
        }

        if ($this->superglobal->getExist() === true) {
            $getValueToken = $this->superglobal->getGetData('token');
            $getValueUserId = $this->superglobal->getGetData('userId');
        }

        if (empty($getValueUserId) !== true && empty($getValueToken) !== true) {
            if ($this->userModel->validationUser($getValueUserId, $getValueToken) === true) {
                $messageValue = 'Votre compte a bien été validé, vous pouvez vous connecter.';
            }
        }

        if ($this->superglobal->postExist() === true) {
            $postValue = $this->superglobal->getPost();

            if (empty($postValue['login']) === false && empty($postValue['password']) === false) {
                $user = $this->userModel->login($postValue['login']);
                if (empty($user) === false) {
                    if (password_verify($postValue['password'], $user['password']) === true) {
                        $this->superglobal->createSession($user);
                        $this->redirect->getRedirect('mon-compte');
                    }
                }
            }
        }

        $success[] = ['message' => $messageValue];
        $view = [];
        $view['folder'] = 'connexion';
        $view['file'] = 'connexion.twig';
        $view['successLog'] = $success;
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

        $message = 'Valider votre compte en cliquant sur le lien : http://mablog.projetformationma.com/connexion-validation-'.$userId.'-'.$userToken.'';

        mail($userEmail, 'Ma-Blog inscription', $message);

    }//end sendEmailRegistration()


    /**
     * Logout
     *
     * @return void
     */
    public function logout()
    {
        session_destroy();
        $this->redirect->getRedirect('connexion-logout-true');

    }//end logout()


}//end class
