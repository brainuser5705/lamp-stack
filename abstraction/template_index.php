<?php
/**
 * This is a template file for index.php that 
 * will be copied whenever a new blog entry is made
 */

    // copied files will include a line here to declare $PATH_TO_TEXT_HTML and $title

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';

    $blogContent = render("/blog/" . $PATH_TO_TEXT_HTML);

    echo render('base.php', ["title"=>$title, "content"=>$blogContent]);

?>