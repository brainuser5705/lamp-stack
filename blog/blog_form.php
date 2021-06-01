<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Blog Submit</title>
    </head>
    <body>
        <h1> Submit an entry: </h1>
        <form method="POST" enctype="multipart/form-data" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
            <label for="text">Text:</label><br>
            <textarea name="text" rows="5" cols="40"></textarea><br>
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

                $text = $_POST["text"];
                $text = htmlspecialchars($text);

                $text = new Text($text);
                $text->executeOperation("Failed to insert text into database");
                $SUBMISSION_ID = $text->getReturn();

                if(isset($_FILES['files']) && $_FILES['files']["name"][0] != ""){
                    $files = $_FILES['files'];
                    $filesCount = count($files["name"]);

                    for ($i = 0; $i < $filesCount; $i++){
                        $fileName = basename($files["name"][$i]);
                        $fileTmpPath = $files["tmp_name"][$i];
                        $file = new File($fileName, $fileTmpPath, $SUBMISSION_ID);

                        $file->upload();
                        $file->executeOperation("Failed to insert " . $fileName . " into database.");
                    }

                }
            }
        ?>

    </body>
</html>