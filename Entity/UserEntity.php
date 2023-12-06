<?php

namespace Entity;

// User Entity.
class UserEntity
{
    /**
     *
     * @var userId user id
     */
    public $userId;

    /**
     *
     * @var userLastName user last name
     */
    public $userLastName;

    /**
     *
     * @var userFirstName user first name
     */
    public $userFirstName;

    /**
     *
     * @var userLogin user login
     */
    public $userLogin;

    /**
     *
     * @var userEmail user email
     */
    public $userEmail;

    /**
     *
     * @var userPassword user password
     */
    public $userPassword;

    /**
     *
     * @var FKIdTypeUser fk id type user
     */
    public $FKIdTypeUser;


    /**
     * Create a user
     *
     * @param $arrayValue value
     * @return void
     */
    function __construct($arrayValue=[])
    {
        $this->hydrate($arrayValue);

    }//end __construct()


    /**
     * Add value
     *
     * @return void
     */
    public function hydrate($data)
    {
        foreach ($data as $attribut => $value) {
            $method = 'setUser'.str_replace(' ', '', $attribut);
            if (is_callable([$this, $method]) === true) {
                $this->$method($value);
            }
        }

    }


    // Setters.
    /**
     * Add value user id
     *
     * @return void
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }


    /**
     * Add value user last name
     *
     * @return void
     */
    public function setUserLastName($userLastName)
    {
        $this->userLastName = $userLastName;
    }


    /**
     * Add value user first name
     *
     * @return void
     */
    public function setUserFirstName($userFirstName)
    {
        $this->userFirstName = $userFirstName;
    }


    /**
     * Add value user login
     *
     * @return void
     */
    public function setUserLogin($userLogin)
    {
        $this->userLogin = $userLogin;
    }


    /**
     * Add value user email
     *
     * @return void
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
    }


    /**
     * Add value user password
     *
     * @return void
     */
    public function setUserPassword($userPassword)
    {
        $this->userPassword = $userPassword;
    }


    /**
     * Add value user fk id type user
     *
     * @return void
     */
    public function setUserFKIdTypeUser($FKIdTypeUser)
    {
        $this->FKIdTypeUser = $FKIdTypeUser;
    }


    // Getters.
    /**
     * Get value user id
     *
     * @return user_id
     */
    public function getUserId()
    {
        return $this->user_id;
    }


    /**
     * Get value user last name
     *
     * @return userLastName
     */
    public function getUserLastName()
    {
        return $this->userLastName;
    }


    /**
     * Get value user first name
     *
     * @return userFirstName
     */
    public function getUserFirstName()
    {
        return $this->userFirstName;
    }


    /**
     * Get value user login
     *
     * @return userLogin
     */
    public function getUserLogin()
    {
        return $this->userLogin;
    }


    /**
     * Get value user email
     *
     * @return userEmail
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }


    /**
     * Get value user password
     *
     * @return userPassword
     */
    public function getUserPassword()
    {
        return $this->userPassword;
    }


    /**
     * Get value user fk id type user
     *
     * @return FKIdTypeUser
     */
    public function getUserFKIdTypeUser()
    {
        return $this->FKIdTypeUser;
    }


}//end class
