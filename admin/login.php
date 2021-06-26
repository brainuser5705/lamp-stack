<?php

    include $_SERVER['DOCUMENT_ROOT'] . "/templates/admin/admin-top.php";
    include $_SERVER['DOCUMENT_ROOT'] . "/abstraction/sanitize.php";

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
                    header("Location: /admin");
                }else{
                    echo "Wrong username or password.";
                }

            }
            
        }

    }

?>

<!-- Note: I need to put this after the PHP script because the error message echoes PHP variables -->
<h1>Login form</h1>
<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
    <label for="username">Username: </label>
    <input type="text" name="username">
    <label for="password">Password: </label>
    <input type="password" name="password">
    <input type="submit" name="login" value="Log in">
</form>

<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/templates/admin/admin-bottom.php";
?>
