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

            $SUBMISSION_ID; // id of submission

            if ($_SERVER["REQUEST_METHOD"] == "POST"){

                /********* TEXT SUBMISSION **************/

                // validate text 
                $text = $_POST["text"];
                $text = htmlspecialchars($text);

                // insert text into database
                try{
                    $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
                    // set PDO error mode to exception
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $insertText = $conn->prepare(
                        "INSERT INTO entries(id, text)
                        VALUES (NULL, ?);"
                    );
                    $insertText->execute([$text]);

                    // set text id for foreign key for media (next step)
                    global $SUBMISSION_ID;
                    $SUBMISSION_ID = $conn->lastInsertId();

                    echo "Text (id: $SUBMISSION_ID) has successfully been inserted into database<br>";

                }catch(PDOException $e){
                    echo "Failed to insert text into database: " . $e->getMessage();
                }
                


                /********* FILE(S) SUBMISSION **************/


                // validate and upload files
                $UPLOAD_DIR = "images/"; 
                
                if (isset($_FILES['files']) &&
                     $_FILES['files']["name"][0] != ""){ // apparently $_FILES is not empty even when nothing is submitted??

                    $files = $_FILES['files']; // stores as associative arrays for each attribute of a file (ex. name)
                    $filesCount = count($files["name"]);

                    // loops through each uploaded file
                    for($i = 0; $i < $filesCount; $i++){
                        
                        // initial configuration
                        $target_file = $UPLOAD_DIR . basename($files["name"][$i]);
                        $pathinfo = pathinfo($target_file); // path info of original file
                        $uploadStatus = 0; // for validation
                        $message = "<i>" . $files["name"][$i] . "</i>: ";
                        $messageExtension = "";

                        // upload error: file with duplicate name
                        if (file_exists($target_file)){
                            $uploadStatus = 1;
                        }

                        //upload error: exceeds file size limit
                        if ($files["size"][$i] > 41943040){
                            $uploadStatus = 2;
                        }

                        // get file type based on file extension
                        $fileType = "other"; //default value
                        $fileExtension = $pathinfo["extension"];
                        if (in_array($fileExtension, array("jpg", "jpeg", "png", "gif"))){
                            $fileType = "image";
                        }elseif (in_array($fileExtension, array("mp4", "mov"))){
                            $fileType = "video";
                        }
                        
                        // uploading file now
                        if(isset($_POST["submit"])){

                            // automatically changes target file name by inserting number
                            if ($uploadStatus == 1){
                                $fileNum = 1;
                                while(file_exists($target_file)){
                                    $target_file = $UPLOAD_DIR . $pathinfo["filename"] . "($fileNum)." . $pathinfo["extension"];
                                    $fileNum++;
                                }
                                $messageExtension = ", due to duplicate, file name changed to " . $target_file;
                            }elseif($uploadStatus == 2){
                                $messageExtension = ", file size limit exceeded";
                            }

                            // uploading the file and send confirmation message
                            if (move_uploaded_file($files["tmp_name"][$i], $target_file)){
                                $message .= "File has successfully been uploaded" . $messageExtension . "<br>";
                            }else{
                                $message .= "<b>Failed to upload file</b>". $messageExtension . "<br>";
                            }

                            echo $message;

                        }

                        // insert media into database
                        try{
                            $conn = new PDO("mysql:host=$servername;dbname=$db", $username, $password);
                            // set PDO error mode to exception
                            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                            $insertMedia = $conn->prepare(
                                "INSERT INTO media (entry_id, type, path)
                                VALUES(?, ?, ?);"
                            );
                            $insertMedia->execute([$SUBMISSION_ID, $fileType, basename($target_file)]);

                            echo "File (id: {$conn->lastInsertId()}, type: $fileType) has successfully been inserted into database<br>";
                        }catch (PDOException $e){
                            echo "Failed to insert " . filename($target_file) . " into database: " . $e->getMessage(); 
                        }

                    }
                }

            }

            
            
        ?>

    </body>
</html>