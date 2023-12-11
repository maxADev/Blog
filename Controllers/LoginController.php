<?php

namespace Controllers;

use Entity\UserEntity;
use Models\UserModel;
use App\Superglobal;

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
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->userModel = new UserModel;
        $this->superglobal = new Superglobal;

    }//end __construct()


    /**
     * Registration
     *
     * @return void
     */
    public function registration()
    {
        $error_log = '';

        if ($this->superglobal->postExist() === true) {
            $user = [];
            $errors = [];

            $postValue = $this->superglobal->getPost();

            foreach ($postValue as $key => $value) {
                if (empty($value) === true) {
                    $errors[] = $key;
                } else {
                    $user[$key] = $this->superglobal->getPostData($key);
                }
            }

            $error_log = ['error' => $errors];

            if (empty($errors) === true) {
                $user['FKIdTypeUser'] = 3;

                $userValues = new UserEntity($user);
                $createUser = $this->userModel->createUser($userValues);

                if ($createUser !== false) {;
                    $this->sendEmailRegistration($createUser);
                    header('Location: Connexion-Redirect-true');
                } else {
                    $error_log = ['error_message' => "Cet utilisateur existe déjà"];
                }
            }
        }//end if

        $view = [];
        $view['folder'] = 'connexion';
        $view['file'] = 'inscription.twig';
        $view['var'] = $error_log;
        return $view;

    }//end registration()


    /**
     * Login
     *
     * @return void
     */
    public function loginPage()
    {
        $messageValue = '';
        $getValueRedirect = '';
        $getValueToken = '';
        $getValueUserId = '';

        if ($this->superglobal->getExist() === true) {
            $getValueRedirect = $this->superglobal->getGetData('redirect');
        }

        if ($getValueRedirect === 'true') {
            $messageValue = "Un email vous a été envoyé pour valider votre compte.";
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

        $view = [];
        $view['folder'] = 'connexion';
        $view['file'] = 'connexion.twig';
        $view['var'] = ['inscription_message' => $messageValue];
        return $view;

    }//end login()


    /**
     * Send Email Registration
     *
     * @return void
     */
    public function sendEmailRegistration($createUser)
    {
        $userId = $createUser['userId'];
        $userToken = $createUser['userToken'];
        $userEmail = $createUser['userEmail'];

        $message = 'Valider votre compte en cliquant sur le lien : http://mablog.projetformationma.com/Connexion-Validation-'.$userId.'-'.$userToken.'';
    
        mail($userEmail, 'Ma-Blog inscription', $message);
    }

}//end class
