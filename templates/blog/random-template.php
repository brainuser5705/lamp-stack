<h1>Random status</h1>
<?php if (isset($entry)){ ?>
    <a id="random-link" href="/blog/random.php">Get another random status</a>
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
                    echo '<img src="/blog/images/' . $file->getPath() . '">';
                }
            ?>

        </div>

    </div>
<?php 
    }else{
        echo '<div>No entries yet</div>';
        echo '<a id="random-link" href="/home.php">Go Back</a>';
    }
?>

