<?php

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';
    include 'project-models.php';
    include 'project-queries.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';

    $title = "Projects";

    // get all the project lists (rendered from template) and concatenate into content variable
    $projectContent = "";
    foreach(getProjectTypes() as $type){
        // template variables: the type and array of projects of type
        $projectContent .= render("projects.php", ["type"=>$type, "projects"=>getProjects($type["name"])]);
    }

    // render into base template
    echo render("base.php", ["title"=>$title, "content"=>$projectContent]);

?>