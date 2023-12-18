<?php

namespace src\Controllers;

use src\Entity\CommentEntity;
use src\Models\PostModel;
use src\Models\CommentModel;
use App\Superglobal;
use App\Redirect;

// Comment Controller.
class CommentController extends Superglobal
{

    /**
     *
     * @var $commentModel for CommentModel class
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
        $this->commentModel = new CommentModel();
        $this->superglobal = new Superglobal();
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
        $return = false;
        if ($this->commentModel->commentModification($commentValue) === true) {
            $return = true;
        };

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

        if (empty($this->superglobal->getCurrentUser()) === false) {
            $varValue['user'] = $this->superglobal->getCurrentUser();
        };

        if ($this->superglobal->getExist() === true) {
            $getValueCommentId = $this->superglobal->getGetData('commentId');
            $commentValue = $this->commentModel->getComment($getValueCommentId);
            if (empty($commentValue) === false) {
                $postModel = new PostModel();
                $post = $postModel->getPost($commentValue['FK_post_id']);
                if ($varValue['user']['id'] !== $commentValue['FK_user_id']) {
                    $this->redirect->getRedirect('post-'.$post['id'].'-'.str_replace(' ', '-', $post['title']).'');
                }

                if (empty($commentValue) === false) {
                    if ($this->commentModel->deleteComment($getValueCommentId) === true) {
                        $this->redirect->getRedirect('post-'.$post['id'].'-'.str_replace(' ', '-', $post['title']).'');
                    }
                }
            }
        }//end if

    }//end commentDeletion()


}//end class
