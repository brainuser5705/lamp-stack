
<a href="/admin">Return to admin dashboard</a>

<?php

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/blog/blog-models.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/blog/blog-queries.php';

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        // determine whether blog id data is from
        if(isset($_POST["blog-edit"])){ // from blog-debug form
            $blogId = $_POST["blog"];
        }elseif (isset($_POST["blog-edit-submit"])){ // from php self
            $blogId = $_POST["blog-id"];
        }

        $blog = getBlog($blogId);


?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" enctype="multipart/form-data" method="POST">
   
    <label for="blog-id">Blog id: </label>
    <input type="text" name="blog-id" value=<?php echo $blogId; ?>><br>

    <label for="blog-title">Change title: </label>
    <input type="text" name="blog-title" value="<?php echo $blog->getTitle(); ?>"><br>
    
    <label for="blog-description">Change description: </label><br>
    <textarea name="blog-description" rows="5" cols="50"><?php echo $blog->getDescription(); ?></textarea><br>
    
    <label for="blog-text">Change text: </label><br>
    <textarea name="blog-text" rows="50" cols="100"><?php echo nl2br($blog->getText()); ?></textarea><br>

    <label for="blog-file">Or upload new text file: </label>
    <input type="file" name="blog-file"><br>

    <input type="submit" name="blog-edit-submit" value="Edit">

</form>

<?php

    }else{
        echo 'Please return to admin dashboard';
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

            $editBlog = new LinkedExecuteStatement($dbconn,
                "UPDATE blog
                SET title=?, description=?, text=?
                WHERE id=?"
            );
            $editBlog->addValue(
                [$_POST["blog-title"], $_POST["blog-description"], $text, $_POST["blog-id"]]
            );
            $editBlog->execute();
            $alertMessage="Successfully edited blog"; // note does not rename folder
        
        }else{

            $alertMessage ="Invalid file type, please try again.";

        }

        echo "<script>alert('$alertMessage');</script>";

    }

?>





