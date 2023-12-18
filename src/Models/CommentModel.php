<?php

namespace src\Models;
use App\Model;
use \PDO;

// Comment Model.
class CommentModel extends Model
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
     * Create comment
     *
     * @param  $commentValues comment
     * @return void
     */
    public function createComment($commentValues)
    {
        $sql = 'INSERT INTO comment (content, creation_date, modification_date, FK_statut_comment_id, FK_user_id, FK_post_id) VALUES (:content, NOW(), :modification_date, 1, :FK_user_id, :FK_post_id)';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":content", $commentValues->getCommentContent(), PDO::PARAM_STR);
        $request->bindValue(":modification_date", NULL);
        $request->bindValue(":FK_user_id", $commentValues->getcommentFKUserId(), PDO::PARAM_INT);
        $request->bindValue(":FK_post_id", $commentValues->getcommentFKPostId(), PDO::PARAM_INT);

        return $request->execute();

    }//end createComment()


    /**
     * Get post comment list
     *
     * @param $postId post id
     * @return comment
     */
    public function getCommentList($postId)
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

    }//end getCommentList()


    /**
     * Comment modification
     *
     * @param $comment comment value
     * @return boolean
     */
    public function commentModification($comment)
    {
        $sql = 'UPDATE comment SET content = :content, FK_statut_comment_id = 1, modification_date = NOW() WHERE id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":content", $comment['commentContent'], PDO::PARAM_STR);
        $request->bindValue(":id", $comment['commentId'], PDO::PARAM_INT);

        return $request->execute();

    }//end commentModification()


    /**
     * Get comment
     *
     * @param $commentId comment id
     * @return comment
     */
    public function getComment($commentId)
    {
        $sql = 'SELECT comment.id, comment.content, comment.creation_date, comment.modification_date, comment.FK_statut_comment_id, comment.FK_user_id, comment.FK_post_id, user.last_name, user.first_name FROM comment
                INNER JOIN user ON user.id = comment.FK_user_id
                WHERE comment.id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":id", $commentId, PDO::PARAM_INT);
        $request->execute();

        $comment = $request->fetch(PDO::FETCH_ASSOC);

        return $comment;

    }//end getComment()


    /**
     * Delete comment
     *
     * @param $commentId comment id
     * @return boolean
     */
    public function deleteComment($commentId)
    {
        $sql = 'DELETE FROM comment WHERE id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":id", $commentId, PDO::PARAM_INT);

        return $request->execute();

    }//end deleteComment()


}//end class
