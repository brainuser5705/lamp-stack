<?php

    include 'blog/config/blog-models.php'; /* called from home.php so you have to put blog/ */

    function getLatestStatus(){
        global $dbconn;

        $getStatus = new SelectStatement($dbconn,
            "SELECT * FROM entry
             ORDER BY datetime DESC
             LIMIT 1;");
        $getStatus->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Entry');
        $status = $getStatus->execute("Fail to get entry from database");

        $getFiles = new SelectStatement($dbconn,
            "SELECT path FROM file WHERE entry_id = ?");

        if ($status){ // if array exists
            $id = $status[0]->getId();
            $files = $getFiles->execute("Fail to get files from database", [$id]);

            $filesArr = [];
            foreach($files as $file){
                $filesArr[] = new File($file[0]); //$file[0] is the path
            }

            return ["status"=>$status[0], "files"=>$filesArr];
        }

        return ["status"=>new Entry("No entries yet")];
    }


?>