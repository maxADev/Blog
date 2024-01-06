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
        $sql = 'SELECT post.id, post.title, post.chapo, post.creation_date, post.modification_date, post.slug, post.FK_user_id, post.FK_post_statut_id, post_statut.post_statut_name, category.name FROM post 
                INNER JOIN post_statut on post_statut.id = post.FK_post_statut_id
                INNER JOIN category on category.id = post.FK_category_id
                WHERE post.FK_post_statut_id = 2
                ORDER BY post.creation_date DESC';

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
        $sql = 'SELECT post.id, post.title, post.chapo, post.content, post.creation_date, post.modification_date, post.image, post.slug, post.FK_user_id, user.last_name, user.first_name, post_statut.post_statut_name, category.name FROM post
                INNER JOIN user ON user.id = post.FK_user_id
                INNER JOIN post_statut on post_statut.id = post.FK_post_statut_id
                INNER JOIN category on category.id = post.FK_category_id
                WHERE post.id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":id", $postId, PDO::PARAM_INT);
        $request->execute();

        $post = $request->fetch(PDO::FETCH_ASSOC);

        return $post;

    }//end getPost()


    /**
     * Get post list home
     *
     * @return post
     */
    public function getPostListHome()
    {
        $sql = 'SELECT post.id, post.title, post.chapo, post.creation_date, post.modification_date, post.slug, post.FK_user_id, post.FK_post_statut_id, post_statut.post_statut_name, category.name FROM post 
                INNER JOIN post_statut on post_statut.id = post.FK_post_statut_id
                INNER JOIN category on category.id = post.FK_category_id
                WHERE post.FK_post_statut_id = 2
                ORDER BY post.creation_date DESC
                LIMIT 3';

        $request = $this->connection->prepare($sql);
        $request->execute();

        $postList = $request->fetchAll(PDO::FETCH_ASSOC);

        return $postList;

    }//end getPostListHome()


    /**
     * Get post id
     *
     * @param $value value
     * @return int
     */
    public function getPostId($value)
    {
        $sql = 'SELECT id FROM post WHERE slug = :slug';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":slug", $value, PDO::PARAM_STR);
        $request->execute();

        $post = $request->fetch(PDO::FETCH_ASSOC);

        return $post;

    }//end getPostId()


}//end class
