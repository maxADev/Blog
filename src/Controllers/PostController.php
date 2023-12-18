<?php

namespace src\Controllers;

use src\Entity\PostEntity;
use src\Models\PostModel;
use App\Superglobal;
use App\Redirect;
use src\Controllers\CommentController;

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
     *
     * @var $commentController for CommentController class
     */
    private $commentController;


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
        $this->commentController = new CommentController();

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
                    $errors[] = [
                                'message' => 'Le champ est obligatoire : ',
                                'value'   => $key
                                ];
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
        $view['folder'] = 'templates\post';
        $view['file'] = 'postCreation.twig';
        $view['var'] = $varValue;
        $view['errorLog'] = $errors;
        $view['successLog'] = $success;
        return $view;

    }//end postCreation()


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
        $view['folder'] = 'templates\post';
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
        $errors[] = ['message' => 'Aucun post trouvé'];
        $success = [];
        $varValue['commentModificationId'] = 0;

        if (empty($this->superglobal->getCurrentUser()) === false) {
            $varValue['user'] = $this->superglobal->getCurrentUser();
        };

        if ($this->superglobal->getExist() === true) {
            $getValuePostId = $this->superglobal->getGetData('postId');
            $getValueCommentId = $this->superglobal->getGetData('commentId');
            if (empty($getValueCommentId) === false) {
                $varValue['commentModificationId'] = $getValueCommentId;
            }
        }

        if (empty($getValuePostId) === false) {
            $post = $this->postModel->getPost($getValuePostId);
            if (empty($post) === false) {
                $errors = [];
                $varValue['post'] = $post;
                $varValue['commentList'] = $this->commentController->getPostCommentList($getValuePostId);
            }
        }

        if ($this->superglobal->postExist() === true) {
            $postValue = [];

            $postValue = $this->superglobal->getPost();

            foreach ($postValue as $key => $value) {
                if (empty($value) === true) {
                    $errors[] = [
                                'message' => 'Le champ est obligatoire : ',
                                'value'   => $key
                                ];
                } else {
                    $postValue[$key] = $this->superglobal->getPostData($key);
                }
            }

            if (empty($errors) === true) {
                $postValue['FKPostId'] = $getValuePostId;
                $postValue['FKUserId'] = $varValue['user']['id'];
                if (isset($postValue['comment_content_modification']) === false) {
                    $errors[] = ['message' => 'Le commentaire ne peut-être ajouté'];
                    if (empty($varValue['user']['id']) === false) {
                        if ($this->commentController->createPostComment($postValue) === true) {
                            $errors = [];
                            $success[] = ['message' => 'Le commentaire a bien été posté, il est en attente de validation'];
                            $varValue['commentList'] = $this->commentController->getPostCommentList($getValuePostId);
                        }
                    }
                } else {
                    $postValue['commentContent'] = $postValue['comment_content_modification'];
                    $postValue['commentId'] = $getValueCommentId;
                    $commentValue = $postValue;
                    if ($this->commentController->commentPostModification($commentValue) === true) {
                        $this->redirect->getRedirect('post-'.$post['id'].'-'.str_replace(' ', '-', $post['title']).'');
                    }
                }
            }//end if
        }//end if

        $view = [];
        $view['folder'] = 'templates\post';
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
        $view['folder'] = 'templates\post';
        $view['file'] = 'postModification.twig';
        $view['var'] = $varValue;
        $view['errorLog'] = $errors;
        $view['successLog'] = $success;
        return $view;

    }//end postModification()


    /**
     * Post deletion
     *
     * @return void
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
