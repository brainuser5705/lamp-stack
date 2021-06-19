<h1>Files</h1>

<form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">

    <?php
        $files = array_diff(scandir($FOLDER_PATH), ['..','.']);
        if (!empty($files)){

            echo '<div>Choose which files to delete:</div>';
            
            foreach($files as $file){
                echo '<input type="checkbox" name="' . $file . '">';
                echo '<label for="' . $file . '">' . $file . '</label>';
                echo '<br>';
            }
    ?>

    <input type="submit" name="file-select-delete" value="Delete selected files"><br>
    <div><b>OR</b></div>
    <input type="submit" name="reset-files" value="Remove all files"><br>

    <?php

        }else{
            echo "No files in folder.";
        }
    
    ?>


</form>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        if (isset($_POST["reset-files"])){

            foreach($files as $file){
                deleteFile($file);
            }

            $alertMessage = "Successfully deleted all files from folder";
        
        }elseif (isset($_POST["file-select-delete"])){
            
            foreach($_POST as $k=>$v){
                deleteFile(convertName($k)); 
                // have to convert file name because it changes . to _ 
                //ex. yas.jpg turns into yas_jpg
            }

            $alertMessage .= "Successfully deleted selected files from folder";
        }   

    }

    function deleteFile($filename){
        global $FOLDER_PATH, $alertMessage;
        $target_dir = $FOLDER_PATH . $filename;
        if (file_exists($target_dir)){
            unlink($target_dir);
            $alertMessage .= "Successfully deleted file: " . $filename . "\\n";
        }
    }

    function convertName($filename){
        // find the last _
        $index = strrpos($filename, "_");
        // replace it with .
        return substr_replace($filename, ".", $index, 1);
    }

?>
