<?php

    $title = "Projects";
    $styleArr = ['projects.css'];

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/projects/queries/get-project.php';
    // feature 
    $content = render("projects/project-template.php", getProjects(True));
    //other
    $content .= render("projects/project-template.php", getProjects(False));

    echo render("base-template.php", ["title"=>$title, "styleArr"=>$styleArr, "content"=>$content]);
?>