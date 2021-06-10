<?php

    include 'blog/config/blog-models.php'; /* called from home.php so you have to put blog/ */

    function getLatestStatus(){
        global $dbconn;

        $getStatus = new SelectStatement($dbconn,
            "SELECT * FROM entry
             ORDER BY datetime DESC
             LIMIT 1;");
        $getStatus->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Entry');
        $status = $getStatus->execute("Fail to get entry from database")[0];

        $getFiles = new SelectStatement($dbconn,
            "SELECT path FROM file WHERE entry_id = ?");

        if ($status){
            $id = $status->getId();
            $files = $getFiles->execute("Fail to get files from database", [$id]);

            $filesArr = [];
            foreach($files as $file){
                $fileArr[] = new File($file[0]); //$file[0] is the path
            }

            return ["status"=>$status, "files"=>$filesArr];
        }

        return "No entries yet";
    }


?>