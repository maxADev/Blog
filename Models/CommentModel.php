<?php

namespace Models;
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


}//end class
