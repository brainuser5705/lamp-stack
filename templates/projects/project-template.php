<h2> <?php echo $label ?> </h2>

<?php
    foreach($projects as $project){
        echo '<div class="projects-list">';
        echo '<span class="projects-list-text">';
        echo '<span class="projects-list-title"> <a href="' . $project->getLink() . '">' . $project->getTitle() . '</a> </span>';
        echo '<span class="projects-list-description"><i>' . $project->getDescription() . '</i></span>';
        echo '</span>';
        echo '</div>';
    }
?>

