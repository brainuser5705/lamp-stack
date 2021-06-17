<?php

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/projects/project-models.php';

    // session_start();

    // if (!isset($_SESSION["admin"])){
    //     header("Location: ../login.php");
    //     die();
    // }

    include 'projects/project-form.php';
    include 'projects/project-debug.php';
    
?>