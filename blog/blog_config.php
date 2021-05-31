<?php

/* server variables */
// most likely store the username and password in some env file
$servername = "localhost";
$username = "root";
$password = "";
$db = "blog";

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
 * Represent the text portion of an blog entry
 */
class Text{
    public $datetime;
    public $text;

    function __toString(){
        return 
        "<i>$this->datetime</i><br>
        <pre>$this->text</pre>";
    }
}

/**
 * Represent picture portion of an blog entry
 */
class Picture{
    public $type;
    public $path;

    function __toString(){
        $str = "";
        switch($this->type){
            case "image":
                $str = '<img src="images\\' . addslashes($this->path) . '" height="200px"; ><br>';
                break;
            case "video":
                $str = "video";
                break;
            case "other":
                $str = '<a href="images\\' . addslashes($this->path) . '" download>' . $this->path . '</a>';
                break;
            default:
                $str = "";
        }
        return $str;
    }
}

?>