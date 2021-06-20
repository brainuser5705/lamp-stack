<?php

    $PATH_TO_TEXT_HTML = "/Cube_Magic/text.html";
    $title = "Cube Magic";

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';

    $blogContent = render("/blog/" . $PATH_TO_TEXT_HTML);

    echo render('base.php', ["title"=>$title, "content"=>$blogContent]);

?>