<div class="status-update">
    <div class="status-update-text">
        <?php 
            $text = $status->getText(); 
            echo $Parsedown->text($text);
        ?>
    </div>
    <div class="status-update-datetime">
        - <?php 
                include_once($_SERVER['DOCUMENT_ROOT'] . '/abstraction/convert_datetime.php');
                echo convert_datetime($status->getDatetime()); 
            ?>

        <br>

        Id: 
        <?php
            session_start();
            if (isset($_SESSION["admin"])){
                echo $status->getId();
            }
        ?>

    </div>
</div>

<hr>