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
                $user['FKIdTypeUser'] = 1;

                $userValues = new UserEntity($user);

                if ($this->userModel->createUser($userValues) === true) {
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
    public function login()
    {
        $messageValue = '';
        $getValue = '';

        if ($this->superglobal->getExist() === true) {
            $getValue = $this->superglobal->getGetData('redirect');
        }

        if ($getValue === 'true') {
            $messageValue = "Votre compte à bien été créé, vous pouvez vous connecter";
        }

        $view = [];
        $view['folder'] = 'connexion';
        $view['file'] = 'connexion.twig';
        $view['var'] = ['inscription_message' => $messageValue];
        return $view;

    }//end login()


}//end class
