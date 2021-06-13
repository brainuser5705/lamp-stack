<?php

    session_start();

    // only allow access if admin is login
    if (!isset($_SESSION["admin"]) || !($_SESSION["admin"])){
        header("Location: /admin/login.php");
        die();
    }

    $title = "Admin Dashboard";

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';

    // render the debug template
    include $_SERVER['DOCUMENT_ROOT'] . '/blog/queries/get-blog.php';
    $blogVars = getBlog();
    include $_SERVER['DOCUMENT_ROOT'] . '/projects/queries/get-project.php';
    $projectVars = getAllProjects();
    $debug = render('admin/debug-template.php', array_merge($blogVars,$projectVars));

    $content = render('admin/dashboard-template.php', ["debug"=>$debug]);

    echo render('admin/admin-base-template.php', ["title"=>$title, "content"=>$content]);
    
?>