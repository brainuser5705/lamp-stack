<?php

    $title = "Projects | This website";

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';

    $content = render('/projects/site/text.html');

    echo render('base.php', ["title"=>$title, "content"=>$content]);
?>