<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Login</title>
        <link rel="shortcut icon" type="image/jpg" href="https://cdn.iconscout.com/icon/free/png-512/b-characters-character-alphabet-letter-36044.png"/>
        <link rel="stylesheet" href="/static/css/layout.css">
        <link rel="stylesheet" href="/static/css/styles.css">
    <head>
    <body>
        <?php
            session_start();

            // setting the environment variables (development)
            $hash = password_hash('testing',PASSWORD_DEFAULT);
            putenv('PASSWORD='.$hash);
            putenv('USERNAME=testuser');

            // default values before logging in
            $login = FALSE;
            $usernameMsg = $passwordMsg = "";
            $username = $password = null;

            if ($_SERVER["REQUEST_METHOD"] == "POST"){

                if (isset($_POST["login"])){

                    if(!empty($_POST["username"]) && !empty($_POST["password"])){
                        $username = sanitize($_POST["username"]);
                        $password = sanitize($_POST["password"]);

                        if ($username == getenv("USERNAME")){
                            if (password_verify($password, getenv("PASSWORD"))){
                                $login = TRUE;
                            }
                        }
                        
                        if($login){
                            session_regenerate_id();
                            $_SESSION["admin"] = TRUE;
                            header("Location: /admin/dashboard.html");
                        }else{
                            echo "Wrong username or password.";
                        }

                    }else{

                        // display error message for missing login info

                        if (empty($_POST["username"])){
                            $usernameMsg = "Please enter a username";
                        }
                        
                        if (empty($_POST["password"])){
                            $passwordMsg = "Please enter a password";
                        }
                    } 
                    
                }

            }

            // security sanitiation for form inputs
            function sanitize($value){
                $value = trim($value);
                $value = stripslashes($value);
                $value = htmlspecialchars($value);
                return $value;
            }
        ?>

        <!-- Note: I need to put this after the PHP script because the error message echoes PHP variables -->
        <h1>Login form</h1>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="username">Username: </label>
            <input type="text" name="username">
            <span>* <?php echo $usernameMsg; ?><span><br>
            <label for="password">Password: </label>
            <input type="password" name="password">
            <span>* <?php echo $passwordMsg; ?><span><br>
            <input type="submit" name="login" value="Log in">
        </form>

    </body>
</html>
