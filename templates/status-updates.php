<div class="status-update">
    <div class="status-update-datetime">
        <?php echo $status->getDatetime(); ?>
    </div>
    <div class="status-update-text">
        <?php 
            $markdown = $status->getMarkdown(); 
            echo $Parsedown->text($markdown);
        ?>
    </div>
</div>