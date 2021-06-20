<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data" method="POST">

    <label for="title">Title: </label>
    <input type="text" name="title"><br>

    <label for="text-file">Text File: </label>
    <input type="file" name="text-file">
    <i>Accepts .txt, .md, .html files</i><br>

    <label for="additional-files">Upload additional files: </label>
    <input type="file" name="additional-files[]" multiple><br><br>

    <input type="submit" name="submit-blog" value="Post">

</form>

<?php

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit-blog"])){

        // make folder in /blog, syntax: /blog/Title_Name
        $title = sanitize($_POST["title"]);
        $title = str_replace(" ", "_", $title);

        $folder = $_SERVER['DOCUMENT_ROOT'] . "/blog/" . $title;
        mkdir($folder);

        // converts text file to html
        if (isset($_FILES["text-file"]) && $_FILES["text-file"]["name"] != ""){
            $file = $_FILES["text-file"];
            
        }

        // puts additional files into files folders
        $inputName = "additional-files";
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]["name"][0] != ""){
            
            // make files folder
            mkdir($folder . "/files");

            // upload to files folder
            $folderPath = $folder . "/files/";
            include $_SERVER["DOCUMENT_ROOT"] . "/abstraction/file_upload.php";
        }
        

        // make a status update for new blog



    }

    function sanitize($value){
        $value = trim($value);
        $value = htmlspecialchars($value);
        return $value;
    }

?>