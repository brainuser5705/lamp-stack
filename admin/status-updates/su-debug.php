<h1>Status Dashboard</h1>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">

    <?php 

        $getStatuses = new SelectStatement($dbconn,
        "SELECT id, datetime FROM status;");
        $getStatuses->setFetchMode(PDO::FETCH_ASSOC);
        $statuses = $getStatuses->execute("Failed to select statuses.");

        if (!empty($statuses[0])){ // if there is any status

            echo '<div>Choose which status to delete:</div>';

            // Create checkbox inputs for each status
            foreach($statuses as $status){
                echo '<input type="checkbox" name="' . $status["id"] . '">';
                $statusLabel = "{ <i>id</i>: " . $status["id"] . " , <i>datetime</i>: " . $status["datetime"] . "}";
                echo '<label for="' . $status["id"] .'">' . $statusLabel . "</label><br>";
            }
    ?>

    <input type="submit" name="status-select-delete" value="Delete selected status updates"><br>
    <div><b>OR</b></div>
    <input type="submit" name="reset-statuses" value="Remove all status updates"><br>

    <?php
        }else{
            echo "No status updates yet.<br>";
        }
    ?>

    <input type="submit" name="status-reset-id" value="Reset auto-increment id"><br>

</form>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($_POST["reset-statuses"])){

            // delete statement to remove all rows from status
            // delete mode is cascade for 'file' table, so I don't need an extra delete statement for 'file'
            $deleteAll = new ExecuteStatement($dbconn,
                "DELETE FROM status;"); 
            $deleteAll->execute("Fail to delete entries");

            // // remove all files
            // $files = array_diff(scandir($FOLDER_PATH), ['..','.']);
            // foreach($files as $filename){
            //     deleteFile($filename);
            // }

            $alertMessage = "Successfully deleted all status updates from database";

        }elseif (isset($_POST["status-select-delete"])){
            
            $deleteSelect = new LinkedExecuteStatement($dbconn,
                "DELETE FROM status WHERE id = ?");

            foreach($_POST as $k=>$v){
                $deleteSelect->addValue([$k]);
            }
            $deleteSelect->execute("Fail to delete statuses.");

            $alertMessage = "Successfully deleted selected status updates from database";

        }elseif (isset($_POST["status-reset-id"])){

            // reset for 'status' table
            $resetAutostatus = new ExecuteStatement($dbconn,
            "ALTER TABLE status AUTO_INCREMENT = 0;");
            $resetAutostatus->execute("Cannot reset auto increment value for <code>'status'</code> table");   

            $alertMessage = "Auto-increment id reset to 0 for status table";
        }

    }

    function deleteFile($filename){
        $target_dir = $FOLDER_PATH . $filename;
        if (file_exists($target_dir)){
            unlink($target_dir);
            $alertMessage .= "Successfully deleted file: " . $filename;
        }
    }

?>