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

    }//end createUser()


    /**
     * Check user exist
     *
     * @param  $userValues user
     * @return void
     */
    public function userExist($userValues)
    {
        $return = false;
        $sql = 'SELECT * FROM user WHERE last_name = :last_name AND first_name = :first_name';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":last_name", $userValues->getUserLastName(), PDO::PARAM_STR);
        $request->bindValue(":first_name", $userValues->getUserFirstName(), PDO::PARAM_STR);
        $request->execute();
        $userVerification = $request->fetch(PDO::FETCH_ASSOC);

        if (empty($userVerification) === true) {
            $return = true;
        }

        return $return;

    }//end userExist()


    /**
     * Validation user
     *
     * @param  $userId    user id
     * @param  $userToken user token
     * 
     * @return void
     */
    public function validationUser($userId, $userToken)
    {
        $return = false;
        $sql = 'UPDATE user SET FK_type_user_id = :FK_type_user_id WHERE id = :id AND token = :token';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":FK_type_user_id", 1, PDO::PARAM_INT);
        $request->bindValue(":token", $userToken, PDO::PARAM_STR);
        $request->bindValue(":id", $userId, PDO::PARAM_INT);

        if ($request->execute() === true) {
            $sql = 'UPDATE user SET token = :token WHERE id = :id';

            $request = $this->connection->prepare($sql);
            $request->bindValue(":token", null, PDO::PARAM_STR);
            $request->bindValue(":id", $userId, PDO::PARAM_INT);

            if ($request->execute() === true) {
                $return = true;
            };
        }

        return $return;

    }//end validationUser()


    /**
     * Check user exist
     *
     * @param $login user login
     * @return void
     */
    public function login($login)
    {
        $sql = 'SELECT * FROM user WHERE (login = :login OR email = :login ) AND token IS NULL AND FK_type_user_id != 3';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":login", $login, PDO::PARAM_STR);
        $request->execute();
        $user = $request->fetch(PDO::FETCH_ASSOC);

        if (empty($user) === false) {
            return $user;
        }

    }//end login()


    /**
     * Create a random token
     *
     * @param  $length length of token
     * 
     * @return string
     */
    public function randomToken($length)
    {
        $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);

    }//end randomToken()


}//end class
