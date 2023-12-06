<?php

namespace Models;
Use App\Model;
Use \PDO;

// Utilisateur Model.
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
     * @param $userValues user
     * @return void
     */
    public function createUser($userValues)
    {
        if($this->userExist($userValues)) {
            $sql = 'INSERT INTO user (last_name, first_name, login, email, password, FK_type_user_id) VALUES (:last_name, :first_name, :login, :email, :password, :FKTypeUserId)';

            $request = $this->_connexion->prepare($sql);
            $request->bindValue(":last_name", $userValues->getUserLastName(), PDO::PARAM_STR);
            $request->bindValue(":first_name", $userValues->getUserFirstName(), PDO::PARAM_STR);
            $request->bindValue(":login", $userValues->getUserLogin(), PDO::PARAM_STR);
            $request->bindValue(":email", $userValues->getUserEmail(), PDO::PARAM_STR);
            $request->bindValue(":password", $userValues->getUserPassword(), PDO::PARAM_STR);
            $request->bindValue(":FKTypeUserId", $userValues->getUserFKIdTypeUser(), PDO::PARAM_INT);
        
            if($request->execute()) {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }//end createUser()


    /**
     * Check user exist
     *
     * @param $userValues user
     * @return void
     */
    public function userExist($userValues)
    {
        $sql = 'SELECT * FROM user WHERE last_name = :last_name AND first_name = :first_name';

        $request = $this->_connexion->prepare($sql);
        $request->bindValue(":last_name", $userValues->getUserLastName(), PDO::PARAM_STR);
        $request->bindValue(":first_name", $userValues->getUserFirstName(), PDO::PARAM_STR);
        $request->execute();
        $userVerification = $request->fetch(PDO::FETCH_ASSOC);

        if(!empty($userVerification)) {
            return false;
        }
        else
        {
            return true;
        }
    }//end userExist()


}
