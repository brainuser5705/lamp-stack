<h1>Blog</h2>

<div id="blog-statuses">
    <?php
        foreach($content as $status){

            extract($status);

            echo  '<div class="status">
                    <div class="status-datetime">' . $entry->getDatetime() . '</div>
                    <div class="status-text">' . $entry->getText() . '</div>
                    <div class="status-files">';
                
            foreach($files as $file){
                echo '<img src="/blog/' . $file->getTargetPath() . '">';
            }


            echo '</div></div>'; // first one for files div, second one for status div

        }

    ?>
</div>