<?php

/* server variables */
// most likely store the username and password in some env file
$servername = "localhost";
$username = "root";
$password = "";
$options = [
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

abstract class Entity{

    private $conn;
    private $return;

    // see if this can be a constructor
    function __construct($dbname){
        global $servername, $username, $password, $options;
        $dsn = "mysql:host=$servername;dbname=$dbname";
        $this->conn = new PDO($dsn, $username, $password, $options);
    }

    abstract function setOperation();

    function executeOperation($errMsg){
        try{
            $this->setOperation();
        }catch(PDOException $e){
            echo $errMsg . ": " . $e->getMessage();
        }
    }

    function getConn(){
        return $this->conn;
    }

    function setReturn($value){
        return $this->return = $value;
    }

    function getReturn(){
        return $this->return;
    }

}

//design 1 action
// insertOperation and selectOperation

// design 2
// db connection class
// constructor connects entity or array of entity
// has set operation method which has the prepared statement, and 
// calls an execute method in the entity which will bind parameters to the prepared statement
// if it even has an linked entity
?>