<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Project Form</title>
    </head>
    <body>
        <h1>Submit a project: </h1>
        <?php
            include 'project-models.php';
            session_start();

            if (!isset($_SESSION["admin"])){
                header("Location: ../login.php");
                die();
            }

            $icon = $title = $description = $link = null;
            $titleMsg = $linkMsg = "";
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-project"])){
                
                if (!empty($_POST["icon-link"])){
                    $icon = sanitize($_POST["icon-link"]);
                }elseif(!empty($_FILES['icon-file']['name'])){
                    $icon = 'icons/' . sanitize($_FILES['icon-file']['name']);   
                }
            
                $description = sanitize($_POST["description"]);
                $link = sanitize($_POST["link"]);

                if (!empty($_POST["title"])){
                    $title = sanitize($_POST["title"]);
                }else{
                    $titleMsg = "Please enter a title.";
                }

                if (!empty($_POST["link"])){
                    $link = sanitize($_POST["link"]);
                }else{
                    $linkMsg = "Please enter a link.";
                }

                if (!empty($_POST["title"]) && !empty($_POST["link"])){
                    $project = new Project($title, $link, $icon, $description);
                    $insertProject = new InsertStatement($dbconn,
                        "INSERT INTO project (icon, title, description, link)
                        VALUES(?, ?, ?, ?);");
                    $insertProject->linkEntity($project);
                    $insertProject->execute("Fail to insert project into database<br>");

                    // upload image if there is no link
                    if(empty($_POST["icon-link"]) && !empty($_FILES['icon-file']['name'])){
                        uploadFile();
                    }
                }
                
            }

            function sanitize($value){
                $value = trim($value);
                $value = htmlspecialchars($value);
                return $value;
            }

            // does not support duplicate files (plan to abstract file upload)
            function uploadFile(){
                $iconImg = $_FILES['icon-file'];
                $path = basename($iconImg["name"]);
                $tmpPath = $iconImg["tmp_name"];
                
                $targetPath = "icons/" . $path;
                if (move_uploaded_file($tmpPath, $targetPath)){
                    echo "Icon image <code>" . $path . "</code> has been succesfully uploaded into database<br>";
                }else{
                    echo "Fail to upload icon image <code>" . $path . "</code> into database<br>";
                }
            }

        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" method="POST">
            <label for="icon">Icon: </label>
            <input type="text" name="icon-link">
             or 
            <input type="file" name="icon-file"><br>

            <label for="title">Title: </label>
            <input type="text" name="title">
            <span>* <?php echo $titleMsg; ?></span><br>

            <label for="description">Description: </label><br>
            <textarea name="description" rows="2" cols="30"></textarea><br>

            <label for="link">Link: </label>
            <input type="text" name="link">
            <span>* <?php echo $linkMsg; ?></span><br>

            <input type="submit" name="submit-project" value="Submit project">
        </form>

        <?php include 'project-debug.php' ?>

    </body>
</html>

