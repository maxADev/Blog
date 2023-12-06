<?php

namespace App;

// Superglobal.
class Superglobal
{


    /**
     *
     * @var $_POST $_POST value
     */
    private $_POST;

    /**
     *
     * @var $_GET $_GET value
     */
    private $_GET;


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
        $this->_POST = (isset($_POST)) ? $_POST : null;
        $this->_GET = (isset($_GET)) ? $_GET : null;
    }//end createSuperglobal()


    /**
     * Check if $_POST exist
     *
     * @return boolean
     */
    public function postExist()
    {
        return !empty($this->_POST);
    }//end postExist()


    /**
     * Get Post
     *
     * @return array
     */
    public function getPost()
    {
        return !empty($this->_POST) ? $this->_POST : null;

    }//end getPost()


    /**
     * Get Post Data
     *
     * @param $key key value
     * @return string
     */
    public function getPostData($key)
    {
        return isset($this->_POST[$key]) ? $this->_POST[$key] : null;

    }//end postData()
    
    /**
     * Check if $_GET exist
     *
     * @return boolean
     */
    public function getExist()
    {
        return !empty($this->_GET);
    }//end getExist()


    /**
     * Get Get
     *
     * @return array
     */
    public function getGet()
    {
        return !empty($this->_GET) ? $this->_GET : null;

    }//end getGet()


    /**
     * Get Get Data
     *
     * @param $key key value
     * @return string
     */
    public function getGetData($key)
    {
        return isset($this->_GET[$key]) ? $this->_GET[$key] : null;

    }//end getGetData()

}//end class
