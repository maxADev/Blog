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
      * @var $postModel for PostModel class
      */
    private $postModel;

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
                    $errors[] = ['message' => 'Le champ est obligatoire : ',
                                 'value' => $key];
                } else {
                    $post[$key] = $this->superglobal->getPostData($key);
                }
            }

            if (empty($errors) === true) {
                $post['FkUserId'] = $varValue['user']['id'];
                $postValues = new PostEntity($post);
                if ($this->postModel->createPost($postValues) === true) {
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


    /**
     * Get post list
     *
     * @return view
     */
    public function postList()
    {
        $varValue = [];
        $errors[] = ['message' => 'Aucun post trouvé : '];

        if (empty($this->superglobal->getCurrentUser()) === false) {
            $varValue['user'] = $this->superglobal->getCurrentUser();
        };

        $postList = $this->postModel->getPostList();

        if (empty($postList) === false) {
            $errors = [];
            $varValue['postList'] = $postList;
        }

        $view = [];
        $view['folder'] = 'post';
        $view['file'] = 'postList.twig';
        $view['var'] = $varValue;
        $view['errorLog'] = $errors;
        return $view;

    }//end postList()


    /**
     * Get post list
     *
     * @return view
     */
    public function readPost()
    {
        $varValue = [];
        $errors[] = ['message' => 'Aucun post trouvé : '];

        if (empty($this->superglobal->getCurrentUser()) === false) {
            $varValue['user'] = $this->superglobal->getCurrentUser();
        };

        if ($this->superglobal->getExist() === true) {
            $getValuePostId = $this->superglobal->getGetData('postId');
        }

        if (empty($getValuePostId) === false) {
            $post = $this->postModel->getPost($getValuePostId);
            if (empty($post) === false) {
                $errors = [];
                $varValue['post'] = $post;
            }
        }

        $view = [];
        $view['folder'] = 'post';
        $view['file'] = 'readPost.twig';
        $view['var'] = $varValue;
        $view['errorLog'] = $errors;
        return $view;

    }//end readPost()


}//end class
