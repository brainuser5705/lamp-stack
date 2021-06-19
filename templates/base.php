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
                    <a href="\projects">Projects</a>
                    <a href="\status-updates">Statuses</a>
                    <hr>
                </nav>
    
                <main>
                    <?php echo $content; ?>
                </main>
                
            </div>

        </div>

        <footer>
            built by <strong>Ashley Liew</strong> 2021<br>
            
            <span class="footer-icon">
                <a href="/about/">
                    <svg id="about-dark" xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="black" class="bi bi-person" viewBox="0 0 16 16">
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                    </svg>

                    <svg id="about-light" xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="white" class="bi bi-person" viewBox="0 0 16 16" >
                        <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z"/>
                    </svg>
                </a>
            </span>

            <span class="footer-icon">
                <a href="https://github.com/brainuser5705" target="_blank">
                    <img id="github-dark" src="/static/images/github_dark.png">
                    <img id="github-light" src="/static/images/github_light.png">
                </a>
            </span>

        </footer>

    </body>
</html>