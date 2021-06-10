<?php

$dbname = "project";
$dbconn = new DBConnection("project");

class Project extends Entity{

    private $icon;
    private $title;
    private $description;
    private $link;
    private $feature;

    public function __construct($title, $link, $icon=null, $description=null, $feature = False){
        $this->icon = $icon;
        $this->title = $title;
        $this->description = $description;
        $this->link = $link;
        $this->feature = $feature;
    }

    public function go(){
        $insertProject = $this->getStatement();
        $insertProject->getPDOStatement()->execute([$this->icon, $this->title, $this->description, $this->link, $this->feature]);

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


    public function getIcon(){
        return $this->icon;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getLink(){
        return $this->link;
    }

}

?>