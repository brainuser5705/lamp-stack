<?php
/*
 * This php file contains queries for project-related backend code. 
 */

    /**
     * Get all project types
     * 
     * @return - array of project types(string)
     */
    function getProjectTypes(){
        global $dbconn;

        $getTypes = new SelectStatement($dbconn, 
            "SELECT * FROM project_type;");
        $getTypes->setFetchMode(PDO::FETCH_ASSOC);
        
        $typesArr = $getTypes->execute("Failed to get project types from database");
        
        return $typesArr;
    }

    /**
     * Get projects that are of specified type
     * 
     * @param type the type of projects to get
     * @return - array of Project objects 
     */
    function getSpecificProjects($type){
        global $dbconn;

        $getProjects = new SelectStatement($dbconn, 
            "SELECT * FROM project
             WHERE type = ?;");
        $getProjects->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Project', ['title', 'description', 'link', 'type']);

        $projectsArr = $getProjects->execute("Failed to get projects from database", [$type]);
        
        return $projectsArr;
    }

    function getProjects(){
        global $dbconn;

        $getProjects = new SelectStatement($dbconn, 
            "SELECT * FROM project");
        $getProjects->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Project', ['title', 'description', 'link', 'type']);

        $projectsArr = $getProjects->execute("Failed to get projects from database");
        
        return $projectsArr;
    }

?>