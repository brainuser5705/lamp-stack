<?php

/* server variables */
// most likely store the username and password in some env file
$servername = "localhost";
$username = "root";
$password = "";
$options = [
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NUM,
];

abstract class Entity{

    protected $statement;
    // entity name, show name parameter for error message?

    function setStatement($statement){
        $this->statement = $statement;
    }

    function getStatement(){
        return $this->statement;
    }

    /**
     * This method is for database-altering functions.
     */
    abstract function go();

}

abstract class Statement{

    protected $dbconn; // DBConnection object
    protected $rawSQL;
    protected $pdoStatement;
    private $return;
    
    function __construct($dbconn, $sql){
        $this->dbconn = $dbconn;
        $this->rawSQL = $sql;
        $this->pdoStatement = $this->dbconn->getConn()->prepare($sql);
    }

    function setReturn($value){
        $this->return = $value;
    }

    function getReturn(){
        return $this->return;
    }

    function getPDOStatement(){
        return $this->pdoStatement;
    }

    abstract function execute($errMsg);

}

class InsertStatement extends Statement{

    private $entityArr = [];
    
    function __construct($dbconn, $sql){
        // confirmation that $sql does start eith insert
        parent::__construct($dbconn, $sql);
    }

    function linkEntity($entity){
        $entity->setStatement($this); // links the PDOStatement object
        $this->entityArr[] = $entity;
    }

    function execute($errMsg){
        try{
            if (count($this->entityArr) != 0){
                foreach($this->entityArr as $entity){
                    $entity->go();
                }
            }else{
                echo "No entities are linked.";
            }
        }catch(PDOException $e){
            echo $errMsg . ": " . $e->getMessage() . "<br>" . $e->getFile() , " - line " . $e->getLine();
        }
    }

}

class SelectStatement extends Statement{

    private $fetchMode;
    private $class;
    private $args;

    function __construct($dbconn, $sql, $fetchMode=null, $class=null, $args=null){
        parent::__construct($dbconn, $sql);
        $this->fetchMode = $fetchMode;
        $this->class = $class;
        $this->args = $args;
    }

    /**
     * $values : array of values to execute with
     */
    function execute($errMsg, $values=[]){
        $stmt = $this->getPDOStatement();
        $stmt->execute($values);

        if ($this->fetchMode == null){ // default is associative array
            $this->setReturn($stmt->fetchAll());
        }elseif ($this->fetchMode == PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE){
            $this->setReturn($stmt->fetchAll($this->fetchMode, $this->class, $this->args));
        }
    }

}

class DBConnection{

    private $conn;

    function __construct($dbname){
        global $servername, $username, $password, $options;
        $dsn = "mysql:host=$servername;dbname=$dbname";
        $this->conn = new PDO($dsn, $username, $password, $options);
    }

    function getConn(){
        return $this->conn;
    }

}

?>