<?php 

    include 'config/blog-models.php';

    function getRandom(){
        global $dbconn;

        /* Get the min and max id of entries in the database */
        $getMin = new SelectStatement($dbconn, 
        "SELECT MIN(id) FROM entry;");
        $minId = $getMin->execute("Failed to get minimum id of entry table")[0][0]; // get first row of fetchAll(), get value (default: numeric)

        $getMax = new SelectStatement($dbconn, 
            "SELECT MAX(id) FROM entry;");
        $maxId = $getMax->execute("Failed to get maximum id of entry table")[0][0]; 

        /**
         * @var entry - represents the entry object returned after query
         */
        $entry;
        do{
            $random_id = rand($minId, $maxId);

            // get entry from database and fetch as Entry objects
            $getEntry = new SelectStatement($dbconn,
            "SELECT id, text, datetime FROM entry
            WHERE id = ?");
            $getEntry->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Entry', ["id","entry"]);
            $entry = $getEntry->execute("Fail to get entry from database", [$random_id])[0]; // access first index because of fetchAll()

            // get file paths from database, see below for more processing
            $getFiles = new SelectStatement($dbconn,
            "SELECT path FROM file
            WHERE entry_id = ?;");
            $files = $getFiles->execute("Fail to get files from database", [$random_id]);

        }while(!$entry);

        /**
         * Converts files query result into File object
         * Note:
         * Unfortunately, FETCH_CLASS does not work with "abnormal" constructor. Since the
         * File class does processing in the constructor, object construction has to be separated from
         * prepared statement.
         */
        $filesArr = [];
        if ($files){
            foreach($files as $files){
                // need to do this because it can't fetch into File's abnormal constructor!! :(
                $filesArr[] = new File($files[0]); // get files's path
            }
        }

        return ["entry"=>$entry, "files"=>$filesArr];

    }
?>