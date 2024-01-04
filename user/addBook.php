<?php 
require_once "../functions/helper.php";
require_once "../functions/connection.php";
require_once "../functions/check-login_user.php";
global $connection;

// if dont set from delete row redirect to user :)
if(!isset($_GET["book_id"]) && $_GET["book_id"] == ""){
    redirect("user/index.php");
}

// check exist form table books id == ture .
$qury = "SELECT * FROM books WHERE id = ?";
$statment = $connection->prepare($qury);
$statment->execute([$_GET["book_id"]]);
$book = $statment->fetch();
if($book === false){
    redirect("user");
}


//get id user whit session for add book 
$qury = "SELECT * FROM users WHERE username = ?";
$statment = $connection->prepare($qury);
$statment->execute([$_SESSION["user"]]);
$user = $statment->fetch();


// get start date 
$start = date("Y-m-d") ;
$date1=date_create($start);
//append 90 day to star date (end date)
$end=date_modify($date1,"+90 days");
$endDay = date_format($end,"Y-m-d");

//update table admin and add book for user
$qury = "INSERT INTO admin (`book_id`, `users_id`, `date_start`, `date_end`) VALUES (?,?,NOW(),?);";
$statment = $connection->prepare($qury);
$statment->execute([$_GET["book_id"],$user->id,$endDay]);
redirect("user");