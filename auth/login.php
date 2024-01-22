<?php 
session_start();
require_once "../functions/helper.php";
require_once "../functions/connection.php";
global $connection;

// if the user befor logged in, now logout
if($_SESSION["user"]){
    unset($_SESSION["user"]);
}
if($_SESSION["admin"]){
    unset($_SESSION["admin"]);
}
// show error
$error = "";

if(isset($_POST["username"]) && $_POST["username"] !== ""
 && isset($_POST["password"]) && $_POST["password"] !== ""){

    if($_POST["username"] === "admin" && $_POST["password"] === "admin"){
        $_SESSION["admin"] = 1;
        redirect("admin");
    }
    
    // check exist email 
    $qury = "SELECT * FROM users WHERE username = ? AND status = 1";
    $statment = $connection->prepare($qury);
    $statment->execute([$_POST["username"]]);
    $user = $statment->fetch();
    if($user !== false){
        if(password_verify($_POST["password"], $user->password)){
            $_SESSION["user"] = $user->username;
            redirect("user");
        }else{
        $error = "not valid password";
    }

    }else{
        $error = "not exist your username";
    }



 }



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=asset("assets/css/login.css");?>">
    <title>Login</title>
</head>
<body>
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <form class="login" method="post" action="<?=url("auth/login.php");?>">
                    <h2>WELLCOME</h2>
                    <p><?php if($error !== "")echo $error;?></p>
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="text" class="login__input" name="username" placeholder="User name">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-lock"></i>
                        <input type="password" class="login__input" name="password" placeholder="Password">
                    </div>
                    <button class="button login__submit">
                        <span class="button__text">Log In Now</span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </button>				
                </form>
                <div class="social-login">
                    <h3>log in via</h3>
                    <div class="social-icons">
                        <a href="#" class="social-login__icon fab fa-instagram"></a>
                        <a href="#" class="social-login__icon fab fa-facebook"></a>
                        <a href="#" class="social-login__icon fab fa-twitter"></a>
                    </div>
                </div>
            </div>
            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape4"></span>
                <span class="screen__background__shape screen__background__shape3"></span>		
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>		
        </div>
    </div>
</body>
</html>