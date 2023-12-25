<?php

namespace src\Controllers;
use App\SuperGlobal;
use App\Redirect;

// Contact Controller.
class ContactController
{

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
        $this->superGlobal = new SuperGlobal();
        $this->redirect = new Redirect();

    }//end __construct()


    /**
     * Index page
     *
     * @return view
     */
    public function index()
    {
        $varValue = [];

        if (empty($this->superGlobal->getCurrentUser()) === false) {
            $varValue['user'] = $this->superGlobal->getCurrentUser();
        };

        if ($this->superGlobal->postExist() === true) {
            $errors = [];

            $postValue = $this->superGlobal->getPost();
            $errors = $this->superGlobal->checkPostData($postValue);

            if (filter_var($postValue['email'], FILTER_VALIDATE_EMAIL) === false) {
                $errors[] = [
                            'type'    => 'danger',
                            'message' => 'Email invalide'
                            ];
            }

            if (empty($errors) === true) {
                    $this->sendEmailContact($postValue);
                    $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Votre message a bien été envoyé']);
            } else {
                $this->superGlobal->createFlashMessage($errors);
            }
        }//end if

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'templates\contact';
        $view['file'] = 'contact.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end index()


    /**
     * Send Email Contact
     *
     * @param $contactValue array with email, object and message
     * @return void
     */
    public function sendEmailContact($contactValue)
    {
        $contactEmail = "maximealaphilippe12@gmail.com";
        mail($contactEmail, 'Formulaire de contact : '.$contactValue['object'].'', $contactValue['message'].' envoyé par : '.$contactValue['email']);

    }//end sendEmailContact()


}//end class
