<?php
/**
 * This is a template file for index.php that 
 * will be copied whenever a new blog entry is made
 * 
 * $id will be added to beginning of file.
 */

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/blog/blog-models.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/blog/blog-queries.php';

    $blog = getBlog($id);

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';

    $heading = 
    '<a href="\blog">Return to blog</a>'.
    "<h1>{$blog->getTitle()}</h1>" . 
    "<p>{$blog->getDescription()}</p>" .
    "<div><i>{$blog->getDatetime()}</i></div>";


    echo render('base.php', ["title"=>$blog->getTitle(), "content"=>$heading . $blog->getText()]);

?>