<?php
    
    include $_SERVER['DOCUMENT_ROOT'] . "/templates/admin/admin-top.php";

    session_start();

    // only if the person is logged in do they have a reason to go to the logout page
    if (isset($_SESSION["admin"])){
        session_destroy();
        echo "Succesfully logged out.";
    }else{
        echo "what are you here for?";
    }
    
?>

<br><a href="/projects/">View projects</a>
<br><a href="/status-updates/">View statuses</a>
<br><a href="/blog/">View blog</a>
<br><a href="login.php">Log in</a>

<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/templates/admin/admin-bottom.php";
?>

