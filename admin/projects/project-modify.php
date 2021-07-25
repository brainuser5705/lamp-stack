<div class="form" id="project-modify">
    <h1>Project:</h1>

    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">

        <?php 

            $projects = getProjects();

            if (!empty($projects)){ // if there is any project

                echo '<div>Choose which project to delete:</div>';

                // Create checkbox inputs for each entry
                foreach($projects as $project){
                    echo '<input type="checkbox" name="' . $project->getId() . '">';
                    $label = "{ <i>id</i>: " . $project->getId() . " , <i>title</i>: " . $project->getTitle() . ", <i>type</i>: " . $project->getType() . "}";
                    echo '<label for="' . $project->getId() .'">' . $label . "</label>";
                    echo '<br>';
                }
        ?>

        <input type="submit" name="project-select-delete" value="Delete selected projects"><br>

        <?php
        // continuing if there are no projects to delete
        // note: beginning '}'  is needed  
            }else{
                echo "No projects yet.<br>";
            }
        ?>

        <input type="submit" name="project-reset-id" value="Reset auto-increment id"><br>

    </form>
</div>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($_POST["project-select-delete"])){
            
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