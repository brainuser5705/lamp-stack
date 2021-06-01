<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Blog Submit</title>
    </head>
    <body>

        <!-- 
            Blog Form
            For me to submit blog entries and linked files
        -->

        <h1> Submit an entry: </h1>
        <form method="POST" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="entry">Entry:</label><br>
            <textarea name="entry" rows="5" cols="40"></textarea><br>
            <label for="files">Upload files:</label><br>
            
            <div>
                <b>Valid file types:</b>
                <ul>
                    <li>Images: <i>jpg/jpeg, png, gif</i> </li>
                    <li>Videos: <i>mp4, mov</i> </li>
                    <li>File size limit: 41943040 bytes </li>
                </ul>
            </div>

            <input type="file" name="files[]" multiple><br><br>
            <!-- display a list of files that were selected and allow for deletion -->
            <input type="submit" value="Submit entry" name="submit">
        </form>

        <?php 
            include 'blog_config.php';

            if ($_SERVER["REQUEST_METHOD"] == "POST"){

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
                        }
                    }

                    // execute prepared statement for all linked entities
                    $insertFile->execute("Failed to insert file into database");

                }

            }
        ?>

    </body>
</html>