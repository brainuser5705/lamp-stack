<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" method="POST">

    <label for="title">Title: </label>
    <input type="text" name="title"><br>

    <label for="description">Description: </label><br>
    <textarea name="description" rows = "5" cols= "50"></textarea><br>

    <label for="text-file">Text File: </label>
    <input type="file" name="text-file">
    <i>Accepts .txt, .md, .html files</i><br>

    <label for="additional-files">Upload additional files: </label>
    <input type="file" name="additional-files[]" multiple><br><br>

    <input type="submit" name="submit-blog" value="Post">

</form>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-blog"])){

        // make folder in /blog, syntax: /blog/Title_Name
        $origTitle = sanitize($_POST["title"]); // with spaces
        $title = str_replace(" ", "_", $origTitle);

        $folder = $_SERVER['DOCUMENT_ROOT'] . "/blog/" . $title;
        
        if (is_dir($folder)){
            die("Title already taken");
        }

        mkdir($folder);

        $filename = "";

        $validFileType = true;
        // converts text file to html
        if (isset($_FILES["text-file"]) && $_FILES["text-file"]["name"] != ""){

            $file = $_FILES["text-file"]; 
            $filename = $file["name"];
            $tmpPath = $file["tmp_name"];
            
            switch(pathinfo($filename)["extension"]){
                case "html":
                case "txt":
                    move_uploaded_file($tmpPath, $folder . "/{$filename}");
                    break;
                case "md":

                    include $_SERVER['DOCUMENT_ROOT'] . '/parsedown-1.7.4/Parsedown.php';
                    $Parsedown = new Parsedown;

                    // create new html file
                    $new_file = fopen($folder . "/text.html", "w") or die("Unable to create text.html file");
                    fwrite($new_file, $Parsedown->text(file_get_contents($tmpPath)));
                    fclose($new_file);

                    $file = $new_file;
                    $filename = "text.html";

                    break;

                default:
                    $validFileType = false;

            }
        }

        if ($validFileType){

            // put index.php
            $templateCode = file_get_contents($_SERVER["DOCUMENT_ROOT"] . "/abstraction/template_index.php");

            $index = fopen($folder . "/index.php", "w") or die("Unable to open index.php file");
            fwrite($index, 
                "<?php\n\t" .
                '$PATH_TO_TEXT_HTML = "' . $title . '/' . $filename . '";' . "\n\t" .
                '$title = "' . $origTitle . '";' . "\n" .
                "?>\n\n" . $templateCode
            );
            
            fclose($index);

            // puts additional files into files folders
            $inputName = "additional-files";
            if (isset($_FILES[$inputName]) && $_FILES[$inputName]["name"][0] != ""){
                
                // make files folder
                mkdir($folder . "/files");

                // upload to files folder
                $folderPath = $folder . "/files/";
                include $_SERVER["DOCUMENT_ROOT"] . "/abstraction/file_upload.php";
            }
            
            // make a status update for new blog
            $description = sanitize($_POST["description"]);
            $statusText =
                "**New blog update:**" . 
                '<a href="/blog/' . $title . '">' . $title . '</a>  ' .
                "<i>{$description}</i>";

            $insertStatus = new LinkedExecuteStatement($dbconn,
                "INSERT INTO status (markdown)
                VALUES(?);");
            $insertStatus->addValue([$statusText]);
            $insertStatus->execute();

            $alertMessage = "Status update successfully posted";
        }

    }

?>