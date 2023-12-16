<?php 
//config
define('BASE_URL', 'http://localhost/myprojects/libraryProjectPDO/');

// redirect function as heder(location:)
function redirect($url) {
     //for dont additional / and spasace in address url, whit trim() clean /, and manually add /
    header("location:".trim(BASE_URL,"/ ")."/".trim($url,"/ "));
    exit;
}



// for Addressing (pic,css,js...) 
function asset($file) {
     //it return address base + address file (pic, css , js...)
    return trim(BASE_URL,"/ ")."/".trim($file,"/ ");
     //exampel : <img src="asset(asset/images/test.png)">
}



// Addressing tag (a, ...)
function url($url){
    return trim(BASE_URL,"/ ")."/".trim($url,"/ ");
    //exampel : <a href="url(app/detail.php)" >...</a>
}


// for dibuging and show array in project whit var_dump()
function dd($var){
    echo "<pre>";
    var_dump($var);
    exit;
}

?>