<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/templates/admin/admin-top.php";

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';
    include $_SERVER['DOCUMENT_ROOT'] . "/abstraction/sanitize.php";

    session_start();

    if (!isset($_SESSION["admin"])){
        header("Location: login.php");
        die();
    }

    $alertMessage = "";

    include $_SERVER['DOCUMENT_ROOT'] . '/status-updates/su-models.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/status-updates/su-queries.php';

    include 'status-updates/su-modify.php';

    if ($alertMessage != ""){
        // alert any confirmation messages at the end of submission
        echo "<script>alert('$alertMessage');</script>";
        // refresh the page for updated lists
        header("Refresh:0");
        die();
    }

    include $_SERVER['DOCUMENT_ROOT'] . "/templates/admin/admin-bottom.php";
        
?>
