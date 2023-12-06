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

        if(isset($_POST) && !empty($_POST))
        {
            $errors = [];

            foreach ($_POST as $key => $value)
            {
                if(empty($value))
                {
                    $errors[] = $key;
                }
            }

            if(empty($errors))
            {
                $utilisateur['lastName'] = $_POST['nom'];
                $utilisateur['firstName'] = $_POST['prenom'];
                $utilisateur['email'] = $_POST['email'];
                $utilisateur['login'] = $_POST['identifiant'];
                $utilisateur['password'] = $_POST['mot_de_passe'];
                $utilisateur['FKIdTypeUser'] = 1;
                
                $utilisateur_values = new UserEntity($utilisateur);

                if($utilisateurModel->createUser($utilisateur_values))
                {
                    header('Location: Connexion-Redirect-true');
                    exit;
                }
                else
                {
                    echo 'Erreur';
                }
            }
            else
            {
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
        $inscription_message_value = '';

        if(isset($_GET['redirect']) and $_GET['redirect'] == 'true')
        {
            $inscription_message_value = "Votre compte à bien été créé, vous pouvez vous connecter";
        }
        
        $view = [];
        $view['folder'] = 'connexion';
        $view['file'] = 'connexion.twig';
        $view['var'] = ['inscription_message' => $inscription_message_value];
        return $view;   
    }//end login()


}
