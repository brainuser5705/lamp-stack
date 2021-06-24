<?php

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';

    // session_start();

    // if (!isset($_SESSION["admin"])){
    //     header("Location: ../login.php");
    //     die();
    // }

    function sanitize($value){
        $value = trim($value);
        $value = htmlspecialchars($value);
        return $value;
    }

    $alertMessage = "";

    include $_SERVER['DOCUMENT_ROOT'] . '/status-updates/su-models.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/blog/blog-models.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/blog/blog-queries.php';
    include 'blog/blog-form.php';
    include 'blog/blog-debug.php';

    include 'files-debug.php';

    include $_SERVER['DOCUMENT_ROOT'] . '/projects/project-models.php';
    include 'projects/project-form.php';
    include 'projects/project-debug.php';

    
    include 'status-updates/su-form.php';
    include 'status-updates/su-debug.php';

    if ($alertMessage != ""){
        // alert any confirmation messages at the end of submission
        echo "<script>alert('$alertMessage');</script>";
        // refresh the page for updated lists
        header("Refresh:0", True);
        die();
    }
    
?>