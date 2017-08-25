<?php






class pdo_connection{
    
    
    
    var $host     = "burrito.asmallorange.com";
    var $username = "tylercra_tscrady";
    var $password = "asteios2";
    var $database = "tylercra_tylercrady.com";
    var $conn     = "";
    function __construct() {
         try{
            $this->conn = new PDO('mysql:host='.$this->host.';dbname='.$this->database, $this->username, $this->password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
        }
        catch(PDOException $e){
            echo 'Connection failed. PDO says -->'. $e->getMessage();
        }
        
        $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    }
    
    
    
    
    
}

