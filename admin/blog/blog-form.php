<?php

    // initial values for form inputs
    $title = "";
    $description = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-blog"])){

        $title = sanitize($_POST["title"]);
        $description = sanitize($_POST["description"]);
        $folder_name = str_replace(" ", "-", $title);

        $validFileType = true;
        
        // converts text file to html
        if (isset($_FILES["text-file"]) && $_FILES["text-file"]["name"] != ""){

            $file = $_FILES["text-file"]; 
            $filename = $file["name"];
            $tmpPath = $file["tmp_name"];
            
            if (pathinfo($filename)["extension"] != "md"){
                $alertMessage = "Invalid extension";
                $validFileType = false;
            }else{
                $text = file_get_contents($tmpPath);
            }
        
        }

        if ($validFileType){

            $folderPath = $_SERVER['DOCUMENT_ROOT'] . "/blog/" . $folder_name;
            
            // insert status update for blog
            $statusText =
                "**New blog update: **" . 
                '<a href="/blog/' . $title . '">' . $folder_name . '</a>  ' .
                "  <i>{$description}</i>";

            $insertStatus = new InsertStatement($dbconn,
                "INSERT INTO status (text)
                VALUES(?);");
            $insertStatus->linkEntity(new Status($statusText));
            $insertStatus->execute("Failed to insert status update into database");
            $status_id = $insertStatus->getReturn();

            $alertMessage .= "Status update successfully posted\\n";
            
            // insert blog into database
            $insertBlog = new InsertStatement($dbconn,
                "INSERT INTO blog (status_id, title, description, folder_name, text)
                VALUES(?,?,?,?,?);");
            $blog = new Blog($status_id, $title, $description, $folder_name, $text);
            $insertBlog->linkEntity($blog);
            $insertBlog->execute("Failed to insert blog into database");
            $blogId = $insertBlog->getReturn();

            $alertMessage .= "Blog (id: {$blogId}) successfully inserted into database\\n";
            
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

        <input type="submit" name="submit-blog" value="Post">

    </form>
</div>

