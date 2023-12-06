<?php

namespace Entity;

// User Entity.
class UserEntity {

    public $userId;
    public $userLastName;
    public $userFirstName;
    public $userLogin;
    public $userEmail;
    public $userPassword;
    public $FKIdTypeUser;

    /**
     * Create a user
     *
     * @return void
     */
    function __construct($arrayValue = array())
    {
        $this->hydrate($arrayValue);
    }

    /**
     * Add value
     *
     * @return void
     */
    public function hydrate($data)
    {
        foreach($data as $attribut => $value)
        {
            $method = 'setUser'.str_replace(' ', '', $attribut);
            if(is_callable(array($this, $method)))
            {
                $this->$method($value);
            }
        }
    }

    // Setters //
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    public function setUserLastName($userLastName)
    {
        $this->userLastName = $userLastName;
    }

    public function setUserFirstName($userFirstName)
    {
        $this->userFirstName = $userFirstName;
    }

    public function setUserLogin($userLogin)
    {
        $this->userLogin = $userLogin;
    }

    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }

    public function setUserPassword($userPassword)
    {
        $this->userPassword = $userPassword;
    }

    public function setUserFKIdTypeUser($FKIdTypeUser)
    {
        $this->FKIdTypeUser = $FKIdTypeUser;
    }

    // Getters //
    public function getUserId()
    {
        return $this->user_id;
    }

    public function getUserLastName()
    {
        return $this->userLastName;
    }

    public function getUserFirstName()
    {
        return $this->userFirstName;
    }

    public function getUserLogin()
    {
        return $this->userLogin;
    }

    public function getUserEmail()
    {
        return $this->userEmail;
    }

    public function getUserPassword()
    {
        return $this->userPassword;
    }

    public function getUserFKIdTypeUser()
    {
        return $this->FKIdTypeUser;
    }

}

?>