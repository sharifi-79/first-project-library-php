<?php  
require_once "../functions/helper.php";
require_once "../functions/connection.php";
require_once "../functions/check-login_user.php";
global $connection;
// get information user 
$qury = "SELECT * FROM users WHERE username = ? ;";
$statment = $connection->prepare($qury);
$statment->execute([$_SESSION["user"]]);
$user = $statment->fetch();
$check = 0;
            if (password_verify($_POST["pass"],$user->password)) {
                global $check;
                $check = 1;
            }
            echo $check;
        if(isset($_POST["pass"])){
            #$check = password_verify($_POST["pass"],$user->password);
        };
?>