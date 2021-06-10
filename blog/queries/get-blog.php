<?php 

    include 'config/blog-models.php';

    function getBlog(){
        global $dbconn;

        $getAllEntry = new SelectStatement($dbconn,
            "SELECT * FROM entry;");
        $getAllEntry->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Entry');
        $entryArr = $getAllEntry->execute("Fail to get entries from database");

        $getFiles = new SelectStatement($dbconn,
            "SELECT path FROM file WHERE entry_id = ?");

        if($entryArr){

            $fullEntryArr = [];

            foreach($entryArr as $entry){
                $id = $entry->getId();
                $files = $getFiles->execute("Fail to get files from database", [$id]);

                $fileArr = [];
                foreach($files as $file){
                    $fileArr[] = new File($file[0]);
                }

                $fullEntryArr[] = ["entry"=>$entry, "files"=>$fileArr];
            }

            return ["content"=>$fullEntryArr]; // returning an associative array

        }else{
            return "No entries yet.";
        }

    }
    

?>