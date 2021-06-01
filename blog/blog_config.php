<?php

include 'db_operations.php';
$dbname = "blog";

/**
 * Full entry including text and picture(s)
 */
class FullEntry{
    
    private $text; // Text object
    private $pictures; // Array of pictures

    function __construct($text, $pictures){
        $this->text = $text;
        $this->pictures = $pictures;
    }

    function compilePictures(){
        $html = "";
        if (!empty($this->pictures)){
            foreach($this->pictures as $img){
                $html .= $img;
            }
        }
        return $html;
    }

    function __toString(){
        return '<div class="blog-entry">' . $this->text . $this->compilePictures() . '</div>';
    }

}

/**
 * Represent picture portion of an blog entry
 */
class Picture{
    public $type;
    public $path;

    function __toString(){
        $string = "";
        switch($this->type){
            case "image":
                $string = '<img src="images\\' . addslashes($this->path) . '" height="200px"; ><br>';
                break;
            case "video":
                $string = "video";
                break;
            case "other":
                $string = '<a href="images\\' . addslashes($this->path) . '" download>' . $this->path . '</a>';
                break;
            default:
                $string = "";
        }
        return $string;
    }
}


/**
 * Represent the text portion of an blog entry
 */
class Text extends Entity{

    private $datetime;
    private $text;

    function __construct($text){
        $this->text = $text;
    }

    function go(){
        $insertText = $this->dbconn->getStatement();
        $insertText->execute([$this->text]);

        $lastId = $this->dbconn->getConn()->lastInsertId();
        $this->dbconn->addReturn($lastId);

        echo "Text (id: {$this->dbconn->getReturn()[0]}) has successfully been inserted into database<br>";
    }

    function __toString(){
        return 
        "<i>$this->datetime</i><br>
        <pre>$this->text</pre>";
    }
}

class File extends Entity{

    private $type = "other";
    private $targetPath;
    private $pathInfo;
    private $tmpPath;
    private $SUBMISSION_ID;

    /**
     * Sets the return string for displaying in blog entry
     */
    public function __construct($path, $tmpPath, $SUBMISSION_ID){
        $this->targetPath = "images/" . $path;
        $this->pathInfo = pathinfo($this->targetPath);
        $this->tmpPath = $tmpPath;
        
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
     */
    public function upload(){
        $msg = "<i>" . $this->pathInfo["basename"] . "</i>: ";
        $msgExt = "";
        
        if (file_exists($this->targetPath)){
            $this->targetPath = $this->changeFileName($this->targetPath, $this->pathInfo);
            $msgExt = ", due to duplicate, file name changed to " . $this->targetPath;
        }

        // uploading the file and send confirmation message
        if (move_uploaded_file($this->tmpPath, $this->targetPath)){
            $msg .= "File has successfully been uploaded" . $msgExt . "<br>";
        }else{
            $msg .= "<b>Failed to upload file</b> <br>";
            echo $msg;
            return 0;
        }

        echo $msg;
        return 1;
    }

    /**
     * Utility method to update file name if duplicate file is found
     * Called by upload()
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
     * Binds parameters to prepared statement and executes it
     * Inherited from Entity abstract class in db_operations.php
     */
    public function go(){
        $insertMedia = $this->dbconn->getStatement();
        $insertMedia->execute([$this->SUBMISSION_ID, $this->type, basename($this->targetPath)]);

        $lastId = $this->dbconn->getConn()->lastInsertId();
        echo "File (id: {$lastId}, type: {$this->type}) has successfully been inserted into database<br>";
    }

    public function __toString(){
        return "this is a file entity";
    }
}

?>