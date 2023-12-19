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


}//end class
