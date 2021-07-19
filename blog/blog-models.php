<?php

class Blog extends Entity{

    private $id;
    private $status_id;
    private $title;
    private $description;
    private $folder_name;
    private $text;
    private $datetime;

    public function __construct($status_id, $title, $description, $folder_name, $text, $id=null, $datetime=null){
        $this->id = $id;
        $this->status_id = $status_id;
        $this->title = $title;
        $this->description = $description;
        $this->folder_name = $folder_name;
        $this->text = $text;
        $this->datetime = $datetime;
    }

    public function go(){
        $insertBlog = $this->getStatement();
        $insertBlog->getPDOStatement()->execute([$this->status_id, $this->title, $this->description, $this->folder_name, $this->text]);

        global $dbconn;
        $lastId = $dbconn->getConn()->lastInsertId();
        $insertBlog->setReturn($lastId);
    }

    public function getId(){
        return $this->id;
    }

    public function getStatusId(){
        return $this->status_id;
    }

    public function getTitle(){
        return $this->title;
    }

    public function getDescription(){
        return $this->description;
    }

    public function getFolderName(){
        return $this->folder_name;
    }

    public function getText(){
        return $this->text;
    }

    public function getDatetime(){
        return $this->datetime;
    }
    
}

