<?php

    function getAllBlogs(){
        global $dbconn;

        $getBlog = new SelectStatement($dbconn,
            "SELECT * FROM blog;");
        $getBlog->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Blog', ['statusId','title','description','pathToIndex','text']);
        $blog = $getBlog->execute("Failed to get blog from database");
        
        return $blog;
    }

    function getBlog($id){
        global $dbconn;

        $getBlog = new SelectStatement($dbconn,
            "SELECT * FROM blog
            WHERE id = ?;");
        $getBlog->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Blog', ['statusId','title','description','pathToIndex','text']);
        $blog = $getBlog->execute("Failed to get blog from database", [$id]); // return as array

        return $blog[0];
    }

?>