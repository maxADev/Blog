<?php

namespace src\AdminModels;
use App\Model;
use \PDO;

// Admin Comment Model.
class AdminCommentModel extends Model
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
     * Admin get post comment list
     *
     * @param $postId post id
     * @return comment
     */
    public function adminGetCommentList($postId)
    {
        $sql = 'SELECT comment.id, comment.content, comment.creation_date, comment.modification_date, comment.FK_statut_comment_id, comment.FK_user_id, comment.FK_post_id, user.last_name, user.first_name, comment_statut.comment_statut_name FROM comment 
                INNER JOIN user ON user.id = comment.FK_user_id
                INNER JOIN comment_statut ON comment_statut.id = comment.FK_statut_comment_id
                WHERE FK_post_id = :FK_post_id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":FK_post_id", $postId, PDO::PARAM_INT);
        $request->execute();

        $postCommentList = $request->fetchAll(PDO::FETCH_ASSOC);

        return $postCommentList;

    }//end adminGetCommentList()


    /**
     * Admin delete comment list
     *
     * @param $postId post id
     * @return boolean
     */
    public function adminDeleteCommentList($postId)
    {
        $sql = 'DELETE FROM comment WHERE FK_post_id = :FK_post_id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":FK_post_id", $postId, PDO::PARAM_INT);

        return $request->execute();

    }//end adminDeleteCommentList()


    /**
     * Admin get all comment
     *
     * @return comment
     */
    public function adminGetAllComment()
    {
        $sql = 'SELECT comment.id, comment.content, comment.creation_date, comment.modification_date, comment.FK_statut_comment_id, comment.FK_user_id, comment.FK_post_id, user.last_name, user.first_name, comment_statut.comment_statut_name FROM comment 
                INNER JOIN user ON user.id = comment.FK_user_id
                INNER JOIN comment_statut ON comment_statut.id = comment.FK_statut_comment_id';

        $request = $this->connection->prepare($sql);
        $request->execute();

        $commentList = $request->fetchAll(PDO::FETCH_ASSOC);

        return $commentList;

    }//end adminGetAllComment()


    /**
     * Admin get comment by statut
     *
     * @param $commentStatut comment statut
     * @return comment
     */
    public function adminGetCommentByStatut($commentStatut)
    {
        $sql = 'SELECT comment.id, comment.content, comment.creation_date, comment.modification_date, comment.FK_statut_comment_id, comment.FK_user_id, comment.FK_post_id, user.last_name, user.first_name, comment_statut.comment_statut_name FROM comment 
                INNER JOIN user ON user.id = comment.FK_user_id
                INNER JOIN comment_statut ON comment_statut.id = comment.FK_statut_comment_id
                WHERE comment.FK_statut_comment_id = :FK_statut_comment_id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":FK_statut_comment_id", $commentStatut, PDO::PARAM_INT);
        $request->execute();

        $commentList = $request->fetchAll(PDO::FETCH_ASSOC);

        return $commentList;

    }//end adminGetCommentByStatut()


    /**
     * Admin get comment
     *
     * @param $commentId comment id
     * @return comment
     */
    public function adminGetComment($commentId)
    {
        $sql = 'SELECT comment.id, comment.content, comment.creation_date, comment.modification_date, comment.FK_statut_comment_id, comment.FK_user_id, comment.FK_post_id, user.last_name, user.first_name, comment_statut.comment_statut_name FROM comment 
                INNER JOIN user ON user.id = comment.FK_user_id
                INNER JOIN comment_statut ON comment_statut.id = comment.FK_statut_comment_id
                WHERE comment.id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":id", $commentId, PDO::PARAM_INT);
        $request->execute();

        $comment = $request->fetch(PDO::FETCH_ASSOC);

        return $comment;

    }//end adminGetComment()


    /**
     * Admin comment validate
     *
     * @param $commentId comment id
     * @return boolean
     */
    public function adminCommentValidate($commentId)
    {
        $sql = 'UPDATE comment SET FK_statut_comment_id = :FK_statut_comment_id WHERE id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":id", $commentId, PDO::PARAM_INT);
        $request->bindValue(":FK_statut_comment_id", 2, PDO::PARAM_INT);
        return $request->execute();

    }//end adminCommentValidate()


    /**
     * Admin comment invalidate
     *
     * @param $commentId comment id
     * @return boolean
     */
    public function adminCommentInvalidate($commentId)
    {
        $sql = 'UPDATE comment SET FK_statut_comment_id = :FK_statut_comment_id WHERE id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":id", $commentId, PDO::PARAM_INT);
        $request->bindValue(":FK_statut_comment_id", 1, PDO::PARAM_INT);
        return $request->execute();

    }//end adminCommentInvalidate()


    /**
     * Admin comment update
     *
     * @param $commentValue comment value
     * @return boolean
     */
    public function adminCommentUpdate($commentValue)
    {
        $sql = 'UPDATE comment SET content = :content, modification_date = NOW() WHERE id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":id", $commentValue['id'], PDO::PARAM_INT);
        $request->bindValue(":content", $commentValue['comment_content_modification'], PDO::PARAM_STR);
        return $request->execute();

    }//end adminCommentUpdate()


    /**
     * Admin delete comment
     *
     * @param $commentId comment id
     * @return boolean
     */
    public function adminDeleteComment($commentId)
    {
        $sql = 'DELETE FROM comment WHERE id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":id", $commentId, PDO::PARAM_INT);

        return $request->execute();

    }//end adminDeleteComment()

}//end class
