<?php

class Blog extends Entity{

    private $id;
    private $statusId;
    private $title;
    private $description;
    private $pathToIndex;
    private $text;
    private $datetime;

    public function __construct($statusId, $title, $description, $pathToIndex, $text, $id=null, $datetime=null){
        $this->id = $id;
        $this->statusId = $statusId;
        $this->title = $title;
        $this->description = $description;
        $this->pathToIndex = $pathToIndex;
        $this->text = $text;
        $this->datetime = $datetime;
    }

    public function go(){
        $insertBlog = $this->getStatement();
        $insertBlog->getPDOStatement()->execute([$this->statusId, $this->title, $this->description, $this->pathToIndex, $this->text]);

        global $dbconn;
        $lastId = $dbconn->getConn()->lastInsertId();
        $insertBlog->setReturn($lastId);
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

    public function getPathToIndex(){
        return $this->pathToIndex;
    }

    public function getText(){
        return $this->text;
    }

    public function getDatetime(){
        return $this->datetime;
    }

    public function getStatusId(){
        return $this->statusId;
    }
    
}

