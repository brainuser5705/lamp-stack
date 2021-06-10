<div class="status">

    <div class="status-datetime">
        <?php echo $status->getDatetime(); ?>
    </div>

    <div class="status-text">
        <?php echo $status->getText(); ?>
    </div>

    <div class="status-files">
        <?php 
            foreach($files as $file){
                echo '<img src="../blog/' . $file->getTargetPath() . '">';
            }
        ?>
    </div>

</div>