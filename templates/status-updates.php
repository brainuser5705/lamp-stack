<div class="status-update">
    <div class="status-update-text">
        <?php 
            $text = $status->getText(); 
            echo $Parsedown->text($text);
        ?>
    </div>
    <div class="status-update-datetime">
        - <?php echo date("m/d/y (h:i a)", strtotime($status->getDatetime())); ?>
    </div>
</div>