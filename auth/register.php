<?php 
require_once "../functions/helper.php";
require_once "../functions/connection.php";
global $connection;
global $error;
//check is not null and empty
if(isset($_POST["name"]) && $_POST["name"] !== ""
&& isset($_POST["family"]) && $_POST["family"] !== ""
&& isset($_POST["userName"]) && $_POST["userName"] !== ""
&& isset($_POST["personalCode"]) && $_POST["personalCode"] !== ""
&& isset($_POST["phone"]) && $_POST["phone"] !== ""
&& isset($_POST["password"]) && $_POST["password"] !== ""
&& isset($_POST["Password_verify"]) && $_POST["Password_verify"] !== ""){

    if($_POST["password"] === $_POST["Password_verify"]){
        if(strlen($_POST["password"]) > 8 ){

            $qury = "SELECT * FROM users WHERE username = ? ;";
            $statment = $connection->prepare($qury);
            $statment->execute([$_POST["userName"]]);
            $users = $statment->fetch();

            if($_POST["userName"] !== $users->username){
                
                $qury = "INSERT INTO `users` SET name = ?, family = ?, username = ?, personalCode = ?, 	phone = ?, password = ?, created_at = NOW(), last_visited = NOW(); ";
                $statment = $connection->prepare($qury);
                $pass = password_hash($_POST["password"],PASSWORD_DEFAULT);
                $statment->execute([$_POST["name"], $_POST["family"], $_POST["userName"], $_POST["personalCode"], $_POST["phone"], $pass]);
                redirect("auth/login.php");

            }else{
                $error = "this user name existed";
            }
            
        }else{
            $error = "your password have more than 8 character";
        }
    }else{
        $error = "plesse check the password";
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
                <form class="login" method="post" action="<?= url("auth/register.php");?>">
                    <h3>WELLCOME</h3>
                    <p><?php if($error !== "")echo $error;?></p>
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="text" class="login__input" name="name" placeholder="name">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="text" class="login__input" name="family" placeholder="family">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="text" class="login__input" name="userName" placeholder="User name">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="text" class="login__input" name="personalCode" placeholder="personalCode">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="text" class="login__input" name="phone" placeholder="phone">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="password" class="login__input" name="password" placeholder="password">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-lock"></i>
                        <input type="password" class="login__input" name="Password_verify" placeholder="Password verify">
                    </div>
                    <button class="button login__submit">
                        <span class="button__text">Register Now</span>
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