<?php 
require_once "../../functions/helper.php";
require_once "../../functions/connection.php";
require_once "../../functions/check_login_admin.php";
global $connection;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>manager User</title>
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
    <h2>Managing Users</h2>
    <a href="<?=url("admin/manegerBook/uploadBook.php");?>" class="btn btn-outline-light m-3" type="button">Upload Book</a>
    <?php 
        require_once "../../layut/navbarDown.php";
    ?>
    <div class="container-xl fix">   
        <table class='table table-striped table-hover'>
            <thead>
                <tr>
                    <td>id</td>
                    <td>name</td>
                    <td>family</td>
                    <td>username</td>
                    <td>personalCode</td>
                    <td>phone</td>
                    <td>created_at</td>
                    <td>last_visited</td>
                    <td>count Book</td>
                    <td>status</td>
                    <td>Delete</td>
                    <td>Edit</td>
                    
                </tr>
            </thead>
            <tbody>
                <?php 
                // whit left join get all users and total books selected user
                $qury = "SELECT users.*, COUNT(admin.book_id) AS countbook FROM `users` LEFT JOIN `admin` ON users.id = admin.users_id GROUP BY id;";
                $statment = $connection->prepare($qury);
                $statment->execute();
                $users = $statment->fetchAll();
                
                foreach ($users as $user) {
                ?>
                <tr>
                    <td><?= $user->id ?></td>
                    <td><?= $user->name ?></td>
                    <td><?= $user->family ?></td>
                    <td><?= $user->username ?></td>
                    <td><?= $user->personalCode?></td>
                    <td><?= $user->phone?></td>
                    <td><?= $user->created_at?></td>
                    <td><?= $user->last_visited?></td>
                    <td><?= $user->countbook?></td>
                    <td>
                        <a href="<?= url("admin/manegerUser/change-status.php?user_id=".$user->id);?>" class="btn btn-primary position-relative">
                            User Access
                            <?php 
                                if($user->status === 1){?>
                                    <span class="position-absolute top-0 start-100 translate-middle p-2 bg-success border border-light rounded-circle"></span>
                                <?php }else{?>
                                    <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle"></span>
                                <?php }?>
                        </a>
                    </td>    
                    <td><a class='btn btn-danger' href='<?= url("admin/manegerUser/delete.php?user_id=".$user->id);?>' role='button'>Delete</a></td>
                    <td><a class='btn btn-success' href='<?= url("admin/manegerUser/advanced.php?user_id=".$user->id);?>' role='button'>Advanced </a></td>
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