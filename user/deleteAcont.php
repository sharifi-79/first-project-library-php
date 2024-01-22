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
var_dump($user->id);

