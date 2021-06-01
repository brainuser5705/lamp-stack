<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Blog</title>
    </head>
    <body>
        <h1>Blog</h1>

        <?php

            include 'blog_config.php';

            $getText = new SelectStatement($dbconn,
                "SELECT id, text, datetime FROM entries",
                PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Text', ["id","text"]);
            $getText->execute("Fail to get entries from database");

            $getFiles = new SelectStatement($dbconn,
                "SELECT path FROM media
                WHERE media.entry_id = ?");

            // get all entries in database
            $textArr = $getText->getReturn();
            if ($textArr){
                foreach ($textArr as $text){
                    $entryId = $text->getId();
                    // get files linked to current entry
                    $getFiles->execute("Fail to get files from database", [$entryId]);
                    $files = $getFiles->getReturn();

                    // turn each return into a File (remember that FETCH_CLASS doesn't work because of the constructor?)
                    $filesArr = [];
                    foreach($files as $file){
                        $filesArr[] = new File($file[0]);
                    }

                    echo new FullEntry($text, $filesArr);
                }
            }else{
                echo "No entries yet.";
            }
    
        ?>

        <!-- good opportunity to use AJAX -->
        <a href="random.php">Get a random entry!</a>

    </body>
</html>

