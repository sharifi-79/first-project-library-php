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


if($user->username !== $_SESSION["user"]){
    redirect("user");
}
//delete from table admin books user
$qury = "DELETE FROM `admin` WHERE users_id = ?";
$statment = $connection->prepare($qury);
$statment->execute([$user->id]);

//delete form table user remove user
$qury = "DELETE FROM `users` WHERE id = ?";
$statment = $connection->prepare($qury);
$statment->execute([$user->id]);

//delete date store in session
session_destroy();
redirect("index.php");

