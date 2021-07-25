<div class="form" id="blog-edit">
    <h1>Blog (Edit):</h1>
    <form action="blog/blog-edit.php" method="POST">

        <?php

            $blogs = getAllBlogs();
            if (!empty($blogs)){
                foreach($blogs as $blog){
                    echo '<input type="radio" name="blog" value="' . $blog->getId() . '"=>';
                    $label = $blog->getTitle() . "{<id>id</i>: {$blog->getId()}}";
                    echo '<label for="' . $blog->getId() . '">' . $blog->getTitle() . "</label>";
                    echo "<br>";
                }

        ?>

        <input type="submit" name="blog-edit" value="Edit entry">

        <?php

            }else{
                echo "No blog yet.<br>";
            }

        ?>

    </form> 
</div>

<div class="form" id="blog-debug">
    <h1>Blog (Debug):</h1>

    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">

        <?php 

            $blogs = getAllBlogs();

            if (!empty($blogs)){ // if there is any blogs

                echo '<div>Choose which blogs to delete:</div>';

                // Create checkbox inputs for each entry
                foreach($blogs as $blog){
                    echo '<input type="checkbox" name="' . $blog->getId() . '">';
                    $label = "{ <i>id</i>: " . $blog->getId() . " , <i>title</i>: " . $blog->getTitle() . ", <i>datetime</i>: " . $blog->getDatetime() . "}";
                    echo '<label for="' . $blog->getId() .'">' . $label . "</label>";
                    echo '<br>';
                }
        ?>

        <input type="submit" name="blog-select-delete" value="Delete selected blogs"><br>

        <?php
        // continuing if there are no blogs to delete
        // note: beginning '}'  is needed  
            }else{
                echo "No blogs yet.<br>";
            }
        ?>

        <input type="submit" name="blog-reset-id" value="Reset auto-increment id"><br>

    </form>
</div>

<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST"){

        $deleteStatus = new LinkedExecuteStatement($dbconn,
                "DELETE FROM status WHERE id = ?");

        if (isset($_POST["blog-select-delete"])){

            $deleteSelect = new LinkedExecuteStatement($dbconn,
                "DELETE FROM blog WHERE id = ?");
            foreach($_POST as $k=>$v){
                if (is_int($k)){
                    $deleteSelect->addValue([$k]);
                    
                    // delete folder
                    $blog = getBlog($k);
                    rmdir_recursive($_SERVER['DOCUMENT_ROOT'] . "/blog/". $blog->getFolderName());
                
                    // delete status
                    $deleteStatus->addValue([$blog->getStatusId()]);
                    $deleteStatus->execute("Fail to delete statuses.");
                }
            }
            $deleteSelect->execute("Fail to delete entries.");

            $alertMessage = "Successfully deleted selected blogs from database";
            
        }elseif (isset($_POST["blog-reset-id"])){

            // reset for 'entry' table
            $resetAutoblog = new ExecuteStatement($dbconn,
            "ALTER TABLE blog AUTO_INCREMENT = 0;");
            $resetAutoblog ->execute("Cannot reset auto increment value for <code>'blog'</code> table");   

            $alertMessage = "Auto-increment id reset to 0 for blog table";
        }

    }

?>