<?php

namespace src\Models;
use App\Model;
use \PDO;

// User Model.
class UserModel extends Model
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
     * Create user
     *
     * @param  $userValues user
     * @return void
     */
    public function createUser($userValues)
    {
        $return = false;
        if ($this->userExist($userValues) === true) {
            $userToken = self::randomToken(15);
            $return = true;
            $sql = 'INSERT INTO user (last_name, first_name, login, email, password, token, FK_type_user_id) VALUES (:last_name, :first_name, :login, :email, :password, :token, :FKTypeUserId)';

            $request = $this->connection->prepare($sql);
            $request->bindValue(":last_name", $userValues->getUserLastName(), PDO::PARAM_STR);
            $request->bindValue(":first_name", $userValues->getUserFirstName(), PDO::PARAM_STR);
            $request->bindValue(":login", $userValues->getUserLogin(), PDO::PARAM_STR);
            $request->bindValue(":email", $userValues->getUserEmail(), PDO::PARAM_STR);
            $request->bindValue(":password", password_hash($userValues->getUserPassword(), PASSWORD_BCRYPT), PDO::PARAM_STR);
            $request->bindValue(":token", $userToken, PDO::PARAM_STR);
            $request->bindValue(":FKTypeUserId", $userValues->getUserFKIdTypeUser(), PDO::PARAM_INT);

            if ($request->execute() === true) {
                $usersId = $this->connection->lastInsertId();
                $return = [
                           'userId'    => $usersId,
                           'userEmail' => $userValues->getUserEmail(),
                           'userToken' => $userToken,
                          ];
            }
        }//end if

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
        $sql = 'SELECT * FROM user
                WHERE (last_name = :last_name AND first_name = :first_name)
                OR email = :email';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":last_name", $userValues->getUserLastName(), PDO::PARAM_STR);
        $request->bindValue(":first_name", $userValues->getUserFirstName(), PDO::PARAM_STR);
        $request->bindValue(":email", $userValues->getUserEmail(), PDO::PARAM_STR);
        $request->execute();
        $userVerification = $request->fetch(PDO::FETCH_ASSOC);

        if (empty($userVerification) === true) {
            $return = true;
        }

        return $return;

    }//end userExist()


    /**
     * Check user exist
     *
     * @param  $userLogin user login
     * @return void
     */
    public function checkUserExist($userLogin)
    {
        $return = false;
        $userToken = self::randomToken(15);
        $sql = 'SELECT user.id, user.email FROM user
                WHERE login = :login OR email = :login';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":login", $userLogin, PDO::PARAM_STR);
        $request->execute();
        $userVerification = $request->fetch(PDO::FETCH_ASSOC);

        if (empty($userVerification) === false) {
            $sqlUpdate = 'UPDATE user SET token = :token WHERE id = :id';

            $requestUpdate = $this->connection->prepare($sqlUpdate);
            $requestUpdate->bindValue(":token", $userToken, PDO::PARAM_STR);
            $requestUpdate->bindValue(":id", $userVerification['id'], PDO::PARAM_INT);
            if ($requestUpdate->execute() === true) {
                $userVerification['token'] = $userToken;
                $return = $userVerification;
            }
        }

        return $return;

    }//end checkUserExist()


    /**
     * Check user token
     *
     * @param  $userToken user token
     * @return void
     */
    public function checkUserToken($userToken)
    {
        $sql = 'SELECT user.id FROM user
                WHERE token = :token';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":token", $userToken, PDO::PARAM_STR);
        $request->execute();
        return $request->fetch(PDO::FETCH_ASSOC);

    }//end checkUserToken()


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
            $sql = 'UPDATE user SET creation_date = NOW(), token = :token WHERE id = :id';

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
     * Change password
     *
     * @param  $userValues array with user id and user password
     * @return void
     */
    public function changePassword($userValues)
    {
        $return = false;
        $user = self::checkUserToken($userValues['token']);
        if (empty($user) === false && $user['id'] === $userValues['id']) {
            $sql = 'UPDATE user SET password = :password, token = :token WHERE id = :id';
            $request = $this->connection->prepare($sql);
            $request->bindValue(":password", password_hash($userValues['password'], PASSWORD_BCRYPT), PDO::PARAM_STR);
            $request->bindValue(":token", null, PDO::PARAM_STR);
            $request->bindValue(":id", $userValues['id'], PDO::PARAM_INT);
            if ($request->execute() === true) {
                $return = true;
            }
        }

        return $return;

    }//end changePassword()


    /**
     * Login
     *
     * @param $login user login
     * @return user
     */
    public function login($login)
    {
        $sql = 'SELECT * FROM user WHERE (login = :login OR email = :login ) AND FK_type_user_id != 3';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":login", $login, PDO::PARAM_STR);
        $request->execute();
        $user = $request->fetch(PDO::FETCH_ASSOC);

        if (empty($user) === false) {
            return $user;
        }

    }//end login()


    /**
     * Login Admin
     *
     * @param $login user login
     * @return user
     */
    public function loginAdmin($login)
    {
        $sql = 'SELECT * FROM user WHERE (login = :login OR email = :login ) AND token IS NULL AND FK_type_user_id = 2';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":login", $login, PDO::PARAM_STR);
        $request->execute();
        $user = $request->fetch(PDO::FETCH_ASSOC);

        if (empty($user) === false) {
            return $user;
        }

    }//end loginAdmin()


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
