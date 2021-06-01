<?php

include 'db_operations.php';
$dbname = "blog";

/**
 * @var dbconn global connection to database 'blog'
 */
$dbconn = new DBConnection("blog");


/**
 * Display text and files in blog page
 */
class FullEntry{
    
    private $entryObj; 
    
    /**
     * @var filesArr all File objects attaches to this entry
     */
    private $filesArr;

    function __construct($entryObj, $filesArr){
        $this->entryObj = $entryObj;
        $this->filesArr = $filesArr;
    }

    /**
     * Compiles all the File objects HTML into a single string
     */
    function compilePictures(){
        $html = "";
        if (!empty($this->filesArr)){
            foreach($this->filesArr as $file){
                $html .= $file;
            }
        }
        return $html;
    }

    function __toString(){
        return '<div class="blog-entry">' . $this->entryObj . $this->compilePictures() . '</div>';
    }

}

/**
 * Represent the Entry part of a blog entry
 */
class Entry extends Entity{

    private $id;
    private $datetime;
    private $text;

    function __construct($text, $id=null, $datetime=null){
        $this->id = $id;
        $this->text = $text;
        $this->datetime = $datetime;
    }

    function getId(){
        return $this->id;
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

        echo "Entry (id: {$lastId}) has successfully been inserted into database<br>";
    }

    function __toString(){
        return 
        "<i>$this->datetime</i><br>
        <pre>$this->text</pre>";
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
        $this->targetPath = "images/" . $path;
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

        // message to send when upload process is completed
        $msg = "<i>" . $this->pathInfo["basename"] . "</i>: ";
        // extension if any special cases
        $msgExt = ""; 
        
        // special case: file already exists, then change the filename
        if (file_exists($this->targetPath)){
            $this->targetPath = $this->changeFileName($this->targetPath, $this->pathInfo);
            $msgExt = ", due to duplicate, file name changed to " . $this->targetPath;
        }

        // uploading the file and send confirmation message
        if (move_uploaded_file($this->tmpPath, $this->targetPath)){
            $msg .= "File has successfully been uploaded" . $msgExt . "<br>";
        }else{
            $msg .= "<b>Failed to upload file</b> <i>" . basename($this->targetPath) . "</i><br>";
            echo $msg;
            return 0;
        }

        echo $msg;
        return 1;
    }

    /**
     * Utility method to update filename if duplicate file is found
     * For example, if file name is 'pic.jpg', the new filename is 'pic(1).jpg'.
     * @see upload
     */
    private function changeFileName($name, $pathInfo){
        $num = 1;
        while(file_exists($name)){
            $name = "images/" . $pathInfo["filename"] . "($num)." . $pathInfo["extension"];
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
        echo "<i>" . basename($this->targetPath) . "</i> has successfully been inserted into database (id: {$lastId}, type: {$this->type})";
    }

    /**
     * Returns HTML markup string depending on file type
     */
    public function __toString(){
        $string = "";
        switch($this->type){
            case "image":
                $string = '<img src="' . addslashes($this->targetPath) . '" height="200px"><br>';
                break;
            case "video":
                $string = '<video height="300px" controls>
                    <source src="' . addslashes($this->targetPath) . '" type="video/' . $this->pathInfo["extension"] . '">
                    </video>';
                break;
            case "other":
                $string = '<a href="' . addslashes($this->targetPath) . '" download>' . basename($this->targetPath) . '</a>';
                break;
            default:
                $string = "There is supposed to an element here, but something went wrong (T_T)";
        }
        return $string;;
    }
}

?>