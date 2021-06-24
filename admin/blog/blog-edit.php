
<a href="/admin">Return to admin dashboard</a>

<?php

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/blog/blog-models.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/blog/blog-queries.php';
    include $_SERVER['DOCUMENT_ROOT'] . "/abstraction/sanitize.php";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        // determine whether blog id data is from
        if(isset($_POST["blog-edit"])){ // from blog-debug form
            $blogId = $_POST["blog"];
        }elseif (isset($_POST["blog-edit-submit"])){ // from php self
            $blogId = $_POST["blog-id"];
        }
        $blog = getBlog($blogId);

    }else{
        echo 'Please return to admin dashboard';
        die();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["blog-edit-submit"])){

        $validInputType = true;

        // file takes precedence
        if (isset($_FILES["blog-file"]) && $_FILES["blog-file"]["name"] != ""){

            $file = $_FILES["blog-file"]; 
            $filename = $file["name"];
            $tmpPath = $file["tmp_name"];
            
            switch(pathinfo($filename)["extension"]){
                case "html":
                case "txt":
                    $text = nl2br(file_get_contents($tmpPath));
                    break;
                case "md":
                    include $_SERVER['DOCUMENT_ROOT'] . '/parsedown-1.7.4/Parsedown.php';
                    $Parsedown = new Parsedown();
                    $text = $Parsedown->text(file_get_contents($tmpPath));
                    break;
                default:
                    $validInputType = false;
            }  
        }else{
            $text = $_POST["blog-text"];
        }

        if($validInputType){

            $title = sanitize($_POST["blog-title"]);
            $description = sanitize($_POST["blog-description"]);

            // rename folder to new title
            $oldFolderName = $blog->getPathToIndex();
            $newFolderName = str_replace(" ", "_", $title);
            rename(
                $_SERVER['DOCUMENT_ROOT'] . "/blog/" . $oldFolderName,
                $_SERVER['DOCUMENT_ROOT'] . "/blog/" . $newFolderName
            );

            // update database
            $editBlog = new LinkedExecuteStatement($dbconn,
                "UPDATE blog
                SET title=?, description=?, text=?, pathToIndex=?
                WHERE id=?"
            );
            $editBlog->addValue([$title, $description, $text, $newFolderName, $blogId]);
            $editBlog->execute();
            $alertMessage="Successfully edited blog";
            
        }else{

            $alertMessage ="Invalid file type, please try again.";

        }

        echo "<script>alert('$alertMessage');</script>";
        $blog = getBlog($blogId); // refresh blog

    }

?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" enctype="multipart/form-data" method="POST">
   
    <label for="blog-id">Blog id: </label>
    <input type="text" name="blog-id" value="<?php echo $blogId; ?>" readonly><br>
    
    <label for="blog-title">Change title: </label>
    <input type="text" name="blog-title" value="<?php echo $blog->getTitle(); ?>"><br>
    
    <label for="blog-description">Change description: </label><br>
    <textarea name="blog-description" rows="5" cols="50"><?php echo $blog->getDescription(); ?></textarea><br>
    
    <label for="blog-text">Change text: </label><br>
    <textarea name="blog-text" rows="30" cols="100"><?php echo nl2br($blog->getText()); ?></textarea><br>

    <label for="blog-file">Or upload new text file: </label>
    <input type="file" name="blog-file"><br>

    <input type="submit" name="blog-edit-submit" value="Edit">

</form>

    





