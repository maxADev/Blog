<?php

namespace src\Entity;

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
     * @var userCreationDate user creation date
     */
    public $userCreationDate;

    /**
     *
     * @var userToken user token
     */
    public $userToken;

    /**
     *
     * @var FKIdTypeUser fk id type user
     */
    public $FKIdTypeUser;


    /**
     *
     * @var error error
     */
    public $error;


    /**
     * Create a user
     *
     * @param $arrayValue value
     * @return void
     */
    public function __construct($arrayValue=[])
    {
        $this->hydrate($arrayValue);

    }//end __construct()


    /**
     * Add value
     *
     * @param $data value
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

    }//end hydrate()


    /**
     * Check no error value
     *
     * @return boolean
     */
    public function isValid()
    {
        $return = false;
        if(empty($this->error) === true)
        {
            $return = true;
        }

        return $return;

    }//end isValid()


    // Setters.


    /**
     * Add value user id
     *
     * @param $userId user id
     * @return void
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

    }//end setUserId()


    /**
     * Add value user last name
     *
     * @return void
     */
    public function setUserLastName($userLastName)
    {
        if (strlen($userLastName) <= 50) {
            $this->userLastName = $userLastName;
        } else {
            $this->error[] = ['type' => 'danger', 'message' => 'Le nom ne doit pas dépasser 50 caractères'];
        }

    }


    /**
     * Add value user first name
     *
     * @return void
     */
    public function setUserFirstName($userFirstName)
    {
        if(strlen($userFirstName) <= 50) {
            $this->userFirstName = $userFirstName;
        } else {
            $this->error[] = ['type' => 'danger', 'message' => 'Le prénom ne doit pas dépasser 50 caractères'];
        }

    }


    /**
     * Add value user login
     *
     * @return void
     */
    public function setUserLogin($userLogin)
    {
        if(strlen($userLogin) <= 50) {
            $this->userLogin = $userLogin;
        } else {
            $this->error[] = ['type' => 'danger', 'message' => 'Le prénom ne doit pas dépasser 50 caractères'];
        }

    }


    /**
     * Add value user email
     *
     * @return void
     */
    public function setUserEmail($userEmail)
    {
        if (filter_var($userEmail, FILTER_VALIDATE_EMAIL) !== false) {
            $this->userEmail = $userEmail;
        } else {
            $this->error[] = ['type' => 'danger', 'message' => 'Email invalide'];
        }

    }


    /**
     * Add value user password
     *
     * @return void
     */
    public function setUserPassword($userPassword)
    {
        if(strlen($userPassword) <= 60 && strlen($userPassword) >= 8) {
            $this->userPassword = $userPassword;
        } else {
            $this->error[] = ['type' => 'danger', 'message' => 'Le mot de passe doit faire entre 8 et 60 caractères'];
        }

    }


    /**
     * Add value user creation date
     *
     * @return void
     */
    public function setUserCreationDate($userCreationDate)
    {
        $this->userCreationDate = $userCreationDate;
    }


    /**
     * Add value user token
     *
     * @return void
     */
    public function setUserToken($userToken)
    {
        $this->userToken = $userToken;

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
     * Get value user creation date
     *
     * @return userCreationDate
     */
    public function getUserCreationDate()
    {
        return $this->userCreationDate;

    }


    /**
     * Get value user token
     *
     * @return userToken
     */
    public function getUserToken()
    {
        return $this->userToken;

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

    /**
     * Get error
     *
     * @return error
     */
    public function getError()
    {
        return $this->error;

    }


}//end class
