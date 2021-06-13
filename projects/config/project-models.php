<?php

$dbconn = new DBConnection("project");

class Project extends Entity{

    private $id;
    private $title;
    private $description;
    private $link;
    private $feature;

    public function __construct($title, $link, $feature, $description="", $id=null){
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->link = $link;
        $this->feature = $feature;
    }

    public function go(){
        $insertProject = $this->getStatement();
        $insertProject->getPDOStatement()->execute([$this->title, $this->description, $this->link, $this->feature]);

        global $dbconn;
        $lastId = $dbconn->getConn()->lastInsertId();
        $insertProject->setReturn($lastId);
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

    public function getId(){
        return $this->id;
    }

}

?>