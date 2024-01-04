<?php

namespace App;
use App\Redirect;

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
     * @var $_FILES $_FILES value
     */
    private $FILES;

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
            $this->checkSession();
            $this->user = $this->SESSION['auth'];
        };

        if ($this->flashMessageSessionExist() === true) {
            $this->SESSION['flashMessage'] = $this->SESSION['flashMessage'];
        };

        if ($this->getExist() === true) {
            $this->GET = $this->GET;
        }

        $this->redirect = new Redirect();

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
     * Check if $_FILES exist
     *
     * @return boolean
     */
    public function postFileExist()
    {
        $return = false;
        if (isset($_FILES['image']['name']) === true && empty($_FILES['image']['name']) === false) {
            $this->FILES = $_FILES;
            $return = true;
        }

        return $return;

    }//end postFileExist()


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
     * Get File Post
     *
     * @return array
     */
    public function getFilePost()
    {
        if (empty($this->FILES) === false) {
            return $this->FILES;
        }

    }//end getFilePost()


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
            if ($key !== 'image') {
                if (empty($value) === true) {
                    $errors[] = [
                                'type' => 'danger',
                                'message'   => 'Le champ est obligatoire : '.$key.''
                                ];
                }
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
            $this->createToken();
            $_SESSION['auth'] = $sessionValue;
            $_SESSION['auth']['token'] = $_SESSION['token'];
            $_SESSION['ipAddress'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
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
        if (isset($this->SESSION['auth']) != true || (int) $this->SESSION['auth']['FK_type_user_id'] != 2) {
            $this->redirect->getRedirect('/login');
        }

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


    /**
     * Create token
     *
     * @return void
     */
    public function createToken()
    {
        if (isset($_SESSION['token']) === false) {
            $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(6));
        }

    }//end createToken()


    /**
     * Check token
     *
     * @return void
     */
    public function checkToken($token)
    {
        if (empty($this->user['token']) === true || empty($token) === true || $this->user['token'] != $token) {
            $this->redirect->getRedirect('/logout');
        }

    }//end checkToken()


    /**
     * Check session
     *
     * @return void
     */
    public function checkSession()
    {
        if ($_SERVER['REMOTE_ADDR'] != $_SESSION['ipAddress'] || $_SERVER['HTTP_USER_AGENT'] != $_SESSION['userAgent']) {
            session_unset();
            session_destroy();
        }

    }//end checkSession()


}//end class
