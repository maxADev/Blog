<?php

namespace App;

session_start();

// SuperGlobal.
class SuperGlobal
{

    /**
     *
     * @var $_POST $_POST value
     */
    private $POST;

    /**
     *
     * @var $_GET $_GET value
     */
    private $GET;

    /**
     *
     * @var $_SESSION $_SESSION value
     */
    private $SESSION;

    /**
     *
     * @var $user $user value
     */
    public $user;


    /**
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        if ($this->authSessionExist() === true) {
            $this->user = $this->SESSION['auth'];
        };

        if ($this->flashMessageSessionExist() === true) {
            $this->SESSION['flashMessage'] = $this->SESSION['flashMessage'];
        };

        if ($this->getExist() === true) {
            $this->GET = $this->GET;
        }

    }//end __construct()


    /**
     * Check if $_POST exist
     *
     * @return boolean
     */
    public function postExist()
    {
        $return = false;
        if (isset($_POST) === true && empty($_POST) === false) {
            $this->POST = $_POST;
            $return = true;
        }

        return $return;

    }//end postExist()


    /**
     * Get Post
     *
     * @return array
     */
    public function getPost()
    {
        if (empty($this->POST) === false) {
            return $this->POST;
        }

    }//end getPost()


    /**
     * Get Post Data
     *
     * @param $key key value
     * @return string
     */
    public function getPostData($key)
    {
        if (isset($this->POST[$key]) === true) {
            return $this->POST[$key];
        }

    }//end getPostData()


    /**
     * Check Post Data
     *
     * @param $postValue post value
     * @return string
     */
    public function checkPostData($postValue)
    {
        $errors = [];

        foreach ($postValue as $key => $value) {
            if (empty($value) === true) {
                $errors[] = [
                            'type' => 'danger',
                            'message'   => 'Le champ est obligatoire : '.$key.''
                            ];
            }
        }

        return $errors;

    }//end checkPostData()


    /**
     * Create a $_GET
     *
     * @param $getValue $_GET value
     * @return void
     */
    public function createGet($getValue)
    {
        $_GET = $getValue;
        $this->GET = $_GET;

    }//end createGet()


    /**
     * Check if $_GET exist
     *
     * @return boolean
     */
    public function getExist()
    {
        $return = false;
        if (isset($_GET) === true && empty($_GET) === false) {
            $this->GET = $_GET;
            $return = true;
        }

        return $return;

    }//end getExist()


    /**
     * Check if $_GET data exist
     *
     * @param $key key
     * @return boolean
     */
    public function getDataExist($key)
    {
        $return = false;
        if (isset($this->GET[$key]) === true && empty($this->GET[$key]) === false) {
            $return = true;
        }

        return $return;

    }//end getDataExist()


    /**
     * Get Get
     *
     * @return array
     */
    public function getGet()
    {
        if (empty($this->GET) === false) {
            return $this->GET;
        }

    }//end getGet()


    /**
     * Get Get Data
     *
     * @param $key key value
     * @return string
     */
    public function getGetData($key)
    {
        if (isset($this->GET[$key]) === true) {
            return $this->GET[$key];
        }

    }//end getGetData()

    /**
     * Create a $_SESSION
     *
     * @return mixed
     */
    public function createSession($sessionValue)
    {
        if (isset($_SESSION['auth']) === false) {
            $_SESSION['auth'] = $sessionValue;
            $this->SESSION['auth'] = $_SESSION['auth'];
        } else {
            $this->SESSION['auth'] = $_SESSION['auth'];
        }

    }//end createSession()


    /**
     * Get User
     *
     * @return array
     */
    public function getCurrentUser()
    {
        if (empty($this->user) === false) {
            return $this->user;
        }

    }//end getCurrentUser()


    /**
     * Check auth session exist
     *
     * @return array
     */
    public function authSessionExist()
    {
        $return = false;
        if (isset($_SESSION['auth']) === true) {
            $this->SESSION['auth'] = $_SESSION['auth'];
            $return = true;
        }

        return $return;

    }//end authSessionExist()


    /**
     * Check flash message session exist
     *
     * @return array
     */
    public function flashMessageSessionExist()
    {
        $return = false;
        if (isset($_SESSION['flashMessage']) === true) {
            $this->SESSION['flashMessage'] = $_SESSION['flashMessage'];
            $return = true;
        }

        return $return;

    }//end flashMessageSessionExist()


    /**
     * Test admin
     *
     * @return boolean
     */
    public function userIsAdmin()
    {
        $return = false;
        if (isset($this->SESSION['auth']) === true && (int) $this->SESSION['auth']['FK_type_user_id'] === 2) {
            $return = true;
        }

        return $return;

    }//end userIsAdmin()


    /**
     * Create flash message
     *
     * @return mixed
     */
    public function createFlashMessage($flashMessageValue)
    {
        if (empty($flashMessageValue) === false) {
            if (isset($flashMessageValue[0]) === false) {
                $_SESSION['flashMessage'][] = $flashMessageValue;
            } else {
                $_SESSION['flashMessage'] = $flashMessageValue;
            }

            $this->SESSION['flashMessage'] = $_SESSION['flashMessage'];
        }

    }//end createFlashMessage()


    /**
     * Clear flash message
     *
     * @return mixed
     */
    public function clearFlashMessage()
    {
        if (isset($_SESSION['flashMessage']) === true) {
            unset($_SESSION['flashMessage']);
            unset($this->SESSION['flashMessage']);
        }

    }//end clearFlashMessage()


    /**
     * Get flash message
     *
     * @return mixed
     */
    public function getFlashMessage()
    {
        $flashMessage = null;

        if (isset($this->SESSION['flashMessage']) === true) {
            $flashMessage = $this->SESSION['flashMessage'];
            $this->clearFlashMessage();
        }

        return $flashMessage;

    }//end getFlashMessage()


    /**
     * Delete session
     *
     * @return void
     */
    public function deleteSession($value)
    {
        unset($_SESSION[$value]);

    }//end deleteSession()


}//end class
