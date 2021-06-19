<?php

    $title = "Random Status";
    $styleArr = ["blog.css"];

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/blog/queries/get-random.php';
    $content = render('blog/random-template.php', getRandom());

    echo render('base-template.php', ["title"=>$title, "styleArr"=>$styleArr, "content"=>$content]);

?>