<?php 
require_once "../../functions/helper.php";
require_once "../../functions/connection.php";
require_once "../../functions/check_login_admin.php";
global $connection;

if(!isset($_GET["user_id"]) && $_GET["user_id"] === ""){
    redirect("admin/manegerBook");
}

$qury = "SELECT * FROM users WHERE id = ?;";
$statment = $connection->prepare($qury);
$statment->execute([$_GET["user_id"]]);
$user = $statment->fetch();
if($user->id !== false){

    $qury = "DELETE FROM users WHERE id = ? ;";
    $statment = $connection->prepare($qury);
    $statment->execute([$_GET["user_id"]]);
    redirect("admin/manegerUser");
}else{
    redirect("admin");
}