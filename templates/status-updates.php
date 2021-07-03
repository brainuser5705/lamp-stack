<div class="status-update">
    <div class="status-update-text">
        <?php 
            $markdown = $status->getMarkdown(); 
            echo $Parsedown->text($markdown);
        ?>
    </div>
    <div class="status-update-datetime">
        - <?php echo date("m/d/y (h:i a)", strtotime($status->getDatetime())); ?>
    </div>
</div>