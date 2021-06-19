<?php

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';

    // session_start();

    // if (!isset($_SESSION["admin"])){
    //     header("Location: ../login.php");
    //     die();
    // }

    $alertMessage = "";

    include $_SERVER['DOCUMENT_ROOT'] . '/projects/project-models.php';
    include 'projects/project-form.php';
    include 'projects/project-debug.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/status-updates/su-models.php';
    include 'status-updates/su-form.php';
    include 'status-updates/su-debug.php';

    if ($alertMessage != ""){
        // alert any confirmation messages at the end of submission
        echo "<script>alert('$alertMessage');</script>";
        // refresh the page for updated lists
        header("Refresh:0");
        die();
    }
    
?>