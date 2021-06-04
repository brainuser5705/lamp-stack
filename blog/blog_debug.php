<h1>Debugging Dashboard</h1>
<?php //include 'blog_config.php'; ?>
<!--
    1. Remove all content in blog database, remove all files in images.
    2. List of all entries, checkbox to select, and delete.
-->

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">

    <?php 

        $getText = new SelectStatement($dbconn,
        "SELECT id, SUBSTR(text, 1) FROM entry;");
        $entries = $getText->execute("Fail to select entries.");

        $getFiles = new SelectStatement($dbconn,
                "SELECT path FROM file WHERE entry_id = ?");

        if (!empty($entries[0])){ // if there is any entry

            echo '<div>Choose which entry to delete:</div>';

            // Create checkbox inputs for each entry
            $i = 0;
            foreach($entries as $entry){
                $entryName = $entry[0]; // the id of the entry
                echo '<input type="checkbox" name="' . $entryName . '">';

                // get files linked to entry
                // and display files as a list
                $files = $getFiles->execute("Fail to select files", [$entryName]);
                $filesList = "";
                $prefix = "";
                foreach($files as $file){
                    $filesList .= $prefix . $file[0];
                    $prefix = ", ";
                }

                $entryLabel = "{ <i>id</i>: " . $entry[0] . " , <i>text</i>: " . $entry[1] . " , <i>files</i>: " . $filesList . "}";
                echo '<label for="' . $entryName .'">' . $entryLabel . "</label><br>";
            }
    ?>

    <input type="submit" name="select-delete" value="Delete selected entries"><br>
    <div><b>OR</b></div>
    <input type="submit" name="reset-entries" value="Remove all entries"><br>

    <?php
    // continuing if there are no entries to delete
    // note: beginning '}'  is needed  
        }else{
            echo "No entries yet.<br>";
        }
    ?>

    <input type="submit" name="reset-id" value="Reset auto-increment id"><br>

</form>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($_POST["reset-entries"])){

            // delete statement to remove all rows from entry
            // delete mode is cascade for 'file' table, so I don't need an extra delete statement for 'file'
            $deleteAll = new ExecuteStatement($dbconn,
                "DELETE FROM entry;"); 
            $deleteAll->execute("Fail to delete entries");

            // remove all files from /images
            $files = array_diff(scandir('images/'), array('..','.'));
            foreach($files as $filename){
                deleteFile($filename);
            }

            // refreshes the page to show updated database
            header("Refresh:0"); 

        }elseif (isset($_POST["select-delete"])){
            
            $deleteSelect = new LinkedExecuteStatement($dbconn,
                "DELETE FROM entry WHERE id = ?");

            foreach($_POST as $k=>$v){
                $deleteSelect->addValue([$k]);
                // delete linked files to this entry
                // using select statement for 'file' table earlier in the code
                $files = $getFiles->execute("Fail to get files for deletion", [$k]);
                if ($files){
                    foreach($files as $file){
                        // file[0] is the path
                        deleteFile($file[0]);
                    }    
                }
            }
            $deleteSelect->execute("Fail to delete entries.");

            // refreshes the page to show updated database
            header("Refresh:0");
            

        }elseif (isset($_POST["reset-id"])){

            // reset for 'entry' table
            $resetAutoEntry = new ExecuteStatement($dbconn,
            "ALTER TABLE entry AUTO_INCREMENT = 0;");
            $resetAutoEntry->execute("Cannot reset auto increment value for <code>'entry'</code> table");   

            // reset for 'id' table
            $resetAutoFile = new ExecuteStatement($dbconn,
                "ALTER TABLE file AUTO_INCREMENT = 0;");
            $resetAutoFile->execute("Cannot reset auto increment value for <code>'file'</code> table");  

            echo "Auto-increment id reset to 0 for entry and file tables";
        }

    }

    function deleteFile($filename){
        $target_dir = "images/" . $filename;
        if (file_exists($target_dir)){
            unlink($target_dir);
            echo "Successfully deleted file <code>" . $filename . "</code><br>";
        }
    }

?>