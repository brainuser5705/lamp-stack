<div id="submission-messages">
    <?php
        if (isset($messages)){
            foreach($messages as $message){
                echo '<div class="message">' . $message . "</div>";
            }
        }
    ?>
</div>

<a href="/admin/">Submit more</a><br>
<a href="/admin/debug.php">Debug more</a><br>
<a href="/home.php">Home</a>
