<?php

/**
 * This file contains classes separating the parts of a procedural PDOStatement execution.  
 */


/**
 * Server variables
 * @var options intial configuration of PDO
 */
$db = parse_url(getenv("HEROKU_POSTGRESQL_CRIMSON_URL"));
$options = [
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NUM,
];

// path to files folder storing all uploaded files
$SU_FOLDER_PATH = $_SERVER['DOCUMENT_ROOT'] . '/status-updates/files/';

/**
 * Represents attached files to a blog entry
 */
class File{

    /**
     * @var targetPath the file path of file
     */
    private $targetPath;

    /**
     * @var pathInfo the path information of file's path
     */
    private $pathInfo;

    /**
     * @var tmpPath the path of the temporary place where file is stored before uploading
     */
    private $tmpPath;

    /**
     * @var folderPath the path of folder to upload to
     */
    private $folderPath;


    /**
     * Processes path received from database and assigns to attributes
     */
    public function __construct($path, $folderPath, $tmpPath=null){

        $this->$folderPath = $folderPath;
        $this->targetPath = $folderPath . $path;
        $this->pathInfo = pathinfo($this->targetPath);
        $this->tmpPath = $tmpPath;

    }

    /**
     * Uploads file to 'files/' folder
     * @uses changeFileName
     */
    public function upload(){
        
        // special case: file already exists, then change the filename
        if (file_exists($this->targetPath)){
            $this->targetPath = $this->changeFileName($this->targetPath, $this->pathInfo);
        }

        // uploading the file and send confirmation message
        if (move_uploaded_file($this->tmpPath, $this->targetPath)){
            return 1;
        }

        return 0;
    }

    /**
     * Utility method to update filename if duplicate file is found
     * For example, if file name is 'pic.jpg', the new filename is 'pic(1).jpg'.
     * @see upload
     */
    private function changeFileName($name, $pathInfo){

        $num = 1;
        while(file_exists($name)){
            $name = $this->folderPath . $pathInfo["filename"] . "($num)." . $pathInfo["extension"];
            $num++;
        }
        
        return $name;
    }

    public function getPath(){
        return basename($this->targetPath);
    }

}

/**
 * Represents database model (or object of a table in database)
 * @see InsertStatement
 */
abstract class Entity{

    /**
     * @var statement linked InsertStatement
     */
    protected $statement;

    function setStatement($statement){
        $this->statement = $statement;
    }

    function getStatement(){
        return $this->statement;
    }

    /**
     * This is called by the linked InsertStatement's execute() method.
     * The execute() method will loop through the statement's linked entities 
     * and call this function.
     * 
     * This function takes the InsertStatement's PDOStatement (through getStatement()), 
     * binds entity parameters to it, and executes.
     */
    abstract function go();

}


/**
 * Represents a generic Statement (or query)
 * @uses DBConnection
 */
abstract class Statement{

    /**
     * @var dbconn the DBConnection object
     * This Statement uses dbconn's PDO object to make prepared statements.
     * @see pdoStatement below
     */
    protected $dbconn; 

    /**
     * @var rawSQL the raw query string that was passed in
     */
    protected $rawSQL;

    /**
     * @var pdoStatement the PDOStatement generated by this Statement 
     */
    protected $pdoStatement;

    /**
     * @var return any value taken from PDOStatement execution
     */
    protected $return;
    
    function __construct($dbconn, $sql){
        $this->dbconn = $dbconn;
        $this->rawSQL = $sql;
        // create the PDOStatement (prepared statement)
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

    /**
     * Each subclass of Statement has its own execution of the PDOStatment.
     */
    abstract function execute($errMsg="Error has occured");

}

/**
 * This type of statement is one that does not require any linking or processing.
 * Just execute it.
 */
class ExecuteStatement extends Statement{

    function __construct($dbconn, $sql){
        parent::__construct($dbconn, $sql);
    }

