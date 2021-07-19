<?php 

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';
    include 'su-models.php';
    include 'su-queries.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';

    $title = "Status Updates";

    $statuses = getStatuses();

    $statusContent = "";
    if (!empty($statuses)){

        require '/vendor/autoload.php';
        $Parsedown = new Parsedown();

        foreach($statuses as $status){
            $statusContent .= render('status-updates.php', ["status"=>$status, "Parsedown"=>$Parsedown]);
        }
        $statusContent .= '<hr><i>Markdown parsed with <a href="https://github.com/erusev/parsedown">Parsedown.</a></i>';
    
    }else{
        $statusContent = "No status updates yet.";
    }

    echo render('base.php', ["title"=>$title, "styleArr"=>["su.css"], "content"=>$statusContent]);

?>