<?php

namespace App;
use PDO;

// Model.
abstract class Model
{

    /**
     *
     * @var _host database host
     */
    private $_host = "projetfmablog.mysql.db:3306";
    /**
     *
     * @var dbName database name
     */
    private $dbName = "projetfmablog";
    /**
     *
     * @var _username database login
     */
    private $_username = "projetfmablog";
    /**
     *
     * @var _password database password
     */
    private $_password = "PshOtondKED7VMo";

    /**
     *
     * @var connection database connection
     */
    protected $connection;


    /**
     * Create database connection
     *
     * @return void
     */
    public function getConnection()
    {
        $this->connection = null;

        try {
            $this->connection = new PDO("mysql:host=".$this->_host.";dbname=".$this->dbName,$this->_username,$this->_password);
            $this->connection->exec("set names utf8");
        } catch (PDOException $exception) {
            $dbError = "Erreur de connection : ".$exception->getMessage();
            return $dbError;
        }

    }//end getConnection()


}
