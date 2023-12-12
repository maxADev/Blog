<?php

namespace Controllers;

use Entity\PostEntity;
use Models\PostModel;
use App\Superglobal;
use App\Redirect;

// Post Controller.
class PostController
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
        $this->postModel = new PostModel();
        $this->superglobal = new Superglobal();
        $this->redirect = new Redirect();

    }//end __construct()


    /**
     * Create post page
     *
     * @return view
     */
    public function create()
    {
        $errors = [];
        $success = [];

        if (empty($this->superglobal->getCurrentUser()) === true) {
            $this->redirect->getRedirect('connexion');
        };

        $varValue = ['user' => $this->superglobal->getCurrentUser()];

        if ($this->superglobal->postExist() === true) {
            $post = [];

            $postValue = $this->superglobal->getPost();

            foreach ($postValue as $key => $value) {
                if (empty($value) === true) {
                    $errors[] = ['message' => 'Le champ est obligatoire : ', 'value' => $key];
                } else {
                    $post[$key] = $this->superglobal->getPostData($key);
                }
            }

            if (empty($errors) === true) {
                $post['FkUserId'] = $varValue['user']['id'];
                $postValues = new PostEntity($post);
                if($this->postModel->createPost($postValues) === true) {
                    $success[] = ['message' => 'Le post a bien été créé'];
                }
            }
        }//end if

        $view = [];
        $view['folder'] = 'post';
        $view['file'] = 'creationPost.twig';
        $view['var'] = $varValue;
        $view['errorLog'] = $errors;
        $view['successLog'] = $success;
        return $view;

    }//end create()


}//end class
