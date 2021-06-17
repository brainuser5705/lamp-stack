<?php

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';

    $title = "About brainuser5705";

    $content = render('about/text.html');

    echo render('base.php', ["title"=>$title, "content"=>$content]);
?>