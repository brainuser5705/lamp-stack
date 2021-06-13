<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($_POST["blog-reset-entries"])){

            // delete statement to remove all rows from entry
            // delete mode is cascade for 'file' table, so I don't need an extra delete statement for 'file'
            $deleteAll = new ExecuteStatement($dbconn,
                "DELETE FROM entry;"); 
            $deleteAll->execute("Fail to delete entries");

            // remove all files from /images
            $files = array_diff(scandir($_SERVER['DOCUMENT_ROOT'] . '/blog/images/'), array('..','.'));
            foreach($files as $filename){
                deleteFile($filename);
            }

            $messages[] = "Succesfully deleted all entries";

        }elseif (isset($_POST["blog-select-delete"])){
            
            $deleteSelect = new LinkedExecuteStatement($dbconn,
                "DELETE FROM entry WHERE id = ?");

            $getFiles = new SelectStatement($dbconn,
            "SELECT path FROM file WHERE entry_id = ?");

            foreach($_POST as $k=>$v){
                $deleteSelect->addValue([$k]);
                // delete linked files to this entry
                $files = $getFiles->execute("Fail to get files for deletion", [$k]);
                if ($files){
                    foreach($files as $file){
                        // file[0] is the path
                        deleteFile($file[0]);
                    }    
                }
            }
            $deleteSelect->execute("Fail to delete entries.");

            $messages[] = "Successfully deleted selected entries and their files.";
            

        }elseif (isset($_POST["blog-reset-id"])){

            // reset for 'entry' table
            $resetAutoEntry = new ExecuteStatement($dbconn,
            "ALTER TABLE entry AUTO_INCREMENT = 0;");
            $resetAutoEntry->execute("Cannot reset auto increment value for <code>'entry'</code> table");   

            // reset for 'id' table
            $resetAutoFile = new ExecuteStatement($dbconn,
                "ALTER TABLE file AUTO_INCREMENT = 0;");
            $resetAutoFile->execute("Cannot reset auto increment value for <code>'file'</code> table");  

            $messages[] = "Auto-increment id reset to 0 for entry and file tables<br>";
        }

    }

    function deleteFile($filename){
        $target_dir = "/blog/images/" . $filename;
        if (file_exists($target_dir)){
            unlink($target_dir);
            $messages[] = "Successfully deleted file <code>" . $filename . "</code><br>";
        }
    }

?>