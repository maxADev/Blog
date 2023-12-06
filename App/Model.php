<?php

namespace App;
use PDO;

// Model.
abstract class Model
{

    /**
     *
     * @var host database host
     */
    private $host = "projetfmablog.mysql.db:3306";

    /**
     *
     * @var dbName database name
     */
    private $dbName = "projetfmablog";

    /**
     *
     * @var username database login
     */
    private $username = "projetfmablog";

    /**
     *
     * @var password database password
     */
    private $password = "PshOtondKED7VMo";

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
            $this->connection = new PDO("mysql:host=".$this->host.";dbname=".$this->dbName,$this->username,$this->password);
            $this->connection->exec("set names utf8");
        } catch (PDOException $exception) {
            $dbError = "Erreur de connection : ".$exception->getMessage();
            return $dbError;
        }

    }//end getConnection()


}//end class
