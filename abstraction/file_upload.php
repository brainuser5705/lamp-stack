<?php

// must declare $inputName, $folderPath before including this file

if (isset($_FILES[$inputName]) && $_FILES[$inputName]["name"][0] != ""){
    $files = $_FILES[$inputName];
    $filesCount = count($files["name"]);

    for ($i = 0; $i < $filesCount; $i++){

        // get file propertries
        $fileName = basename($files["name"][$i]);
        $fileTmpPath = $files["tmp_name"][$i];

        // make file object
        $file = new File($fileName, $folderPath, $fileTmpPath);

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

?>