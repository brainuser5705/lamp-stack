<?php 

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';
    include 'blog-models.php';
    include 'blog-queries.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';

    $title = "Blog";

    $blog = getAllBlogs();

    $blogContent = "";
    if (!empty($blog)){
        foreach($blog as $entry){
            $blogContent .= render('blog.php', ["entry"=>$entry]);
        }
    }else{
        $blogContent = "No blog entries yet.";
    }

    echo render("base.php", ["title"=>$title, "styleArr"=>["blog.css"], "content"=>$blogContent]);

?>

    