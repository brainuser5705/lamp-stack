<div class="blog-entry">

    <div class="blog-entry-title">
        <a href="\blog\<?php echo $entry->getFolderName(); ?>">
            <?php echo $entry->getTitle(); ?>
        </a>
    </div>

    <div class="blog-entry-datetime">
        <?php 
            include_once($_SERVER['DOCUMENT_ROOT'] . '/abstraction/convert_datetime.php');
            echo convert_datetime($entry->getDatetime()); 
        ?>
    </div>

    <div class="blog-entry-description">
        <?php echo $entry->getDescription(); ?>
    </div>
    
</div>
