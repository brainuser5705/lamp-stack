<?php

/**
 * Represent the text of status update
 */
class Status extends Entity{

    private $id;
    private $datetime;
    private $text;

    // all parameters are null because when db fetch into entry, it does not need an required parameters
    function __construct($text=null, $id=null, $datetime=null){
        $this->id = $id;
        $this->text = $text;
        $this->datetime = $datetime;
    }

    function getId(){
        return $this->id;
    }

    public function getDatetime(){
        return $this->datetime;
    }

    public function getText(){
        return $this->text;
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
    }

}

?>