<?php 
require_once "../functions/helper.php";
require_once "../functions/connection.php";
require_once "../functions/check-login_user.php";
global $connection;

if(isset($_POST["input"])){

    $input = $_POST["input"];

    $qury = "SELECT * FROM `books` WHERE `nameBook` LIKE '{$input}%';";
    $statment = $connection->prepare($qury);
    $statment->execute();
    $books = $statment->fetchAll();
}
?>
<table class='table table-striped table-hover '> 
            <tr>
                <td>#</td>
                <td>Id</td>
                <td>nameBook</td>
                <td>Publications</td>
                <td>Writer</td>
                <td>Size</td>
                <td>Add</td>
            </tr>
            <?php 
            foreach ($books as $book) { ?>
            <tr>
                <td><img src="<?= asset($book->addressPic ) ?>" alt="#"></td>
                <td><?= $book->id ?></td>
                <td><?= $book->nameBook ?></td>
                <td><?= $book->Publications ?></td>
                <td><?= $book->Writer ?></td>
                <td><?= $book->Size ?></td>
                <td><a class='btn btn-success' href='' role='button'>Add +</a></td>
                <?php }?>