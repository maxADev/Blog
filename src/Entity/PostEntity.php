<?php

namespace src\Entity;

// Post Entity.
class PostEntity
{

    /**
     *
     * @var postId post id
     */
    public $postId;

    /**
     *
     * @var postTitle post title
     */
    public $postTitle;

    /**
     *
     * @var postChapo post chapo
     */
    public $postChapo;

    /**
     *
     * @var postContent post content
     */
    public $postContent;

    /**
     *
     * @var postCreationDate post creation date
     */
    public $postCreationDate;

    /**
     *
     * @var postModificationDate post modification date
     */
    public $postModificationDate;

    /**
     *
     * @var FKUserId post fk user id
     */
    public $FKUserId;


    /**
     *
     * @var error error
     */
    public $error;


    /**
     * Create a Post
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
            $method = 'setPost'.str_replace(' ', '', $attribut);
            if (is_callable([$this, $method]) === true) {
                $this->$method($value);
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
     * Add value post id
     *
     * @param $postId post id
     * @return void
     */
    public function setPostId($postId)
    {
        $this->postId = $postId;

    }//end setPostId()


    /**
     * Add value post title
     *
     * @return void
     */
    public function setPostTitle($postTitle)
    {
        if (strlen($postTitle) <= 50) {
            $this->postTitle = $postTitle;
        } else {
            $this->error[] = ['type' => 'danger', 'message' => 'Le titre ne doit pas dépasser 50 caractères'];
        }

    }


    /**
     * Add value post chapo
     *
     * @return void
     */
    public function setPostChapo($postChapo)
    {
        if (strlen($postChapo) <= 50) {
            $this->postChapo = $postChapo;
        } else {
            $this->error[] = ['type' => 'danger', 'message' => 'Le chapo ne doit pas dépasser 150 caractères'];
        }

    }


    /**
     * Add value post content
     *
     * @return void
     */
    public function setPostContent($postContent)
    {
        $this->postContent = $postContent;

    }


    /**
     * Add value post creation date
     *
     * @return void
     */
    public function setPostCreationDate($postCreationDate)
    {
        $this->postCreationDate = $postCreationDate;

    }


    /**
     * Add value post modification date
     *
     * @return void
     */
    public function setPostModificationDate($postModificationDate)
    {
        $this->postModificationDate = $postModificationDate;

    }


    /**
     * Add value post fk user id
     *
     * @return void
     */
    public function setPostFKUserId($FKUserId)
    {
        if (empty($FKUserId) === false) {
            $this->FKUserId = $FKUserId;
        } else {
            $this->error[] = ['type' => 'danger', 'message' => 'Vous devez être connecté pour créer un post'];
        }

    }


    // Getters.


    /**
     * Get value post id
     *
     * @return post_id
     */
    public function getPostId()
    {
        return $this->post_id;

    }


    /**
     * Get value post title
     *
     * @return postTitle
     */
    public function getPostTitle()
    {
        return $this->postTitle;

    }


    /**
     * Get value post chapo
     *
     * @return postChapo
     */
    public function getPostChapo()
    {
        return $this->postChapo;

    }


    /**
     * Get value post content
     *
     * @return postContent
     */
    public function getPostContent()
    {
        return $this->postContent;

    }


    /**
     * Get value post creation date
     *
     * @return postCreationDate
     */
    public function getPostCreationDate()
    {
        return $this->postCreationDate;

    }


    /**
     * Get value post modification date
     *
     * @return postModificationDate
     */
    public function getPostModificationDate()
    {
        return $this->postModificationDate;

    }


    /**
     * Get value post fk user id
     *
     * @return FKUserId
     */
    public function getPostFKUserId()
    {
        return $this->FKUserId;

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
