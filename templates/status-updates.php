<div class="status-update">
    <div class="status-update-text">
        <?php 
            $text = $status->getText(); 
            echo $Parsedown->text($text);
        ?>
    </div>
    <div class="status-update-datetime">
        - <?php 
                echo convert_datetime($status->getDatetime()); 
            ?>
    </div>
</div>