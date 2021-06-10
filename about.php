<?php

    $title = "About";

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';

    $content = render("about.html");

    echo render('base-template.php', ["title"=>$title, "content"=>$content]); // base template

?>