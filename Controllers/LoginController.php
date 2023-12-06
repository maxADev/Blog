<?php

namespace Controllers;

use Entity\UserEntity;
use Models\UserModel;

// Login Controller.
class LoginController
{


    /**
     * Registration
     *
     * @return void
     */
    public function registration()
    {
        $utilisateurModel = new UserModel;

        if (isset($_POST) === true && empty($_POST) === false) {
            $user = [];
            $errors = [];

            foreach ($_POST as $key => $value) {
                if (empty($value) === true) {
                    $errors[] = $key;
                } else {
                    $user[$key] = $_POST[$key];
                }
            }

            if (empty($errors) === true) {
                $user['FKIdTypeUser'] = 1;

                $userValues = new UserEntity($user);

                if ($utilisateurModel->createUser($userValues) === true) {
                    header('Location: Connexion-Redirect-true');
                } else {
                   $error_log = "Error create user";
                }
            } else {
                var_dump($errors);
            }
        }//end if

        $view = [];
        $view['folder'] = 'connexion';
        $view['file'] = 'inscription.twig';
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

        if (isset($_GET['redirect']) === true && $_GET['redirect'] === 'true') {
            $messageValue = "Votre compte à bien été créé, vous pouvez vous connecter";
        }

        $view = [];
        $view['folder'] = 'connexion';
        $view['file'] = 'connexion.twig';
        $view['var'] = ['inscription_message' => $messageValue];
        return $view;

    }//end login()


}//end class
