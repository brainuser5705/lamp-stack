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

            try{
                // set connection
                $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
                // set PDO error mode to exception
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                // set a prepared statement to get text part of entries
                $getTextStmt = $conn->prepare(
                    "SELECT id, text, datetime FROM entries");
                $getTextStmt->execute();

                // set a prepared statement to get picture part of entries
                $getPicStmt = $conn->prepare(
                    "SELECT type, path FROM media
                    WHERE media.entry_id = ?");
                
                // fetch result rows as an array of Text objects (with id, text, datetime attributes)
                $textsArr = $getTextStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Text');

                if ($textsArr){
                    foreach ($textsArr as $text){

                        // get foreign key connected Media rows
                        $entry_id = $text->id;
                        $getPicStmt->execute([$entry_id]); // bind the parameters to prepared statement
                        $pictures = $getPicStmt->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Picture');
                        
                        echo new FullEntry($text, $pictures);
                    }
                }else{
                    echo "No entries yet.";
                }
                

            }catch(PDOException $e){
                echo "Server Error, no entries from blog can be loaded." . $e->getMessage();
                // on the real site, if this happens, it can auto send an email to me
            }

        ?>

        <!-- good opportunity to use AJAX -->
        <a href="random.php">Get a random entry!</a>

    </body>
</html>

