<?php

namespace src\AdminModels;
use App\Model;
use \PDO;

// Admin Post Model.
class AdminPostModel extends Model
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
     * Admin get post list
     *
     * @return post
     */
    public function adminGetPostList()
    {
        $sql = 'SELECT * FROM post';

        $request = $this->connection->prepare($sql);
        $request->execute();

        $postList = $request->fetchAll(PDO::FETCH_ASSOC);

        return $postList;

    }//end adminGetPostList()


    /**
     * Admin get post
     *
     * @param $postId post id
     * @return post
     */
    public function adminGetPost($postId)
    {
        $sql = 'SELECT post.id, post.title, post.chapo, post.content, post.creation_date, post.modification_date, post.FK_user_id, user.last_name, user.first_name FROM post
                INNER JOIN user ON user.id = post.FK_user_id
                WHERE post.id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":id", $postId, PDO::PARAM_INT);
        $request->execute();

        $post = $request->fetch(PDO::FETCH_ASSOC);

        return $post;

    }//end adminGetPost()


    /**
     * Admin post modification
     *
     * @param $post post value
     * @return boolean
     */
    public function adminPostModification($post)
    {
        $sql = 'UPDATE post SET title = :tile, chapo = :chapo, content = :content, modification_date = NOW() WHERE id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":tile", $post['title'], PDO::PARAM_STR);
        $request->bindValue(":chapo", $post['chapo'], PDO::PARAM_STR);
        $request->bindValue(":content", $post['content'], PDO::PARAM_STR);
        $request->bindValue(":id", $post['id'], PDO::PARAM_INT);

        return $request->execute();

    }//end adminPostModification()


}//end class
