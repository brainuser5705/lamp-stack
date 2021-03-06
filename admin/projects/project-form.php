<div class="form" id="project-form">

    <h1>Project:</h1>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" method="POST">
        <label for="title">Title: </label>
        <input type="text" name="title">

        <label for="description">Description: </label>
        <textarea name="description" rows="2" cols="30"></textarea>

        <label for="link">Link: </label>
        <input type="text" name="link">

        <label for="type">Type: </label>
        <?php
            
            foreach(getProjectTypes() as $type){
                $typeName = $type["name"];

                echo '<input type="radio" name="type" value="' . $typeName . '">';
                echo '<label for="' . $typeName . '"> ' . $typeName . "</label>";
                echo '<br>';
            }

        ?>
        
        <!-- insert new project type -->
        <input type="radio" name="type" value="new">
        <label for="new">New: </label>

        <div id="new-project-type">
            <label for="new-type-name"><i>Name - </i></label>
            <input type="text" name="new-type-name">
            <label for="new-type-description"><i>Description - </i></label>
            <textarea name="new-type-description" rows="2" cols="20"></textarea>
        </div>

        <input type="submit" name="submit-project" value="Submit project">
    </form>

</div>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-project"])){

        $title = sanitize($_POST["title"]);
        $description = sanitize($_POST["description"]);
        $link = sanitize($_POST["link"]);
        $type = sanitize($_POST["type"]);

        // if new project type, insert it into database
        if ($type == "new"){
            $newTypeName = sanitize($_POST["new-type-name"]);
            $newTypeDescription = sanitize($_POST["new-type-description"]);

            // must use linkedexecute b/c prepared statement
            $insertNewType = new LinkedExecuteStatement($dbconn,
                "INSERT INTO project_type (name, description)
                 VALUES(?, ?);");
            $insertNewType->addValue([$newTypeName, $newTypeDescription]);
            $insertNewType->execute();

            $type = $newTypeName;
        }

        $project = new Project($title, $description, $link, $type);

        $insertProject = new InsertStatement($dbconn,
            "INSERT INTO project (title, description, link, type)
            VALUES(?, ?, ?, ?);");
        $insertProject->linkEntity($project);
        $insertProject->execute("Fail to insert project into database");

        // confirmation message signifying success!
        $alertMessage = "Successfully inserted project (id: " . $insertProject->getReturn() . ") into database";        

    }

?>

