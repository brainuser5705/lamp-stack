<?php

    $title = "Admin Debug";

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/blog/queries/get-blog.php';
    $blogVars = getBlog();

    include $_SERVER['DOCUMENT_ROOT'] . '/projects/queries/get-project.php';
    $projectVars = getAllProjects();

    $content = render('admin/debug-template.php', array_merge($blogVars,$projectVars));

    echo render('admin/admin-base-template.php', ["title"=>$title, "content"=>$content]);
?>