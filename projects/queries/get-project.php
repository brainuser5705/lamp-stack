<?php

    include $_SERVER['DOCUMENT_ROOT'] . '/projects/config/project-models.php'; // used by project index.php and home.php

    function getProjects($isFeature){
        global $dbconn;

        $getProjects = new SelectStatement($dbconn,
            "SELECT * FROM project WHERE feature = ?;");
        $getProjects->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Project', ["title", "link", "icon", "description"]);

        $projectsArr = $getProjects->execute("Fail to get featured projects from database", [$isFeature]);
        
        $label = ($isFeature ? "Featured" : "Other") . " Projects";
        return ["label"=>$label, "projects"=>$projectsArr];
    }

?>