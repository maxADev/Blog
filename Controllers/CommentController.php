<?php

namespace Controllers;

use Entity\CommentEntity;
use Models\CommentModel;
use App\Superglobal;

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
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->commentModel = new CommentModel();
        $this->superglobal = new Superglobal();

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
        if($this->commentModel->createComment($comment)) {
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
        if($this->commentModel->commentModification($commentValue)) {
            $return = true;
        };

        return $return;

    }//end commentPostModification()


}//end class
