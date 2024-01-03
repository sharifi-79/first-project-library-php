<?php 
require_once "../../functions/helper.php";
require_once "../../functions/connection.php";
require_once "../../functions/check_login_admin.php";
global $connection;

//check exist id in table admin
if(isset($_GET["admin_id"]) && $_GET["admin_id"] !== ""){
    $qury = "SELECT * FROM `admin` WHERE id = ? ;";
    $statment = $connection->prepare($qury);
    $statment->execute([$_GET["admin_id"]]);
    $admin = $statment->fetch();
    if($admin !== false){

        //appended days to date_end in table admin
        // get number in form 
        $date_old = $_POST["add"];
        //change format
        $dateformat=date_create($admin->date_end);
        // append numbers day
        $date = date_modify($dateformat,"+$date_old days");
        //add new format for show and dataBase
        $appendDate = date_format($date,"Y-m-d");

        //send new date in database
        $qury = "UPDATE `admin` SET date_end = ? WHERE id = ? ;";
        $statment = $connection->prepare($qury);
        $statment->execute([$appendDate, $_GET["admin_id"]]);
        //return to advanced page user
        redirect("admin/manegerUser/advanced.php?user_id=".$admin->users_id);
        


    }else{
        redirect("admin");
    }


}
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
        require_once "../../layut/navbarTop.php";
    ?>
    <div class="btn-group" role="group" aria-label="Basic outlined example">
        <a href="<?= url("admin/manegerUser") ?>" class="btn btn-outline-light">Back</a>
        <a href="<?=url("auth/logout.php");?>" class="btn btn-outline-light">Logout</a>
    </div>
    <h2>Extension of book date</h2>
    <a href="<?=url("admin");?>" class="btn btn-outline-light m-3" type="button"> Home Admin </a>
    <?php 
        require_once "../../layut/navbarDown.php";
    ?>
    <div class="container-xl fix">   
        <table class='table table-striped table-hover'>
            <thead>
                <tr>
                <td>#</td>
                <td>nameBook</td>
                <td>Publications</td>
                <td>Writer</td>
                <td>date_start</td>
                <td>date_end</td>
                <td>Extension of book date</td>
            </tr>
            <?php 
                // inner join admin and book table. show all the books owned by the user
                $qury = "SELECT books.*, admin.date_start,admin.date_end,admin.id AS id_admin FROM books INNER JOIN admin ON books.id = admin.book_id WHERE admin.users_id = ? ;";
                $statment = $connection->prepare($qury);
                $statment->execute([$_GET["user_id"]]);
                $records = $statment->fetchAll();
                foreach ($records as $record) { ?>
            <tr>
                <td><img src='<?= asset($record->addressPic); ?>' width=75 height=100 ></td>
                <td><?= $record->nameBook ?></td>
                <td><?= $record->Publications ?></td>
                <td><?= $record->Writer ?></td>
                <td><?= $record->date_start ?></td>
                <td><?= $record->date_end ?></td>
                <td>
                    <!-- access to the user who selected the book whith this ID  -->
                    <form action="<?=url("admin/manegerUser/advanced.php?admin_id=".$record->id_admin); ?>" method="post">
                        <input type="number" name="add" placeholder="append days">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </td>
            </tr>
            <?php }?>

        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>