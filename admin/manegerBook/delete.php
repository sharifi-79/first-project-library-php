<?php 
require_once "../../functions/helper.php";
require_once "../../functions/connection.php";
global $connection;

if(!isset($_GET["book_id"]) && $_GET["book_id"] === ""){
    redirect("admin/manegerBook");
}

$qury = "SELECT * FROM books WHERE id = ?;";
$statment = $connection->prepare($qury);
$statment->execute([$_GET["book_id"]]);
$book = $statment->fetch();
if($book->id !== false){
    //dirname() = ../
    $basePath = dirname(dirname(__DIR__));
    if(file_exists($basePath.$book->addressPic)){
        unlink($basePath.$book->addressPic);
    }
    if(file_exists($basePath.$book->addressBook)){
        unlink($basePath.$book->addressBook);
    }
    $qury = "DELETE FROM books WHERE id = ? ;";
    $statment = $connection->prepare($qury);
    $statment->execute([$_GET["book_id"]]);
    redirect("admin/manegerBook");
}else{
    redirect("admin/manegerBook");
}