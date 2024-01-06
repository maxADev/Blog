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
     * @return commentList
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
     * @return boolean
     */
    public function createPostComment($commentValue)
    {
        $return = false;
        $comment = new CommentEntity($commentValue);

        if ($comment->isValid() === true) {
            if ($this->commentModel->createComment($comment) === true) {
                $return = true;
            }
        } else {
            $return = $comment->getError();
        }

        return $return;

    }//end createPostComment()


    /**
     * Comment post modification
     *
     * @param $commentValue comment value
     * @return boolean
     */
    public function commentPostModification($commentValue)
    {
        $varValue = [];
        $return = [
                   'type'    => 'danger',
                   'message' => 'Vous ne pouvez pas modifier ce commentaire',
                  ];

        if (empty($this->superGlobal->getCurrentUser()) === true) {
            $this->redirect->getRedirect('/login');
        }

        $this->superGlobal->checkToken($this->superGlobal->getGetData('token'));
        $varValue['user'] = $this->superGlobal->getCurrentUser();

        $comment = $this->commentModel->getComment($commentValue['commentId']);

        if ($varValue['user']['id'] === $comment['FK_user_id']) {
            $commentValue['content'] = $commentValue['comment_content_modification'];
            $commentValue['id'] = $comment['id'];
            $newComment = new CommentEntity($commentValue);
            if ($newComment->isValid() === true) {
                if ($this->commentModel->commentModification($newComment) === true) {
                    $return = true;
                }
            } else {
                $return = $newComment->getError();
            }
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

        if (empty($this->superGlobal->getCurrentUser()) === true) {
            $this->redirect->getRedirect('/login');
        };

        $this->superGlobal->checkToken($this->superGlobal->getGetData('token'));
        $varValue['user'] = $this->superGlobal->getCurrentUser();

        if ($this->superGlobal->getDataExist('commentId') === true) {
            $getValueCommentId = $this->superGlobal->getGetData('commentId');
            $commentValue = $this->commentModel->getComment($getValueCommentId);
            if (empty($commentValue) === false) {
                $postModel = new PostModel();
                $post = $postModel->getPost($commentValue['FK_post_id']);
                if ($varValue['user']['id'] !== $commentValue['FK_user_id']) {
                    $this->redirect->getRedirect('/post/'.$post['slug'].'');
                } else {
                    if ($this->commentModel->deleteComment($getValueCommentId) === true) {
                        $this->superGlobal->createFlashMessage(['type' => 'success', 'message' => 'Le commentaire a bien été supprimé']);
                        $this->redirect->getRedirect('/post/'.$post['slug'].'');
                    }
                }
            } else {
                $this->redirect->getRedirect('/posts');
            }
        }//end if

    }//end commentDeletion()


    /**
     * Get user comment
     *
     * @param $userId user id
     * @return void
     */
    public function getUserComment($userId)
    {
        if (empty($userId) === false) {
            $commentList = $this->commentModel->getUserComments($userId);
            return $commentList;
        }

    }//end getUserComment()


}//end class
