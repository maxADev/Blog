<?php

namespace src\Controllers;

use src\Entity\PostEntity;
use src\Models\PostModel;
use App\SuperGlobal;
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
        $this->postModel = new PostModel();
        $this->superGlobal = new SuperGlobal();
        $this->redirect = new Redirect();
        $this->commentController = new CommentController();

    }//end __construct()


    /**
     * Get post list
     *
     * @return view
     */
    public function postList()
    {
        $varValue = [];

        if (empty($this->superGlobal->getCurrentUser()) === false) {
            $varValue['user'] = $this->superGlobal->getCurrentUser();
        };

        $postList = $this->postModel->getPostList();

        if (empty($postList) === false) {
            $varValue['postList'] = $postList;
        }

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'templates\post';
        $view['file'] = 'postList.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
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
        $errors;
        $varValue['commentModificationId'] = 0;

        if (empty($this->superGlobal->getCurrentUser()) === false) {
            $varValue['user'] = $this->superGlobal->getCurrentUser();
        };

        if ($this->superGlobal->getDataExist('postSlug') === true) {
            $getValuePostSlug = $this->superGlobal->getGetData('postSlug');
            $getValuePostId = $this->postModel->getPostId($getValuePostSlug);
        }

        if ($this->superGlobal->getDataExist('commentId') === true) {
            $getValueCommentId = $this->superGlobal->getGetData('commentId');
            $varValue['commentModificationId'] = $getValueCommentId;
        }

        if (empty($getValuePostId) === false) {
            $post = $this->postModel->getPost($getValuePostId['id']);
            if (empty($post) === false) {
                $errors = [];
                $varValue['post'] = $post;
                $varValue['commentList'] = $this->commentController->getPostCommentList($getValuePostId['id']);
            }
        }

        if ($this->superGlobal->postExist() === true) {
            if (empty($varValue['user']['id']) === true) {
                $this->redirect->getRedirect('/posts');
            }
            $postValue = [];
            $postValue = $this->superGlobal->getPost();
            $errors = $this->superGlobal->checkPostData($postValue);

            if (empty($errors) === true) {
                $postValue['FKUserId'] = $varValue['user']['id'];
                $postValue['FKPostId'] = $getValuePostId['id'];
                if (isset($postValue['comment_content_modification']) === false) {
                    $commentResult = $this->commentController->createPostComment($postValue);
                    if ($commentResult === true) {
                        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le commentaire a bien été posté, il est en attente de validation']);
                        $varValue['commentList'] = $this->commentController->getPostCommentList($getValuePostId['id']);
                    } else {
                        $this->superGlobal->createFlashMessage($commentResult);    
                    }
                } else {
                    $postValue['commentContent'] = $postValue['comment_content_modification'];
                    $postValue['commentId'] = $getValueCommentId;
                    $commentValue = $postValue;
                    $commentResult = $this->commentController->commentPostModification($commentValue);
                    if ($commentResult === true) {
                        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le commentaire a bien été modifié, il est en attente de validation']);
                        $this->redirect->getRedirect('/post/'.$post['slug'].'');
                    } else {
                        $this->superGlobal->createFlashMessage($commentResult);
                        $this->redirect->getRedirect('/post/'.$post['slug'].'');
                    }
                }
            } else {
                $this->superGlobal->createFlashMessage($errors);
            }
        }//end if

        $flashMessageList = $this->superGlobal->getFlashMessage();
        $view = [];
        $view['folder'] = 'templates\post';
        $view['file'] = 'readPost.twig';
        $view['var'] = $varValue;
        $view['flashMessageList'] = $flashMessageList;
        return $view;

    }//end readPost()


    /**
     * Get post list home
     *
     * @return postList
     */
    public function postListHome()
    {
        $postList = $this->postModel->getPostListHome();
        return $postList;

    }//end postListHome()


}//end class
