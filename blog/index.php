<?php 

    $title = "Status Updates";
    $styleArr = ['blog.css'];

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/blog/queries/get-blog.php';
    $content = render('blog/blog-template.php', getBlog());

    echo render('base-template.php', ["title"=>$title, "styleArr"=>$styleArr, "content"=>$content]);

?>