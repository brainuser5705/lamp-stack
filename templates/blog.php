<div class="blog-entry">

    <div class="blog-entry-title">
        <a href="\blog\<?php echo $entry->getPathToIndex(); ?>">
            <?php echo $entry->getTitle(); ?>
        </a>
    </div>
    <div class="blog-entry-datetime">
        <?php echo $entry->getDatetime(); ?>
    </div>
    <div class="blog-entry-description">
        <?php echo $entry->getDescription(); ?>
    </div>
    
</div>
