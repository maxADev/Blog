<?php

namespace Controllers;

use Entity\UserEntity;
use Models\UserModel;
use App\Superglobal;

// Login Controller.
class LoginController
{

    private $userModel;
    private $superglobals;


    /**
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->userModel = new UserModel;
        $this->superglobals = new Superglobal;
    }//end __construct()

    /**
     * Registration
     *
     * @return void
     */
    public function registration()
    {
        $error_log = '';
        
        if ($this->superglobals->postExist() === true) {

            $user = [];
            $errors = [];

            $postValue = $this->superglobals->getPost();

            foreach ($postValue as $key => $value) {
                if (empty($value) === true) {
                    $errors[] = $key;
                } else {
                    $user[$key] = $this->superglobals->getPostData($key);
                }
            }

            if (empty($errors) === true) {
                $user['FKIdTypeUser'] = 1;

                $userValues = new UserEntity($user);

                if ($this->userModel->createUser($userValues) === true) {
                    header('Location: Connexion-Redirect-true');
                } else {
                    $error_log = "Error create user";
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

        if($this->superglobals->getExist())
        {
            $getValue = $this->superglobals->getGetData('redirect');
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
