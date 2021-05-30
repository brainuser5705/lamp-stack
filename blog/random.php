<?php

    include 'blog_config.php';

    try{
        $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
        // set PDO error mode to exception
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        //get min id
        $stmt = "SELECT MIN(id) FROM entries;";
        $arr = $conn->query($stmt); // fetch mode is associative array
        $min = $arr->fetch()[0]; // gets the first value of array
        //get max id
        $stmt = "SELECT MAX(id) FROM entries;";
        $arr = $conn->query($stmt); // fetch mode is associative array
        $max = $arr->fetch()[0]; // gets the first value of array 

        $text;
        // do while loop in the case entries are deleted and random id generates deleted id
        do{
            // get a random id
            $random_id = rand($min, $max);

            // set a prepared statement to get text part of entries
            $getTextStmt = $conn->prepare(
                "SELECT id, text, datetime FROM entries
                WHERE entries.id = ?");
            $getTextStmt->execute([$random_id]);

            // set a prepared statement to get picture part of entries
            $getPicStmt = $conn->prepare(
                "SELECT path FROM media
                WHERE media.entry_id = ?");
            $getPicStmt->execute([$random_id]);

            $textClass = new Text();
            $getTextStmt->setFetchMode(PDO::FETCH_INTO, $textClass);
            // get the single Text object
            $text = $getTextStmt->fetch(); 

            // get array of Picture objects
            $pictures = $getPicStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Picture');

        }while(!$text); // text is not null
        
        echo new FullEntry($text, $pictures);

    }catch(PDOException $e){
        echo "Server Error, no entries from blog can be loaded." . $e->getMessage();
        // on the real site, if this happens, it can auto send an email to me
    }
    
?>

<a href="random.php">Get a random entry!</a>
