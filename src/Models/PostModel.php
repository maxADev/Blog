<?php

namespace src\Models;
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
     * Get post list
     *
     * @return post
     */
    public function getPostList()
    {
        $sql = 'SELECT * FROM post ORDER BY creation_date DESC';

        $request = $this->connection->prepare($sql);
        $request->execute();

        $postList = $request->fetchAll(PDO::FETCH_ASSOC);

        return $postList;

    }//end getPostList()


    /**
     * Get post
     *
     * @param $postId post id
     * @return post
     */
    public function getPost($postId)
    {
        $sql = 'SELECT post.id, post.title, post.chapo, post.content, post.creation_date, post.modification_date, post.FK_user_id, user.last_name, user.first_name FROM post
                INNER JOIN user ON user.id = post.FK_user_id
                WHERE post.id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":id", $postId, PDO::PARAM_INT);
        $request->execute();

        $post = $request->fetch(PDO::FETCH_ASSOC);

        return $post;

    }//end getPost()


}//end class
