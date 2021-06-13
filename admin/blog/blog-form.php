<?php

    include $_SERVER['DOCUMENT_ROOT'] . '/blog/config/blog-models.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-entry"])){

        // retrieve entry from form 
        // validate text
        // create Entity object
        $entry = $_POST["entry"];
        $entry = htmlspecialchars($entry);
        $entry = new Entry($entry);

        // insert Entry into database
        $insertEntry = new InsertStatement($dbconn, 
            "INSERT INTO entry(text)
            VALUES (?);");
        $insertEntry->linkEntity($entry);
        $insertEntry->execute("Failed to insert entry into database");
        
        $SUBMISSION_ID = $insertEntry->getReturn(); // get the id of Entry

        // adding success message to array
        $messages[] = "Entry (id: {$SUBMISSION_ID}) has successfully been inserted into database";

        // if entry has attached files
        if(isset($_FILES['files']) && $_FILES['files']["name"][0] != ""){
            $files = $_FILES['files'];
            $filesCount = count($files["name"]);

            // prepared statement to insert File into database 
            $insertFile = new InsertStatement($dbconn,
                "INSERT INTO file (entry_id, type, path)
                VALUES(?, ?, ?);");

            for ($i = 0; $i < $filesCount; $i++){

                // get file propertries and get Entity object
                $fileName = basename($files["name"][$i]);
                $fileTmpPath = $files["tmp_name"][$i];
                $file = new File($fileName, $fileTmpPath, $SUBMISSION_ID);

                // Upload files
                // If file upload is successful, then link entities for database insertion.
                if($file->upload()){
                    $insertFile->linkEntity($file);
                    $messages[] = basename($file->getPath()) . ": File has successfully been uploaded";
                }else{
                    $messages[] = basename($file->getPath()) . ": Failed to upload file";
                }

            }

            // execute prepared statement for all linked entities
            $insertFile->execute("Failed to insert file into database");

            $messages[] = "Files has successfully been inserted into database";

        }

    }

?>