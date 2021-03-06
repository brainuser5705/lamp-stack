<?php

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';
    include 'project-models.php';
    include 'project-queries.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';
	
    $title = "Projects";

    // get all the project lists (rendered from template) and concatenate into content variable
    $projectContent = "";

    $projectTypes = getProjectTypes();
    if (!empty($projectTypes)){
        foreach($projectTypes as $type){
            // template variables: the type and array of projects of type
            $projects = getSpecificProjects($type["name"]);
            $projectContent .= render("projects.php", ["type"=>$type, "projects"=>$projects]);
        }
    }else{
        $projectContent = "No projects yet.";
    }

    // render into base template
    echo render("base.php", ["title"=>$title, "content"=>$projectContent]);

?>
