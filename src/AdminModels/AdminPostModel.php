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
     * Admin create post
     *
     * @param  $postValues post
     * @return void
     */
    public function adminCreatePost($postValues)
    {
        $return = false;

        $sql = 'INSERT INTO post (title, chapo, content, creation_date, modification_date, image, FK_user_id) VALUES (:title, :chapo, :content, NOW(), :modification_date, :image, :FK_user_id)';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":title", $postValues->getPostTitle(), PDO::PARAM_STR);
        $request->bindValue(":chapo", $postValues->getPostChapo(), PDO::PARAM_STR);
        $request->bindValue(":content", $postValues->getPostContent(), PDO::PARAM_STR);
        $request->bindValue(":modification_date", NULL);
        $request->bindValue(":image", $postValues->getPostImage());
        $request->bindValue(":FK_user_id", $postValues->getPostFKUserId(), PDO::PARAM_STR);

        if ($request->execute() === true) {
            $lastPostId = $this->connection->lastInsertId();
            $return = [
                        'return'    => true,
                        'lastPostId' => $lastPostId
                        ];
        }

        return $return;

    }//end adminCreatePost()


    /**
     * Admin get post list
     *
     * @return post
     */
    public function adminGetPostList()
    {
        $sql = 'SELECT * FROM post ORDER BY creation_date DESC';

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
        $sql = 'SELECT post.id, post.title, post.chapo, post.content, post.creation_date, post.modification_date, post.image, post.FK_user_id, user.last_name, user.first_name FROM post
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
     * @param $postValues post
     * @return boolean
     */
    public function adminPostModification($postValues)
    {
        $sql = 'UPDATE post SET title = :tile, chapo = :chapo, content = :content, modification_date = NOW(), image = :image WHERE id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":tile", $postValues->getPostTitle(), PDO::PARAM_STR);
        $request->bindValue(":chapo", $postValues->getPostChapo(), PDO::PARAM_STR);
        $request->bindValue(":content", $postValues->getPostContent(), PDO::PARAM_STR);
        $request->bindValue(":image", $postValues->getPostImage(), PDO::PARAM_STR);
        $request->bindValue(":id", $postValues->getPostId(), PDO::PARAM_INT);

        return $request->execute();

    }//end adminPostModification()


    /**
     * Admin post deletion
     *
     * @param $postId post id
     * @return boolean
     */
    public function adminPostDeletion($postId)
    {
        $sql = 'DELETE FROM post WHERE id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":id", $postId, PDO::PARAM_INT);

        return $request->execute();

    }//end adminPostDeletion()


}//end class
