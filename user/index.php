<?php  
require_once "../functions/helper.php";
require_once "../functions/connection.php";
require_once "../functions/check-login_user.php";
global $connection;
// get information user 
$qury = "SELECT * FROM users WHERE username = ? ;";
$statment = $connection->prepare($qury);
$statment->execute([$_SESSION["user"]]);
$user = $statment->fetch();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>admin panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= asset("assets/css/showitem.css")?>">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <?php 
        require_once "../layut/navbarTop.php";
    ?>
    <div>
        <a href="<?=url("auth/logout.php");?>" class="btn btn-outline-light m-3" type="button">log out</a>
        <button id="delAccount" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Delete Account</button>
    </div>
    
    <h2>Managing And Library Books</h2>
    <h3> WELCOME : <?= $_SESSION["user"];?> </h3>


    <?php 
        require_once "../layut/navbarDown.php";
    ?>    
    
    <div class="container-xl fix">

    <!----------popup card---------------->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Delete Account</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h4 dir="rtl">هشدار</h4>
                    <p dir="rtl">با وارد کردن رمز عبور حساب کاربری شما حذف گردیده و تمام کتاب های شما از بین می رود</p>
                    <div class="mb-3">
                        <label for="recipient-name" class="col-form-label">password:</label>
                        <input type="password" class="form-control " id="recipient-name" name="password">
                        <div class="valid-feedback">Success! You've done it.</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <a href="<?=url("user/deleteAcont.php")?>" id="deleteAccountBtn"  class="btn btn-danger disabled">Delete</a>
                </div>
                </div>
            </div>
        </div>
     <!----------popup card end---------------->


    <!----------script chek password---------------->
        <script ype="text/javascript">
            $(document).ready(function (){
                $("input").keyup(function(){
                    var pass = $("input").val();
                    
                        if(pass != ""){
                            $.ajax({
                                url : "checkPass.php",
                                method: "POST",
                                data: {pass},
                                // response with echo in php validation
                                success: function(response) {
                                        // console.log(response);
                                        if(response == 1){
                                            $("#recipient-name").addClass("is-valid");
                                            $("#deleteAccountBtn").removeClass("disabled");
                                        }else{
                                            $("#recipient-name").removeClass("is-valid");
                                        }
                                       
                                }
                            })
                    }
                    
                })
            })
        </script>
    <!----------script chek password end---------------->
    
   






    <!----------input live serch---------------->
        <div class="hederUser"></div>
        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-wrapping">serch</span>
            <input type="text" id="serch" class="form-control" placeholder="serch Book..." >
        </div>
        <div id="show_items" class="showitem"></div>
    <!----------end input live serch---------------->



        <!-- liveserch book  -->
<script type="text/javascript">

$(document).ready(function() {
    $("#serch").keyup(function() {
        var input = $(this).val();
        
        if(input != ""){
            $.ajax({

                url:"liveserch.php",
                method:"POST",
                data:{input},
                success:function(data) {
                    $("#show_items").html(data);
                    $("#show_items").css("display","block");
                }
                

            });
        }
        else{
            $("#show_items").css("display","none");
            
        }
    }); 
});

</script>
<!-- end liveserch book  -->




        <div class="d-grid gap-2 col-6 mx-auto">
            
        </div>
        <div class="alert alert-info mt-5" role="alert">
        <h3>Your Books</h3>
        </div>
    

        <table class='table table-striped table-hover '> 
            <tr>
                <td>#</td>
                <td>Date Start</td>
                <td>Date End</td>
                <td>Name Book</td>
                <td>Publications</td>
                <td>Writer</td>
                <td>Remaining Time</td>
                <td>Read</td>
                <td>Delete</td>
            </tr>
            <?php 
                $qury = "SELECT admin.id, admin.date_start, admin.date_end, books.nameBook,books.Publications,books.Writer,admin.id,books.addressPic,books.addressBook FROM (( admin INNER JOIN books ON admin.book_id = books.id ) INNER JOIN users ON admin.users_id = users.id AND users.id= ?  );";               
                $statment = $connection->prepare($qury);
                $statment->execute([$user->id]);
                $records = $statment->fetchAll();
                foreach ($records as $record) { 

                    //date start for remaid date
                    $start = date("Y-m-d") ;
                    $dateEnter=date_create($start);
                    $formatDate = date_format($dateEnter,"Y-m-d");
                    //First, we took the time from the database and sorted and calculated it with the user's login time
                    $creat = date_create($record->date_end);
                    $formatDateEnd = date_format($creat,"Y-m-d");
                    $from_time =strtotime($formatDateEnd); 
                    $to_time = strtotime($formatDate); 
                    $diff_minutes = intval(round($from_time - $to_time) /60/60/24)." Day";

                    //delete book after date end
                    if($diff_minutes <= 0){
                        redirect("user/delete.php?book_id=".$record->id);
                    }

                    ?>
            <tr>
                <td><img src="<?= asset($record->addressPic ) ?>" alt="#" width="75" height="100"></td>
                <td><?= $record->date_start ?></td>
                <td><?= $record->date_end ?></td>
                <td><?= $record->nameBook ?></td>
                <td><?= $record->Publications ?></td>
                <td><?= $record->Writer ?></td>
                <td><?= $diff_minutes ?></td>
                <td><a class='btn btn-success' href='<?= asset($record->addressBook); ?>' role='button'>Reade</a></td>
                <td><a class='btn btn-danger' href='<?= url("user/delete.php?book_id=".$record->id) ?>' role='button'>Delete</a></td>
            </tr>
            <?php }?>

        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>