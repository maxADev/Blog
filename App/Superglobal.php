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

    public function __construct() {
        if ($this->authSessionExist() === true) {
            $this->user = $this->SESSION['auth'];
        };
    }


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
            $this->SESSION = $_SESSION;
        } else {
            $this->SESSION = $_SESSION;
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

    }//end getSession()


    /**
     * Get Session
     *
     * @return array
     */
    public function authSessionExist()
    {
        $return = false;
        if (isset($_SESSION['auth']) === true) {
            $this->SESSION = $_SESSION;
            $return = true;
        }

        return $return;

    }//end authSessionExist()


}//end class
