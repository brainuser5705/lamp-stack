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