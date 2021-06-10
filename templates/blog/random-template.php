<h1>Random status</h1>

<div class="status">
    <div class="status-datetime">
        <?php echo $entry->getDatetime() ?>
    </div>
    <div class="status-text">
        <?php echo $entry->getText() ?>
    </div>
    <div class="status-files">
        
        <?php
            foreach($files as $file){
                echo '<img src="/blog/' . $file->getTargetPath() . '">';
            }
        ?>

    </div>

</div>

<a id="random-link" href="/blog/random.php">Get another random status</a>