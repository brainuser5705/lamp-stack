<h2> <?php echo $label ?> </h2>

<?php
    foreach($projects as $project){
        echo '<div class="projects-list">';
        //echo '<span class="projects-list-icon"> <img src="' . $project->getIcon() . '"> </span>';
        echo '<span class="projects-list-text">';
        echo '<span class="projects-list-title"> <a href="' . $project->getLink() . '">' . $project->getTitle() . '</a> </span>';
        echo '<span class="projects-list-description">' . $project->getDescription() . '</span>';
        echo '</span>';
        echo '</div>';
    }
?>

