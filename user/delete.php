<?php 
require_once "../functions/helper.php";
require_once "../functions/connection.php";
require_once "../functions/check-login_user.php";
global $connection;
// if dont set from delete row redirect to user :)
if(!isset($_GET["book_id"]) && $_GET["book_id"] == ""){
    redirect("user/index.php");
}
// check exist form table admin id == ture .
$qury = "SELECT * FROM admin WHERE id = ?";
$statment = $connection->prepare($qury);
$statment->execute([$_GET["book_id"]]);
$book = $statment->fetch();
if($book === false){
    redirect("user");
}
// delete book for id in table admin
$qury = "DELETE FROM `admin` WHERE id = ?";
$statment = $connection->prepare($qury);
$statment->execute([$_GET["book_id"]]);
redirect("user");







?>