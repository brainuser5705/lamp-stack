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


    // parse the text from database
    include $_SERVER['DOCUMENT_ROOT'] . '/parsedown-1.7.4/Parsedown.php';
    $Parsedown = new Parsedown();
    $text = $Parsedown->text($blog->getText());


    echo render('base.php', ["title"=>$blog->getTitle(), "content"=>$heading . $text]);

?>