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
    '<div class="blog-entry">' . 
    '<h1>' . $blog->getTitle() . "</h1>" . 
    '<p class="blog-entry-decription">' . $blog->getDescription(). "</p>" .
    '<div class="blog-entry-datetime"><i>'. date("m/d/y (h:i a)", strtotime($blog->getDatetime())) . "</i></div>" .
    '</div> <hr>';


    // parse the text from database
    require '/app/vendor/autoload.php';
    $Parsedown = new Parsedown();
    $text = $Parsedown->text($blog->getText());

    $blogContent = 
    $heading .
    '<div class="blog-entry-text">' .
    $text .
    "</div>";

    echo render('base.php', ["title"=>$blog->getTitle(), "styleArr"=>["blog.css"], "content"=>$blogContent]);

?>