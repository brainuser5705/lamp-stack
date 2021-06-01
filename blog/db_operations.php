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

    protected $dbconn;

    function setDBConn($dbconn){
        $this->dbconn = $dbconn;
    }

    function getDBConn(){
        return $this->dbconn;
    }

    /**
     * This method is for database-altering functions.
     */
    abstract function go();

}

class DBConnection{

    private $conn;
    private $prepStat;
    private $entityArr = []; // check if need to initialize?
    private $return = [];

    function __construct($dbname){
        global $servername, $username, $password, $options;
        $dsn = "mysql:host=$servername;dbname=$dbname";
        $this->conn = new PDO($dsn, $username, $password, $options);
    }

    function addEntity($entity){
        $entity->setDBConn($this); // links entity to this database connection
        $this->entityArr[] = $entity;
    }

    function setStatement($sql){
        $this->prepStat = $this->conn->prepare($sql);
        $this->entityArr = []; // reset entity array
    }

    function getStatement(){
        return $this->prepStat;
    }

    function executeStatement($errMsg){
        try{
            if (count($entityArr) != 0){
                foreach($this->entityArr as $entity){
                    $entity->go();
                }
            }else{
                // directly execute the statement and put the results in return
                // this should be for select function
            }
        }catch(PDOException $e){
            echo $errMsg . ": " . $e->getMessage() . "<br>" . $e->getFile() , " - line " . $e->getLine();
        }
    }

    function getConn(){
        return $this->conn;
    }

    function addReturn($value){
        $this->return[] = $value;
    }

    function getReturn(){
        return $this->return;
    }

}

// DB connection: makes the database connection
// statement: select, insert
// Entity: abstract class for any database element
// database specific entity?
// binding parameters is always entity specific
?>