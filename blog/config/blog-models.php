<?php

$dbname = "blog";

/**
 * @var dbconn global connection to database 'blog'
 */
$dbconn = new DBConnection("blog");

/**
 * Represent the Entry part of a blog entry
 */
class Entry extends Entity{

    private $id;
    private $datetime;
    private $text;

    // all parameters are null because when db fetch into entry, it does not need an required parameters
    function __construct($text=null, $id=null, $datetime=null){
        $this->id = $id;
        $this->text = $text;
        $this->datetime = $datetime;
    }

    function getId(){
        return $this->id;
    }

    /* testing */
    public function getDatetime(){
        return $this->datetime;
    }

    public function getText(){
        return $this->text;
    }


    /**
     * The go() function binds parameter to the linked prepared statement and executes it.
     * 
     * Note: The prepared statement is acessed through these commands
     *  1. $s = $this->getStatement() - gets the Statement object this Entity is linked to
     *  2. $s->getPDOStatement()->execute(...) - gets the actual PDOStatement from the Statement object
     * 
     * @see blog_form.php - line 44 to 51
     * 
     * @global dbconn database connection, used to get lastInsertId
     */
    function go(){
        $insertEntry = $this->getStatement();
        $insertEntry->getPDOStatement()->execute([$this->text]); // binds the text attribute

        global $dbconn;
        $lastId = $dbconn->getConn()->lastInsertId();
        $insertEntry->setReturn($lastId);
    }

}


/**
 * Represents attached files to a blog entry
 */
class File extends Entity{

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
     * @var type the type of the file
     * Enum values: 'image', 'video', 'other'
     */
    private $type = "other";

    /**
     * @var SUBMISSION_ID the foreign id (Entry) of this File object
     */
    private $SUBMISSION_ID;


    /**
     * Processes path received from database and assigns to attributes
     */
    public function __construct($path, $tmpPath=null, $SUBMISSION_ID=null){
        $this->targetPath = $_SERVER['DOCUMENT_ROOT'] . "/blog/images/" . $path;
        $this->pathInfo = pathinfo($this->targetPath);
        $this->tmpPath = $tmpPath;
    
        // determine type of file based on extension
        $fileExt = $this->pathInfo["extension"];
        if (in_array($fileExt, array("jpg", "jpeg", "png", "gif"))){
            $this->type = "image";
        }elseif (in_array($fileExt, array("mp4", "mov"))){
            $this->type = "video";
        }

        $this->SUBMISSION_ID = $SUBMISSION_ID;
    }

    /**
     * Uploads file to 'images/' folder
     * @uses changeFileName
     */
    public function upload(){
        
        // special case: file already exists, then change the filename
        if (file_exists($this->targetPath)){
            $this->targetPath = $this->changeFileName($this->targetPath, $this->pathInfo);
            $msgExt = ", due to duplicate, file name changed to " . $this->targetPath;
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
            $name = $_SERVER['DOCUMENT_ROOT'] . "/blog/images/" . $pathInfo["filename"] . "($num)." . $pathInfo["extension"];
            $num++;
        }
        
        return $name;
    }

    /**
     * The go() function binds parameter to the linked prepared statement and executes it.
     *
     * @see blog_form.php - line 71 to 81
     * 
     * @global dbconn database connection, used to get lastInsertId
     */
    public function go(){
        $insertMedia = $this->getStatement();
        $insertMedia->getPDOStatement()->execute([$this->SUBMISSION_ID, $this->type, basename($this->targetPath)]);

        global $dbconn;
        $lastId = $dbconn->getConn()->lastInsertId();
    }

    public function getPath(){
        return basename($this->targetPath);
    }
}

?>