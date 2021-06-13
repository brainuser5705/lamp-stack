<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($_POST["project-reset-entries"])){

            // delete statement to remove all rows from entry
            // delete mode is cascade for 'file' table, so I don't need an extra delete statement for 'file'
            $deleteAll = new ExecuteStatement($dbconn,
                "DELETE FROM project;"); 
            $deleteAll->execute("Fail to delete projects");

            $messages[] = "Successfully deleted all projects";

        }elseif (isset($_POST["project-select-delete"])){
            
            $deleteSelect = new LinkedExecuteStatement($dbconn,
                "DELETE FROM project WHERE id = ?");

            foreach($_POST as $k=>$v){
                $deleteSelect->addValue([$k]);
            }

            $deleteSelect->execute("Fail to delete projects");

            $messages[] = "Successfully deleted selected projects";

        }elseif (isset($_POST["project-reset-id"])){

            // reset for 'entry' table
            $resetAutoProject = new ExecuteStatement($dbconn,
            "ALTER TABLE project AUTO_INCREMENT = 0;");
            $resetAutoProject->execute("Cannot reset auto increment value for <code>'project'</code> table");   

            $messages[] = "Auto-increment id reset to 0 for project table";
        }

    }

?>