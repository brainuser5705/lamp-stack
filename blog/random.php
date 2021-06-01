<?php

    include 'blog_config.php';
    
    $getMin = new SelectStatement($dbconn, 
        "SELECT MIN(id) FROM entries;");
    $minId = $getMin->execute("fail")[0][0];

    $getMax = new SelectStatement($dbconn, 
        "SELECT MAX(id) FROM entries;");
    $maxId = $getMax->execute("fail")[0][0];
    // get first row of fetchAll(), get value (default: numeric)
    
    $text; // the text of the entry
    do{
        // get a random id
        $random_id = rand($minId, $maxId);

        // get text that has the random id
        $getText = new SelectStatement($dbconn,
        "SELECT id, text, datetime FROM entries
        WHERE entries.id = ?",
        PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Text', ["id","text"]);
        $text = $getText->execute("Fail to get text from database", [$random_id]);

        // get the pictures
        $getPic = new SelectStatement($dbconn,
        "SELECT path FROM media
        WHERE media.entry_id = ?;");
        $pics = $getPic->execute("Fail to get files from database", [$random_id]);

    }while(!$text);

    $filesArr = [];
    if ($pics){
        foreach($pics as $pic){
            // need to do this because it can't fetch into File's abnormal constructor!! :(
            $filesArr[] = new File($pic[0]); // get pic's path
        }
    }

    echo new FullEntry($text[0], $filesArr);
    
?>

<a href="random.php">Get a random entry!</a>
