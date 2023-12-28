<?php 
require_once "../../functions/helper.php";
require_once "../../functions/connection.php";
require_once "../../functions/check_login_admin.php";
global $connection;

if(!isset($_GET["book_id"]) && $_GET["book_id"] === ""){
    redirect("admin/manegerBook");
}
// get status in row, 0 or 1 ?
$qury = "SELECT * FROM books WHERE id = ? ;";
$statment = $connection->prepare($qury);
$statment->execute([$_GET["book_id"]]);
$book = $statment->fetch();
if($book->id !== false){
    //shotcode if and else
    $status = ($book->status === 1)? 0 : 1;
    $qury = "UPDATE books SET `status`= ? WHERE id = ?;";
    $statment = $connection->prepare($qury);
    $statment->execute([$status, $_GET["book_id"] ]);
    redirect("admin/manegerBook");

}else{
    redirect("admin/manegerBook");
}








?>