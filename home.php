<?php

    $title="brainuser5705";
    $styleArr = ["home.css"];

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';

    $intro = render("intro.html");
    
    include $_SERVER['DOCUMENT_ROOT'] . '/projects/queries/get-project.php';
    $projects = render('projects/project-template.php', getProjects(True));

    include $_SERVER['DOCUMENT_ROOT'] . '/blog/queries/get-last-status.php';
    $status = render('blog/status-template.php', getLatestStatus()); // status template
    
    $content = render('home/home-template.php', ["intro"=>$intro, "projects"=>$projects, "status"=>$status]); // home content template

    echo render('base-template.php', ["title"=>$title, "styleArr"=>$styleArr, "content"=>$content]); // base template

?>