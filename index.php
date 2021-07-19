<?php

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';

    $title = "brainuser5705";

    $content = render('static/index.html');

    echo render('base.php', ["title"=>$title, "content"=>$content]);
?>