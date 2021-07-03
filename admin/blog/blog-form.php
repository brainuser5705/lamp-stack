<?php

    // initial values for form inputs
    $title = "";
    $description = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-blog"])){

        $title = sanitize($_POST["title"]);
        $description = sanitize($_POST["description"]);
        $pathToIndex = str_replace(" ", "_", $title);

        $validFileType = true;
        // converts text file to html
        if (isset($_FILES["text-file"]) && $_FILES["text-file"]["name"] != ""){

            $file = $_FILES["text-file"]; 
            $filename = $file["name"];
            $tmpPath = $file["tmp_name"];
            
            if (pathinfo($filename)["extension"] != "md"){
                $alertMessage = "Invalid extension";
                $validInputType = false;
            }else{
                $text = file_get_contents($tmpPath);
            }
        
        }

        if ($validFileType){

            // make folder
            $folderPath = $_SERVER['DOCUMENT_ROOT'] . "/blog/" . $pathToIndex;
            if (is_dir($folderPath)){
                $alertMessage = "Title already taken, change title.";
            }else{

                mkdir($folderPath);
                
                // insert status update for blog
                $statusText =
                    "**New blog update:**" . 
                    '<a href="/blog/' . $title . '">' . $title . '</a>  ' . "\n" .
                    "<i>{$description}</i>";

                $insertStatus = new InsertStatement($dbconn,
                    "INSERT INTO status (markdown)
                    VALUES(?);");
                $insertStatus->linkEntity(new Status($statusText));
                $insertStatus->execute("Failed to insert status update into database");
                $statusId = $insertStatus->getReturn();

                $alertMessage .= "Status update successfully posted\\n";
                
                // insert blog into database
                $insertBlog = new InsertStatement($dbconn,
                    "INSERT INTO blog (statusId, title, description, pathToIndex, text)
                    VALUES(?,?,?,?,?);");
                $blog = new Blog($statusId, $title, $description, $pathToIndex, $text);
                $insertBlog->linkEntity($blog);
                $insertBlog->execute("Failed to insert blog into database");
                $blogId = $insertBlog->getReturn();

                $alertMessage .= "Blog (id: {$blogId}) successfully inserted into database\\n";

                // put index.php
                $index = fopen($folderPath . "/index.php", "w") or die("Unable to open index.php file");
                fwrite($index, 
                    "<?php\n\t" .
                    "\$id = {$blogId};\n\t" .
                    'include $_SERVER["DOCUMENT_ROOT"] . "/abstraction/template_blog_index.php"' . // include the template code
                    "\n?>" 
                );
                fclose($index);

                // puts additional files into files folders
                $inputName = "additional-files";
                if (isset($_FILES[$inputName]) && $_FILES[$inputName]["name"][0] != ""){
                    
                    // make files folder
                    mkdir($folderPath . "/files");

                    // upload to files folder
                    $folderPath = $folderPath . "/files/";
                    include $_SERVER["DOCUMENT_ROOT"] . "/abstraction/file_upload.php";
                }
            }
            
        }

    }

?>

<div class="form" id="blog-form">
    <h1>Blog:</h1>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" method="POST">

        <label for="title">Title: </label>
        <input type="text" name="title" value="<?php echo $title; ?>">

        <label for="description">Description: </label>
        <textarea name="description" rows = "5" cols= "50"><?php echo $description; ?></textarea>

        <label for="text-file">Text File: </label>
        <input type="file" name="text-file">
        <i>Accepts .txt, .md, .html files</i>

        <label for="additional-files">Upload additional files: </label>
        <input type="file" name="additional-files[]" multiple>

        <input type="submit" name="submit-blog" value="Post">

    </form>
</div>

