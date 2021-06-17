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
            "SELECT * FROM projectType;");
        $getTypes->setFetchMode(PDO::FETCH_ASSOC);
        
        $typesArr = $getTypes->execute("Fail to get project types from database");
        
        return $typesArr;
    }

    /**
     * Get projects that are of specified type
     * 
     * @param type the type of projects to get
     * @return - array of Project objects 
     */
    function getProjects($type){
        global $dbconn;

        $getProjects = new SelectStatement($dbconn, 
            "SELECT * FROM project
             WHERE type = ?;");
        $getProjects->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Project', ['title', 'description', 'link', 'type']);

        $projectsArr = $getProjects->execute("Fail to get projects from database", [$type]);
        
        return $projectsArr;
    }

?>