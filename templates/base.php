<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>
            <?php echo $title; ?>
        </title>
        <link rel="shortcut icon" type="image/jpg" href="/favicon.ico"/>
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

            <div class="visible-body">


                <h1 id="header">
                    Ashley says...
                </h1>
                
                <nav>
                    <?php
                        session_start();
                        if (isset($_SESSION["admin"])){
                            echo '<a href="/admin/">Admin</a>';
                        }
                    ?>
                </nav>
    
                <main>
                    <button id="theme-switch" >Dark Mode</button>
                    <?php echo $content; ?>
                </main>
                
            </div>

        </div>

        <footer>
            built by <strong>Ashley Liew</strong> 2021<br>
            
            <a href="https://www.ashleyliew.com/">Go to my main site</a>

        </footer>

        <script src = "/static/js/toggle-mode.js"></script>

    </body>
</html>