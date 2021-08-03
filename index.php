<?php 

    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/database.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/status-updates/su-models.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/status-updates/su-queries.php';
    include $_SERVER['DOCUMENT_ROOT'] . '/abstraction/render.php';

    $title = "Status Updates";

    $statuses = getStatuses();

    $statusContent = "";
    if (!empty($statuses)){

        require '/app/vendor/autoload.php';
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