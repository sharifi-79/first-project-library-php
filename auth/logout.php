<?php 
session_start();
require_once "../functions/helper.php";
require_once "../functions/connection.php";


//delete date store in session
session_destroy();
redirect("index.php");






?>