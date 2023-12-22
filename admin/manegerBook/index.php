<?php 
require_once "../../functions/helper.php";
require_once "../../functions/connection.php";
global $connection;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>manager Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=asset("assets/css/showitem.css"); ?>">
</head>
<body>
<?php 
        require_once "../../layut/navbarTop.php";
    ?>
    <div class="btn-group" role="group" aria-label="Basic outlined example">
        <a href="" class="btn btn-outline-light">Back</a>
        <a href="" class="btn btn-outline-light">Logout</a>
    </div>
    <h2>Managing books</h2>
    <a href="<?=url("admin/manegerBook/uploadBook.php");?>" class="btn btn-outline-light m-3" type="button">Upload Book</a>
    <?php 
        require_once "../../layut/navbarDown.php";
    ?>
    <div class="container-xl fix">   
        <table class='table table-striped table-hover'>
            <thead>
                <tr>
                    <td>#</td>
                    <td>Id</td>
                    <td>nameBook</td>
                    <td>Publications</td>
                    <td>Writer</td>
                    <td>Size</td>
                    <td>category</td>
                    <td>status</td>
                    <td>Delete</td>
                    <td>Edite</td>
                    
                </tr>
            </thead>
            <tbody>
                <?php 
                $qury = "SELECT books.* ,categories.name AS cat_name FROM `books` LEFT JOIN categories ON books.category_id = categories.id; ";
                $statment = $connection->prepare($qury);
                $statment->execute();
                $books = $statment->fetchAll();
                
                foreach ($books as $book) {
                ?>
                <tr>
                    <td><img src='<?= asset($book->addressPic); ?>' width=75 height=100 ></td>
                    <td><?= $book->id ?></td>
                    <td><?= $book->nameBook ?></td>
                    <td><?= $book->Publications ?></td>
                    <td><?= $book->Writer ?></td>
                    <td><?= $book->Size?></td>
                    <td><?= $book->cat_name?></td>
                    <td>
                        <a href="<?= url("admin/manegerBook/change-status.php?book_id=".$book->id);?>" class="btn btn-primary position-relative">
                            Change Status
                            <?php 
                                if($book->status === 1){?>
                                    <span class="position-absolute top-0 start-100 translate-middle p-2 bg-success border border-light rounded-circle"></span>
                                <?php }else{?>
                                    <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle"></span>
                                <?php }?>
                        </a>
                    </td>    
                    <td><a class='btn btn-danger' href='<?= url("admin/manegerBook/delete.php?book_id=".$book->id);?>' role='button'>Delete</a></td>
                    <td><a class='btn btn-success' href='<?= url("admin/manegerBook/edit.php?book_id=".$book->id);?>' role='button'>Edite</a></td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
<?php 
require_once "../../layut/pageDowne1.php"
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>