<?php 

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';
    include 'su-models.php';
    include 'su-queries.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';

    $title = "Status Updates";

    $statuses = getStatuses();

    $statusContent = "";
    if (!empty($statuses)){

        include $_SERVER['DOCUMENT_ROOT'] . '/parsedown-1.7.4/Parsedown.php';

        $Parsedown = new Parsedown();

        foreach($statuses as $status){
            $statusContent .= render('status-updates.php', ["status"=>$status, "Parsedown"=>$Parsedown]);
        }
        $statusContent .= '<i>Markdown parsed with <a href="https://github.com/erusev/parsedown">Parsedown.</a></i>';
    
    }else{
        $statusContent = "No status updates yet.";
    }

    echo render('base.php', ["title"=>$title, "content"=>$statusContent]);

?>