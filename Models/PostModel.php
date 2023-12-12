<?php

namespace Models;
use App\Model;
use \PDO;

// Post Model.
class PostModel extends Model
{


    /**
     * Get database connection
     *
     * @return void
     */
    public function __construct()
    {
        $this->getConnection();

    }//end __construct()


    /**
     * Create post
     *
     * @param  $postValues post
     * @return void
     */
    public function createPost($postValues)
    {
        $return = false;

        $sql = 'INSERT INTO post (title, chapo, content, creation_date, modification_date, FK_user_id) VALUES (:title, :chapo, :content, NOW(), :modification_date, :FK_user_id)';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":title", $postValues->getPostTitle(), PDO::PARAM_STR);
        $request->bindValue(":chapo", $postValues->getPostChapo(), PDO::PARAM_STR);
        $request->bindValue(":content", $postValues->getPostContent(), PDO::PARAM_STR);
        $request->bindValue(":modification_date", NULL);
        $request->bindValue(":FK_user_id", $postValues->getPostFKUserId(), PDO::PARAM_STR);

        if ($request->execute() === true) {
            $return = true;
        }

        return $return;

    }//end createPost()


}//end class
