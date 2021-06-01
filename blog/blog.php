<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Blog</title>
    </head>
    <body>

        <!-- 
            Blog page 
            Display all blog entries from blog database
        -->

        <h1>Blog</h1>

        <?php

            include 'blog_config.php';

            // get all entries from database
            $getEntry = new SelectStatement($dbconn,
                "SELECT id, text, datetime FROM entry");
            $getEntry->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Entry', ["id","entry"]);
            $entryArr = $getEntry->execute("Fail to get entries from database");

            // create SELECT prepared statement
            $getFiles = new SelectStatement($dbconn,
                "SELECT path FROM file
                WHERE entry_id = ?");

            if ($entryArr){
                foreach ($entryArr as $entry){

                    $entryId = $entry->getId();
                    // get files linked to current entry
                    $files = $getFiles->execute("Fail to get files from database", [$entryId]);

                    // turn each return into a File
                    // (remember that FETCH_CLASS doesn't work because of the constructor)
                    $filesArr = [];
                    foreach($files as $file){
                        $filesArr[] = new File($file[0]);
                    }

                    echo new FullEntry($entry, $filesArr);
                }
        ?>
        
            <!-- good opportunity to use AJAX -->
            <a href="random.php">Get a random entry!</a>
        
        <?php
            }else{
                echo "No entries yet.";
            }
    
        ?>

    </body>
</html>

