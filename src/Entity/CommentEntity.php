<?php

namespace src\Entity;

// Comment Entity.
class CommentEntity
{

    /**
     *
     * @var commentId comment id
     */
    public $commentId;

    /**
     *
     * @var commentContent comment content
     */
    public $commentContent;

    /**
     *
     * @var commentCreationDate comment creation date
     */
    public $commentCreationDate;

    /**
     *
     * @var commentModificationDate comment modification date
     */
    public $commentModificationDate;

    /**
     *
     * @var FKStatutCommentId comment fk statut comment id
     */
    public $FKStatutCommentId;

    /**
     *
     * @var FKUserId comment fk user id
     */
    public $FKUserId;

    /**
     *
     * @var FKPostId comment fk post id
     */
    public $FKPostId;


    /**
     *
     * @var error error
     */
    public $error;


    /**
     * Create a comment
     *
     * @param $arrayValue value
     * @return void
     */
    public function __construct($arrayValue=[])
    {
        $this->hydrate($arrayValue);

    }//end __construct()


    /**
     * Add value
     *
     * @param $data value
     * @return void
     */
    public function hydrate($data)
    {
        foreach ($data as $attribut => $value) {
            $method = 'setComment'.str_replace(' ', '', $attribut);
            if (is_callable([$this, $method]) === true) {
                $this->$method(htmlspecialchars($value));
            }
        }

    }//end hydrate()


    /**
     * Check no error value
     *
     * @return boolean
     */
    public function isValid()
    {
        $return = false;
        if(empty($this->error) === true)
        {
            $return = true;
        }

        return $return;

    }//end isValid()


    // Setters.


    /**
     * Add value comment id
     *
     * @param $commentId comment id
     * @return void
     */
    public function setCommentId($commentId)
    {
        $this->commentId = $commentId;

    }//end setCommentId()


    /**
     * Add value comment content
     *
     * @return void
     */
    public function setCommentContent($commentContent)
    {
        if (strlen($commentContent) <= 150) {
            $this->commentContent = $commentContent;
        } else {
            $this->error[] = ['type' => 'danger', 'message' => 'Le commentaire ne doit pas dépasser 150 caractères'];
        }

    }//end setCommentContent()


    /**
     * Add value comment creation date
     *
     * @return void
     */
    public function setCommentCreationDate($commentCreationDate)
    {
        $this->commentCreationDate = $commentCreationDate;

    }


    /**
     * Add value comment modification date
     *
     * @return void
     */
    public function setCommentModificationDate($commentModificationDate)
    {
        $this->commentModificationDate = $commentModificationDate;

    }


    /**
     * Add value comment fk statut comment id
     *
     * @return void
     */
    public function setCommentFKStatutCommentId($FKStatutCommentId)
    {
        $this->FKStatutCommentId = $FKStatutCommentId;

    }


    /**
     * Add value comment fk user id
     *
     * @return void
     */
    public function setcommentFKUserId($FKUserId)
    {
        $this->FKUserId = $FKUserId;

    }


    /**
     * Add value comment fk post id
     *
     * @return void
     */
    public function setcommentFKPostId($FKPostId)
    {
        $this->FKPostId = $FKPostId;

    }


    // Getters.


    /**
     * Get value comment id
     *
     * @return commentId
     */
    public function getCommentId()
    {
        return $this->commentId;

    }


    /**
     * Get value comment content
     *
     * @return commentContent
     */
    public function getCommentContent()
    {
        return $this->commentContent;

    }


    /**
     * Get value comment creation date
     *
     * @return commentCreationDate
     */
    public function getCommentCreationDate()
    {
        return $this->commentCreationDate;

    }


    /**
     * Get value comment modification date
     *
     * @return commentModificationDate
     */
    public function getCommentModificationDate()
    {
        return $this->commentModificationDate;

    }


    /**
     * Get value comment fk statut comment id
     *
     * @return FKStatutCommentId
     */
    public function getcommentFKStatutCommentId()
    {
        return $this->FKStatutCommentId;

    }


    /**
     * Get value comment fk user id
     *
     * @return FKUserId
     */
    public function getcommentFKUserId()
    {
        return $this->FKUserId;

    }


    /**
     * Get value comment fk post id
     *
     * @return setcommentFKPostId
     */
    public function getcommentFKPostId()
    {
        return $this->FKPostId;

    }


    /**
     * Get error
     *
     * @return error
     */
    public function getError()
    {
        return $this->error;

    }


}//end class
