<?php

/* server variables */
// most likely store the username and password in some env file
$servername = "localhost";
$username = "root";
$password = "";
$db = "blog";

echo "<h1>blog</h1>";

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
        return '<div class="blog-entry">' . $this->text . $this::compilePictures() . '</div>';
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
        <p>$this->text</p>";
    }
}

/**
 * Represent picture portion of an blog entry
 */
class Picture{
    public $path;

    function __toString(){
        return '<img src="images\\' . addslashes($this->path) . '" height="200px"; ><br>';
    }
}

?>