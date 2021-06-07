<?php

include '../abstraction/database.php';
$dbname = "project";

$dbconn = new DBConnection("project");


class Project extends Entity{

    private $icon;
    private $title;
    private $description;
    private $link;

    public function __construct($title, $link, $icon=null, $description=null){
        $this->icon = $icon;
        $this->title = $title;
        $this->description = $description;
        $this->link = $link;
    }

    public function go(){
        $insertProject = $this->getStatement();
        $insertProject->getPDOStatement()->execute([$this->icon, $this->title, $this->description, $this->link]);

        global $dbconn;
        $lastId = $dbconn->getConn()->lastInsertId();
        echo "<code>" . $this->title . "</code> <i>(id: " . $lastId . ")</i> has successfully been inserted into database<br>"; 
    }

    public function __toString(){
        return '<span class="project-link">
                <img height="30px" width="30px" src="' . $this->icon . '" > 
                <a href="' . $this->link . '"><b>' . $this->title . '</b></a>
                <i>' . $this->description . '</i><span>';
    }

}

?>