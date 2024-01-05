<?php

namespace src\AdminModels;
use App\Model;
use \PDO;

// Admin User Model.
class AdminUserModel extends Model
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
     * Admin get user list
     *
     * @return userList
     */
    public function adminGetUserList()
    {
        $sql = 'SELECT user.id, user.last_name, user.first_name, user.creation_date, user.FK_type_user_id, type_user.type_user_name FROM user 
                INNER JOIN type_user ON user.FK_type_user_id = type_user.id';

        $request = $this->connection->prepare($sql);
        $request->execute();

        $userList = $request->fetchAll(PDO::FETCH_ASSOC);

        return $userList;

    }//end adminGetUserList()


    /**
     * Admin get type user list
     *
     * @return typeUserList
     */
    public function adminGetTypeUserList()
    {
        $sql = 'SELECT * FROM type_user';

        $request = $this->connection->prepare($sql);
        $request->execute();

        $typeUserList = $request->fetchAll(PDO::FETCH_ASSOC);

        return $typeUserList;

    }//end adminGetTypeUserList()


    /**
     * Admin update user
     *
     * @param $userValue user value
     * @return boolean
     */
    public function adminUpdateUser($userValue)
    {
        $sql = 'UPDATE user SET FK_type_user_id = :FK_type_user_id WHERE id = :id';

        $request = $this->connection->prepare($sql);
        $request->bindValue(":id", $userValue['userId'], PDO::PARAM_INT);
        $request->bindValue(":FK_type_user_id", $userValue['userStatut'], PDO::PARAM_INT);
        return $request->execute();

    }//end adminUpdateUser()


}//end class