<?php

namespace src\Controllers;

use src\Entity\CommentEntity;
use src\Models\PostModel;
use src\Models\CommentModel;
use App\SuperGlobal;
use App\Redirect;

// Comment Controller.
class CommentController extends SuperGlobal
{

    /**
     *
     * @var $commentModel for CommentModel class
     */
    private $commentModel;

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
        $this->commentModel = new CommentModel();
        $this->superGlobal = new SuperGlobal();
        $this->redirect = new Redirect();

    }//end __construct()


    /**
     * Get post comment list
     *
     * @param $postId post id
     * @return comment
     */
    public function getPostCommentList($postId)
    {
        $commentList = $this->commentModel->getCommentList($postId);

        return $commentList;

    }//end getPostCommentList()


    /**
     * Create post comment
     *
     * @param $commentValue comment value
     * @return comment
     */
    public function createPostComment($commentValue)
    {
        $return = false;
        $comment = new CommentEntity($commentValue);
        if ($this->commentModel->createComment($comment) === true) {
            $return = true;
        };

        return $return;

    }//end createPostComment()


    /**
     * Comment post modification
     *
     * @param $commentValue comment value
     * @return comment
     */
    public function commentPostModification($commentValue)
    {
        $varValue = [];
        $return = false;

        if (empty($this->superGlobal->getCurrentUser()) === false) {
            $varValue['user'] = $this->superGlobal->getCurrentUser();
        } else {
            $this->redirect->getRedirect('/login');
        }

        $comment = $this->commentModel->getComment($commentValue['commentId']);

        if ($varValue['user']['id'] === $comment['FK_user_id']) {
            if ($this->commentModel->commentModification($commentValue) === true) {
                $return = true;
            };
        }

        return $return;

    }//end commentPostModification()


    /**
     * Comment deletion
     *
     * @return void
     */
    public function commentDeletion()
    {
        $varValue = [];

        if (empty($this->superGlobal->getCurrentUser()) === false) {
            $varValue['user'] = $this->superGlobal->getCurrentUser();
        };

        if ($this->superGlobal->getExist() === true) {
            $getValueCommentId = $this->superGlobal->getGetData('commentId');
            $commentValue = $this->commentModel->getComment($getValueCommentId);
            if (empty($commentValue) === false) {
                $postModel = new PostModel();
                $post = $postModel->getPost($commentValue['FK_post_id']);
                if ($varValue['user']['id'] !== $commentValue['FK_user_id']) {
                    $this->redirect->getRedirect('/post/'.$post['id'].'/'.str_replace(' ', '-', $post['title']).'');
                }

                if (empty($commentValue) === false) {
                    if ($this->commentModel->deleteComment($getValueCommentId) === true) {
                        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le commentaire a bien été supprimé']);
                        $this->redirect->getRedirect('/post/'.$post['id'].'/'.str_replace(' ', '-', $post['title']).'');
                    }
                }
            }
        }//end if

    }//end commentDeletion()


}//end class
