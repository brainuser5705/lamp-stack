<?php

    $title = "Submission Receipt";

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';

    $messages = [];
    include $_SERVER['DOCUMENT_ROOT'] . '/admin/blog/blog-form.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/admin/blog/blog-debug.php'; 

    // will reset $dbconn variable
    include $_SERVER['DOCUMENT_ROOT'] . '/admin/projects/project-form.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/admin/projects/project-debug.php';
    $content = render("admin/submission-template.php", ["messages"=>$messages]);

    echo render("admin/admin-base-template.php", ["title"=>$title, "content"=>$content]);

?>