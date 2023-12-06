<?php

namespace Controllers;

Use Entity\UserEntity;
Use Models\UserModel;

// Login Controller.
class LoginController {

    /**
     * Registration
     *
     * @return void
     */
    public function registration()
    {
        $utilisateurModel = new UserModel;

        if (isset($_POST) === true && !empty($_POST) === true) {
            $user = [];
            $errors = [];

            foreach ($_POST as $key => $value) {
                if (empty($value)) {
                    $errors[] = $key;
                }
            }

            if (empty($errors)) {
                $user['lastName'] = $_POST['nom'];
                $user['firstName'] = $_POST['prenom'];
                $user['email'] = $_POST['email'];
                $user['login'] = $_POST['identifiant'];
                $user['password'] = $_POST['mot_de_passe'];
                $user['FKIdTypeUser'] = 1;
                
                $userValues = new UserEntity($user);

                if ($utilisateurModel->createUser($userValues)) {
                    header('Location: Connexion-Redirect-true');
                } else {
                    echo 'Erreur';
                }
            } else {
                var_dump($errors);
            }
        }

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

        if (isset($_GET['redirect']) and $_GET['redirect'] == 'true') {
            $messageValue = "Votre compte à bien été créé, vous pouvez vous connecter";
        }

        $view = [];
        $view['folder'] = 'connexion';
        $view['file'] = 'connexion.twig';
        $view['var'] = ['inscription_message' => $messageValue];
        return $view;
    }//end login()


}
