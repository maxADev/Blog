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
        $varValue = [];
        $errors = [];
        if ($this->superGlobal->postExist() === true) {
            $postValue = $this->superGlobal->getPost();
            $errors = $this->superGlobal->checkPostData($postValue);

            if ($postValue['password'] !== $postValue['confirmPassword']) {
                $errors[] = [
                            'type' => 'danger',
                            'message'   => 'Les mots de passe sont différents'
                            ];
            }

            $postValue['FKIdTypeUser'] = 3;
            $userValues = new UserEntity($postValue);

            if (empty($errors) === true && $userValues->isValid() === true) {
                    $createUser = $this->userModel->createUser($userValues);
                    if ($createUser !== false) {
                        $this->sendEmailRegistration($createUser);
                        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Votre a bien été créé, vous allez recevoir un mail pour le valider']);
                        $this->redirect->getRedirect('/login');
                    } else {
                        $this->superGlobal->createFlashMessage(['type' => 'danger', 'message' => 'Cet utilisateur existe déjà']);
                    }
            } else {
                $varValue['registrationValue'] = $postValue;
                $this->superGlobal->createFlashMessage($userValues->getError());
                $this->superGlobal->createFlashMessage($errors);
            }
        }//end if

        $varValue['formSetting'] = [
                                    "lastName"        => ["label" => "Nom",                          "type" => "text",     "placeholder" => "Votre nom"],
                                    "firstName"       => ["label" => "Prénom",                       "type" => "text",     "placeholder" => "Votre prénom"],
                                    "email"           => ["label" => "Email",                        "type" => "email", "   placeholder" => "Votre email"],
                                    "login"           => ["label" => "Identifiant",                  "type" => "text", "    placeholder" => "Votre identifiant"],
                                    "password"        => ["label" => "Mot de passe",                 "type" => "password", "placeholder" => "Votre mot de passe"],
                                    "confirmPassword" => ["label" => "Confirmation du mot de passe", "type" => "password", "placeholder" => "Confirmer votre mot de passe"],
                                   ];

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'templates\login';
        $view['file'] = 'registration.twig';
        $view['var'] = $varValue;
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
        $getValueToken;
        $getValueUserId;

        if (empty($this->superGlobal->getCurrentUser()) === false) {
            $this->redirect->getRedirect('/account');
        };

        if ($this->superGlobal->getDataExist('token') === true && $this->superGlobal->getDataExist('userId')) {
            $getValueToken = $this->superGlobal->getGetData('token');
            $getValueUserId = $this->superGlobal->getGetData('userId');

            if ($this->userModel->validationUser($getValueUserId, $getValueToken) === true) {
                $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Votre compte a bien été validé, vous pouvez vous connecter']);
            }
        }

        if ($this->superGlobal->postExist() === true) {
            $postValue = $this->superGlobal->getPost();
            $errors = $this->superGlobal->checkPostData($postValue);

            if (empty($errors) === true) {
                $user = $this->userModel->login($postValue['login']);
                if (empty($user) === false && password_verify($postValue['password'], $user['password']) === true) {
                    $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Vous êtes bien connecté']);
                    $this->superGlobal->createSession($user);
                    $this->redirect->getRedirect('/account');
                } else {
                    $this->superGlobal->createFlashMessage(['type' => 'danger', 'message' => 'Identifiant ou mot de passe incorrect']);
                }
            } else {
                $this->superGlobal->createFlashMessage($errors);
            }
        }

        $flashMessageList = $this->superGlobal->getFlashMessage();
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

        $message = 'Valider votre compte en cliquant sur le lien : http://mablog.projetformationma.com/login/validation/'.$userId.'/'.$userToken.'';

        mail($userEmail, 'Ma-Blog inscription', $message);

    }//end sendEmailRegistration()


    /**
     * Send Email reset password
     *
     * @param $userValue array with userId and userEmail
     * @return void
     */
    public function sendEmailResetPassword($userValue)
    {
        $userId = $userValue['id'];
        $userEmail = $userValue['email'];
        $userToken = $userValue['token'];

        $message = 'Réinitialiser votre mot de passe en cliquant sur le lien : http://mablog.projetformationma.com/login/reset/password/'.$userId.'/'.$userToken.'';

        mail($userEmail, 'Ma-Blog réinitialisation', $message);

    }//end sendEmailResetPassword()


    /**
     * Reset Password page
     *
     * @return void
     */
    public function resetPasswordPage()
    {
        if ($this->superGlobal->postExist() === true) {
            $postValue = $this->superGlobal->getPost();
            $errors = $this->superGlobal->checkPostData($postValue);

            if (empty($errors) === true) {
                $user = $this->userModel->checkUserExist($postValue['login']);
                if (empty($user) === false || $user !== false) {
                    $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Un email vas vous être envoyé pour changer votre mot de passe']);
                    $this->sendEmailResetPassword($user);
                } else {
                    $this->superGlobal->createFlashMessage(['type' => 'danger', 'message' => 'Cet email correspond a aucun compte']);
                }
            } else {
                $this->superGlobal->createFlashMessage($errors);
            }
        }

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'templates\login';
        $view['file'] = 'resetPasswordPage.twig';
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end resetPasswordPage()


    /**
     * Reset Password
     *
     * @return void
     */
    public function resetPassword()
    {
        if ($this->superGlobal->getDataExist('userId') === false || $this->superGlobal->getDataExist('token') === false) {
            $this->redirect->getRedirect('/login');
        }

        $getValueUserId = $this->superGlobal->getGetData('userId');
        $getValueToken = $this->superGlobal->getGetData('token');

        if ($this->superGlobal->postExist() === true) {
            $postValue = $this->superGlobal->getPost();
            $errors = $this->superGlobal->checkPostData($postValue);

            if (empty($errors) === true) {
                $postValue['id'] = $getValueUserId;
                $postValue['token'] = $getValueToken;
                if(strlen($postValue['password']) <= 60 && strlen($postValue['password']) >= 8) {
                    if ($postValue['password'] === $postValue['confirmPassword']) {
                        if ($this->userModel->changePassword($postValue) === true) {
                            $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Votre mot de passe a bien été changé']);
                            $this->redirect->getRedirect('/login');
                        } else {
                            $this->superGlobal->createFlashMessage(['type' => 'danger', 'message' => 'Erreur vous ne pouvez pas changer le mot de passe']);
                        }
                    } else {
                        $this->superGlobal->createFlashMessage(['type' => 'danger', 'message' => 'Les mots de passe sont différents']);
                    }
            } else {
                $this->superGlobal->createFlashMessage(['type' => 'danger', 'message' => 'Le mot de passe doit faire entre 8 et 60 caractères']);
            }
            } else {
                $this->superGlobal->createFlashMessage($errors);
            }
        }

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'templates\login';
        $view['file'] = 'resetPassword.twig';
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end resetPassword()


    /**
     * Logout
     *
     * @return void
     */
    public function logout()
    {
        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Vous êtes bien déconnecté']);
        $this->superGlobal->deleteSession('auth');
        $this->redirect->getRedirect('/login');

    }//end logout()


}//end class
