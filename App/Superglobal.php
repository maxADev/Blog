<?php

namespace App;

session_start();

// Superglobal.
class Superglobal
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
     * @return void
     */
    public function createGet($getValue)
    {
        $_GET = $getValue;
        $this->GET = $_GET;
      
    }//end createSession()


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
        if(isset($_SESSION['auth']) === false) {
            $_SESSION['auth'] = $sessionValue;
            $this->SESSION = $_SESSION;
        }
        else
        {
            $this->SESSION = $_SESSION;
        }
      
    }//end createSession()


    /**
     * Get Session
     *
     * @return array
     */
    public function getSession()
    {
        if (empty($this->SESSION) === false) {
            return $this->SESSION;
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

    }//end getSession()

    public function destroySession()
    {
        session_destroy();
    }

}//end class
