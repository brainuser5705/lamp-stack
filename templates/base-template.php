<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>
            <?php echo $title; ?>
        </title>
        <link rel="shortcut icon" type="image/jpg" href="https://cdn.iconscout.com/icon/free/png-512/b-characters-character-alphabet-letter-36044.png"/>
        <link rel="stylesheet" href="/static/css/layout.css">
        <link rel="stylesheet" href="/static/css/styles.css">
        <?php
            if (isset($styleArr)){
                foreach($styleArr as $style){
                    echo '<link rel="stylesheet" href="/static/css/' . $style . '">';
                }
            }
        ?>
    </head>
    <body>
        <div class="visible">
            
            <header>
                <h1 id="website-name"><a href="\home.php">brainuser5705</a></h1>
                <div>the internet home of Ashley Liew</div>
            </header>

            <div class="visible-body">

                <nav>
                    <hr>
                    <a href="\home.php">Home</a>
                    <a href="\projects">Projects</a>
                    <a href="\blog">Statuses</a>
                    <hr>
                </nav>
    
                <main>
                    <?php echo $content; ?>
                </main>
                
            </div>

        </div>

        <footer>
            built by <strong>Ashley Liew</strong> 2021<br>
            <a href="https://github.com/brainuser5705" target="_blank">
                <img id="github-dark" src="/static/images/github_dark.png">
                <img id="github-light" src="/static/images/github_light.png">
            </a>
        </footer>

    </body>
</html>