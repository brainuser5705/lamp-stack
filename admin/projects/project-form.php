<?php
    include $_SERVER['DOCUMENT_ROOT'] . '/projects/config/project-models.php';

    $title = $description = $link = $feature = null;
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-project"])){
    
        $description = sanitize($_POST["description"]);

        // require title and link
        if (!empty($_POST["title"])){
            $title = sanitize($_POST["title"]);
        }else{
            $messages[] = "Please enter a title.";
        }

        if (!empty($_POST["link"])){
            $link = sanitize($_POST["link"]);
        }else{
            $messages[] = "Please enter a link.";
        }

        if(isset($_POST["feature"])){
            $feature = 1;
        }else{
            $feature = 0;
        }

        // only insert into database if required inputs are filled in
        if (!empty($_POST["title"]) && !empty($_POST["link"])){
            $project = new Project($title, $link, $feature, $description);
            $insertProject = new InsertStatement($dbconn,
                "INSERT INTO project (title, description, link, feature)
                VALUES(?, ?, ?, ?);");
            $insertProject->linkEntity($project);
            $insertProject->execute("Fail to insert project into database<br>");

            $id = $insertProject->getReturn();
            $messages[] = "Project (id: {$id}) has been successfully been into the database.";
        }        
    }

    function sanitize($value){
        $value = trim($value);
        $value = htmlspecialchars($value);
        return $value;
    }

?>