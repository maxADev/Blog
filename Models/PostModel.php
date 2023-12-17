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


    /**
     * Get post list
     *
     * @return post
     */
    public function getPostList()
    {
        $sql = 'SELECT * FROM post';

        $request = $this->connection->prepare($sql);
        $request->execute();

        $postList = $request->fetchAll(PDO::FETCH_ASSOC);

        return $postList;

    }//end getPostList()


    /**
     * Get post list
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


    /**
     * Post modification
     *
     * @param $post post value
     * @return boolean
     */
    public function postModification($post)
    {
        $sql = 'UPDATE post SET title = :tile, chapo = :chapo, content = :content, modification_date = NOW() WHERE id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":tile", $post['title'], PDO::PARAM_STR);
        $request->bindValue(":chapo", $post['chapo'], PDO::PARAM_STR);
        $request->bindValue(":content", $post['content'], PDO::PARAM_STR);
        $request->bindValue(":id", $post['id'], PDO::PARAM_INT);

        return $request->execute();

    }//end postModification()


    /**
     * Post deletion
     *
     * @param $postId post id
     * @return boolean
     */
    public function postDeletion($postId)
    {
        $sql = 'DELETE FROM post WHERE id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":id", $postId, PDO::PARAM_INT);

        return $request->execute();

    }//end postDeletion()


}//end class
