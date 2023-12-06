<?php

namespace App;
use PDO;

// Model.
abstract class Model
{

    // Database login.
    private $_host = "projetfmablog.mysql.db:3306";
    private $_db_name = "projetfmablog";
    private $_username = "projetfmablog";
    private $_password = "PshOtondKED7VMo";

    protected $_connexion;

    public $table;
    public $id;

    /**
     * Create database connection
     *
     * @return void
     */
    public function getConnection()
    {
        $this->_connexion = null;

        try
        {
            $this->_connexion = new PDO("mysql:host=" . $this->_host . ";dbname=" . $this->_db_name, $this->_username, $this->_password);
            $this->_connexion->exec("set names utf8");
        }
        catch(PDOException $exception)
        {
            echo "Erreur de connexion : " . $exception->getMessage();
        }

    }//end getConnection()


}
