<!-- 
This is a template for displaying a list of projects of a certain type.
Context variables: project type and list of projects of type.
-->

<div class="project-type-list">

    <!-- List heading -->
    <h1><?php echo $type["name"] ?></h1>
    <p><i><?php echo $type["description"] ?></i></p>

    <ul>
        <?php
            if (!empty($projects)){
                foreach($projects as $project){
                    echo '<li>';
                    echo '<a href="'. $project->getLink() . '">' . $project->getTitle() . '</a> ';
                    echo '<i>' . $project->getDescription() . '</i>';
                    echo '</li>';
                }
            }else{
                echo "No projects yet.";
            }
        ?>
    </ul>

</div>