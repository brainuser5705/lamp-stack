<h1>Project Dashboard</h1>
<?php //include 'project-models.php'; ?>
<!--
    1. Remove all content in blog database, remove all files in images.
    2. List of all entries, checkbox to select, and delete.
-->

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">

    <?php 

        $getProjects= new SelectStatement($dbconn,
        "SELECT id, title FROM project;");
        $projects = $getProjects->execute("Fail to select projects.");

        if (!empty($projects[0])){ // if there is any project

            echo '<div>Choose which project to delete:</div>';

            // Create checkbox inputs for each entry
            $i = 0;
            foreach($projects as $project){
                $name = $project[0]; // the id of the entry
                echo '<input type="checkbox" name="' . $name . '">';
                $label = "{ <i>id</i>: " . $project[0] . " , <i>title</i>: " . $project[1] . "}";
                echo '<label for="' . $name .'">' . $label . "</label><br>";
            }
    ?>

    <input type="submit" name="select-delete" value="Delete selected entries"><br>
    <div><b>OR</b></div>
    <input type="submit" name="reset-entries" value="Remove all entries"><br>

    <?php
    // continuing if there are no projects to delete
    // note: beginning '}'  is needed  
        }else{
            echo "No projects yet.<br>";
        }
    ?>

    <input type="submit" name="reset-id" value="Reset auto-increment id"><br>

</form>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($_POST["reset-entries"])){

            // delete statement to remove all rows from entry
            // delete mode is cascade for 'file' table, so I don't need an extra delete statement for 'file'
            $deleteAll = new ExecuteStatement($dbconn,
                "DELETE FROM project;"); 
            $deleteAll->execute("Fail to delete projects");

            // remove all files from /images
            $files = array_diff(scandir('icons/'), array('..','.'));
            foreach($files as $filename){
                deleteFile($filename);
            }

            // refreshes the page to show updated database
            header("Refresh:0");
            die();

        }elseif (isset($_POST["select-delete"])){
            
            $deleteSelect = new LinkedExecuteStatement($dbconn,
                "DELETE FROM project WHERE id = ?");

            $getIcons= new SelectStatement($dbconn,
            "SELECT icon FROM project WHERE id = ?;");

            foreach($_POST as $k=>$v){
                $deleteSelect->addValue([$k]);
                $icon = $getIcons->execute("Fail to select icons for projects.", [$k])[0];
                if ($icon){
                    deleteFile($icon[0]); // the filepath
                }
            }
            $deleteSelect->execute("Fail to delete entries.");

            // refreshes the page to show updated database
            header("Refresh:0");
            die();
            

        }elseif (isset($_POST["reset-id"])){

            // reset for 'entry' table
            $resetAutoProject = new ExecuteStatement($dbconn,
            "ALTER TABLE project AUTO_INCREMENT = 0;");
            $resetAutoProject ->execute("Cannot reset auto increment value for <code>'project'</code> table");   

            echo "Auto-increment id reset to 0 for project table";
        }

    }

    function deleteFile($filename){
        $target_dir = $filename;
        if (file_exists($target_dir)){
            unlink($target_dir);
            echo "Successfully deleted icon image <code>" . $filename . "</code><br>";
        }
    }

?>