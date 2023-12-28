<?php 
require_once "../../functions/helper.php";
require_once "../../functions/connection.php";
require_once "../../functions/check_login_admin.php";
global $connection;

if(!isset($_GET["user_id"]) && $_GET["user_id"] === ""){
    redirect("admin/manegerBook");
}
// get status in row, 0 or 1 ?
$qury = "SELECT * FROM users WHERE id = ? ;";
$statment = $connection->prepare($qury);
$statment->execute([$_GET["user_id"]]);
$user = $statment->fetch();
if($user->id !== false){
    //shotcode if and else
    $status = ($user->status === 1)? 0 : 1;
    $qury = "UPDATE users SET `status`= ? WHERE id = ?;";
    $statment = $connection->prepare($qury);
    $statment->execute([$status, $_GET["user_id"] ]);
    redirect("admin/manegerUser");

}else{
    redirect("admin");
}








?>