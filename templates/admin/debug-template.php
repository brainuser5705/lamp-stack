<div id="blog-debug">
    <h1>Blog debug</h1>
    <form action="/admin/submission.php" method="POST">

        <?php 

            if (is_array($content)){ // if there is any entry

                echo '<div>Choose which entry to delete:</div>';

                // Create checkbox inputs for each entry
                foreach($content as $status){
                    
                    extract($status);
                    
                    $id = $entry->getId();
                    echo '<input type="checkbox" name="' . $id . '">';

                    // get files linked to entry
                    // and display files as a list
                    $filesList = "";
                    $prefix = "";
                    foreach($files as $file){
                        $filesList .= $prefix . $file->getPath();
                        $prefix = ", ";
                    }

                    $entryLabel = "{ <i>id</i>: " . $id . " , <i>text</i>: " . substr($entry->getText(), 0, 20) . "... , <i>files</i>: " . $filesList . "}";
                    echo '<label for="' . $id .'">' . $entryLabel . "</label><br>";
                }
        ?>

        <input type="submit" name="blog-select-delete" value="Delete selected entries"><br>
        <div><b>OR</b></div>
        <input type="submit" name="blog-reset-entries" value="Remove all entries"><br>

        <?php
        // continuing if there are no entries to delete
        // note: beginning '}'  is needed  
            }else{
                echo "No entries yet.";
            }
        ?>

        <input type="submit" name="blog-reset-id" value="Reset auto-increment id"><br>

    </form>
</div>

<div id="project-debug">
    <h1>Project Debug</h1>
    <form action="/admin/submission.php" method="POST">

        <?php 

            if (!empty($projects)){ // if there is any project

                echo '<div>Choose which project to delete:</div>';

                foreach($projects as $project){
                    $id = $project->getId();
                    echo '<input type="checkbox" name="' . $id . '">';
                    $label = "{ <i>id</i>: " . $id . " , <i>title</i>: " . $project->getTitle() . "}";
                    echo '<label for="' . $id .'">' . $label . "</label><br>";
                }
        ?>

        <input type="submit" name="project-select-delete" value="Delete selected entries"><br>
        <div><b>OR</b></div>
        <input type="submit" name="project-reset-entries" value="Remove all entries"><br>

        <?php
            }else{
                echo "No projects yet.";
            }
        ?>

        <input type="submit" name="project-reset-id" value="Reset auto-increment id"><br>

    </form>
</div>