<div class="form" id="status-modify">
    <h1>Status Updates:</h1>

    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">

        <?php 

            $statuses = getStatuses();

            // formatting for checkboxes
            include_once($_SERVER['DOCUMENT_ROOT'] . '/abstraction/convert_datetime.php');

            if (!empty($statuses)){ // if there is any status

                echo '<div>Choose which status to delete:</div>';

                // Create checkbox inputs for each status
                foreach($statuses as $status){
                    echo '<input type="checkbox" name="' . $status->getId() . '">';

                    $label = " <i>id</i>: " . $status->getId() . "<br><i>datetime</i>: " . convert_datetime($status->getDatetime());

                    echo '<label for="' . $status->getId() .'">' . $label . "</label>";
                    echo "<br>";
                }
        ?>

        <input type="submit" name="status-select-delete" value="Delete selected status updates" onclick="return confirm('Are you sure you want to do that?');">
        <?php
            }else{
                echo "No status updates yet.<br>";
            }
        ?>
        <input type="submit" name="status-reset-id" value="Reset auto-increment id">

    </form>
</div>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($_POST["status-select-delete"])){

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
            "ALTER SEQUENCE status RESTART WITH 0");
            $resetAutostatus->execute("Cannot reset auto increment value for <code>'status'</code> table");   

            $alertMessage = "Auto-increment id reset to 0";
        }

    }

?>