    function execute($errMsg="Error has occured"){
        try{
            $stmt = $this->getPDOStatement();
            $stmt->execute();

        }catch(PDOException $e){
            echo $errMsg . ": " . $e->getMessage() . "<br>";
        }
    }

}

/**
 * Execute statement but with values to bind
 */
class LinkedExecuteStatement extends Statement{

    private $values = [];

    function __construct($dbconn, $sql){
        parent::__construct($dbconn, $sql);
    }

    function addValue($value){
        $this->values[] = $value;
    }

    function execute($errMsg="Error has occured"){
        try{
            $stmt = $this->getPDOStatement();
            foreach($this->values as $value){
                $stmt->execute($value);
            }
        }catch(PDOException $e){
            echo $errMsg . ": " . $e->getMessage() . "<br>";
        }
    }
}

/**
 * Represents an INSERT statement
 * @uses Entity
 */
class InsertStatement extends Statement{

    /**
     * @var entityArr the array of linked Entity objects
     */
    private $entityArr = [];
    
    function __construct($dbconn, $sql){
        parent::__construct($dbconn, $sql);
    }

    /**
     * Links an entity to this InsertStatement
     * @param entity the entity to link with
     */
    function linkEntity($entity){
        $entity->setStatement($this);
        $this->entityArr[] = $entity;
    }

    /**
     * For each linked Entity, call its go() function.
     * @see Entity's go()
     * @throws PDOException if PDOStatement binding or execution fails
     */
    function execute($errMsg="Error has occured"){
        try{
            if (count($this->entityArr) != 0){
                foreach($this->entityArr as $entity){
                    $entity->go();
                }
            }else{
                echo "No entities are linked.";
            }
        }catch(PDOException $e){
            echo $errMsg . ": " . $e->getMessage() . "<br>";
        }
    }

}

/**
 * Represent an SELECT statement
 */
class SelectStatement extends Statement{

    /**
     * @var fetchMode the structure to return queries in
     */
    private $fetchMode = null;

    /**
     * @var class the type of class for FETCH_CLASS
     */
    private $class;

    /**
     * @var args the list of parameters to pass into class
     */
    private $args;

    function __construct($dbconn, $sql){
        parent::__construct($dbconn, $sql);
    }

    function setFetchMode($fetchMode, $class=null, $args=null){
        $this->fetchMode = $fetchMode;
        $this->class = $class;
        $this->args = $args;
    }

    /**
     * Executes PDOStatement with optional values to bind with
     * 
     * @param errMsg error message if failure
     * @param values arguments to bind to PDO statement
     * 
     * @throws PDOException if fetching fails, return error message
     */
    function execute($errMsg="Error has occured", $values=[]){
        try{
            $stmt = $this->getPDOStatement();
            $stmt->execute($values);

            // for some reason, it gotta be like this
            if ($this->fetchMode == null){ // default is returning numeric array
                return $stmt->fetchAll();
            }elseif ($this->fetchMode == PDO::FETCH_ASSOC){
                return $stmt->fetchAll($this->fetchMode);
            }elseif ($this->fetchMode == PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE){
                return $stmt->fetchAll($this->fetchMode, $this->class, $this->args);
            } 
        }catch(PDOException $e){
            echo $errMsg . ": " . $e->getMessage() . "<br>";
        }
    }

}


/**
 * Represent a database connection
 * 
 * @global server_variables the server variables
 */
class DBConnection{

    /**
     * @var conn the PDO object created
     */
    private $conn;

    /**
     * Construct the PDO object
     * @param dbname the database to connect to
     */
    function __construct($dbname){
        global $db, $options;
        $this->conn = new PDO("pgsql:" . sprintf(
            "host=%s;port=%s;user=%s;password=%s;dbname=%s",
            $db["host"],
            $db["port"],
            $db["user"],
            $db["pass"],
            ltrim($db["path"], "/")
        ));
    }

    function getConn(){
        return $this->conn;
    }

}

/**
 * @var dbconn global connection to database 'website'
 */
$dbconn = new DBConnection("website");

?>