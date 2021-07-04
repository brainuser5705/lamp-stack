<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>
            <?php echo $title; ?>
        </title>
        <link rel="shortcut icon" type="image/jpg" href="/favicon.png"/>
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
                <h1 id="website-name">brainuser5705</h1>
                <div>the internet home of Ashley Liew</div>
            </header>

            <div class="visible-body">

                <nav>
                    <hr>
                    <a href="\projects">Projects</a>
                    <a href="\status-updates">Statuses</a>
                    <a href="\blog">Blog</a>
                    <?php

                        // add link to admin page if logged in
                        session_start();
                        if (isset($_SESSION["admin"])){
                            echo '<a href="/admin/">Admin</a>';
                        }

                    ?>
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

                    <svg id="about-dark" xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="black" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                    </svg>

                    <svg id="about-light" xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="white" class="bi bi-info-circle-fill" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
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