<?php

namespace App;

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
     * Create a superglobal
     *
     * @return void
     */
    public function __construct()
    {
        $this->createSuperglobal();

    }//end __construct()


    /**
     * Create a superglobal
     *
     * @return mixed
     */
    private function createSuperglobal()
    {
        if (isset($_POST) === true) {
            $this->POST = $_POST;
        }

        if (isset($_GET) === true) {
            $this->GET = $_GET;
        }

    }//end createSuperglobal()


    /**
     * Check if $_POST exist
     *
     * @return boolean
     */
    public function postExist()
    {
        if (empty($this->POST) === false) {
            return true;
        }

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
        if (isset($this->POST[$key]) === true)
        {
            return $this->POST[$key];
        }

    }//end postData()
    
    /**
     * Check if $_GET exist
     *
     * @return boolean
     */
    public function getExist()
    {
        if (empty($this->GET) === false) {
            return true;
        }

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
        if(isset($this->GET[$key]) === true) {
            return $this->GET[$key];
        }

    }//end getGetData()


}//end class
