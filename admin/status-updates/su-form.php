<div class="form" id="status-form">
    <h1>Update the people!</h1>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" method="POST">
        
        <label for="status">Status Markdown:</label><br>
        <i>Reminder that two spaces (not carriage return) will mean newline in Markdown.</i><br>
        <textarea name="status" rows="7" cols="50"></textarea><br>

        <label for="files">Upload files:</label>
        <input type="file" name="files[]" multiple><br><br>
        <!-- display a list of files that were selected and allow for deletion -->

        Current uploaded files:
        <ul>
            <?php
                $files = array_diff(scandir($FOLDER_PATH), ['..','.']);
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
            "INSERT INTO status(markdown)
            VALUES (?);");
        $insertStatus->linkEntity($status);
        $insertStatus->execute("Failed to insert status into database");
        
        $status_id = $insertStatus->getReturn(); // get the id of status
        $alertMessage .= "Status (id: {$status_id}) has successfully been inserted into database\\n";

        // if status has attached files
        if(isset($_FILES['files']) && $_FILES['files']["name"][0] != ""){
            $files = $_FILES['files'];
            $filesCount = count($files["name"]);

            for ($i = 0; $i < $filesCount; $i++){

                // get file propertries and get Entity object
                $fileName = basename($files["name"][$i]);
                $fileTmpPath = $files["tmp_name"][$i];
                $file = new File($fileName, $fileTmpPath);

                // Upload files and add confirmation message
                $alertMessage .= basename($file->getPath()) . ": "; 
                if($file->upload()){
                    $alertMessage .= "File has successfully been uploaded";
                }else{
                    $alertMessage .= "Failed to upload";
                }
                $alertMessage .= "\\n";

            }

        }
    }
?>

    
