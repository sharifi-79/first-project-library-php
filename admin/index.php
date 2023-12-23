<?php 
require_once "../functions/helper.php";
require_once "../functions/connection.php";
global $connection;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>admin panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= asset("assets/css/showitem.css")?>">
</head>
<body>
    <?php 
        require_once "../layut/navbarTop.php";
    ?>
    <a href="managerBook.php" class="btn btn-outline-light m-3" type="button">log out</a>
    <h2>Managing users and their books</h2>
    <div class="btn-group" role="group" aria-label="Basic outlined example">
        <a href="<?=url("admin/manegerBook");?>" class="btn btn-outline-light">maneger Book</a>
        <a href="<?=url("admin/manegerUser");?>" class="btn btn-outline-light">maneger Users</a>
    </div>


    <?php 
        require_once "../layut/navbarDown.php";
    ?>    

    
    <div class="container-xl fix">
        <div class="d-grid gap-2 col-6 mx-auto">
            
        </div>
        <div class="alert alert-info" role="alert">
        <h3>Managing users and their books</h3>
        </div>
    

        <table class='table table-striped table-hover '> 
            <tr>
                <td>name</td>
                <td>family</td>
                <td>username</td>
                <td>phone</td>
                <td>date_start</td>
                <td>date_end</td>
                <td>nameBook</td>
                <td>Writer</td>
                <td>delete</td>
            </tr>
            <?php 
                $qury = "SELECT users.name,users.family,users.username,users.personalCode,users.phone,users.password, admin.date_start, admin.date_end, books.nameBook,books.Writer,books.addressPic, admin.id FROM (( admin INNER JOIN books ON admin.book_id = books.id ) INNER JOIN users ON admin.users_id = users.id);";
                $statment = $connection->prepare($qury);
                $statment->execute();
                $records = $statment->fetchAll();
                foreach ($records as $record) { ?>
            <tr>
                <td><?= $record->name ?></td>
                <td><?= $record->family ?></td>
                <td><?= $record->username ?></td>
                <td><?= $record->phone ?></td>
                <td><?= $record->date_start ?></td>
                <td><?= $record->date_end ?></td>
                <td><?= $record->nameBook ?></td>
                <td><?= $record->Writer ?></td>
                <td><a class='btn btn-danger' href='delete_row_admin.php?id=$row[11]' role='button'>Delete</a></td>
            </tr>
            <?php }?>

        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>