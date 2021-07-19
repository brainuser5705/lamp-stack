<div class="form" id="status-form">

    <h1>Status Updates:</h1>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" method="POST">
        
        <label for="status">Status Markdown:</label>
        <i>Reminder that two spaces (not carriage return) will mean newline in Markdown.</i>
        <textarea name="status" rows="7" cols="50"></textarea>

        <label for="files">Upload files:</label>
        <input type="file" name="files[]" multiple>
        <!-- display a list of files that were selected and allow for deletion -->

        <i>Current uploaded files:</i>
        <ul>
            <?php
                $files = array_diff(scandir($SU_FOLDER_PATH), ['..','.']);
                foreach($files as $file){
                    echo "<li>{$file}</li>"; 
                }
            ?>
        </ul>

        <input type="submit" value="Post update" name="submit-status">
    </form>  
    
</div>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-status"])){

        // retrieve status from form and validate text
        $status = $_POST["status"];
        $status = htmlspecialchars($status);
        //$status = nl2br($status); // keep the line breaks so markdown parses correctly
        $status = new Status($status);

        // insert status into database
        $insertStatus = new InsertStatement($dbconn, 
            "INSERT INTO status(text)
            VALUES (?);");
        $insertStatus->linkEntity($status);
        $insertStatus->execute("Failed to insert status into database");
        
        $status_id = $insertStatus->getReturn(); // get the id of status
        $alertMessage .= "Status (id: {$status_id}) has successfully been inserted into database\\n";

        $inputName = "files";
        $folderPath = $SU_FOLDER_PATH;
        include $_SERVER["DOCUMENT_ROOT"] . "/abstraction/file_upload.php";
        
    }
?>

    
