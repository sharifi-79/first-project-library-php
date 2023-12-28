<?php 
session_start();

if($_SESSION["admin"] != 1){
    redirect("auth/login.php");
}



