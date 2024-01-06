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
        $return = [
                   'return'     => false,
                   'lastPostId' => '',
                  ];

        $sql = 'INSERT INTO post (title, chapo, content, creation_date, modification_date, image, slug, FK_user_id, FK_post_statut_id, FK_category_id) VALUES (:title, :chapo, :content, NOW(), :modification_date, :image, :slug, :FK_user_id, :FK_post_statut_id, :FK_category_id)';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":title", $postValues->getPostTitle(), PDO::PARAM_STR);
        $request->bindValue(":chapo", $postValues->getPostChapo(), PDO::PARAM_STR);
        $request->bindValue(":content", $postValues->getPostContent(), PDO::PARAM_STR);
        $request->bindValue(":modification_date", NULL);
        $request->bindValue(":image", $postValues->getPostImage());
        $request->bindValue(":slug", $postValues->getPostSlug());
        $request->bindValue(":FK_user_id", $postValues->getPostFKUserId(), PDO::PARAM_INT);
        $request->bindValue(":FK_post_statut_id", $postValues->getPostFKPostStatutId(), PDO::PARAM_INT);
        $request->bindValue(":FK_category_id", $postValues->getPostFKCategoryId(), PDO::PARAM_INT);

        if ($request->execute() === true) {
            $lastPostId = $this->connection->lastInsertId();
            $return = [
                       'return'     => true,
                       'lastPostId' => $lastPostId,
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
        $sql = 'SELECT post.id, post.title, post.chapo, post.creation_date, post.modification_date, post.slug, post.FK_user_id, post.FK_post_statut_id, post_statut.post_statut_name, category.name FROM post 
                INNER JOIN post_statut on post_statut.id = post.FK_post_statut_id
                INNER JOIN category on category.id = post.FK_category_id
                ORDER BY post.creation_date DESC';

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
        $sql = 'SELECT post.id, post.title, post.chapo, post.content, post.creation_date, post.modification_date, post.image, post.slug, post.FK_user_id, post.FK_post_statut_id, post.FK_category_id, user.last_name, user.first_name, post_statut.post_statut_name, category.name FROM post
                INNER JOIN user ON user.id = post.FK_user_id
                INNER JOIN post_statut on post_statut.id = post.FK_post_statut_id
                INNER JOIN category on category.id = post.FK_category_id
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
        $sql = 'UPDATE post SET title = :tile, chapo = :chapo, content = :content, modification_date = NOW(), image = :image, slug = :slug, FK_user_id = :FK_user_id, FK_post_statut_id = :FK_post_statut_id, FK_category_id = :FK_category_id WHERE id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":tile", $postValues->getPostTitle(), PDO::PARAM_STR);
        $request->bindValue(":chapo", $postValues->getPostChapo(), PDO::PARAM_STR);
        $request->bindValue(":content", $postValues->getPostContent(), PDO::PARAM_STR);
        $request->bindValue(":image", $postValues->getPostImage(), PDO::PARAM_STR);
        $request->bindValue(":slug", $postValues->getPostSlug(), PDO::PARAM_STR);
        $request->bindValue(":FK_user_id", $postValues->getPostFKUserId(), PDO::PARAM_INT);
        $request->bindValue(":FK_post_statut_id", $postValues->getPostFKPostStatutId(), PDO::PARAM_INT);
        $request->bindValue(":FK_category_id", $postValues->getPostFKCategoryId(), PDO::PARAM_INT);
        $request->bindValue(":id", $postValues->getPostId(), PDO::PARAM_INT);

        return $request->execute();

    }//end adminPostModification()


    /**
     * Admin post admin modification
     *
     * @param $postValues post
     * @return boolean
     */
    public function adminPostAdminModification($postValues)
    {
        $sql = 'UPDATE post SET FK_user_id = :FK_user_id, FK_post_statut_id = :FK_post_statut_id, modification_date = NOW() WHERE id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":FK_user_id", $postValues['FKUserId'], PDO::PARAM_INT);
        $request->bindValue(":FK_post_statut_id", $postValues['FKPostStatutId'], PDO::PARAM_INT);
        $request->bindValue(":id", $postValues['id'], PDO::PARAM_INT);

        return $request->execute();

    }//end adminPostAdminModification()


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


    /**
     * Admin get post category list
     *
     * @return postCategoryList
     */
    public function adminGetPostCategoryList()
    {
        $sql = 'SELECT * FROM category';

        $request = $this->connection->prepare($sql);
        $request->execute();

        $postCategoryList = $request->fetchAll(PDO::FETCH_ASSOC);

        return $postCategoryList;

    }//end adminGetPostCategoryList()


    /**
     * Admin get post statut list
     *
     * @return postStatutList
     */
    public function adminGetPostStatutList()
    {
        $sql = 'SELECT * FROM post_statut';

        $request = $this->connection->prepare($sql);
        $request->execute();

        $postStatutList = $request->fetchAll(PDO::FETCH_ASSOC);

        return $postStatutList;

    }//end adminGetPostStatutList()


    /**
     * Admin check post slug
     *
     * @param $value value
     * @return boolean
     */
    public function adminCheckPostSlug($value)
    {
        $return = false;
        $sql = 'SELECT id FROM post WHERE slug = :slug';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":slug", $value, PDO::PARAM_STR);
        $request->execute();

        $post = $request->fetchAll(PDO::FETCH_ASSOC);

        if (empty($post) === true) {
            $return = true;
        }

        return $return;

    }//end adminCheckPostSlug()


    /**
     * Admin get post id
     *
     * @return int
     */
    public function adminGetPostId($value)
    {
        $sql = 'SELECT id FROM post WHERE slug = :slug';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":slug", $value, PDO::PARAM_STR);
        $request->execute();

        $post = $request->fetch(PDO::FETCH_ASSOC);

        return $post;

    }//end adminGetPostId()


}//end class
