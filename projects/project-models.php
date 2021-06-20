<?php

class Project extends Entity{

    private $id;
    private $title;
    private $description;
    private $link;
    private $type;

    public function __construct($title, $description, $link, $type, $id=null){
        $this->id = $id; // null value for auto-increment
        $this->title = $title;
        $this->description = $description;
        $this->link = $link;
        $this->type = $type;
    }

    public function go(){
        $insertProject = $this->getStatement();
        $insertProject->getPDOStatement()->execute([$this->title, $this->description, $this->link, $this->type]);

        global $dbconn;
        $lastId = $dbconn->getConn()->lastInsertId();
        $insertProject->setReturn($lastId);
    }

    public function getId(){
        return $this->id;
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

    public function getType(){
        return $this->type;
    }

}

?>