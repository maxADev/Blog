<?php

namespace src\AdminControllers;

use src\Entity\PostEntity;
use src\AdminModels\AdminPostModel;
use App\SuperGlobal;
use App\Redirect;
use src\AdminControllers\AdminCommentController;

// Admin Post Controller.
class AdminPostController
{

    /**
      *
      * @var $postModel for PostModel class
      */
    private $postModel;

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
        $this->adminPostModel = new AdminPostModel();
        $this->superGlobal = new SuperGlobal();
        $this->redirect = new Redirect();
        $this->adminCommentController = new AdminCommentController();

    }//end __construct()


    /**
     * Get post list
     *
     * @return view
     */
    public function adminPostList()
    {
        $varValue = [];
        $errors[] = ['message' => 'Aucun post trouvé : '];

        if (empty($this->superGlobal->userIsAdmin()) === true) {
            $this->redirect->getRedirect('login');
        };

        $varValue['userAdmin'] = $this->superGlobal->getCurrentUser();
        $postList = $this->adminPostModel->adminGetPostList();

        if (empty($postList) === false) {
            $errors = [];
            $varValue['postList'] = $postList;
        }

        $view = [];
        $view['folder'] = 'adminTemplates\post';
        $view['file'] = 'adminPostList.twig';
        $view['var'] = $varValue;
        $view['errorLog'] = $errors;
        return $view;

    }//end postListAdmin()


    /**
     * Admin read post
     *
     * @return view
     */
    public function adminReadPost()
    {
        $varValue = [];
        $errors[] = ['message' => 'Aucun post trouvé'];
        $success = [];
        $varValue['commentModificationId'] = 0;

        if (empty($this->superGlobal->userIsAdmin()) === true) {
            $this->redirect->getRedirect('login');
        };

        $varValue['userAdmin'] = $this->superGlobal->getCurrentUser();

        if ($this->superGlobal->getExist() === true) {
            $getValuePostId = $this->superGlobal->getGetData('postId');
            $getValueCommentId = $this->superGlobal->getGetData('commentId');
            if (empty($getValueCommentId) === false) {
                $varValue['commentModificationId'] = $getValueCommentId;
            }
        }

        if (empty($getValuePostId) === false) {
            $post = $this->adminPostModel->adminGetPost($getValuePostId);
            if (empty($post) === false) {
                $errors = [];
                $varValue['post'] = $post;
                $varValue['commentList'] = $this->adminCommentController->adminGetPostCommentList($getValuePostId);
            }
        }

        if ($this->superGlobal->postExist() === true) {
            $postValue = [];

            $postValue = $this->superGlobal->getPost();

            foreach ($postValue as $key => $value) {
                if (empty($value) === true) {
                    $errors[] = ['message' => 'Le champ est obligatoire : ',
                                 'value' => $key];
                } else {
                    $postValue[$key] = $this->superGlobal->getPostData($key);
                }
            }

            if (empty($errors) === true) {
                $postValue['FKPostId'] = $getValuePostId;
                if (isset($postValue['comment_content_modification']) === false) {
                    $errors[] = ['message' => 'Le commentaire ne peut-être ajouté'];
                    if (empty($varValue['userAdmin']['id']) === false) {
                        if ($this->commentController->createPostComment($postValue) === true) {
                            $errors = [];
                            $success[] = ['message' => 'Le commentaire a bien été posté, il est en attente de validation'];
                            $varValue['commentList'] = $this->adminCommentController->adminGetPostCommentList($getValuePostId);
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
            }
        }//end if

        $view = [];
        $view['folder'] = 'adminTemplates\post';
        $view['file'] = 'adminReadPost.twig';
        $view['var'] = $varValue;
        $view['errorLog'] = $errors;
        $view['successLog'] = $success;
        return $view;

    }//end adminReadPost()


    /**
     * Admin Post Modification
     *
     * @return view
     */
    public function adminPostModification()
    {
        $varValue = [];
        $errors[] = ['message' => 'Aucun post trouvé : '];
        $success = [];

        if (empty($this->superGlobal->userIsAdmin()) === true) {
            $this->redirect->getRedirect('login');
        };

        $varValue['userAdmin'] = $this->superGlobal->getCurrentUser();

        if ($this->superGlobal->getExist() === true) {
            $getValuePostId = $this->superGlobal->getGetData('postId');
            $postValue = $this->adminPostModel->adminGetPost($getValuePostId);
            if (empty($postValue) === false) {
                if ($varValue['userAdmin']['FK_type_user_id'] != 2) {
                    $this->redirect->getRedirect('posts');
                }

                $errors = [];
                $varValue['post'] = $postValue;
            }
        }

        if ($this->superGlobal->postExist() === true) {
            $post = [];

            $postValue = $this->superGlobal->getPost();

            foreach ($postValue as $key => $value) {
                if (empty($value) === true) {
                    $errors[] = ['message' => 'Le champ est obligatoire : ',
                                 'value' => $key];
                } else {
                    $post[$key] = $this->superGlobal->getPostData($key);
                }
            }

            if (empty($errors) === true) {
                $post['id'] = $getValuePostId;
                if ($this->adminPostModel->adminPostModification($post) === true) {
                    $success[] = ['message' => 'Le post a bien été modifié'];
                }
            }
        }//end if

        $view = [];
        $view['folder'] = 'adminTemplates\post';
        $view['file'] = 'adminPostModification.twig';
        $view['var'] = $varValue;
        $view['errorLog'] = $errors;
        $view['successLog'] = $success;
        return $view;

    }//end adminPostModification()


}//end class
