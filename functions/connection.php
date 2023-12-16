<?php 
//connect to database whit PDO
$serverName = "localhost";
$userName = "root";
$pass = "";
$dbName = "libraryPro";

// for accsess $connection other files
global $connection;

try {
    //$option for show error and get data in objects
    $options = array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_OBJ);
    $connection = new PDO("mysql:host=$serverName;dbname=$dbName",$userName, $pass, $options);
    // for accsess $connection other files
    return $connection;

} catch (PDOException $e) {
    echo "error ". $e->getMessage();
    exit;
}





?>
