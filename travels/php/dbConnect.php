<?php


class dbConnection{

        
    var $servername = "localhost";
    var $username   = "tylercra_admin";
    var $password   = "am2pas2d@asteios2";
    var $dbname     = "tylercra_travels";
    var $conn;


    public function __construct(){
        try{
            $this->conn = new PDO('mysql:host='.$this->servername.';dbname='.$this->dbname, $this->username, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        }
        catch(PDOException $e){
            echo 'Connection failed. PDO says -->'. $e->getMessage();
        }
    }
        

}