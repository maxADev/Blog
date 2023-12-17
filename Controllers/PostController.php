<?php

namespace Controllers;

use Entity\PostEntity;
use Entity\CommentEntity;
use Models\PostModel;
use Models\CommentModel;
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
      * @var $postModel for PostModel class
      */
    private $commentModel;

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
        $this->commentModel = new CommentModel();
        $this->superglobal = new Superglobal();
        $this->redirect = new Redirect();

    }//end __construct()


    /**
     * Create post page
     *
     * @return view
     */
    public function postCreation()
    {
        $errors = [];
        $success = [];

        if (empty($this->superglobal->getCurrentUser()) === true) {
            $this->redirect->getRedirect('login');
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
        $view['file'] = 'postCreation.twig';
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
        $success = [];

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

        if ($this->superglobal->postExist() === true) {
            $postValue = [];

            $postValue = $this->superglobal->getPost();

            foreach ($postValue as $key => $value) {
                if (empty($value) === true) {
                    $errors[] = ['message' => 'Le champ est obligatoire : ',
                                 'value' => $key];
                } else {
                    $postValue[$key] = $this->superglobal->getPostData($key);
                }
            }

            if (empty($errors) === true) {
                $postValue['FKPostId'] = $getValuePostId;
                $postValue['FKUserId'] = $varValue['user']['id'];
                $commentValue = new CommentEntity($postValue);
                $errors[] = ['message' => 'Le commentaire ne peut-être ajouté'];
                if (empty($varValue['user']['id']) === false) {
                    if ($this->commentModel->createComment($commentValue) === true) {
                        $errors = [];
                        $success[] = ['message' => 'Le commentaire a bien été posté, il est en attente de validation'];
                    }
                }
            }
        }//end if

        $view = [];
        $view['folder'] = 'post';
        $view['file'] = 'readPost.twig';
        $view['var'] = $varValue;
        $view['errorLog'] = $errors;
        $view['successLog'] = $success;
        return $view;

    }//end readPost()


    /**
     * Get post list
     *
     * @return view
     */
    public function postModification()
    {
        $varValue = [];
        $errors[] = ['message' => 'Aucun post trouvé : '];
        $success = [];

        if (empty($this->superglobal->getCurrentUser()) === false) {
            $varValue['user'] = $this->superglobal->getCurrentUser();
        };

        if ($this->superglobal->getExist() === true) {
            $getValuePostId = $this->superglobal->getGetData('postId');
            $postValue = $this->postModel->getPost($getValuePostId);
            if (empty($postValue) === false) {
                if ($varValue['user']['id'] !== $postValue['FK_user_id']) {
                    $this->redirect->getRedirect('posts');
                }

                $errors = [];
                $varValue['post'] = $postValue;
            }
        }

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
                $post['id'] = $getValuePostId;
                if ($this->postModel->postModification($post) === true) {
                    $success[] = ['message' => 'Le post a bien été modifié'];
                }
            }
        }//end if

        $view = [];
        $view['folder'] = 'post';
        $view['file'] = 'postModification.twig';
        $view['var'] = $varValue;
        $view['errorLog'] = $errors;
        $view['successLog'] = $success;
        return $view;

    }//end postModification()


    /**
     * Get post list
     *
     * @return view
     */
    public function postDeletion()
    {
        $varValue = [];

        if (empty($this->superglobal->getCurrentUser()) === false) {
            $varValue['user'] = $this->superglobal->getCurrentUser();
        };

        if ($this->superglobal->getExist() === true) {
            $getValuePostId = $this->superglobal->getGetData('postId');
            $postValue = $this->postModel->getPost($getValuePostId);
            if (empty($postValue) === false) {
                if ($varValue['user']['id'] !== $postValue['FK_user_id']) {
                    $this->redirect->getRedirect('posts');
                }

                if (empty($postValue) === false) {
                    if ($this->postModel->postDeletion($getValuePostId) === true) {
                        $this->redirect->getRedirect('posts');
                    }
                }
            }
        }//end if

    }//end postDeletion()


}//end class
