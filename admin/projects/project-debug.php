<h1>Project Dashboard</h1>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">

    <?php 

        $getProjects= new SelectStatement($dbconn,
        "SELECT id, title, type FROM project;");
        $getProjects->setFetchMode(PDO::FETCH_ASSOC);
        $projects = $getProjects->execute("Failed to select projects.");

        if (!empty($projects[0])){ // if there is any project

            echo '<div>Choose which project to delete:</div>';

            // Create checkbox inputs for each entry
            foreach($projects as $project){
                echo '<input type="checkbox" name="' . $project["id"] . '">';
                $label = "{ <i>id</i>: " . $project["id"] . " , <i>title</i>: " . $project["title"] . ", <i>type</i>: " . $project["type"] . "}";
                echo '<label for="' . $project["id"] .'">' . $label . "</label>";
                echo '<br>';
            }
    ?>

    <input type="submit" name="project-select-delete" value="Delete selected projects"><br>
    <div><b>OR</b></div>
    <input type="submit" name="reset-projects" value="Remove all projects"><br>

    <?php
    // continuing if there are no projects to delete
    // note: beginning '}'  is needed  
        }else{
            echo "No projects yet.<br>";
        }
    ?>

    <input type="submit" name="project-reset-id" value="Reset auto-increment id"><br>

</form>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($_POST["reset-projects"])){

            // delete statement to remove all rows from entry
            // delete mode is cascade for 'file' table, so I don't need an extra delete statement for 'file'
            $deleteAll = new ExecuteStatement($dbconn,
                "DELETE FROM project;"); 
            $deleteAll->execute("Fail to delete all projects");

            $alertMessage = "Successfully deleted all projects from database";

        }elseif (isset($_POST["project-select-delete"])){
            
            $deleteSelect = new LinkedExecuteStatement($dbconn,
                "DELETE FROM project WHERE id = ?");

            foreach($_POST as $k=>$v){
                $deleteSelect->addValue([$k]);
            }
            $deleteSelect->execute("Fail to delete entries.");

            $alertMessage = "Successfully deleted selected projects from database";
            
        }elseif (isset($_POST["project-reset-id"])){

            // reset for 'entry' table
            $resetAutoProject = new ExecuteStatement($dbconn,
            "ALTER TABLE project AUTO_INCREMENT = 0;");
            $resetAutoProject ->execute("Cannot reset auto increment value for <code>'project'</code> table");   

            $alertMessage = "Auto-increment id reset to 0 for project table";
        }

    }

?>